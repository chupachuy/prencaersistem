-- ============================================================
-- Tabla: Reporte_1er_Trimestre
-- Propósito: Almacenar datos del primer trimestre del seguimiento prenatal
-- Conexión: Relación con tabla Consultas mediante id_consulta
-- ============================================================

CREATE TABLE Reporte_1er_Trimestre (
    -- Claves
    id_reporte_1t INT AUTO_INCREMENT PRIMARY KEY,
    id_consulta INT NOT NULL,
    
    -- Datos de fechas obstétricas
    fecha_ultima_regla DATE NULL,
    fpp_fum DATE NULL,
    fpp_usg DATE NULL,
    
    -- Medidas ecográficas del primer trimestre
    longitud_craneo_cauda_mm DECIMAL(5,2) NULL,
    translucencia_nucal_mm DECIMAL(5,2) NULL,
    frecuencia_cardiaca_fetal INT NULL,
    longitud_cervical_mm DECIMAL(5,2) NULL,
    
    -- Evaluaciones de riesgo
    riesgo_cromosomopatias ENUM('Baja Probabilidad', 'Alta Probabilidad') NULL,
    riesgo_placentaria_temprana ENUM('Baja', 'Alta') NULL,
    riesgo_placentaria_tardia ENUM('Baja', 'Alta') NULL,
    riesgo_parto_pretermino ENUM('Baja', 'Alta') NULL,
    
    -- Hallazgos anatómicos
    -- NOTA: Los campos booleanos están diseñados para mapear casillas de verificación (checkboxes)
    -- en el frontend. Esto permite al doctor marcar con un solo clic si la estructura
    -- anatómica evaluada es normal o presenta anomalías durante la exploración ecográfica.
    -- Valor TRUE (1) = Estructura normal/no presente anomalía
    -- Valor FALSE (0) = Anomalía detectada
    hueso_nasal_presente BOOLEAN DEFAULT TRUE,
    anatomia_craneo_snc_normal BOOLEAN DEFAULT TRUE,
    anatomia_corazon_normal BOOLEAN DEFAULT TRUE,
    anatomia_abdomen_normal BOOLEAN DEFAULT TRUE,
    anatomia_extremidades_normal BOOLEAN DEFAULT TRUE,
    
    -- Evaluación de placenta y líquido amniótico
    localizacion_placenta VARCHAR(100) NULL,
    liquido_amniotico_normal BOOLEAN DEFAULT TRUE,
    
    -- Comentarios adicionales
    observaciones_comentarios TEXT NULL,
    
    -- Índices para optimizar consultas desde la interfaz de usuario
    INDEX idx_id_consulta (id_consulta),
    INDEX idx_fecha_ultima_regla (fecha_ultima_regla),
    INDEX idx_riesgo_cromosomopatias (riesgo_cromosomopatias),
    
    -- Foreign Key
    CONSTRAINT fk_reporte_1t_consulta FOREIGN KEY (id_consulta)
        REFERENCES Consultas(id_consulta) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT = 'Reportes de seguimiento prenatal primer trimestre - datos de ecografía y evaluaciones de riesgo';
