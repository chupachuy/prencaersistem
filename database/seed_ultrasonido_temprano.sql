-- ============================================================
-- Seed: 4 Ultrasonidos Tempranos simulados
-- Ejecutar en phpMyAdmin → pestana SQL, BD: prenacersistem
-- Requisito previo: tener la tabla creada (create_ultrasonido_temprano.sql)
-- ============================================================

SET NAMES utf8mb4;

-- Variables con IDs reales de la BD existente
SET @p1 = (SELECT id FROM pacientes ORDER BY id ASC LIMIT 1 OFFSET 0);
SET @p2 = (SELECT id FROM pacientes ORDER BY id ASC LIMIT 1 OFFSET 1);
SET @p3 = (SELECT id FROM pacientes ORDER BY id ASC LIMIT 1 OFFSET 2);
SET @p4 = (SELECT id FROM pacientes ORDER BY id ASC LIMIT 1 OFFSET 3);
SET @m1 = (SELECT id FROM usuarios WHERE rol_id = 4 AND activo = 1 ORDER BY id ASC LIMIT 1);
SET @a1 = (SELECT id FROM usuarios WHERE rol_id = 1 ORDER BY id ASC LIMIT 1);
SET @uid = COALESCE(@a1, @m1, 1);

-- ============================================================
-- Registro 1: Embarazo normal viable, 8.3 semanas
-- ============================================================
INSERT INTO ultrasonido_temprano (
    paciente_id, medico_id, codigo_reporte, fecha_estudio, edad, fum,
    edad_gest_semanas, edad_gest_dias,
    indic_confirmacion_embarazo, indic_sangrado, indic_dolor_pelvico,
    indic_viabilidad, indic_perdidas_gestacionales, indic_reproduccion_asistida, indic_otro,
    via_transvaginal, via_transabdominal, via_ambas,
    utero_posicion, utero_contornos_regulares, utero_ecogenicidad_conservada,
    utero_dim_x, utero_dim_y, utero_dim_z, endometrio,
    localizacion, localizacion_otra,
    sg_tipo, sg_morfologia, sg_medida_mm,
    sv_presente, sv_cantidad, sv_diametro_mm,
    corion_amnios_normal,
    ovario_der_dim_x, ovario_der_dim_y, ovario_der_dim_z,
    ovario_der_normal, ovario_der_cuerpo_luteo_mm, ovario_der_quiste_simple_mm, ovario_der_otra_alteracion,
    ovario_izq_dim_x, ovario_izq_dim_y, ovario_izq_dim_z,
    ovario_izq_normal, ovario_izq_cuerpo_luteo_mm, ovario_izq_quiste_simple_mm, ovario_izq_otra_alteracion,
    douglas,
    hematoma_subcorionico, hematoma_localizacion, hematoma_dim_x, hematoma_dim_y, hematoma_dim_z, hematoma_volumen_ml,
    miomas_uterinos, adenomiosis, malformacion_uterina, hallazgos_otro,
    impresion_crl_mm, impresion_semanas, impresion_dias, impresion_fcf_lpm, impresion_texto,
    estado, created_by, updated_by
) VALUES (
    @p1, @m1, 'UST-0001-2026', '2026-05-15', 28, '2026-03-20',
    8, 0,
    1, 0, 0, 0, 0, 0, NULL,
    1, 0, 0,
    'Anteroversion', 1, 1,
    72.0, 45.0, 55.0, 'Endometrio decidualizado de aspecto normal.',
    'Fundica', NULL,
    'Unico', 'Regular', 25.0,
    1, 1, 4.2,
    1,
    28.0, 18.0, 25.0, 1, NULL, NULL, NULL,
    30.0, 20.0, 26.0, 1, NULL, NULL, NULL,
    'Libre',
    0, NULL, NULL, NULL, NULL, NULL,
    0, 0, 0, NULL,
    18.2, 8, 3, 172,
    'Embarazo intrauterino unico viable de 8.3 semanas por CRL (18.2 mm), con frecuencia cardiaca embrionaria de 172 lpm. Saco vitelino y anexos de aspecto normal. Sin evidencia de colecciones perigestacionales ni liquido libre pelvico.',
    'Completado', @uid, @uid
);

SET @ust1 = LAST_INSERT_ID();

INSERT INTO embriones_temprano (ultrasonido_id, numero, crl_mm, fcf_visible, fcf_lpm, localizacion)
VALUES (@ust1, 1, 18.2, 1, 172, 'Fundo uterino');

-- ============================================================
-- Registro 2: Amenaza de aborto, 7.1 semanas
-- ============================================================
INSERT INTO ultrasonido_temprano (
    paciente_id, medico_id, codigo_reporte, fecha_estudio, edad, fum,
    edad_gest_semanas, edad_gest_dias,
    indic_confirmacion_embarazo, indic_sangrado, indic_dolor_pelvico,
    indic_viabilidad, indic_perdidas_gestacionales, indic_reproduccion_asistida, indic_otro,
    via_transvaginal, via_transabdominal, via_ambas,
    utero_posicion, utero_contornos_regulares, utero_ecogenicidad_conservada,
    utero_dim_x, utero_dim_y, utero_dim_z, endometrio,
    localizacion, localizacion_otra,
    sg_tipo, sg_morfologia, sg_medida_mm,
    sv_presente, sv_cantidad, sv_diametro_mm,
    corion_amnios_normal,
    ovario_der_dim_x, ovario_der_dim_y, ovario_der_dim_z,
    ovario_der_normal, ovario_der_cuerpo_luteo_mm, ovario_der_quiste_simple_mm, ovario_der_otra_alteracion,
    ovario_izq_dim_x, ovario_izq_dim_y, ovario_izq_dim_z,
    ovario_izq_normal, ovario_izq_cuerpo_luteo_mm, ovario_izq_quiste_simple_mm, ovario_izq_otra_alteracion,
    douglas,
    hematoma_subcorionico, hematoma_localizacion, hematoma_dim_x, hematoma_dim_y, hematoma_dim_z, hematoma_volumen_ml,
    miomas_uterinos, adenomiosis, malformacion_uterina, hallazgos_otro,
    impresion_crl_mm, impresion_semanas, impresion_dias, impresion_fcf_lpm, impresion_texto,
    estado, created_by, updated_by
) VALUES (
    @p2, @m1, 'UST-0002-2026', '2026-05-20', 32, '2026-04-01',
    7, 0,
    0, 1, 1, 0, 0, 0, NULL,
    1, 1, 1,
    'Anteroversion', 1, 1,
    68.0, 42.0, 50.0, 'Endometrio engrosado, heterogeneo.',
    'Corporal', NULL,
    'Unico', 'Irregular', 18.0,
    0, NULL, NULL,
    1,
    35.0, 22.0, 28.0, 0, 18.0, NULL, NULL,
    30.0, 18.0, 25.0, 1, NULL, NULL, NULL,
    'Escasa cantidad de liquido libre',
    1, 'Lateral derecho al saco gestacional', 15.0, 8.0, 10.0, 3.5,
    0, 0, 0, NULL,
    10.5, 7, 1, 130,
    'Embarazo intrauterino unico viable de 7.1 semanas por CRL (10.5 mm), con FCF de 130 lpm. Se observa hematoma subcorionico de 15 x 8 x 10 mm (vol. estimado 3.5 ml) en zona lateral derecha. Escasa cantidad de liquido libre en fondo de saco de Douglas. Control evolutivo en 2 semanas.',
    'En proceso', @uid, @uid
);

SET @ust2 = LAST_INSERT_ID();

INSERT INTO embriones_temprano (ultrasonido_id, numero, crl_mm, fcf_visible, fcf_lpm, localizacion)
VALUES (@ust2, 1, 10.5, 1, 130, 'Cavidad corporal');

-- ============================================================
-- Registro 3: Embarazo gemelar, 9.4 semanas (Reproduccion asistida)
-- ============================================================
INSERT INTO ultrasonido_temprano (
    paciente_id, medico_id, codigo_reporte, fecha_estudio, edad, fum,
    edad_gest_semanas, edad_gest_dias,
    indic_confirmacion_embarazo, indic_sangrado, indic_dolor_pelvico,
    indic_viabilidad, indic_perdidas_gestacionales, indic_reproduccion_asistida, indic_otro,
    via_transvaginal, via_transabdominal, via_ambas,
    utero_posicion, utero_contornos_regulares, utero_ecogenicidad_conservada,
    utero_dim_x, utero_dim_y, utero_dim_z, endometrio,
    localizacion, localizacion_otra,
    sg_tipo, sg_morfologia, sg_medida_mm,
    sv_presente, sv_cantidad, sv_diametro_mm,
    corion_amnios_normal,
    ovario_der_dim_x, ovario_der_dim_y, ovario_der_dim_z,
    ovario_der_normal, ovario_der_cuerpo_luteo_mm, ovario_der_quiste_simple_mm, ovario_der_otra_alteracion,
    ovario_izq_dim_x, ovario_izq_dim_y, ovario_izq_dim_z,
    ovario_izq_normal, ovario_izq_cuerpo_luteo_mm, ovario_izq_quiste_simple_mm, ovario_izq_otra_alteracion,
    douglas,
    hematoma_subcorionico, hematoma_localizacion, hematoma_dim_x, hematoma_dim_y, hematoma_dim_z, hematoma_volumen_ml,
    miomas_uterinos, adenomiosis, malformacion_uterina, hallazgos_otro,
    impresion_crl_mm, impresion_semanas, impresion_dias, impresion_fcf_lpm, impresion_texto,
    estado, created_by, updated_by
) VALUES (
    @p3, @m1, 'UST-0003-2026', '2026-05-25', 36, '2026-03-15',
    10, 1,
    0, 0, 0, 0, 0, 1, NULL,
    1, 0, 0,
    'Retroversion', 1, 1,
    85.0, 52.0, 60.0, 'Endometrio decidualizado grueso, adecuado para EG.',
    'Fundica', NULL,
    'Multiple', 'Regular', 28.0,
    1, 2, 5.0,
    1,
    32.0, 20.0, 26.0, 1, NULL, NULL, NULL,
    33.0, 21.0, 27.0, 1, NULL, NULL, NULL,
    'Libre',
    0, NULL, NULL, NULL, NULL, NULL,
    0, 0, 0, NULL,
    24.5, 9, 4, 168,
    'Embarazo gemelar bicorial biamniotico viable de 9.4 semanas. Embrion A con CRL de 24.5 mm y FCF de 168 lpm en cavidad A. Embrion B con CRL de 23.8 mm y FCF de 162 lpm en cavidad B. Ambos sacos gestacionales con anatomia y localizacion normal. Corion y amnios identificables, signo del pico lambda presente. Anexos sin alteraciones. Sin evidencia de hematomas ni liquido libre.',
    'Completado', @uid, @uid
);

SET @ust3 = LAST_INSERT_ID();

INSERT INTO embriones_temprano (ultrasonido_id, numero, crl_mm, fcf_visible, fcf_lpm, localizacion)
VALUES
(@ust3, 1, 24.5, 1, 168, 'Cavidad A (fundo derecho)'),
(@ust3, 2, 23.8, 1, 162, 'Cavidad B (fundo izquierdo)');

-- ============================================================
-- Registro 4: Sospecha gestacion anembrionada, 6.2 semanas
-- ============================================================
INSERT INTO ultrasonido_temprano (
    paciente_id, medico_id, codigo_reporte, fecha_estudio, edad, fum,
    edad_gest_semanas, edad_gest_dias,
    indic_confirmacion_embarazo, indic_sangrado, indic_dolor_pelvico,
    indic_viabilidad, indic_perdidas_gestacionales, indic_reproduccion_asistida, indic_otro,
    via_transvaginal, via_transabdominal, via_ambas,
    utero_posicion, utero_contornos_regulares, utero_ecogenicidad_conservada,
    utero_dim_x, utero_dim_y, utero_dim_z, endometrio,
    localizacion, localizacion_otra,
    sg_tipo, sg_morfologia, sg_medida_mm,
    sv_presente, sv_cantidad, sv_diametro_mm,
    corion_amnios_normal,
    ovario_der_dim_x, ovario_der_dim_y, ovario_der_dim_z,
    ovario_der_normal, ovario_der_cuerpo_luteo_mm, ovario_der_quiste_simple_mm, ovario_der_otra_alteracion,
    ovario_izq_dim_x, ovario_izq_dim_y, ovario_izq_dim_z,
    ovario_izq_normal, ovario_izq_cuerpo_luteo_mm, ovario_izq_quiste_simple_mm, ovario_izq_otra_alteracion,
    douglas,
    hematoma_subcorionico, hematoma_localizacion, hematoma_dim_x, hematoma_dim_y, hematoma_dim_z, hematoma_volumen_ml,
    miomas_uterinos, adenomiosis, malformacion_uterina, hallazgos_otro,
    impresion_crl_mm, impresion_semanas, impresion_dias, impresion_fcf_lpm, impresion_texto,
    estado, created_by, updated_by
) VALUES (
    @p4, @m1, 'UST-0004-2026', '2026-06-01', 25, '2026-04-20',
    6, 0,
    0, 0, 0, 1, 1, 0, NULL,
    1, 0, 0,
    'Anteroversion', 1, 1,
    65.0, 40.0, 48.0, 'Endometrio decidualizado.',
    'Corporal', NULL,
    'Unico', 'Irregular', 14.0,
    1, 1, 3.5,
    0,
    25.0, 15.0, 20.0, 0, NULL, 22.0, NULL,
    24.0, 14.0, 19.0, 1, NULL, NULL, NULL,
    'Libre',
    0, NULL, NULL, NULL, NULL, NULL,
    0, 0, 0, NULL,
    NULL, NULL, NULL, NULL,
    'Saco gestacional intrauterino unico de 14 mm con saco vitelino presente de 3.5 mm. No se identifica embrion ni actividad cardiaca. Corion y amnios no identificables. El tamano del saco gestacional corresponde a 6.2 semanas. Se observa quiste simple anecoico de 22 mm en ovario derecho. Ovario izquierdo normal. Sin liquido libre en fondo de saco de Douglas. Se recomienda control ecografico en 10-14 dias para reevaluacion. Sospecha de gestacion anembrionada.',
    'Pendiente', @uid, @uid
);
