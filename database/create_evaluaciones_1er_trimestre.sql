-- ============================================================
-- NUEVO SISTEMA: Evaluaciones 1er Trimestre
-- Ejecutar en phpMyAdmin → pestaña SQL, BD: prenacersistem
-- ============================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS historial_clinico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    hipertension_cronica BOOLEAN DEFAULT FALSE,
    diabetes BOOLEAN DEFAULT FALSE,
    lupus_les BOOLEAN DEFAULT FALSE,
    sindrome_antifosfolipido_saf BOOLEAN DEFAULT FALSE,
    antecedente_preeclampsia_rciu BOOLEAN DEFAULT FALSE,
    fertilizacion_in_vitro BOOLEAN DEFAULT FALSE,
    antecedente_parto_pretermino BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    UNIQUE KEY unique_paciente_historial (paciente_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS evaluaciones_1er_trimestre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    medico_id INT NOT NULL,
    codigo_reporte VARCHAR(50) UNIQUE NOT NULL,
    fecha_evaluacion DATE NOT NULL,
    fecha_estudio DATE NULL,
    peso_kg DECIMAL(5,2) NULL,
    talla_cm DECIMAL(5,2) NULL,
    ta_sistolica INT NULL,
    ta_diastolica INT NULL,
    fum DATE NULL COMMENT 'Fecha de ultima regla',
    fpp_usg DATE NULL COMMENT 'Fecha probable de parto por USG',
    embarazo_multiple BOOLEAN DEFAULT FALSE,
    estado_feto ENUM('Vivo','Muerto') DEFAULT 'Vivo',
    fcf_lpm INT NULL COMMENT 'Frecuencia Cardiaca Fetal',
    lcc_mm DECIMAL(5,2) NULL COMMENT 'Longitud Craneo Cauda',
    edad_gestacional_semanas DECIMAL(4,1) NULL,
    estado ENUM('Pendiente','En proceso','Completado','Archivado') DEFAULT 'Pendiente',
    activo BOOLEAN DEFAULT TRUE,
    created_by INT NULL,
    updated_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (medico_id) REFERENCES usuarios(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS anatomia_fetal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    estado_exploracion ENUM('Completa','Incompleta') DEFAULT 'Completa',
    snc_simetria_plexos BOOLEAN DEFAULT TRUE COMMENT 'Signo de mariposa - SNC',
    macizo_facial_integro BOOLEAN DEFAULT TRUE,
    torax_situs ENUM('Solitus','Inversus') DEFAULT 'Solitus',
    torax_eje_cardiaco_grados INT NULL,
    abdomen_camara_gastrica BOOLEAN DEFAULT TRUE,
    extremidades_completas BOOLEAN DEFAULT TRUE,
    observaciones_anomalias TEXT NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_1er_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_anatomia (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS marcadores_fmf (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    translucencia_nucal_mm DECIMAL(4,2) NULL,
    hueso_nasal_presente BOOLEAN DEFAULT TRUE,
    ductus_venoso_onda_a ENUM('Positiva','Reversa','Ausente') NULL,
    regurgitacion_tricuspidea_ausente BOOLEAN DEFAULT TRUE,
    vejiga_fetal_mm DECIMAL(4,2) NULL,
    uta_pi_promedio DECIMAL(4,2) NULL COMMENT 'Indice de pulsatilidad arterias uterinas',
    muesca_bilateral BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_1er_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_marcadores (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS entorno_materno (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    liquido_amniotico ENUM('Normal','Anormal') DEFAULT 'Normal',
    placenta_posicion ENUM('Anterior','Posterior','Lateral Derecho','Lateral Izquierdo') NULL,
    placenta_insercion ENUM('Normal','Baja Temprana','Previa Temprana') NULL,
    longitud_cervical_mm DECIMAL(5,2) NULL,
    indice_consistencia_cervical_pct INT NULL,
    morfologia_uterina_eshre ENUM('U0','U1','U2','U3','U4','U5','U6') NULL COMMENT 'Clasificacion ESHRE-ESGE',
    miomas_visibles BOOLEAN DEFAULT FALSE,
    miomas_figo_tipo VARCHAR(50) NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_1er_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_entorno (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS impresion_diagnostica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    riesgo_basal_cromosomopatias VARCHAR(20) NULL,
    riesgo_ajustado_cromosomopatias VARCHAR(20) NULL,
    probabilidad_cromosomopatias ENUM('Baja','Intermedia','Alta') NULL,
    riesgo_preeclampsia_temprana ENUM('Baja','Alta') NULL,
    riesgo_enfermedad_placentaria_tardia ENUM('Baja','Alta') NULL,
    riesgo_parto_pretermino ENUM('Bajo','Alto') NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_1er_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_diagnostica (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
