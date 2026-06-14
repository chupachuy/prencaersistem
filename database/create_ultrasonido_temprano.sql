-- ============================================================
-- Ultrasonido Obstetrico Temprano (<11 semanas) — v2
-- Ejecutar en phpMyAdmin → pestana SQL, BD: prenacersistem
-- ============================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS ultrasonido_temprano (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    medico_id INT NOT NULL,
    codigo_reporte VARCHAR(50) UNIQUE NOT NULL,
    fecha_estudio DATE NOT NULL,
    edad INT NULL COMMENT 'Edad al momento del estudio',
    fum DATE NULL COMMENT 'Fecha de ultima menstruacion',
    edad_gest_semanas INT NULL COMMENT 'Edad gestacional por FUM - semanas',
    edad_gest_dias INT NULL COMMENT 'Edad gestacional por FUM - dias',

    -- Indicacion del estudio
    indic_confirmacion_embarazo BOOLEAN DEFAULT FALSE,
    indic_sangrado BOOLEAN DEFAULT FALSE,
    indic_dolor_pelvico BOOLEAN DEFAULT FALSE,
    indic_viabilidad BOOLEAN DEFAULT FALSE,
    indic_perdidas_gestacionales BOOLEAN DEFAULT FALSE,
    indic_reproduccion_asistida BOOLEAN DEFAULT FALSE,
    indic_otro VARCHAR(255) NULL,

    -- Via de exploracion
    via_transvaginal BOOLEAN DEFAULT FALSE,
    via_transabdominal BOOLEAN DEFAULT FALSE,
    via_ambas BOOLEAN DEFAULT FALSE,

    -- Utero
    utero_posicion ENUM('Anteroversion','Retroversion') NULL,
    utero_contornos ENUM('Regulares','Irregulares') DEFAULT 'Regulares',
    utero_ecogenicidad_conservada BOOLEAN DEFAULT TRUE,
    utero_dim_x DECIMAL(5,2) NULL COMMENT 'Dimension uterina longitudinal (mm)',
    utero_dim_y DECIMAL(5,2) NULL COMMENT 'Dimension uterina AP (mm)',
    utero_dim_z DECIMAL(5,2) NULL COMMENT 'Dimension uterina transversal (mm)',
    endometrio VARCHAR(255) NULL,

    -- Localizacion del embarazo
    localizacion ENUM('Fundica','Corporal','Segmentaria','Cicatriz de cesarea','Otra') NULL,
    localizacion_otra VARCHAR(255) NULL,

    -- Saco gestacional (resumen principal)
    sg_tipo ENUM('Unico','Multiple') NULL,
    sg_morfologia ENUM('Regular','Irregular') NULL,
    sg_medida_mm DECIMAL(5,2) NULL,
    sg_cantidad TINYINT NULL COMMENT 'Cantidad total de sacos gestacionales (1-4)',

    -- Saco vitelino (resumen principal)
    sv_presente BOOLEAN NULL,
    sv_cantidad TINYINT NULL COMMENT 'Cantidad de sacos vitelinos (1, 2, 3)',
    sv_diametro_mm DECIMAL(5,2) NULL,

    -- Decidua
    decidua TEXT NULL COMMENT 'Descripcion de la decidua',

    -- Corion y Amnios
    corion_amnios_normal BOOLEAN DEFAULT TRUE,

    -- Ovario derecho
    ovario_der_dim_x DECIMAL(5,2) NULL,
    ovario_der_dim_y DECIMAL(5,2) NULL,
    ovario_der_dim_z DECIMAL(5,2) NULL,
    ovario_der_normal BOOLEAN DEFAULT TRUE,
    ovario_der_cuerpo_luteo_mm DECIMAL(5,2) NULL,
    ovario_der_quiste_simple_mm DECIMAL(5,2) NULL,
    ovario_der_otra_alteracion VARCHAR(255) NULL,

    -- Ovario izquierdo
    ovario_izq_dim_x DECIMAL(5,2) NULL,
    ovario_izq_dim_y DECIMAL(5,2) NULL,
    ovario_izq_dim_z DECIMAL(5,2) NULL,
    ovario_izq_normal BOOLEAN DEFAULT TRUE,
    ovario_izq_cuerpo_luteo_mm DECIMAL(5,2) NULL,
    ovario_izq_quiste_simple_mm DECIMAL(5,2) NULL,
    ovario_izq_otra_alteracion VARCHAR(255) NULL,

    -- Fondo de saco de Douglas
    douglas ENUM('Libre','Escasa cantidad de liquido libre','Moderada cantidad de liquido libre','Abundante liquido libre') NULL,

    -- Hallazgos adicionales
    hematoma_subcorionico BOOLEAN DEFAULT FALSE,
    hematoma_localizacion VARCHAR(255) NULL,
    hematoma_dim_x DECIMAL(5,2) NULL,
    hematoma_dim_y DECIMAL(5,2) NULL,
    hematoma_dim_z DECIMAL(5,2) NULL,
    hematoma_volumen_ml DECIMAL(6,2) NULL,
    miomas_uterinos BOOLEAN DEFAULT FALSE,
    adenomiosis BOOLEAN DEFAULT FALSE,
    malformacion_uterina BOOLEAN DEFAULT FALSE,
    hallazgos_otro TEXT NULL,

    -- Impresion diagnostica
    impresion_crl_mm DECIMAL(5,2) NULL,
    impresion_semanas INT NULL,
    impresion_dias INT NULL,
    impresion_fcf_lpm INT NULL,
    viabilidad ENUM('Viable','No viable','Incierto') NULL COMMENT 'Determinacion de viabilidad',
    impresion_texto TEXT NULL,

    -- Estado y auditoria
    estado ENUM('Pendiente','En proceso','Completado','Archivado') DEFAULT 'Pendiente',
    activo BOOLEAN DEFAULT TRUE,
    created_by INT NULL,
    updated_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (medico_id) REFERENCES usuarios(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_paciente_ultratemp (paciente_id),
    INDEX idx_medico_ultratemp (medico_id),
    INDEX idx_codigo_ultratemp (codigo_reporte)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sacos gestacionales detallados (un registro por cada saco)
CREATE TABLE IF NOT EXISTS sacos_gestacionales_temprano (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ultrasonido_id INT NOT NULL,
    numero TINYINT NOT NULL COMMENT 'Numero de saco (1, 2, 3, 4)',
    medida_mm DECIMAL(5,2) NULL COMMENT 'Medida del saco gestacional (mm)',
    morfologia ENUM('Regular','Irregular') NULL,
    sv_presente BOOLEAN NULL COMMENT 'Saco vitelino presente',
    sv_diametro_mm DECIMAL(5,2) NULL COMMENT 'Diametro del saco vitelino (mm)',
    descripcion TEXT NULL COMMENT 'Descripcion adicional del saco',

    FOREIGN KEY (ultrasonido_id) REFERENCES ultrasonido_temprano(id) ON DELETE CASCADE,
    INDEX idx_saco_ultrasonido (ultrasonido_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Embriones asociados al ultrasonido temprano (1 a 4 por estudio)
CREATE TABLE IF NOT EXISTS embriones_temprano (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ultrasonido_id INT NOT NULL,
    saco_id INT NULL COMMENT 'FK al saco gestacional que contiene este embrion',
    numero TINYINT NOT NULL COMMENT 'Numero de embrion (1, 2, 3, 4)',
    crl_mm DECIMAL(5,2) NULL COMMENT 'Longitud Cefalocaudal (mm)',
    fcf_visible BOOLEAN NULL COMMENT 'Frecuencia Cardiaca Fetal visible',
    fcf_lpm INT NULL COMMENT 'Frecuencia Cardiaca Fetal (lpm)',
    localizacion VARCHAR(255) NULL,

    FOREIGN KEY (ultrasonido_id) REFERENCES ultrasonido_temprano(id) ON DELETE CASCADE,
    FOREIGN KEY (saco_id) REFERENCES sacos_gestacionales_temprano(id) ON DELETE SET NULL,
    INDEX idx_ultrasonido_embrion (ultrasonido_id),
    INDEX idx_embrion_saco (saco_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
