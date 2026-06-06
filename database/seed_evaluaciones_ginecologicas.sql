-- ============================================================
-- SEED: REPORTES DE ULTRASONIDO GINECOLÓGICO ENDOVAGINAL
-- 2 reportes de prueba con datos simulados realistas
-- Ejecutar después de create_evaluaciones_ginecologicas.sql
-- ============================================================
SET NAMES utf8mb4;

-- ==========================================
-- CASO 1: María García López (33 años)
-- Miomatosis + Adenomiosis + Quiste hemorrágico
-- ==========================================

INSERT INTO evaluaciones_ginecologicas (
    paciente_id, medico_id, medico_solicitante_id, codigo_reporte, fecha_estudio,
    indicacion_clinica, fum, dia_ciclo_menstrual, observaciones,
    estado, activo, created_by, updated_by
) VALUES (
    3, 4, 3, 'USG-0001-2026', '2026-06-02',
    'Paciente refiere sangrado menstrual abundante de 6 meses de evolución, dolor pélvico cíclico. Antecedente de miomatosis conocida.',
    '2026-05-18', 16,
    'Se solicita valoración endometrial por sangrado uterino anormal.',
    'Completado', 1, 4, 4
);

SET @eval1 = LAST_INSERT_ID();

INSERT INTO indicaciones_ginecologicas (
    evaluacion_id, sangrado_uterino_anormal, dolor_pelvico, miomatosis_uterina,
    sospecha_polipo_endometrial, engrosamiento_endometrial, control_diu,
    infertilidad_reproduccion, quiste_ovarico_masa_anexial, sindrome_climaterico,
    sangrado_posmenopausico, motivo_estudio_otro,
    premenopausica, perimenopausica, posmenopausica,
    terapia_hormonal, tamoxifeno, anticonceptivos_hormonales, estatus_no_especificado
) VALUES (
    @eval1, 1, 1, 1, 0, 1, 0, 0, 0, 0, 0, NULL,
    0, 1, 0, 0, 0, 0, 0
);

INSERT INTO antecedentes_ginecologicos (
    evaluacion_id, gesta, para, cesareas, abortos,
    paridad_satisfecha, legrado_cirugia_uterina, miomectomia,
    endometriosis_adenomiosis, otros
) VALUES (
    @eval1, 2, 2, 0, 0, 1, 0, 0, 0,
    'Madre con antecedente de miomatosis. Hermana con cáncer de mama a los 48 años.'
);

INSERT INTO tecnica_ultrasonido_ginecologico (
    evaluacion_id, via_endovaginal, via_transabdominal, via_doppler_color,
    via_evaluacion_3d, via_sonohisterografia, calidad,
    limitada_dolor, limitada_distension_intestinal, limitada_habitus_corporal,
    limitada_posicion_uterina, calidad_otra
) VALUES (
    @eval1, 1, 0, 1, 0, 0,
    'Adecuada', 0, 0, 0, 0, NULL
);

INSERT INTO utero_cervix_ginecologico (
    evaluacion_id, situacion,
    morfologia_regular, morfologia_bordes_irregulares, morfologia_globoso,
    morfologia_aumentado, morfologia_disminuido, morfologia_otro,
    dim_longitud_mm, dim_anteroposterior_mm, dim_transverso_mm, volumen_cc,
    miometrio_homogeneo, miometrio_heterogeneo, miometrio_imagenes_leiomiomas,
    miometrio_sugestivo_adenomiosis, miometrio_calcificaciones,
    miometrio_areas_quisticas, miometrio_sombra_acustica, miometrio_otro,
    cervix_longitud_mm, cervix_sin_alteraciones, cervix_quistes_naboth,
    cervix_polipo_endocervical, cervix_lesion_visible_usg, cervix_liquido_canal, cervix_otro
) VALUES (
    @eval1, 'Anteversoflexion',
    0, 1, 1, 1, 0, NULL,
    92.5, 48.3, 55.1, 128.7,
    0, 1, 1, 1, 0, 1, 1, 'Zona de transición miometrio-endometrio irregular en pared posterior',
    34.0, 0, 1, 0, 0, 0, NULL
);

INSERT INTO miomas_ginecologicos (
    evaluacion_id, identificados, numero_aproximado, mioma_dominante_mm,
    predominio_submucosos, predominio_intramurales, predominio_subserosos,
    predominio_pediculados, predominio_cervicales, predominio_distribucion_difusa
) VALUES (
    @eval1, 1, 3, 45.2,
    1, 1, 0, 0, 0, 0
);

INSERT INTO miomas_detalle_ginecologico (
    evaluacion_id, numero, localizacion, medida_x_mm, medida_y_mm, medida_z_mm,
    relacion_endometrio, clasificacion_figo, doppler, comentarios
) VALUES
(@eval1, 1, 'Posterior', 45.2, 38.1, 42.5, 'Distorsiona cavidad', 'FIGO 2', 'Aumentado', 'Mioma dominante. Compromete línea endometrial. Vascularización periférica e intralesional aumentada.'),
(@eval1, 2, 'Fondo',     28.4, 22.0, 25.3, 'Contacta',          'FIGO 3', 'Moderado',  'Contacta con endometrio sin distorsión franca.'),
(@eval1, 3, 'Anterior',  18.7, 15.2, 16.8, 'No contacta',       'FIGO 4', 'Escaso',    'Intramural puro. Vascularización periférica escasa.');

INSERT INTO adenomiosis_ginecologica (
    evaluacion_id, hallazgos,
    utero_globoso, asimetria_paredes, miometrio_heterogeneo,
    estriaciones_lineales, quistes_miometriales, islas_hiperecogenicas,
    sombra_abanico, zona_union_irregular, vascularidad_translesional, datos_otro,
    distribucion, predominio_anterior, predominio_posterior, predominio_fundico
) VALUES (
    @eval1, 'Si se observan',
    1, 1, 1, 1, 1, 1, 1, 1, 1, 'Se observan pequeños quistes miometriales subendometriales en pared posterior.',
    'Focal', 0, 1, 0
);

INSERT INTO endometrio_ginecologico (
    evaluacion_id, grosor_mm, patron, correlacion_ciclo,
    cavidad_regular, cavidad_distorsionada, cavidad_liquido_intracavitario,
    cavidad_imagen_focal_polipo, cavidad_imagen_mioma_submucoso,
    cavidad_sinequias, cavidad_diu_intrauterino, cavidad_otro,
    doppler, diu_posicion, diu_distancia_fondo_mm
) VALUES (
    @eval1, 14.2, 'Hiperecogenico', 'Engrosado',
    0, 1, 0, 0, 1, 0, 0, 'Distorsión cavitaria por mioma FIGO 2 que protruye a la cavidad.',
    'VascularidadDifusa', NULL, NULL
);

INSERT INTO ovarios_ginecologicos (
    evaluacion_id,
    der_dim_x_mm, der_dim_y_mm, der_dim_z_mm, der_volumen_cc,
    der_normal, der_atrofico, der_multifolicular, der_poliquistico,
    der_cuerpo_luteo, der_quiste_simple, der_quiste_hemorragico,
    der_endometrioma, der_lesion_solida, der_lesion_compleja, der_no_visible,
    der_foliculo_med_x_mm, der_foliculo_med_y_mm, der_foliculo_med_z_mm,
    der_foliculo_contenido, der_foliculo_pared,
    der_foliculo_septos, der_foliculo_septos_grosor,
    der_foliculo_papilares, der_foliculo_papilares_num,
    der_foliculo_sombra, der_foliculo_doppler,
    izq_dim_x_mm, izq_dim_y_mm, izq_dim_z_mm, izq_volumen_cc,
    izq_normal, izq_atrofico, izq_multifolicular, izq_poliquistico,
    izq_cuerpo_luteo, izq_quiste_simple, izq_quiste_hemorragico,
    izq_endometrioma, izq_lesion_solida, izq_lesion_compleja, izq_no_visible,
    izq_foliculo_med_x_mm, izq_foliculo_med_y_mm, izq_foliculo_med_z_mm,
    izq_foliculo_contenido, izq_foliculo_pared,
    izq_foliculo_septos, izq_foliculo_septos_grosor,
    izq_foliculo_papilares, izq_foliculo_papilares_num,
    izq_foliculo_sombra, izq_foliculo_doppler
) VALUES (
    @eval1,
    36.2, 28.5, 25.1, 13.6,
    0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0,
    28.3, 22.1, 25.8,
    'Hemorragico', 'Gruesa',
    0, NULL, 0, NULL, 1, 'FlujoPeriferico',
    32.1, 18.8, 22.3, 7.0,
    1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
    NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL
);

INSERT INTO anexos_fondo_saco_ginecologico (
    evaluacion_id,
    der_sin_alteraciones, der_lesion_anexial, der_hidrosalpinx, der_paraovarico, der_otro,
    izq_sin_alteraciones, izq_lesion_anexial, izq_hidrosalpinx, izq_paraovarico, izq_otro,
    fondo_saco_libre, fondo_saco_liquido_escaso, fondo_saco_liquido_moderado,
    fondo_saco_liquido_abundante, fondo_saco_liquido_ecos, fondo_saco_nodulo_implante,
    fondo_saco_dolor_presion, sliding_sign
) VALUES (
    @eval1,
    0, 1, 0, 0, 'Quiste hemorrágico descrito en ovario derecho.',
    1, 0, 0, 0, NULL,
    1, 0, 0, 0, 0, 0, 1, 'Positivo'
);

INSERT INTO clasificacion_orientativa_ginecologica (
    evaluacion_id,
    palm_polipo, palm_adenomiosis, palm_leiomioma, palm_malignidad,
    palm_coagulopatia, palm_ovulatoria, palm_endometrial, palm_iatrogenica, palm_no_clasificada,
    anexial_funcional, anexial_benigna, anexial_indeterminada, anexial_sospechosa, anexial_sugiere_o_rads
) VALUES (
    @eval1,
    0, 1, 1, 1, 0, 0, 0, 0, 0,
    1, 1, 0, 0, 0
);

INSERT INTO impresion_diagnostica_ginecologica (
    evaluacion_id, utero_tamano, utero_morfologia,
    miometrio_sin_alteraciones, miometrio_miomatosis, miometrio_adenomiosis, miometrio_otro,
    endometrio_grosor_mm, endometrio_patron,
    endometrio_acorde_contexto, endometrio_engrosado_contexto, endometrio_requiere_correlacion,
    ovario_derecho, ovario_izquierdo, anexos_fondo_saco
) VALUES (
    @eval1,
    'Aumentado', 'Globoso, bordes irregulares, con imágenes nodulares compatibles con leiomiomas.',
    0, 1, 1, NULL,
    14.2, 'Hiperecogénico / secretor, distorsionado por mioma FIGO 2.',
    0, 1, 1,
    'Con quiste hemorrágico de 28 mm, probablemente funcional. Pared gruesa con contenido hemorrágico en organización.',
    'De características ecográficas conservadas.',
    'Fondo de saco libre. Dolor a la presión con transductor en fosa ilíaca derecha. Sliding sign positivo.'
);

INSERT INTO conclusion_recomendaciones_ginecologicas (
    evaluacion_id,
    estudio_limites_esperados, miomatosis_uterina,
    conclusion_mioma_dominante_mm, conclusion_figo,
    engrosamiento_endometrial, conclusion_medida_endometrio_mm,
    imagen_focal_polipo, datos_sugestivos_adenomiosis,
    quiste_simple_der, quiste_simple_izq, quiste_hemorragico_der, quiste_hemorragico_izq,
    endometrioma_der, endometrioma_izq,
    conclusion_quiste_medida_mm, masa_anexial_indeterminada, conclusion_otro,
    rec_correlacion_edad_fum, rec_correlacion_hb_hormonal,
    rec_estudio_histologico, rec_histeroscopia_endometrio,
    rec_sonohisterografia_histeroscopia, rec_valorar_manejo_miomatosis,
    rec_iorads_marcadores_oncologia, rec_control_ultrasonografico,
    rec_control_tiempo, rec_control_unidad, rec_otro
) VALUES (
    @eval1,
    0, 1, 45.2, 'FIGO 2', 1, 14.2,
    0, 1, 0, 0, 1, 0, 0, 0, 28.0, 0,
    'Se identifica útero aumentado de tamaño de aspecto globoso a expensas de miomatosis uterina múltiple. Mioma dominante FIGO 2 de 45 mm que distorsiona la cavidad endometrial condicionando engrosamiento endometrial reactivo de 14 mm. Datos sonográficos sugestivos de adenomiosis focal en pared posterior.',
    1, 1, 1, 1, 0, 1, 0, 1, 3, 'Meses',
    'Valorar miomectomía histeroscópica de mioma FIGO 2. Control posterior a tratamiento.'
);


-- ==========================================
-- CASO 2: Ana Martínez Ruiz (37 años)
-- Infertilidad + Ovarios poliquísticos + Sospecha pólipo endometrial
-- ==========================================

INSERT INTO evaluaciones_ginecologicas (
    paciente_id, medico_id, medico_solicitante_id, codigo_reporte, fecha_estudio,
    indicacion_clinica, fum, dia_ciclo_menstrual, observaciones,
    estado, activo, created_by, updated_by
) VALUES (
    4, 4, 3, 'USG-0002-2026', '2026-06-03',
    'Paciente en estudio por infertilidad primaria de 2 años de evolución. Ciclos irregulares (35-60 días).',
    '2026-05-22', 12,
    'Se solicita valoración de la cavidad endometrial y reserva ovárica. Descartar patología estructural.',
    'Completado', 1, 4, 4
);

SET @eval2 = LAST_INSERT_ID();

INSERT INTO indicaciones_ginecologicas (
    evaluacion_id, sangrado_uterino_anormal, dolor_pelvico, miomatosis_uterina,
    sospecha_polipo_endometrial, engrosamiento_endometrial, control_diu,
    infertilidad_reproduccion, quiste_ovarico_masa_anexial, sindrome_climaterico,
    sangrado_posmenopausico, motivo_estudio_otro,
    premenopausica, perimenopausica, posmenopausica,
    terapia_hormonal, tamoxifeno, anticonceptivos_hormonales, estatus_no_especificado
) VALUES (
    @eval2, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 'Amenorrea ocasional. Ciclos anovulatorios.',
    1, 0, 0, 0, 0, 0, 0
);

INSERT INTO antecedentes_ginecologicos (
    evaluacion_id, gesta, para, cesareas, abortos,
    paridad_satisfecha, legrado_cirugia_uterina, miomectomia,
    endometriosis_adenomiosis, otros
) VALUES (
    @eval2, 0, 0, 0, 0, 0, 0, 0, 0,
    'Hermana con síndrome de ovario poliquístico. Madre con diabetes tipo 2.'
);

INSERT INTO tecnica_ultrasonido_ginecologico (
    evaluacion_id, via_endovaginal, via_transabdominal, via_doppler_color,
    via_evaluacion_3d, via_sonohisterografia, calidad,
    limitada_dolor, limitada_distension_intestinal, limitada_habitus_corporal,
    limitada_posicion_uterina, calidad_otra
) VALUES (
    @eval2, 1, 0, 1, 1, 0,
    'Adecuada', 0, 0, 0, 0, NULL
);

INSERT INTO utero_cervix_ginecologico (
    evaluacion_id, situacion,
    morfologia_regular, morfologia_bordes_irregulares, morfologia_globoso,
    morfologia_aumentado, morfologia_disminuido, morfologia_otro,
    dim_longitud_mm, dim_anteroposterior_mm, dim_transverso_mm, volumen_cc,
    miometrio_homogeneo, miometrio_heterogeneo, miometrio_imagenes_leiomiomas,
    miometrio_sugestivo_adenomiosis, miometrio_calcificaciones,
    miometrio_areas_quisticas, miometrio_sombra_acustica, miometrio_otro,
    cervix_longitud_mm, cervix_sin_alteraciones, cervix_quistes_naboth,
    cervix_polipo_endocervical, cervix_lesion_visible_usg, cervix_liquido_canal, cervix_otro
) VALUES (
    @eval2, 'Anteversoflexion',
    1, 0, 0, 0, 0, NULL,
    74.8, 35.2, 42.6, 59.3,
    1, 0, 0, 0, 0, 0, 0, NULL,
    36.5, 1, 0, 0, 0, 0, NULL
);

INSERT INTO miomas_ginecologicos (
    evaluacion_id, identificados, numero_aproximado, mioma_dominante_mm,
    predominio_submucosos, predominio_intramurales, predominio_subserosos,
    predominio_pediculados, predominio_cervicales, predominio_distribucion_difusa
) VALUES (
    @eval2, 0, NULL, NULL, 0, 0, 0, 0, 0, 0
);

INSERT INTO adenomiosis_ginecologica (
    evaluacion_id, hallazgos,
    utero_globoso, asimetria_paredes, miometrio_heterogeneo,
    estriaciones_lineales, quistes_miometriales, islas_hiperecogenicas,
    sombra_abanico, zona_union_irregular, vascularidad_translesional, datos_otro,
    distribucion, predominio_anterior, predominio_posterior, predominio_fundico
) VALUES (
    @eval2, 'No se observan',
    0, 0, 0, 0, 0, 0, 0, 0, 0, NULL,
    NULL, 0, 0, 0
);

INSERT INTO endometrio_ginecologico (
    evaluacion_id, grosor_mm, patron, correlacion_ciclo,
    cavidad_regular, cavidad_distorsionada, cavidad_liquido_intracavitario,
    cavidad_imagen_focal_polipo, cavidad_imagen_mioma_submucoso,
    cavidad_sinequias, cavidad_diu_intrauterino, cavidad_otro,
    doppler, diu_posicion, diu_distancia_fondo_mm
) VALUES (
    @eval2, 8.5, 'Trilaminar', 'Acorde',
    0, 0, 0, 1, 0, 0, 0, 'Imagen focal hiperecogénica de 9.2 × 5.8 mm en cara anterior de cavidad, sugestiva de pólipo endometrial.',
    'VasoUnicoPolipo', NULL, NULL
);

INSERT INTO ovarios_ginecologicos (
    evaluacion_id,
    der_dim_x_mm, der_dim_y_mm, der_dim_z_mm, der_volumen_cc,
    der_normal, der_atrofico, der_multifolicular, der_poliquistico,
    der_cuerpo_luteo, der_quiste_simple, der_quiste_hemorragico,
    der_endometrioma, der_lesion_solida, der_lesion_compleja, der_no_visible,
    der_foliculo_med_x_mm, der_foliculo_med_y_mm, der_foliculo_med_z_mm,
    der_foliculo_contenido, der_foliculo_pared,
    der_foliculo_septos, der_foliculo_septos_grosor,
    der_foliculo_papilares, der_foliculo_papilares_num,
    der_foliculo_sombra, der_foliculo_doppler,
    izq_dim_x_mm, izq_dim_y_mm, izq_dim_z_mm, izq_volumen_cc,
    izq_normal, izq_atrofico, izq_multifolicular, izq_poliquistico,
    izq_cuerpo_luteo, izq_quiste_simple, izq_quiste_hemorragico,
    izq_endometrioma, izq_lesion_solida, izq_lesion_compleja, izq_no_visible,
    izq_foliculo_med_x_mm, izq_foliculo_med_y_mm, izq_foliculo_med_z_mm,
    izq_foliculo_contenido, izq_foliculo_pared,
    izq_foliculo_septos, izq_foliculo_septos_grosor,
    izq_foliculo_papilares, izq_foliculo_papilares_num,
    izq_foliculo_sombra, izq_foliculo_doppler
) VALUES (
    @eval2,
    38.5, 25.2, 30.1, 15.2,
    0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0,
    NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
    40.1, 26.8, 31.5, 17.8,
    0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0,
    NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL
);

INSERT INTO anexos_fondo_saco_ginecologico (
    evaluacion_id,
    der_sin_alteraciones, der_lesion_anexial, der_hidrosalpinx, der_paraovarico, der_otro,
    izq_sin_alteraciones, izq_lesion_anexial, izq_hidrosalpinx, izq_paraovarico, izq_otro,
    fondo_saco_libre, fondo_saco_liquido_escaso, fondo_saco_liquido_moderado,
    fondo_saco_liquido_abundante, fondo_saco_liquido_ecos, fondo_saco_nodulo_implante,
    fondo_saco_dolor_presion, sliding_sign
) VALUES (
    @eval2,
    1, 0, 0, 0, NULL,
    1, 0, 0, 0, NULL,
    1, 0, 0, 0, 0, 0, 0, 'No evaluado'
);

INSERT INTO clasificacion_orientativa_ginecologica (
    evaluacion_id,
    palm_polipo, palm_adenomiosis, palm_leiomioma, palm_malignidad,
    palm_coagulopatia, palm_ovulatoria, palm_endometrial, palm_iatrogenica, palm_no_clasificada,
    anexial_funcional, anexial_benigna, anexial_indeterminada, anexial_sospechosa, anexial_sugiere_o_rads
) VALUES (
    @eval2,
    1, 0, 0, 0, 0, 1, 0, 0, 0,
    0, 1, 0, 0, 0
);

INSERT INTO impresion_diagnostica_ginecologica (
    evaluacion_id, utero_tamano, utero_morfologia,
    miometrio_sin_alteraciones, miometrio_miomatosis, miometrio_adenomiosis, miometrio_otro,
    endometrio_grosor_mm, endometrio_patron,
    endometrio_acorde_contexto, endometrio_engrosado_contexto, endometrio_requiere_correlacion,
    ovario_derecho, ovario_izquierdo, anexos_fondo_saco
) VALUES (
    @eval2,
    'Normal', 'Regular, bordes lisos.',
    1, 0, 0, NULL,
    8.5, 'Trilaminar, fase proliferativa tardía/preovulatoria.',
    1, 0, 0,
    'De aspecto poliquístico. Múltiples folículos periféricos de 2-8 mm. Volumen aumentado (15.2 cc).',
    'De aspecto poliquístico con similar patrón al ovario derecho. Múltiples folículos periféricos. Volumen aumentado (17.8 cc).',
    'Anexos sin alteraciones. Fondo de saco libre.'
);

INSERT INTO conclusion_recomendaciones_ginecologicas (
    evaluacion_id,
    estudio_limites_esperados, miomatosis_uterina,
    conclusion_mioma_dominante_mm, conclusion_figo,
    engrosamiento_endometrial, conclusion_medida_endometrio_mm,
    imagen_focal_polipo, datos_sugestivos_adenomiosis,
    quiste_simple_der, quiste_simple_izq, quiste_hemorragico_der, quiste_hemorragico_izq,
    endometrioma_der, endometrioma_izq,
    conclusion_quiste_medida_mm, masa_anexial_indeterminada, conclusion_otro,
    rec_correlacion_edad_fum, rec_correlacion_hb_hormonal,
    rec_estudio_histologico, rec_histeroscopia_endometrio,
    rec_sonohisterografia_histeroscopia, rec_valorar_manejo_miomatosis,
    rec_iorads_marcadores_oncologia, rec_control_ultrasonografico,
    rec_control_tiempo, rec_control_unidad, rec_otro
) VALUES (
    @eval2,
    0, 0, NULL, NULL, 0, NULL,
    1, 0, 0, 0, 0, 0, 0, 0, NULL, 0,
    'Útero de tamaño y morfología normal. Endometrio trilaminar de 8.5 mm acorde a día 12 del ciclo. Se identifica imagen focal intracavitaria sugestiva de pólipo endometrial de 9 mm con vaso único nutricio en Doppler. Ovarios de aspecto poliquístico bilateral (volumen derecho 15.2 cc, izquierdo 17.8 cc) con múltiples folículos periféricos. Hallazgos compatibles con síndrome de ovario poliquístico (criterios de Rotterdam).',
    0, 1, 0, 0, 1, 0, 0, 0, NULL, NULL,
    'Iniciar protocolo de estudio de infertilidad. Considerar histeroscopia diagnóstica para resección de pólipo endometrial previo a técnicas de reproducción asistida. Valoración por endocrinología reproductiva.'
);
