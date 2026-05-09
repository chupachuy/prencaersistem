-- Tabla: Catálogo de Consentimientos
CREATE TABLE IF NOT EXISTS catalogo_consentimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_documento VARCHAR(150) NOT NULL,
    version VARCHAR(10),
    requiere_firma_medico BOOLEAN DEFAULT TRUE,
    cantidad_testigos INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla: Consentimientos Asignados (Transaccional)
CREATE TABLE IF NOT EXISTS consentimientos_asignados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    medico_id INT NOT NULL,
    documento_id INT NOT NULL,
    fecha_generacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('Pendiente', 'Parcialmente Firmado', 'Completado', 'Revocado') DEFAULT 'Pendiente',
    datos_dinamicos JSON NULL,
    ruta_pdf_final VARCHAR(255) NULL,
    activo BOOLEAN DEFAULT TRUE,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (medico_id) REFERENCES usuarios(id),
    FOREIGN KEY (documento_id) REFERENCES catalogo_consentimientos(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id)
);

-- Tabla: Registro de Firmas
CREATE TABLE IF NOT EXISTS registro_firmas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    asignacion_id INT NOT NULL,
    rol_firmante ENUM('Paciente', 'Medico', 'Testigo 1', 'Testigo 2') NOT NULL,
    nombre_firmante VARCHAR(150) NOT NULL,
    tipo_accion ENUM('Aceptacion', 'Denegacion') DEFAULT 'Aceptacion',
    ruta_imagen_firma VARCHAR(255) NOT NULL,
    fecha_firma DATETIME DEFAULT CURRENT_TIMESTAMP,
    ip_origen VARCHAR(45) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (asignacion_id) REFERENCES consentimientos_asignados(id),
    INDEX idx_asignacion (asignacion_id)
);

-- Seed: Documentos por defecto en el catálogo
INSERT IGNORE INTO catalogo_consentimientos (id, nombre_documento, version, requiere_firma_medico, cantidad_testigos) VALUES
(1, 'Consentimiento Informado DIU Mirena', 'v1.0', TRUE, 2),
(2, 'Aviso de Privacidad', 'v1.0', FALSE, 0),
(3, 'Consentimiento Informado Ultrasonido Nivel II', 'v1.0', TRUE, 2),
(4, 'Consentimiento Informado Evaluación Ginecológica', 'v1.0', TRUE, 1),
(5, 'Consentimiento Informado General', 'v1.0', TRUE, 1);
