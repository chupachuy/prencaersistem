-- Tabla: Consultas
-- Propósito: Almacenar consultas/prenatal

CREATE TABLE Consultas (
    id_consulta INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    fecha_consulta DATETIME DEFAULT CURRENT_TIMESTAMP,
    motivo_consulta VARCHAR(255) NULL,
    observaciones TEXT NULL,
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
