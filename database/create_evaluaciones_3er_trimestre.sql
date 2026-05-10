-- ============================================================
-- EVALUACIONES 3ER TRIMESTRE
-- Ejecutar en phpMyAdmin -> SQL -> BD: prenacersistem
-- ============================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS evaluaciones_3er_trimestre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    medico_id INT NOT NULL,
    codigo_reporte VARCHAR(50) UNIQUE NOT NULL,
    fecha_evaluacion DATE NOT NULL,
    fecha_estudio DATE NULL,
    edad_gestacional_semanas DECIMAL(4,1) NULL,
    peso_kg DECIMAL(5,2) NULL,
    ta_sistolica INT NULL,
    ta_diastolica INT NULL,
    situacion_fetal ENUM('Longitudinal','Transversa') NULL,
    presentacion_fetal ENUM('Cefalico','Pelvico') NULL,
    posicion_fetal VARCHAR(50) NULL COMMENT 'Dorso anterior, dorso posterior',
    fcf_lpm INT NULL,
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

CREATE TABLE IF NOT EXISTS antecedentes_3er_trimestre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    curva_tolerancia_glucosa ENUM('Normal','Alterada','No realizada') DEFAULT 'No realizada',
    diabetes_gestacional_actual BOOLEAN DEFAULT FALSE,
    movimientos_fetales ENUM('Normales','Disminuidos') DEFAULT 'Normales',
    signos_amenaza_parto_pretermino BOOLEAN DEFAULT FALSE,
    plan_nacimiento_definido BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_3er_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_antecedentes3 (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS crecimiento_3er_trimestre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    peso_fetal_estimado_gr INT NULL,
    percentil_ajustado INT NULL,
    clasificacion_crecimiento ENUM('Adecuado','Mayor a lo esperado','Menor a lo esperado') NULL,
    estadio_rciu_barcelona ENUM('Ninguno','Estadio I','Estadio II','Estadio III','Estadio IV') DEFAULT 'Ninguno',
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_3er_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_crecimiento3 (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS doppler_3er_trimestre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    au_pi DECIMAL(4,2) NULL COMMENT 'Arteria Umbilical PI',
    au_flujo_diastolico ENUM('Presente','Ausente','Reverso') NULL,
    acm_pi DECIMAL(4,2) NULL COMMENT 'Arteria Cerebral Media PI',
    dv_onda_a ENUM('Positiva','Ausente','Reversa') NULL COMMENT 'Ductus Venoso Onda A',
    uta_pi_promedio DECIMAL(4,2) NULL COMMENT 'Arterias Uterinas PI',
    ratio_cu_icp DECIMAL(4,2) NULL COMMENT 'Cociente cerebro-placentario',
    alteracion_doppler_detectada BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_3er_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_doppler3 (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS anatomia_liquido_3er_trimestre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    circular_cordon_cuello ENUM('Negativo','Simple','Doble') DEFAULT 'Negativo',
    liquido_amniotico_mm INT NULL,
    metodo_medicion_liquido ENUM('Phelan','Bolsillo Maximo') DEFAULT 'Bolsillo Maximo',
    diagnostico_liquido ENUM('Normal','Oligohidramnios','Polihidramnios') DEFAULT 'Normal',
    estructuras_normales BOOLEAN DEFAULT TRUE COMMENT 'Validacion general craneo, torax, abdomen',
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_3er_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_anatomia3 (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS evaluacion_placentaria_3er_trimestre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    distancia_oci_mm DECIMAL(5,2) NULL,
    grosor_placentario_mm INT NULL,
    grado_madurez ENUM('Grado 0-1','Grado 2','Grado 3') NULL,
    lagunas_vasculares ENUM('Ausentes/minimas','Si','Extensas') DEFAULT 'Ausentes/minimas',
    interfase_miometrial ENUM('Intacta','Adelgazada','Discontinua') DEFAULT 'Intacta',
    vasos_puente BOOLEAN DEFAULT FALSE,
    acretismo_figo_pas ENUM('Grado 0','Grado 1','Grado 2','Grado 3') DEFAULT 'Grado 0',
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_3er_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_placentaria3 (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
