-- ============================================================
-- Migracion v2: Ultrasonido Temprano
-- Ajustes solicitados por el cliente (Junio 2026)
-- Ejecutar en phpMyAdmin → pestana SQL, BD: prenacersistem
-- ============================================================

SET NAMES utf8mb4;

-- 1. Cambiar contornos de BOOLEAN a ENUM
ALTER TABLE ultrasonido_temprano
    CHANGE utero_contornos_regulares utero_contornos ENUM('Regulares','Irregulares') DEFAULT 'Regulares';

-- 2. Agregar nuevos campos a ultrasonido_temprano
ALTER TABLE ultrasonido_temprano
    ADD COLUMN sg_cantidad TINYINT NULL COMMENT 'Cantidad total de sacos gestacionales (1-4)' AFTER sg_medida_mm,
    ADD COLUMN decidua TEXT NULL COMMENT 'Descripcion de la decidua' AFTER sg_cantidad,
    ADD COLUMN viabilidad ENUM('Viable','No viable','Incierto') NULL COMMENT 'Determinacion de viabilidad' AFTER impresion_fcf_lpm;

-- 3. Crear tabla sacos_gestacionales_temprano
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

-- 4. Agregar saco_id a embriones_temprano
ALTER TABLE embriones_temprano
    ADD COLUMN saco_id INT NULL COMMENT 'FK al saco gestacional que contiene este embrion' AFTER numero,
    ADD FOREIGN KEY (saco_id) REFERENCES sacos_gestacionales_temprano(id) ON DELETE SET NULL,
    ADD INDEX idx_embrion_saco (saco_id);
