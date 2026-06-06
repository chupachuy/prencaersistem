-- ============================================================
-- REPORTE DE ULTRASONIDO GINECOLÓGICO ENDOVAGINAL
-- Ejecutar en phpMyAdmin → pestaña SQL, BD: prenacersistem
-- ============================================================

SET NAMES utf8mb4;

-- ==========================================
-- TABLA CABEZA
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

-- ==========================================
-- 1. INDICACIONES (Motivo de estudio + Estatus hormonal)
-- ==========================================
CREATE TABLE IF NOT EXISTS indicaciones_ginecologicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    -- Motivo del estudio
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
    -- Estatus hormonal
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

-- ==========================================
-- 2. ANTECEDENTES RELEVANTES
-- ==========================================
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

-- ==========================================
-- 3. TÉCNICA DEL ULTRASONIDO
-- ==========================================
CREATE TABLE IF NOT EXISTS tecnica_ultrasonido_ginecologico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    -- Via de exploracion
    via_endovaginal BOOLEAN DEFAULT FALSE,
    via_transabdominal BOOLEAN DEFAULT FALSE,
    via_doppler_color BOOLEAN DEFAULT FALSE,
    via_evaluacion_3d BOOLEAN DEFAULT FALSE,
    via_sonohisterografia BOOLEAN DEFAULT FALSE,
    -- Calidad del estudio
    calidad ENUM('Adecuada','Limitada') NULL,
    limitada_dolor BOOLEAN DEFAULT FALSE,
    limitada_distension_intestinal BOOLEAN DEFAULT FALSE,
    limitada_habitus_corporal BOOLEAN DEFAULT FALSE,
    limitada_posicion_uterina BOOLEAN DEFAULT FALSE,
    calidad_otra VARCHAR(255) NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_tecnica (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 4. ÚTERO + CÉRVIX
-- ==========================================
CREATE TABLE IF NOT EXISTS utero_cervix_ginecologico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    -- Situacion uterina
    situacion ENUM('Anteversoflexion','Retroversoflexion','Intermedia','Lateralizado') NULL,
    -- Morfologia
    morfologia_regular BOOLEAN DEFAULT FALSE,
    morfologia_bordes_irregulares BOOLEAN DEFAULT FALSE,
    morfologia_globoso BOOLEAN DEFAULT FALSE,
    morfologia_aumentado BOOLEAN DEFAULT FALSE,
    morfologia_disminuido BOOLEAN DEFAULT FALSE,
    morfologia_otro VARCHAR(255) NULL,
    -- Dimensiones uterinas
    dim_longitud_mm DECIMAL(5,2) NULL,
    dim_anteroposterior_mm DECIMAL(5,2) NULL,
    dim_transverso_mm DECIMAL(5,2) NULL,
    volumen_cc DECIMAL(7,2) NULL,
    -- Miometrio
    miometrio_homogeneo BOOLEAN DEFAULT FALSE,
    miometrio_heterogeneo BOOLEAN DEFAULT FALSE,
    miometrio_imagenes_leiomiomas BOOLEAN DEFAULT FALSE,
    miometrio_sugestivo_adenomiosis BOOLEAN DEFAULT FALSE,
    miometrio_calcificaciones BOOLEAN DEFAULT FALSE,
    miometrio_areas_quisticas BOOLEAN DEFAULT FALSE,
    miometrio_sombra_acustica BOOLEAN DEFAULT FALSE,
    miometrio_otro VARCHAR(255) NULL,
    -- Cervix
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

-- ==========================================
-- 5. MIOMAS / LEIOMIOMAS (Resumen 1:1)
-- ==========================================
CREATE TABLE IF NOT EXISTS miomas_ginecologicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    identificados BOOLEAN DEFAULT FALSE,
    numero_aproximado INT NULL,
    mioma_dominante_mm DECIMAL(5,2) NULL,
    -- Predominio
    predominio_submucosos BOOLEAN DEFAULT FALSE,
    predominio_intramurales BOOLEAN DEFAULT FALSE,
    predominio_subserosos BOOLEAN DEFAULT FALSE,
    predominio_pediculados BOOLEAN DEFAULT FALSE,
    predominio_cervicales BOOLEAN DEFAULT FALSE,
    predominio_distribucion_difusa BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_miomas (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 5b. MIOMAS DETALLE (1:N — un reporte puede listar varios miomas)
-- ==========================================
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

-- ==========================================
-- 6. ADENOMIOSIS
-- ==========================================
CREATE TABLE IF NOT EXISTS adenomiosis_ginecologica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    hallazgos ENUM('No se observan','Si se observan','Indeterminado') NULL,
    -- Datos sonograficos
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
    -- Distribucion
    distribucion ENUM('Difusa','Focal') NULL,
    predominio_anterior BOOLEAN DEFAULT FALSE,
    predominio_posterior BOOLEAN DEFAULT FALSE,
    predominio_fundico BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_adenomiosis (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 7. ENDOMETRIO
-- ==========================================
CREATE TABLE IF NOT EXISTS endometrio_ginecologico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    grosor_mm DECIMAL(5,2) NULL,
    patron ENUM('Lineal','Trilaminar','Hiperecogenico','Heterogeneo','Quistico','Irregular','NoValorable') NULL,
    -- Correlacion con dia del ciclo / estatus hormonal
    correlacion_ciclo ENUM('Acorde','Engrosado','Delgado','NoValorableSangrado','NoValorableMiomas') NULL,
    -- Cavidad endometrial
    cavidad_regular BOOLEAN DEFAULT FALSE,
    cavidad_distorsionada BOOLEAN DEFAULT FALSE,
    cavidad_liquido_intracavitario BOOLEAN DEFAULT FALSE,
    cavidad_imagen_focal_polipo BOOLEAN DEFAULT FALSE,
    cavidad_imagen_mioma_submucoso BOOLEAN DEFAULT FALSE,
    cavidad_sinequias BOOLEAN DEFAULT FALSE,
    cavidad_diu_intrauterino BOOLEAN DEFAULT FALSE,
    cavidad_otro VARCHAR(255) NULL,
    -- Doppler endometrial / intracavitario
    doppler ENUM('SinVascularidad','VasoUnicoPolipo','VascularidadDifusa','VascularidadIrregular','NoEvaluado') NULL,
    -- DIU
    diu_posicion ENUM('Normoinserto','Descendido','ParcialmenteExpulsado','BrazoIncluidoMiometrio','NoVisible') NULL,
    diu_distancia_fondo_mm DECIMAL(5,2) NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_endometrio (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 8. OVARIOS (Derecho + Izquierdo unificados)
-- ==========================================
CREATE TABLE IF NOT EXISTS ovarios_ginecologicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    -- Ovario derecho: dimensiones
    der_dim_x_mm DECIMAL(5,2) NULL,
    der_dim_y_mm DECIMAL(5,2) NULL,
    der_dim_z_mm DECIMAL(5,2) NULL,
    der_volumen_cc DECIMAL(7,2) NULL,
    -- Ovario derecho: morfologia
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
    -- Ovario derecho: foliculo / lesion
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
    -- Ovario izquierdo: dimensiones
    izq_dim_x_mm DECIMAL(5,2) NULL,
    izq_dim_y_mm DECIMAL(5,2) NULL,
    izq_dim_z_mm DECIMAL(5,2) NULL,
    izq_volumen_cc DECIMAL(7,2) NULL,
    -- Ovario izquierdo: morfologia
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
    -- Ovario izquierdo: foliculo / lesion
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

-- ==========================================
-- 9. ANEXOS + FONDO DE SACO
-- ==========================================
CREATE TABLE IF NOT EXISTS anexos_fondo_saco_ginecologico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    -- Anexo derecho
    der_sin_alteraciones BOOLEAN DEFAULT FALSE,
    der_lesion_anexial BOOLEAN DEFAULT FALSE,
    der_hidrosalpinx BOOLEAN DEFAULT FALSE,
    der_paraovarico BOOLEAN DEFAULT FALSE,
    der_otro VARCHAR(255) NULL,
    -- Anexo izquierdo
    izq_sin_alteraciones BOOLEAN DEFAULT FALSE,
    izq_lesion_anexial BOOLEAN DEFAULT FALSE,
    izq_hidrosalpinx BOOLEAN DEFAULT FALSE,
    izq_paraovarico BOOLEAN DEFAULT FALSE,
    izq_otro VARCHAR(255) NULL,
    -- Fondo de saco posterior
    fondo_saco_libre BOOLEAN DEFAULT FALSE,
    fondo_saco_liquido_escaso BOOLEAN DEFAULT FALSE,
    fondo_saco_liquido_moderado BOOLEAN DEFAULT FALSE,
    fondo_saco_liquido_abundante BOOLEAN DEFAULT FALSE,
    fondo_saco_liquido_ecos BOOLEAN DEFAULT FALSE,
    fondo_saco_nodulo_implante BOOLEAN DEFAULT FALSE,
    fondo_saco_dolor_presion BOOLEAN DEFAULT FALSE,
    -- Sliding sign (endometriosis)
    sliding_sign ENUM('Positivo','Negativo','No evaluado') NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_anexos (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 10. CLASIFICACIÓN ORIENTATIVA (PALM-COEIN + Anexial)
-- ==========================================
CREATE TABLE IF NOT EXISTS clasificacion_orientativa_ginecologica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    -- PALM-COEIN para sangrado uterino anormal
    palm_polipo BOOLEAN DEFAULT FALSE COMMENT 'P: Polipo endometrial sospechado',
    palm_adenomiosis BOOLEAN DEFAULT FALSE COMMENT 'A: Adenomiosis sospechada',
    palm_leiomioma BOOLEAN DEFAULT FALSE COMMENT 'L: Leiomioma / miomatosis uterina',
    palm_malignidad BOOLEAN DEFAULT FALSE COMMENT 'M: Malignidad o hiperplasia endometrial por descartar',
    palm_coagulopatia BOOLEAN DEFAULT FALSE COMMENT 'C: Coagulopatia, requiere correlacion clinica/laboratorio',
    palm_ovulatoria BOOLEAN DEFAULT FALSE COMMENT 'O: Disfuncion ovulatoria',
    palm_endometrial BOOLEAN DEFAULT FALSE COMMENT 'E: Causa endometrial funcional',
    palm_iatrogenica BOOLEAN DEFAULT FALSE COMMENT 'I: Iatrogenica / hormonal / farmacos',
    palm_no_clasificada BOOLEAN DEFAULT FALSE COMMENT 'N: No clasificada',
    -- Clasificacion anexial (si hay masa)
    anexial_funcional BOOLEAN DEFAULT FALSE COMMENT 'Lesion funcional probable',
    anexial_benigna BOOLEAN DEFAULT FALSE COMMENT 'Lesion benigna probable',
    anexial_indeterminada BOOLEAN DEFAULT FALSE COMMENT 'Lesion indeterminada',
    anexial_sospechosa BOOLEAN DEFAULT FALSE COMMENT 'Lesion sospechosa',
    anexial_sugiere_o_rads BOOLEAN DEFAULT FALSE COMMENT 'Se sugiere complementar con O-RADS / IOTA ADNEX',
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_clasificacion (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 11. IMPRESIÓN DIAGNÓSTICA
-- ==========================================
CREATE TABLE IF NOT EXISTS impresion_diagnostica_ginecologica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    -- Utero
    utero_tamano ENUM('Normal','Aumentado','Disminuido') NULL,
    utero_morfologia TEXT NULL,
    -- Miometrio
    miometrio_sin_alteraciones BOOLEAN DEFAULT FALSE,
    miometrio_miomatosis BOOLEAN DEFAULT FALSE,
    miometrio_adenomiosis BOOLEAN DEFAULT FALSE,
    miometrio_otro TEXT NULL,
    -- Endometrio
    endometrio_grosor_mm DECIMAL(5,2) NULL,
    endometrio_patron TEXT NULL,
    endometrio_acorde_contexto BOOLEAN DEFAULT FALSE,
    endometrio_engrosado_contexto BOOLEAN DEFAULT FALSE,
    endometrio_requiere_correlacion BOOLEAN DEFAULT FALSE,
    -- Ovarios y anexos
    ovario_derecho TEXT NULL,
    ovario_izquierdo TEXT NULL,
    anexos_fondo_saco TEXT NULL,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones_ginecologicas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_evaluacion_impresion (evaluacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 12. CONCLUSIÓN + RECOMENDACIONES
-- ==========================================
CREATE TABLE IF NOT EXISTS conclusion_recomendaciones_ginecologicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evaluacion_id INT NOT NULL,
    -- Conclusiones
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
    -- Recomendaciones / conducta sugerida
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
