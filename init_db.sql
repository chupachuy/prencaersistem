-- Base de datos: prenacersistem
-- CREATE DATABASE IF NOT EXISTS prenacersistem; -- Usually not needed if already exists
USE prenacersistem;

-- Tabla de roles
CREATE TABLE IF NOT EXISTS roles (
	id INT PRIMARY KEY AUTO_INCREMENT,
	nombre VARCHAR(50) UNIQUE NOT NULL,
	descripcion TEXT,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
 
-- Tabla de usuarios (ACTUALIZADA con campos para autenticación)
CREATE TABLE IF NOT EXISTS usuarios (
	id INT PRIMARY KEY AUTO_INCREMENT,
	nombre VARCHAR(100) NOT NULL,
	apellido VARCHAR(100) NOT NULL,
	email VARCHAR(100) UNIQUE NOT NULL,
	password VARCHAR(255) NOT NULL,
	telefono VARCHAR(20),
	especialidad VARCHAR(100),
	rol_id INT,
	activo BOOLEAN DEFAULT TRUE,
	email_verified BOOLEAN DEFAULT FALSE,
	email_verified_at TIMESTAMP NULL,
	remember_token VARCHAR(100) NULL,
	last_login TIMESTAMP NULL,
	last_login_ip VARCHAR(45),
	login_attempts INT DEFAULT 0,
	locked_until TIMESTAMP NULL,
	created_by INT,
	updated_by INT,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	FOREIGN KEY (rol_id) REFERENCES roles(id),
	FOREIGN KEY (created_by) REFERENCES usuarios(id),
	FOREIGN KEY (updated_by) REFERENCES usuarios(id),
	INDEX idx_email (email),
	INDEX idx_activo (activo)
);
 
-- Tabla para recuperación de contraseñas (NUEVA)
CREATE TABLE IF NOT EXISTS password_resets (
	id INT PRIMARY KEY AUTO_INCREMENT,
	email VARCHAR(100) NOT NULL,
	token VARCHAR(255) NOT NULL,
	expires_at TIMESTAMP NOT NULL,
	used BOOLEAN DEFAULT FALSE,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	INDEX idx_token (token),
	INDEX idx_email_token (email, token)
);
 
-- Tabla de pacientes
CREATE TABLE IF NOT EXISTS pacientes (
	id INT PRIMARY KEY AUTO_INCREMENT,
	nombre VARCHAR(100) NOT NULL,
	apellido VARCHAR(100) NOT NULL,
	fecha_nacimiento DATE NOT NULL,
	email VARCHAR(100),
	telefono VARCHAR(20),
	direccion TEXT,
	historial_medico TEXT,
	created_by INT,
	updated_by INT,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	FOREIGN KEY (created_by) REFERENCES usuarios(id),
	FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);
 
-- Tabla de diagnósticos
CREATE TABLE IF NOT EXISTS diagnosticos (
	id INT PRIMARY KEY AUTO_INCREMENT,
	paciente_id INT NOT NULL,
	medico_id INT NOT NULL,
	asignado_por INT NOT NULL,
	codigo_diagnostico VARCHAR(20),
	titulo VARCHAR(200) NOT NULL,
	descripcion TEXT,
	fecha_diagnostico DATE NOT NULL,
	fecha_control DATE,
	gravedad ENUM('Leve', 'Moderado', 'Grave', 'Crítico') DEFAULT 'Leve',
	estado ENUM('Activo', 'En tratamiento', 'Controlado', 'Resuelto') DEFAULT 'Activo',
	observaciones TEXT,
	activo BOOLEAN DEFAULT TRUE,
	created_by INT,
	updated_by INT,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
	FOREIGN KEY (medico_id) REFERENCES usuarios(id),
	FOREIGN KEY (asignado_por) REFERENCES usuarios(id),
	FOREIGN KEY (created_by) REFERENCES usuarios(id),
	FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);
 
-- Tabla de tratamientos
CREATE TABLE IF NOT EXISTS tratamientos (
	id INT PRIMARY KEY AUTO_INCREMENT,
	diagnostico_id INT NOT NULL,
	medicamento VARCHAR(200),
	dosis VARCHAR(100),
	frecuencia VARCHAR(100),
	duracion VARCHAR(100),
	instrucciones TEXT,
	fecha_inicio DATE,
	fecha_fin DATE,
	activo BOOLEAN DEFAULT TRUE,
	created_by INT,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (diagnostico_id) REFERENCES diagnosticos(id),
	FOREIGN KEY (created_by) REFERENCES usuarios(id)
);
 
-- Tabla de asignaciones médico-paciente
CREATE TABLE IF NOT EXISTS asignaciones (
	id INT PRIMARY KEY AUTO_INCREMENT,
	medico_id INT NOT NULL,
	paciente_id INT NOT NULL,
	asignado_por INT NOT NULL,
	fecha_asignacion DATE NOT NULL,
	fecha_fin DATE,
	motivo TEXT,
	activo BOOLEAN DEFAULT TRUE,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	FOREIGN KEY (medico_id) REFERENCES usuarios(id),
	FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
	FOREIGN KEY (asignado_por) REFERENCES usuarios(id),
	UNIQUE KEY unique_asignacion_activa (medico_id, paciente_id, activo)
);
 
-- Tabla de sesiones activas (para control)
CREATE TABLE IF NOT EXISTS user_sessions (
	id INT PRIMARY KEY AUTO_INCREMENT,
	user_id INT NOT NULL,
	session_token VARCHAR(255) NOT NULL,
	ip_address VARCHAR(45),
	user_agent TEXT,
	expires_at TIMESTAMP NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE,
	INDEX idx_token (session_token)
);
 
-- Tabla de bitácora (logs)
CREATE TABLE IF NOT EXISTS bitacora (
	id INT PRIMARY KEY AUTO_INCREMENT,
	usuario_id INT,
	accion VARCHAR(100) NOT NULL,
	tabla_afectada VARCHAR(50),
	registro_id INT,
	datos_anteriores TEXT,
	datos_nuevos TEXT,
	ip_address VARCHAR(45),
	user_agent TEXT,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
 
-- Insertar roles iniciales
INSERT IGNORE INTO roles (id, nombre, descripcion) VALUES
(1, 'Superadministrador', 'Acceso total al sistema'),
(2, 'Administrador', 'Gestión de médicos y asignaciones'),
(3, 'Jefe de Médicos', 'Supervisión de diagnósticos y médicos'),
(4, 'Médico', 'Creación y gestión de diagnósticos');
 
-- Tabla de informes de exploración estructural (3 por trimestre)
CREATE TABLE IF NOT EXISTS informes_exploracion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    paciente_id INT NOT NULL,
    medico_id INT NOT NULL,
    medico_referido_id INT NOT NULL,
    
    -- Identificador único del informe
    codigo_informe VARCHAR(50) UNIQUE NOT NULL,
    
    -- Trimestre (1, 2, o 3)
    trimestre ENUM('1', '2', '3') NOT NULL,
    
    -- Datos del informe
    fecha_informe DATE NOT NULL,
    estudio_solicitado VARCHAR(255),
    
    -- Datos del ultrasonido (USG)
    fecha_publicacion_parto_usg DATE,
    fecha_probable_parto_usg DATE,
    resumen_ultrasonido TEXT,
    
    -- Observaciones adicionales
    observaciones TEXT,
    
    -- Estado del informe
    estado ENUM('Pendiente', 'En proceso', 'Completado', 'Archivado') DEFAULT 'Pendiente',
    
    activo BOOLEAN DEFAULT TRUE,
    created_by INT,
    updated_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (medico_id) REFERENCES usuarios(id),
    FOREIGN KEY (medico_referido_id) REFERENCES usuarios(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_paciente_trimestre (paciente_id, trimestre),
    INDEX idx_codigo (codigo_informe)
);

-- Tabla de diagnósticos relacionados con informe de exploración
CREATE TABLE IF NOT EXISTS diagnosticos_exploracion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    informe_exploracion_id INT NOT NULL,
    paciente_id INT NOT NULL,
    medico_id INT NOT NULL,
    
    codigo_diagnostico VARCHAR(20),
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    fecha_diagnostico DATE NOT NULL,
    
    FOREIGN KEY (informe_exploracion_id) REFERENCES informes_exploracion(id),
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (medico_id) REFERENCES usuarios(id),
    INDEX idx_informe (informe_exploracion_id)
);

-- Insertar Superadministrador por defecto (password: Admin123!)
INSERT IGNORE INTO usuarios (id, nombre, apellido, email, password, rol_id, email_verified) VALUES
(1, 'Super', 'Admin', 'superadmin@medical.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, TRUE);
