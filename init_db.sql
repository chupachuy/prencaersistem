-- Base de datos: prenacersistem
-- CREATE DATABASE IF NOT EXISTS prenacersistem DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; -- Usually not needed if already exists
SET NAMES utf8mb4;
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
	ruta_firma VARCHAR(255) NULL,
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
  
-- Tabla de consultas médicas
CREATE TABLE IF NOT EXISTS Consultas (
	id_consulta INT PRIMARY KEY AUTO_INCREMENT,
	id_paciente INT NOT NULL,
	motivo_consulta VARCHAR(255) NULL,
	observaciones TEXT NULL,
	fecha_consulta DATETIME DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (id_paciente) REFERENCES pacientes(id),
	INDEX idx_paciente_consulta (id_paciente)
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

-- Tabla de referencias medicas (remisiones)
CREATE TABLE IF NOT EXISTS referencias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo_referencia VARCHAR(50) UNIQUE NOT NULL,
    paciente_id INT NOT NULL,
    medico_solicitante_id INT NOT NULL,
    medico_referido_id INT NOT NULL,
    tipo_estudio VARCHAR(255) NOT NULL,
    motivo_referencia TEXT NOT NULL,
    observaciones TEXT,
    estado ENUM('Pendiente','Aceptada','Rechazada','Completada') DEFAULT 'Pendiente',
    fecha_referencia DATE NOT NULL,
    fecha_respuesta DATE NULL,
    respuesta_motivo TEXT NULL,
    informe_exploracion_id INT NULL,
    activo BOOLEAN DEFAULT TRUE,
    created_by INT NOT NULL,
    updated_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (medico_solicitante_id) REFERENCES usuarios(id),
    FOREIGN KEY (medico_referido_id) REFERENCES usuarios(id),
    FOREIGN KEY (informe_exploracion_id) REFERENCES informes_exploracion(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_codigo_referencia (codigo_referencia),
    INDEX idx_paciente_referencia (paciente_id),
    INDEX idx_medico_solicitante (medico_solicitante_id),
    INDEX idx_medico_referido (medico_referido_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de medicos referidos externos
CREATE TABLE IF NOT EXISTS medicos_referidos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    especialidad VARCHAR(100),
    institucion VARCHAR(150),
    activo BOOLEAN DEFAULT TRUE,
    created_by INT NOT NULL,
    updated_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: Catálogo de Consentimientos
CREATE TABLE IF NOT EXISTS catalogo_consentimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_documento VARCHAR(150) NOT NULL,
    version VARCHAR(10),
    contenido TEXT NULL,
    requiere_firma_medico BOOLEAN DEFAULT TRUE,
    cantidad_testigos INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla: Consentimientos Asignados
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
    INDEX idx_asignacion_firmas (asignacion_id)
);

-- Seed: Documentos por defecto
INSERT IGNORE INTO catalogo_consentimientos (id, nombre_documento, version, requiere_firma_medico, cantidad_testigos) VALUES
(1, 'Consentimiento Informado DIU Mirena', 'v1.0', TRUE, 2),
(2, 'Aviso de Privacidad', 'v1.0', FALSE, 0),
(3, 'Consentimiento Informado Ultrasonido Nivel II', 'v1.0', TRUE, 2),
(4, 'Consentimiento Informado Evaluación Ginecológica', 'v1.0', TRUE, 1),
(5, 'Consentimiento Informado General', 'v1.0', TRUE, 1);

-- ============================================================
-- NUEVO SISTEMA: Evaluaciones 1er Trimestre
-- ============================================================

-- Tabla: historial_clinico (Antecedentes y Factores de Riesgo por paciente)
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
    UNIQUE KEY unique_paciente_historial (paciente_id),
    INDEX idx_paciente_historial (paciente_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: evaluaciones_1er_trimestre (Central: signos vitales + biometría fetal)
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
    fum DATE NULL COMMENT 'Fecha de última regla',
    fpp_usg DATE NULL COMMENT 'Fecha probable de parto por USG',
    embarazo_multiple BOOLEAN DEFAULT FALSE,
    estado_feto ENUM('Vivo','Muerto') DEFAULT 'Vivo',
    fcf_lpm INT NULL COMMENT 'Frecuencia Cardiaca Fetal',
    lcc_mm DECIMAL(5,2) NULL COMMENT 'Longitud Cráneo Cauda',
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
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_paciente_evaluacion (paciente_id),
    INDEX idx_codigo (codigo_reporte)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: anatomia_fetal (Exploración estructural cualitativa)
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

-- Tabla: marcadores_fmf (Checklist ecográfico estándar Fetal Medicine Foundation)
CREATE TABLE IF NOT EXISTS marcadores_fmf (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    translucencia_nucal_mm DECIMAL(4,2) NULL,
    hueso_nasal_presente BOOLEAN DEFAULT TRUE,
    ductus_venoso_onda_a ENUM('Positiva','Reversa','Ausente') NULL,
    regurgitacion_tricuspidea_ausente BOOLEAN DEFAULT TRUE,
    vejiga_fetal_mm DECIMAL(4,2) NULL,
    uta_pi_promedio DECIMAL(4,2) NULL COMMENT 'Índice de pulsatilidad arterias uterinas',
    muesca_bilateral BOOLEAN DEFAULT FALSE,
    papp_a_mom DECIMAL(5,2) NULL COMMENT 'PAPP-A MoM',
    plgf_mom DECIMAL(5,2) NULL COMMENT 'PlGF MoM',
    tamizaje_genetico_tipo VARCHAR(50) DEFAULT 'No realizado',
    tamizaje_genetico_resultado VARCHAR(100) NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_1er_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_marcadores (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: entorno_materno (Evaluación útero-placentaria)
CREATE TABLE IF NOT EXISTS entorno_materno (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    liquido_amniotico ENUM('Normal','Anormal') DEFAULT 'Normal',
    placenta_posicion ENUM('Anterior','Posterior','Lateral Derecho','Lateral Izquierdo') NULL,
    placenta_insercion ENUM('Normal','Baja Temprana','Previa Temprana') NULL,
    longitud_cervical_mm DECIMAL(5,2) NULL,
    indice_consistencia_cervical_pct INT NULL,
    morfologia_uterina_eshre ENUM('U0','U1','U2','U3','U4','U5','U6') NULL COMMENT 'Clasificación ESHRE-ESGE',
    miomas_visibles BOOLEAN DEFAULT FALSE,
    miomas_figo_tipo VARCHAR(50) NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_1er_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_entorno (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: impresion_diagnostica (Resultados de tamizaje y semáforos de riesgo)
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

-- ============================================================
-- EVALUACIONES 2DO TRIMESTRE
-- ============================================================

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
    peso_1er_trimestre_kg DECIMAL(5,2) NULL,
    ganancia_peso_kg DECIMAL(5,2) NULL,
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
    morfologia_uterina_eshre ENUM('U0','U1','U2','U3','U4','U5','U6') NULL COMMENT 'Clasificacion ESHRE-ESGE',
    miomas_visibles BOOLEAN DEFAULT FALSE,
    miomas_figo_tipo VARCHAR(50) NULL,
    miomas_dimensiones_mm VARCHAR(100) NULL,
    miomas_vascularizacion VARCHAR(100) NULL,
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

-- ============================================================
-- EVALUACIONES 3ER TRIMESTRE
-- ============================================================

CREATE TABLE IF NOT EXISTS evaluaciones_3er_trimestre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    medico_id INT NOT NULL,
    codigo_reporte VARCHAR(50) UNIQUE NOT NULL,
    fecha_evaluacion DATE NOT NULL,
    fecha_estudio DATE NULL,
    estudio_solicitado VARCHAR(200) NULL,
    edad_gestacional_semanas DECIMAL(4,1) NULL,
    fpp_fum DATE NULL,
    fpp_usg DATE NULL,
    peso_kg DECIMAL(5,2) NULL,
    talla_cm DECIMAL(5,2) NULL,
    ta_sistolica INT NULL,
    ta_diastolica INT NULL,
    situacion_fetal ENUM('Longitudinal','Transversa') NULL,
    presentacion_fetal ENUM('Cefalico','Pelvico') NULL,
    posicion_fetal VARCHAR(50) NULL,
    feto_unico_vivo ENUM('Vivo','Muerto','No evaluado') NULL,
    fcf_lpm INT NULL,
    equipo_ultrasonido VARCHAR(200) NULL,
    observaciones TEXT NULL,
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
    checklist_riesgo_preeclampsia_1t VARCHAR(20) NULL,
    checklist_doppler_uterino_1t_pi DECIMAL(4,2) NULL,
    checklist_doppler_uterino_1t_muesca BOOLEAN NULL,
    checklist_papp_a_mom DECIMAL(5,2) NULL,
    checklist_plgf_mom DECIMAL(5,2) NULL,
    checklist_tamizaje_genetico_resultado VARCHAR(100) NULL,
    checklist_longitud_cervical_1t_mm DECIMAL(5,2) NULL,
    checklist_morfologia_fetal_2t_normal BOOLEAN NULL,
    checklist_doppler_uterino_2t_pi DECIMAL(4,2) NULL,
    checklist_placenta_2t_posicion VARCHAR(50) NULL,
    checklist_placenta_2t_acretismo VARCHAR(20) NULL,
    checklist_longitud_cervical_2t_mm DECIMAL(5,2) NULL,
    checklist_funneling_2t_presente BOOLEAN NULL,
    checklist_sludge_2t ENUM('Si','No','Dudoso') NULL,
    checklist_icc_2t_pct INT NULL,
    checklist_rciu_2t_signos BOOLEAN NULL,
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
    au_pi DECIMAL(4,2) NULL,
    au_flujo_diastolico ENUM('Presente','Ausente','Reverso') NULL,
    acm_pi DECIMAL(4,2) NULL,
    dv_onda_a ENUM('Positiva','Ausente','Reversa') NULL,
    uta_pi_promedio DECIMAL(4,2) NULL,
    ratio_cu_icp DECIMAL(4,2) NULL,
    vena_umbilical ENUM('Normal','Pulsatil') NULL,
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
    estructuras_normales BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_3er_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_anatomia3 (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS evaluacion_placentaria_3er_trimestre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    localizacion_placentaria ENUM('Anterior','Posterior','Fundica','Lateral','Lateral Derecha','Lateral Izquierda') NULL,
    distancia_oci_mm DECIMAL(5,2) NULL,
    grosor_placentario_mm INT NULL,
    grado_madurez ENUM('Grado 0-1','Grado 2','Grado 3') NULL,
    ecogenicidad ENUM('Homogenea','Heterogenea') NULL,
    lagunas_vasculares ENUM('Ausentes/minimas','Si','Extensas') DEFAULT 'Ausentes/minimas',
    interfase_miometrial ENUM('Intacta','Adelgazada','Discontinua') DEFAULT 'Intacta',
    vasos_puente BOOLEAN DEFAULT FALSE,
    zona_retroplacentaria ENUM('Presente','Ausente') NULL,
    protrusion_placentaria BOOLEAN DEFAULT FALSE,
    vascularizacion_anomala_doppler ENUM('Normal','Turbulento','Extendido a vejiga') NULL,
    insercion_cordon ENUM('Central','Paracentral','Marginal','Velamentosa') NULL,
    numero_vasos_umbilicales ENUM('3','2') NULL,
    calcificaciones ENUM('Ausentes','Moderadas','Extensas') NULL,
    perfusion_vi DECIMAL(5,2) NULL,
    perfusion_fi DECIMAL(5,2) NULL,
    perfusion_vfi DECIMAL(5,2) NULL,
    acretismo_figo_pas ENUM('Grado 0','Grado 1','Grado 2','Grado 3') DEFAULT 'Grado 0',
    morfologia_uterina_eshre ENUM('U0','U1','U2','U3','U4','U5','U6') NULL COMMENT 'Clasificacion ESHRE-ESGE',
    miomas_visibles BOOLEAN DEFAULT FALSE,
    miomas_figo_tipo VARCHAR(50) NULL,
    miomas_dimensiones_mm VARCHAR(100) NULL,
    miomas_obstruyen_canal BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_3er_trimestre(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_placentaria3 (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para imagenes de evaluaciones trimestrales
CREATE TABLE IF NOT EXISTS imagenes_evaluacion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    trimestre ENUM('1','2','3') NOT NULL,
    evaluacion_id INT NOT NULL,
    ruta_imagen VARCHAR(255) NOT NULL,
    nombre_original VARCHAR(255) NULL,
    mime_type VARCHAR(50) NULL,
    tamanio_bytes INT NULL,
    orden INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_trim_eval (trimestre, evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- ULTRASONIDO GINECOLOGICO ENDOVAGINAL
-- ==========================================
CREATE TABLE IF NOT EXISTS evaluaciones_ginecologicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    medico_id INT NOT NULL COMMENT 'Medico que realiza el estudio',
    medico_solicitante_id INT NULL COMMENT 'Medico solicitante',
    codigo_reporte VARCHAR(50) UNIQUE NOT NULL,
    fecha_estudio DATE NOT NULL,
    indicacion_clinica TEXT NULL,
    fum DATE NULL COMMENT 'Fecha de ultima menstruacion',
    dia_ciclo_menstrual INT NULL,
    observaciones TEXT NULL,
    estado ENUM('Pendiente','En proceso','Completado','Archivado') DEFAULT 'Pendiente',
    activo BOOLEAN DEFAULT TRUE,
    created_by INT NULL,
    updated_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (medico_id) REFERENCES usuarios(id),
    FOREIGN KEY (medico_solicitante_id) REFERENCES usuarios(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_paciente_gine (paciente_id),
    INDEX idx_codigo_gine (codigo_reporte)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS indicaciones_ginecologicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    sangrado_uterino_anormal BOOLEAN DEFAULT FALSE,
    dolor_pelvico BOOLEAN DEFAULT FALSE,
    miomatosis_uterina BOOLEAN DEFAULT FALSE,
    sospecha_polipo_endometrial BOOLEAN DEFAULT FALSE,
    engrosamiento_endometrial BOOLEAN DEFAULT FALSE,
    control_diu BOOLEAN DEFAULT FALSE,
    infertilidad_reproduccion BOOLEAN DEFAULT FALSE,
    quiste_ovarico_masa_anexial BOOLEAN DEFAULT FALSE,
    sindrome_climaterico BOOLEAN DEFAULT FALSE,
    sangrado_posmenopausico BOOLEAN DEFAULT FALSE,
    motivo_estudio_otro VARCHAR(255) NULL,
    premenopausica BOOLEAN DEFAULT FALSE,
    perimenopausica BOOLEAN DEFAULT FALSE,
    posmenopausica BOOLEAN DEFAULT FALSE,
    terapia_hormonal BOOLEAN DEFAULT FALSE,
    tamoxifeno BOOLEAN DEFAULT FALSE,
    anticonceptivos_hormonales BOOLEAN DEFAULT FALSE,
    estatus_no_especificado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_indicaciones (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS antecedentes_ginecologicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    gesta INT NULL,
    para INT NULL,
    cesareas INT NULL,
    abortos INT NULL,
    paridad_satisfecha BOOLEAN NULL,
    legrado_cirugia_uterina BOOLEAN DEFAULT FALSE,
    miomectomia BOOLEAN DEFAULT FALSE,
    endometriosis_adenomiosis BOOLEAN DEFAULT FALSE,
    otros TEXT NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_antecedentes (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tecnica_ultrasonido_ginecologico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    via_endovaginal BOOLEAN DEFAULT FALSE,
    via_transabdominal BOOLEAN DEFAULT FALSE,
    via_doppler_color BOOLEAN DEFAULT FALSE,
    via_evaluacion_3d BOOLEAN DEFAULT FALSE,
    via_sonohisterografia BOOLEAN DEFAULT FALSE,
    calidad ENUM('Adecuada','Limitada') NULL,
    limitada_dolor BOOLEAN DEFAULT FALSE,
    limitada_distension_intestinal BOOLEAN DEFAULT FALSE,
    limitada_habitus_corporal BOOLEAN DEFAULT FALSE,
    limitada_posicion_uterina BOOLEAN DEFAULT FALSE,
    calidad_otra VARCHAR(255) NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_tecnica (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS utero_cervix_ginecologico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    situacion ENUM('Anteversoflexion','Retroversoflexion','Intermedia','Lateralizado') NULL,
    morfologia_regular BOOLEAN DEFAULT FALSE,
    morfologia_bordes_irregulares BOOLEAN DEFAULT FALSE,
    morfologia_globoso BOOLEAN DEFAULT FALSE,
    morfologia_aumentado BOOLEAN DEFAULT FALSE,
    morfologia_disminuido BOOLEAN DEFAULT FALSE,
    morfologia_otro VARCHAR(255) NULL,
    dim_longitud_mm DECIMAL(5,2) NULL,
    dim_anteroposterior_mm DECIMAL(5,2) NULL,
    dim_transverso_mm DECIMAL(5,2) NULL,
    volumen_cc DECIMAL(7,2) NULL,
    miometrio_homogeneo BOOLEAN DEFAULT FALSE,
    miometrio_heterogeneo BOOLEAN DEFAULT FALSE,
    miometrio_imagenes_leiomiomas BOOLEAN DEFAULT FALSE,
    miometrio_sugestivo_adenomiosis BOOLEAN DEFAULT FALSE,
    miometrio_calcificaciones BOOLEAN DEFAULT FALSE,
    miometrio_areas_quisticas BOOLEAN DEFAULT FALSE,
    miometrio_sombra_acustica BOOLEAN DEFAULT FALSE,
    miometrio_otro VARCHAR(255) NULL,
    cervix_longitud_mm DECIMAL(5,2) NULL,
    cervix_sin_alteraciones BOOLEAN DEFAULT FALSE,
    cervix_quistes_naboth BOOLEAN DEFAULT FALSE,
    cervix_polipo_endocervical BOOLEAN DEFAULT FALSE,
    cervix_lesion_visible_usg BOOLEAN DEFAULT FALSE,
    cervix_liquido_canal BOOLEAN DEFAULT FALSE,
    cervix_otro VARCHAR(255) NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_utero_cervix (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS miomas_ginecologicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    identificados BOOLEAN DEFAULT FALSE,
    numero_aproximado INT NULL,
    mioma_dominante_mm DECIMAL(5,2) NULL,
    predominio_submucosos BOOLEAN DEFAULT FALSE,
    predominio_intramurales BOOLEAN DEFAULT FALSE,
    predominio_subserosos BOOLEAN DEFAULT FALSE,
    predominio_pediculados BOOLEAN DEFAULT FALSE,
    predominio_cervicales BOOLEAN DEFAULT FALSE,
    predominio_distribucion_difusa BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_miomas (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS miomas_detalle_ginecologico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    numero INT NOT NULL COMMENT 'Numero de mioma en la lista (1, 2, 3...)',
    localizacion ENUM('Fondo','Anterior','Posterior','Lateral','Cervical') NULL,
    medida_x_mm DECIMAL(5,2) NULL,
    medida_y_mm DECIMAL(5,2) NULL,
    medida_z_mm DECIMAL(5,2) NULL,
    relacion_endometrio ENUM('No contacta','Contacta','Desplaza','Distorsiona cavidad') NULL,
    clasificacion_figo VARCHAR(10) NULL COMMENT 'FIGO 0 al 8',
    doppler ENUM('Escaso','Moderado','Aumentado') NULL,
    comentarios TEXT NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    INDEX idx_evaluacion_miomas_det (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS adenomiosis_ginecologica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    hallazgos ENUM('No se observan','Si se observan','Indeterminado') NULL,
    utero_globoso BOOLEAN DEFAULT FALSE,
    asimetria_paredes BOOLEAN DEFAULT FALSE,
    miometrio_heterogeneo BOOLEAN DEFAULT FALSE,
    estriaciones_lineales BOOLEAN DEFAULT FALSE,
    quistes_miometriales BOOLEAN DEFAULT FALSE,
    islas_hiperecogenicas BOOLEAN DEFAULT FALSE,
    sombra_abanico BOOLEAN DEFAULT FALSE,
    zona_union_irregular BOOLEAN DEFAULT FALSE,
    vascularidad_translesional BOOLEAN DEFAULT FALSE,
    datos_otro VARCHAR(255) NULL,
    distribucion ENUM('Difusa','Focal') NULL,
    predominio_anterior BOOLEAN DEFAULT FALSE,
    predominio_posterior BOOLEAN DEFAULT FALSE,
    predominio_fundico BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_adenomiosis (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS endometrio_ginecologico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    grosor_mm DECIMAL(5,2) NULL,
    patron ENUM('Lineal','Trilaminar','Hiperecogenico','Heterogeneo','Quistico','Irregular','NoValorable') NULL,
    correlacion_ciclo ENUM('Acorde','Engrosado','Delgado','NoValorableSangrado','NoValorableMiomas') NULL,
    cavidad_regular BOOLEAN DEFAULT FALSE,
    cavidad_distorsionada BOOLEAN DEFAULT FALSE,
    cavidad_liquido_intracavitario BOOLEAN DEFAULT FALSE,
    cavidad_imagen_focal_polipo BOOLEAN DEFAULT FALSE,
    cavidad_imagen_mioma_submucoso BOOLEAN DEFAULT FALSE,
    cavidad_sinequias BOOLEAN DEFAULT FALSE,
    cavidad_diu_intrauterino BOOLEAN DEFAULT FALSE,
    cavidad_otro VARCHAR(255) NULL,
    doppler ENUM('SinVascularidad','VasoUnicoPolipo','VascularidadDifusa','VascularidadIrregular','NoEvaluado') NULL,
    diu_posicion ENUM('Normoinserto','Descendido','ParcialmenteExpulsado','BrazoIncluidoMiometrio','NoVisible') NULL,
    diu_distancia_fondo_mm DECIMAL(5,2) NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_endometrio (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS ovarios_ginecologicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    der_dim_x_mm DECIMAL(5,2) NULL,
    der_dim_y_mm DECIMAL(5,2) NULL,
    der_dim_z_mm DECIMAL(5,2) NULL,
    der_volumen_cc DECIMAL(7,2) NULL,
    der_normal BOOLEAN DEFAULT FALSE,
    der_atrofico BOOLEAN DEFAULT FALSE,
    der_multifolicular BOOLEAN DEFAULT FALSE,
    der_poliquistico BOOLEAN DEFAULT FALSE,
    der_cuerpo_luteo BOOLEAN DEFAULT FALSE,
    der_quiste_simple BOOLEAN DEFAULT FALSE,
    der_quiste_hemorragico BOOLEAN DEFAULT FALSE,
    der_endometrioma BOOLEAN DEFAULT FALSE,
    der_lesion_solida BOOLEAN DEFAULT FALSE,
    der_lesion_compleja BOOLEAN DEFAULT FALSE,
    der_no_visible BOOLEAN DEFAULT FALSE,
    der_foliculo_med_x_mm DECIMAL(5,2) NULL,
    der_foliculo_med_y_mm DECIMAL(5,2) NULL,
    der_foliculo_med_z_mm DECIMAL(5,2) NULL,
    der_foliculo_contenido ENUM('Anecoico','Hemorragico','EcosFinos','Solido','Mixto') NULL,
    der_foliculo_pared ENUM('Fina','Gruesa','Irregular') NULL,
    der_foliculo_septos BOOLEAN DEFAULT FALSE,
    der_foliculo_septos_grosor DECIMAL(5,2) NULL,
    der_foliculo_papilares BOOLEAN DEFAULT FALSE,
    der_foliculo_papilares_num INT NULL,
    der_foliculo_sombra BOOLEAN DEFAULT FALSE,
    der_foliculo_doppler ENUM('SinFlujo','FlujoPeriferico','FlujoCentral','FlujoComponenteSolido') NULL,
    izq_dim_x_mm DECIMAL(5,2) NULL,
    izq_dim_y_mm DECIMAL(5,2) NULL,
    izq_dim_z_mm DECIMAL(5,2) NULL,
    izq_volumen_cc DECIMAL(7,2) NULL,
    izq_normal BOOLEAN DEFAULT FALSE,
    izq_atrofico BOOLEAN DEFAULT FALSE,
    izq_multifolicular BOOLEAN DEFAULT FALSE,
    izq_poliquistico BOOLEAN DEFAULT FALSE,
    izq_cuerpo_luteo BOOLEAN DEFAULT FALSE,
    izq_quiste_simple BOOLEAN DEFAULT FALSE,
    izq_quiste_hemorragico BOOLEAN DEFAULT FALSE,
    izq_endometrioma BOOLEAN DEFAULT FALSE,
    izq_lesion_solida BOOLEAN DEFAULT FALSE,
    izq_lesion_compleja BOOLEAN DEFAULT FALSE,
    izq_no_visible BOOLEAN DEFAULT FALSE,
    izq_foliculo_med_x_mm DECIMAL(5,2) NULL,
    izq_foliculo_med_y_mm DECIMAL(5,2) NULL,
    izq_foliculo_med_z_mm DECIMAL(5,2) NULL,
    izq_foliculo_contenido ENUM('Anecoico','Hemorragico','EcosFinos','Solido','Mixto') NULL,
    izq_foliculo_pared ENUM('Fina','Gruesa','Irregular') NULL,
    izq_foliculo_septos BOOLEAN DEFAULT FALSE,
    izq_foliculo_septos_grosor DECIMAL(5,2) NULL,
    izq_foliculo_papilares BOOLEAN DEFAULT FALSE,
    izq_foliculo_papilares_num INT NULL,
    izq_foliculo_sombra BOOLEAN DEFAULT FALSE,
    izq_foliculo_doppler ENUM('SinFlujo','FlujoPeriferico','FlujoCentral','FlujoComponenteSolido') NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_ovarios (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS anexos_fondo_saco_ginecologico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    der_sin_alteraciones BOOLEAN DEFAULT FALSE,
    der_lesion_anexial BOOLEAN DEFAULT FALSE,
    der_hidrosalpinx BOOLEAN DEFAULT FALSE,
    der_paraovarico BOOLEAN DEFAULT FALSE,
    der_otro VARCHAR(255) NULL,
    izq_sin_alteraciones BOOLEAN DEFAULT FALSE,
    izq_lesion_anexial BOOLEAN DEFAULT FALSE,
    izq_hidrosalpinx BOOLEAN DEFAULT FALSE,
    izq_paraovarico BOOLEAN DEFAULT FALSE,
    izq_otro VARCHAR(255) NULL,
    fondo_saco_libre BOOLEAN DEFAULT FALSE,
    fondo_saco_liquido_escaso BOOLEAN DEFAULT FALSE,
    fondo_saco_liquido_moderado BOOLEAN DEFAULT FALSE,
    fondo_saco_liquido_abundante BOOLEAN DEFAULT FALSE,
    fondo_saco_liquido_ecos BOOLEAN DEFAULT FALSE,
    fondo_saco_nodulo_implante BOOLEAN DEFAULT FALSE,
    fondo_saco_dolor_presion BOOLEAN DEFAULT FALSE,
    sliding_sign ENUM('Positivo','Negativo','No evaluado') NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_anexos (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS clasificacion_orientativa_ginecologica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    palm_polipo BOOLEAN DEFAULT FALSE COMMENT 'P: Polipo endometrial sospechado',
    palm_adenomiosis BOOLEAN DEFAULT FALSE COMMENT 'A: Adenomiosis sospechada',
    palm_leiomioma BOOLEAN DEFAULT FALSE COMMENT 'L: Leiomioma / miomatosis uterina',
    palm_malignidad BOOLEAN DEFAULT FALSE COMMENT 'M: Malignidad o hiperplasia endometrial por descartar',
    palm_coagulopatia BOOLEAN DEFAULT FALSE COMMENT 'C: Coagulopatia, requiere correlacion clinica/laboratorio',
    palm_ovulatoria BOOLEAN DEFAULT FALSE COMMENT 'O: Disfuncion ovulatoria',
    palm_endometrial BOOLEAN DEFAULT FALSE COMMENT 'E: Causa endometrial funcional',
    palm_iatrogenica BOOLEAN DEFAULT FALSE COMMENT 'I: Iatrogenica / hormonal / farmacos',
    palm_no_clasificada BOOLEAN DEFAULT FALSE COMMENT 'N: No clasificada',
    anexial_funcional BOOLEAN DEFAULT FALSE COMMENT 'Lesion funcional probable',
    anexial_benigna BOOLEAN DEFAULT FALSE COMMENT 'Lesion benigna probable',
    anexial_indeterminada BOOLEAN DEFAULT FALSE COMMENT 'Lesion indeterminada',
    anexial_sospechosa BOOLEAN DEFAULT FALSE COMMENT 'Lesion sospechosa',
    anexial_sugiere_o_rads BOOLEAN DEFAULT FALSE COMMENT 'Se sugiere complementar con O-RADS / IOTA ADNEX',
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_clasificacion (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS impresion_diagnostica_ginecologica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    utero_tamano ENUM('Normal','Aumentado','Disminuido') NULL,
    utero_morfologia TEXT NULL,
    miometrio_sin_alteraciones BOOLEAN DEFAULT FALSE,
    miometrio_miomatosis BOOLEAN DEFAULT FALSE,
    miometrio_adenomiosis BOOLEAN DEFAULT FALSE,
    miometrio_otro TEXT NULL,
    endometrio_grosor_mm DECIMAL(5,2) NULL,
    endometrio_patron TEXT NULL,
    endometrio_acorde_contexto BOOLEAN DEFAULT FALSE,
    endometrio_engrosado_contexto BOOLEAN DEFAULT FALSE,
    endometrio_requiere_correlacion BOOLEAN DEFAULT FALSE,
    ovario_derecho TEXT NULL,
    ovario_izquierdo TEXT NULL,
    anexos_fondo_saco TEXT NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_impresion (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS conclusion_recomendaciones_ginecologicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    estudio_limites_esperados BOOLEAN DEFAULT FALSE,
    miomatosis_uterina BOOLEAN DEFAULT FALSE,
    conclusion_mioma_dominante_mm DECIMAL(5,2) NULL,
    conclusion_figo VARCHAR(10) NULL,
    engrosamiento_endometrial BOOLEAN DEFAULT FALSE,
    conclusion_medida_endometrio_mm DECIMAL(5,2) NULL,
    imagen_focal_polipo BOOLEAN DEFAULT FALSE,
    datos_sugestivos_adenomiosis BOOLEAN DEFAULT FALSE,
    quiste_simple_der BOOLEAN DEFAULT FALSE,
    quiste_simple_izq BOOLEAN DEFAULT FALSE,
    quiste_hemorragico_der BOOLEAN DEFAULT FALSE,
    quiste_hemorragico_izq BOOLEAN DEFAULT FALSE,
    endometrioma_der BOOLEAN DEFAULT FALSE,
    endometrioma_izq BOOLEAN DEFAULT FALSE,
    conclusion_quiste_medida_mm DECIMAL(5,2) NULL,
    masa_anexial_indeterminada BOOLEAN DEFAULT FALSE,
    conclusion_otro TEXT NULL,
    rec_correlacion_edad_fum BOOLEAN DEFAULT FALSE,
    rec_correlacion_hb_hormonal BOOLEAN DEFAULT FALSE,
    rec_estudio_histologico BOOLEAN DEFAULT FALSE,
    rec_histeroscopia_endometrio BOOLEAN DEFAULT FALSE,
    rec_sonohisterografia_histeroscopia BOOLEAN DEFAULT FALSE,
    rec_valorar_manejo_miomatosis BOOLEAN DEFAULT FALSE,
    rec_iorads_marcadores_oncologia BOOLEAN DEFAULT FALSE,
    rec_control_ultrasonografico BOOLEAN DEFAULT FALSE,
    rec_control_tiempo INT NULL,
    rec_control_unidad ENUM('Semanas','Meses') NULL,
    rec_otro TEXT NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_conclusion_rec (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar Superadministrador por defecto (password: Admin123!)
INSERT IGNORE INTO usuarios (id, nombre, apellido, email, password, rol_id, email_verified) VALUES
(1, 'Super', 'Admin', 'superadmin@medical.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, TRUE);
