-- ============================================================
-- EVALUACIONES 2DO TRIMESTRE
-- Ejecutar en phpMyAdmin -> SQL -> BD: prenacersistem
-- ============================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS evaluaciones_2do_trimestre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    medico_id INT NOT NULL,
    codigo_reporte VARCHAR(50) UNIQUE NOT NULL,
    fecha_evaluacion DATE NOT NULL,
    fecha_estudio DATE NULL,
    edad_gestacional_semanas DECIMAL(4,1) NULL,
    fpp_actual DATE NULL,
    peso_kg DECIMAL(5,2) NULL,
    talla_cm DECIMAL(5,2) NULL,
    pam_mmhg DECIMAL(5,2) NULL COMMENT 'Presion Arterial Media',
    uta_pi_promedio DECIMAL(4,2) NULL COMMENT 'Indice Pulsatilidad Arterias Uterinas',
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

CREATE TABLE IF NOT EXISTS biometria_2do_trimestre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    estado_feto ENUM('Vivo','Muerto') DEFAULT 'Vivo',
    fcf_lpm INT NULL,
    peso_fetal_estimado_gr INT NULL,
    percentil_hadlock INT NULL,
    crecimiento_armonico BOOLEAN DEFAULT TRUE,
    indice_cefalico_ci DECIMAL(4,2) NULL,
    fl_ac_pct DECIMAL(4,2) NULL,
    hc_ac_campbell DECIMAL(4,2) NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_2do_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_biometria (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS anatomia_fetal_2do_trimestre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    craneo_snc_normal BOOLEAN DEFAULT TRUE,
    cara_cuello_normal BOOLEAN DEFAULT TRUE,
    corazon_normal BOOLEAN DEFAULT TRUE,
    torax_diafragma_normal BOOLEAN DEFAULT TRUE,
    abdomen_normal BOOLEAN DEFAULT TRUE,
    genitourinario_normal BOOLEAN DEFAULT TRUE,
    columna_normal BOOLEAN DEFAULT TRUE,
    extremidades_normal BOOLEAN DEFAULT TRUE,
    detalles_anomalias TEXT NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_2do_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_anatomia2 (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS marcadores_ecograficos_2do_trimestre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    ventriculomegalia_leve BOOLEAN DEFAULT FALSE,
    quistes_plexos_coroideos BOOLEAN DEFAULT FALSE,
    pliegue_nucal_aumentado BOOLEAN DEFAULT FALSE,
    hueso_nasal_ausente BOOLEAN DEFAULT FALSE,
    foco_ecogenico_cardiaco BOOLEAN DEFAULT FALSE,
    intestino_hiperecogenico BOOLEAN DEFAULT FALSE,
    femur_corto BOOLEAN DEFAULT FALSE,
    arteria_umbilical_unica BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_2do_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_marcadores2 (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS entorno_placentario_2do_trimestre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    placenta_posicion VARCHAR(50) NULL,
    distancia_borde_oci_mm DECIMAL(5,2) NULL,
    acretismo_figo_grado ENUM('0','1','2','3') NULL COMMENT '0:Normal, 1:Parcial, 2:Invasion, 3:Percretismo',
    bolsillo_max_liquido_mm INT NULL,
    longitud_cervical_mm DECIMAL(5,2) NULL,
    indice_consistencia_cervical INT NULL,
    funneling_presente BOOLEAN DEFAULT FALSE,
    funneling_mm DECIMAL(5,2) NULL,
    sludge_intraamniotico ENUM('Si','No','Dudoso') NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_2do_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_entorno2 (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS impresion_diagnostica_2do_trimestre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    riesgo_cromosomopatias ENUM('Bajo','Intermedio','Alto') NULL,
    riesgo_parto_pretermino ENUM('Bajo','Intermedio','Alto','Muy Alto') NULL,
    riesgo_preeclampsia ENUM('Bajo','Intermedio','Alto','Muy Alto') NULL,
    observaciones_medicas TEXT NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_2do_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_diagnostica2 (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
