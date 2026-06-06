-- ============================================================
-- SEED: Nuevos usuarios, pacientes y evaluaciones completas
-- Password de todos los usuarios: Admin123!
-- ============================================================

-- ============================================================
-- 1. USUARIOS (uno por cada rol faltante)
-- ============================================================
INSERT INTO usuarios (id, nombre, apellido, email, password, rol_id, email_verified, created_by) VALUES
(2, 'Carlos', 'García Villa', 'admin@medical.com', '$2y$10$l6sge6qPp/vJvUYwejGVv.cZbcBkqtY5zUyiuRJQMQu/ZMupfmqoC', 2, TRUE, 1),
(3, 'Juana', 'Flores Méndez', 'jefa@medical.com', '$2y$10$l6sge6qPp/vJvUYwejGVv.cZbcBkqtY5zUyiuRJQMQu/ZMupfmqoC', 3, TRUE, 1),
(4, 'Pedro', 'López Torres', 'medico@medical.com', '$2y$10$l6sge6qPp/vJvUYwejGVv.cZbcBkqtY5zUyiuRJQMQu/ZMupfmqoC', 4, TRUE, 1)
ON DUPLICATE KEY UPDATE password = VALUES(password), rol_id = VALUES(rol_id), email_verified = VALUES(email_verified);

-- ============================================================
-- 2. PACIENTES NUEVOS (3)
-- ============================================================
INSERT IGNORE INTO pacientes (id, nombre, apellido, fecha_nacimiento, email, telefono, direccion, created_by) VALUES
(3, 'María', 'García López',   '1992-06-15', 'maria.gl@email.com',   '555-123-4567', 'Av. Insurgentes 123, CDMX',    2),
(4, 'Ana',   'Martínez Ruiz',  '1988-11-22', 'ana.mr@email.com',     '555-234-5678', 'Calle Reforma 456, CDMX',       3),
(5, 'Laura', 'Hernández Díaz', '1995-03-08', 'laura.hd@email.com',   '555-345-6789', 'Blvd. Puerta de Hierro 789, GDL', 4);

-- ============================================================
-- 3. HISTORIALES CLÍNICOS (variados por paciente)
-- ============================================================
INSERT IGNORE INTO historial_clinico (paciente_id, hipertension_cronica, diabetes, lupus_les, sindrome_antifosfolipido_saf, antecedente_preeclampsia_rciu, fertilizacion_in_vitro, antecedente_parto_pretermino) VALUES
-- María García: antecedente preeclampsia + parto pretérmino (riesgo bajo-moderado)
(3, 0, 0, 0, 0, 1, 0, 1),
-- Ana Martínez: diabetes + hipertensión crónica (riesgo alto)
(4, 1, 1, 0, 0, 0, 0, 0),
-- Laura Hernández: limpia (bajo riesgo)
(5, 0, 0, 0, 0, 0, 0, 0);

-- ============================================================
-- 4. BLOQUE: Dr. Carlos (Admin, ID 2) + Paciente María García (ID 3)
-- ============================================================
SET @pid_maria   = 3;
SET @mid_carlos  = 2;
SET @uid_carlos  = 2;

-- -------------------- 1er Trimestre: María García --------------------
INSERT INTO evaluaciones_1er_trimestre (paciente_id, medico_id, codigo_reporte, fecha_evaluacion, fecha_estudio, peso_kg, talla_cm, ta_sistolica, ta_diastolica, fum, fpp_usg, embarazo_multiple, estado_feto, fcf_lpm, lcc_mm, edad_gestacional_semanas, estado, created_by, updated_by)
VALUES (@pid_maria, @mid_carlos, 'EV1T-0003-2026', '2026-02-20', '2026-02-20', 58.00, 160.00, 105, 68, '2025-12-01', '2026-09-07', 0, 'Vivo', 155, 45.50, 11.5, 'Completado', @uid_carlos, @uid_carlos);
SET @ev_maria_1t = LAST_INSERT_ID();

INSERT INTO anatomia_fetal (evaluacion_id, estado_exploracion, snc_simetria_plexos, macizo_facial_integro, torax_situs, torax_eje_cardiaco_grados, abdomen_camara_gastrica, extremidades_completas, observaciones_anomalias)
VALUES (@ev_maria_1t, 'Incompleta', 1, 1, 'Solitus', 48, 1, 1, 'Visualización limitada por hábito materno. Repetir en 2T.');

INSERT INTO marcadores_fmf (evaluacion_id, translucencia_nucal_mm, hueso_nasal_presente, ductus_venoso_onda_a, regurgitacion_tricuspidea_ausente, vejiga_fetal_mm, uta_pi_promedio, muesca_bilateral, papp_a_mom, plgf_mom, tamizaje_genetico_tipo, tamizaje_genetico_resultado)
VALUES (@ev_maria_1t, 1.45, 1, 'Positiva', 1, 5.20, 1.52, 0, 0.98, 1.02, 'No realizado', NULL);

INSERT INTO entorno_materno (evaluacion_id, liquido_amniotico, placenta_posicion, placenta_insercion, longitud_cervical_mm, indice_consistencia_cervical_pct, morfologia_uterina_eshre, miomas_visibles, miomas_figo_tipo)
VALUES (@ev_maria_1t, 'Normal', 'Anterior', 'Normal', 36.00, 72, 'U0', 0, NULL);

INSERT INTO impresion_diagnostica (evaluacion_id, riesgo_basal_cromosomopatias, riesgo_ajustado_cromosomopatias, probabilidad_cromosomopatias, riesgo_preeclampsia_temprana, riesgo_enfermedad_placentaria_tardia, riesgo_parto_pretermino)
VALUES (@ev_maria_1t, '1:1800', '1:4100', 'Baja', 'Baja', 'Alta', 'Bajo');

-- -------------------- 2do Trimestre: María García --------------------
INSERT INTO evaluaciones_2do_trimestre (paciente_id, medico_id, codigo_reporte, fecha_evaluacion, fecha_estudio, edad_gestacional_semanas, fpp_actual, peso_kg, talla_cm, pam_mmhg, uta_pi_promedio, peso_1er_trimestre_kg, ganancia_peso_kg, estado, created_by, updated_by)
VALUES (@pid_maria, @mid_carlos, 'EV2T-0003-2026', '2026-05-10', '2026-05-10', 20.5, '2026-09-10', 62.00, 160.00, 82.00, 1.12, 58.00, 4.00, 'Completado', @uid_carlos, @uid_carlos);
SET @ev_maria_2t = LAST_INSERT_ID();

INSERT INTO biometria_2do_trimestre (evaluacion_id, estado_feto, fcf_lpm, peso_fetal_estimado_gr, percentil_hadlock, crecimiento_armonico, indice_cefalico_ci, fl_ac_pct, hc_ac_campbell)
VALUES (@ev_maria_2t, 'Vivo', 145, 350, 40, 1, 78.00, 21.80, 1.12);

INSERT INTO anatomia_fetal_2do_trimestre (evaluacion_id, craneo_snc_normal, cara_cuello_normal, corazon_normal, torax_diafragma_normal, abdomen_normal, genitourinario_normal, columna_normal, extremidades_normal, detalles_anomalias)
VALUES (@ev_maria_2t, 1, 1, 1, 1, 1, 1, 1, 1, 'Exploración completa. Sin alteraciones estructurales aparentes.');

INSERT INTO marcadores_ecograficos_2do_trimestre (evaluacion_id, ventriculomegalia_leve, quistes_plexos_coroideos, pliegue_nucal_aumentado, hueso_nasal_ausente, foco_ecogenico_cardiaco, intestino_hiperecogenico, femur_corto, arteria_umbilical_unica)
VALUES (@ev_maria_2t, 0, 0, 0, 0, 1, 0, 0, 0);

INSERT INTO entorno_placentario_2do_trimestre (evaluacion_id, placenta_posicion, distancia_borde_oci_mm, acretismo_figo_grado, bolsillo_max_liquido_mm, longitud_cervical_mm, indice_consistencia_cervical, funneling_presente, funneling_mm, sludge_intraamniotico, morfologia_uterina_eshre, miomas_visibles, miomas_figo_tipo, miomas_dimensiones_mm, miomas_vascularizacion)
VALUES (@ev_maria_2t, 'Anterior', 40.00, '0', 52, 34.00, 75, 0, NULL, 'No', 'U0', 0, NULL, NULL, NULL);

INSERT INTO impresion_diagnostica_2do_trimestre (evaluacion_id, riesgo_cromosomopatias, riesgo_parto_pretermino, riesgo_preeclampsia, observaciones_medicas)
VALUES (@ev_maria_2t, 'Bajo', 'Bajo', 'Intermedio', 'Foco ecogénico intracardiaco aislado sin otras anormalidades. Riesgo de preeclampsia intermedio por antecedente + uta_pi elevada. AAS 150 mg diario.');

-- -------------------- 3er Trimestre: María García --------------------
INSERT INTO evaluaciones_3er_trimestre (paciente_id, medico_id, codigo_reporte, fecha_evaluacion, fecha_estudio, estudio_solicitado, edad_gestacional_semanas, fpp_fum, fpp_usg, peso_kg, talla_cm, ta_sistolica, ta_diastolica, situacion_fetal, presentacion_fetal, posicion_fetal, feto_unico_vivo, fcf_lpm, equipo_ultrasonido, observaciones, estado, created_by, updated_by)
VALUES (@pid_maria, @mid_carlos, 'EV3T-0003-2026', '2026-07-28', '2026-07-28', 'Crecimiento fetal, Evaluación placentaria, Doppler', 31.0, '2026-09-05', '2026-09-10', 68.50, 160.00, 112, 70, 'Longitudinal', 'Cefalico', 'Dorso anterior', 'Vivo', 140, 'Samsung Hera W10', 'Crecimiento fetal adecuado. Doppler dentro de límites normales. Se sugiere control semanal a partir de semana 36 por antecedente de preeclampsia.', 'Completado', @uid_carlos, @uid_carlos);
SET @ev_maria_3t = LAST_INSERT_ID();

INSERT INTO antecedentes_3er_trimestre (evaluacion_id, curva_tolerancia_glucosa, diabetes_gestacional_actual, movimientos_fetales, signos_amenaza_parto_pretermino, plan_nacimiento_definido)
VALUES (@ev_maria_3t, 'Normal', 0, 'Normales', 0, 1);

INSERT INTO crecimiento_3er_trimestre (evaluacion_id, peso_fetal_estimado_gr, percentil_ajustado, clasificacion_crecimiento, estadio_rciu_barcelona)
VALUES (@ev_maria_3t, 1850, 38, 'Adecuado', 'Ninguno');

INSERT INTO doppler_3er_trimestre (evaluacion_id, au_pi, au_flujo_diastolico, acm_pi, dv_onda_a, uta_pi_promedio, ratio_cu_icp, vena_umbilical, alteracion_doppler_detectada)
VALUES (@ev_maria_3t, 0.98, 'Presente', 1.65, 'Positiva', 0.92, 1.68, 'Normal', 0);

INSERT INTO anatomia_liquido_3er_trimestre (evaluacion_id, circular_cordon_cuello, liquido_amniotico_mm, metodo_medicion_liquido, diagnostico_liquido, estructuras_normales)
VALUES (@ev_maria_3t, 'Negativo', 125, 'Phelan', 'Normal', 1);

INSERT INTO evaluacion_placentaria_3er_trimestre (evaluacion_id, localizacion_placentaria, distancia_oci_mm, grosor_placentario_mm, grado_madurez, ecogenicidad, lagunas_vasculares, interfase_miometrial, vasos_puente, zona_retroplacentaria, protrusion_placentaria, vascularizacion_anomala_doppler, insercion_cordon, numero_vasos_umbilicales, calcificaciones, perfusion_vi, perfusion_fi, perfusion_vfi, acretismo_figo_pas, morfologia_uterina_eshre, miomas_visibles, miomas_figo_tipo, miomas_dimensiones_mm, miomas_obstruyen_canal)
VALUES (@ev_maria_3t, 'Anterior', 35.00, 33, 'Grado 0-1', 'Homogenea', 'Ausentes/minimas', 'Intacta', 0, 'Presente', 0, 'Normal', 'Central', '3', 'Ausentes', 31.00, 40.50, 8.00, 'Grado 0', 'U0', 0, NULL, NULL, 0);


-- ============================================================
-- 5. BLOQUE: Dra. Juana (Jefa, ID 3) + Paciente Ana Martínez (ID 4)
-- ============================================================
SET @pid_ana   = 4;
SET @mid_juana = 3;
SET @uid_juana = 3;

-- -------------------- 1er Trimestre: Ana Martínez --------------------
INSERT INTO evaluaciones_1er_trimestre (paciente_id, medico_id, codigo_reporte, fecha_evaluacion, fecha_estudio, peso_kg, talla_cm, ta_sistolica, ta_diastolica, fum, fpp_usg, embarazo_multiple, estado_feto, fcf_lpm, lcc_mm, edad_gestacional_semanas, estado, created_by, updated_by)
VALUES (@pid_ana, @mid_juana, 'EV1T-0004-2026', '2026-03-05', '2026-03-05', 72.00, 155.00, 135, 88, '2025-12-14', '2026-09-20', 0, 'Vivo', 162, 50.20, 11.5, 'Completado', @uid_juana, @uid_juana);
SET @ev_ana_1t = LAST_INSERT_ID();

INSERT INTO anatomia_fetal (evaluacion_id, estado_exploracion, snc_simetria_plexos, macizo_facial_integro, torax_situs, torax_eje_cardiaco_grados, abdomen_camara_gastrica, extremidades_completas, observaciones_anomalias)
VALUES (@ev_ana_1t, 'Completa', 1, 1, 'Solitus', 50, 1, 1, 'Exploración completa sin hallazgos anormales.');

INSERT INTO marcadores_fmf (evaluacion_id, translucencia_nucal_mm, hueso_nasal_presente, ductus_venoso_onda_a, regurgitacion_tricuspidea_ausente, vejiga_fetal_mm, uta_pi_promedio, muesca_bilateral, papp_a_mom, plgf_mom, tamizaje_genetico_tipo, tamizaje_genetico_resultado)
VALUES (@ev_ana_1t, 2.10, 1, 'Positiva', 1, 5.80, 1.85, 1, 0.72, 0.68, 'No realizado', NULL);

INSERT INTO entorno_materno (evaluacion_id, liquido_amniotico, placenta_posicion, placenta_insercion, longitud_cervical_mm, indice_consistencia_cervical_pct, morfologia_uterina_eshre, miomas_visibles, miomas_figo_tipo)
VALUES (@ev_ana_1t, 'Normal', 'Lateral Derecho', 'Baja Temprana', 31.00, 62, 'U1', 1, 'FIGO Tipo 3 - Subseroso');

INSERT INTO impresion_diagnostica (evaluacion_id, riesgo_basal_cromosomopatias, riesgo_ajustado_cromosomopatias, probabilidad_cromosomopatias, riesgo_preeclampsia_temprana, riesgo_enfermedad_placentaria_tardia, riesgo_parto_pretermino)
VALUES (@ev_ana_1t, '1:200', '1:350', 'Alta', 'Alta', 'Alta', 'Alto');

-- -------------------- 2do Trimestre: Ana Martínez --------------------
INSERT INTO evaluaciones_2do_trimestre (paciente_id, medico_id, codigo_reporte, fecha_evaluacion, fecha_estudio, edad_gestacional_semanas, fpp_actual, peso_kg, talla_cm, pam_mmhg, uta_pi_promedio, peso_1er_trimestre_kg, ganancia_peso_kg, estado, created_by, updated_by)
VALUES (@pid_ana, @mid_juana, 'EV2T-0004-2026', '2026-05-28', '2026-05-28', 21.5, '2026-09-22', 76.50, 155.00, 98.00, 1.42, 72.00, 4.50, 'Completado', @uid_juana, @uid_juana);
SET @ev_ana_2t = LAST_INSERT_ID();

INSERT INTO biometria_2do_trimestre (evaluacion_id, estado_feto, fcf_lpm, peso_fetal_estimado_gr, percentil_hadlock, crecimiento_armonico, indice_cefalico_ci, fl_ac_pct, hc_ac_campbell)
VALUES (@ev_ana_2t, 'Vivo', 152, 420, 55, 1, 79.50, 22.50, 1.15);

INSERT INTO anatomia_fetal_2do_trimestre (evaluacion_id, craneo_snc_normal, cara_cuello_normal, corazon_normal, torax_diafragma_normal, abdomen_normal, genitourinario_normal, columna_normal, extremidades_normal, detalles_anomalias)
VALUES (@ev_ana_2t, 1, 1, 1, 1, 1, 1, 1, 1, NULL);

INSERT INTO marcadores_ecograficos_2do_trimestre (evaluacion_id, ventriculomegalia_leve, quistes_plexos_coroideos, pliegue_nucal_aumentado, hueso_nasal_ausente, foco_ecogenico_cardiaco, intestino_hiperecogenico, femur_corto, arteria_umbilical_unica)
VALUES (@ev_ana_2t, 0, 0, 1, 0, 0, 0, 0, 0);

INSERT INTO entorno_placentario_2do_trimestre (evaluacion_id, placenta_posicion, distancia_borde_oci_mm, acretismo_figo_grado, bolsillo_max_liquido_mm, longitud_cervical_mm, indice_consistencia_cervical, funneling_presente, funneling_mm, sludge_intraamniotico, morfologia_uterina_eshre, miomas_visibles, miomas_figo_tipo, miomas_dimensiones_mm, miomas_vascularizacion)
VALUES (@ev_ana_2t, 'Lateral Derecho', 28.00, '1', 48, 29.00, 60, 0, NULL, 'Dudoso', 'U1', 1, 'FIGO Tipo 3', '18x12x10', 'Moderada');

INSERT INTO impresion_diagnostica_2do_trimestre (evaluacion_id, riesgo_cromosomopatias, riesgo_parto_pretermino, riesgo_preeclampsia, observaciones_medicas)
VALUES (@ev_ana_2t, 'Intermedio', 'Alto', 'Alto', 'Paciente con hipertensión crónica + diabetes pregestacional. Acretismo FIGO G1. Pliegue nucal aumentado. Se recomienda NIPT y seguimiento estricto cada 2 semanas. AAS 150 mg + metformina.');

-- -------------------- 3er Trimestre: Ana Martínez --------------------
INSERT INTO evaluaciones_3er_trimestre (paciente_id, medico_id, codigo_reporte, fecha_evaluacion, fecha_estudio, estudio_solicitado, edad_gestacional_semanas, fpp_fum, fpp_usg, peso_kg, talla_cm, ta_sistolica, ta_diastolica, situacion_fetal, presentacion_fetal, posicion_fetal, feto_unico_vivo, fcf_lpm, equipo_ultrasonido, observaciones, estado, created_by, updated_by)
VALUES (@pid_ana, @mid_juana, 'EV3T-0004-2026', '2026-08-15', '2026-08-15', 'Crecimiento, Doppler materno-fetal, Evaluación placentaria y cervical', 33.0, '2026-09-18', '2026-09-22', 82.00, 155.00, 142, 92, 'Longitudinal', 'Cefalico', 'Dorso posterior', 'Vivo', 148, 'Samsung Hera W10', 'Paciente con control de TA subóptimo. Placenta lateral derecha con signos de acretismo. Cérvix acortado con sludge dudoso. Riesgo alto de preeclampsia y parto pretérmino. Se programa cesárea + posible histerectomía a semana 37.', 'En proceso', @uid_juana, @uid_juana);
SET @ev_ana_3t = LAST_INSERT_ID();

INSERT INTO antecedentes_3er_trimestre (evaluacion_id, curva_tolerancia_glucosa, diabetes_gestacional_actual, movimientos_fetales, signos_amenaza_parto_pretermino, plan_nacimiento_definido)
VALUES (@ev_ana_3t, 'Alterada', 1, 'Disminuidos', 1, 1);

INSERT INTO crecimiento_3er_trimestre (evaluacion_id, peso_fetal_estimado_gr, percentil_ajustado, clasificacion_crecimiento, estadio_rciu_barcelona)
VALUES (@ev_ana_3t, 1980, 55, 'Mayor a lo esperado', 'Ninguno');

INSERT INTO doppler_3er_trimestre (evaluacion_id, au_pi, au_flujo_diastolico, acm_pi, dv_onda_a, uta_pi_promedio, ratio_cu_icp, vena_umbilical, alteracion_doppler_detectada)
VALUES (@ev_ana_3t, 1.12, 'Presente', 1.40, 'Positiva', 1.25, 0.80, 'Normal', 1);

INSERT INTO anatomia_liquido_3er_trimestre (evaluacion_id, circular_cordon_cuello, liquido_amniotico_mm, metodo_medicion_liquido, diagnostico_liquido, estructuras_normales)
VALUES (@ev_ana_3t, 'Doble', 185, 'Phelan', 'Polihidramnios', 1);

INSERT INTO evaluacion_placentaria_3er_trimestre (evaluacion_id, localizacion_placentaria, distancia_oci_mm, grosor_placentario_mm, grado_madurez, ecogenicidad, lagunas_vasculares, interfase_miometrial, vasos_puente, zona_retroplacentaria, protrusion_placentaria, vascularizacion_anomala_doppler, insercion_cordon, numero_vasos_umbilicales, calcificaciones, perfusion_vi, perfusion_fi, perfusion_vfi, acretismo_figo_pas, morfologia_uterina_eshre, miomas_visibles, miomas_figo_tipo, miomas_dimensiones_mm, miomas_obstruyen_canal)
VALUES (@ev_ana_3t, 'Lateral Derecha', 18.00, 38, 'Grado 2', 'Heterogenea', 'Si', 'Adelgazada', 1, 'Ausente', 1, 'Turbulento', 'Marginal', '3', 'Moderadas', 45.00, 38.00, 12.50, 'Grado 2', 'U1', 1, 'FIGO Tipo 3', '20x14x12', 0);


-- ============================================================
-- 6. BLOQUE: Dr. Pedro (Médico, ID 4) + Paciente Laura Hernández (ID 5)
-- ============================================================
SET @pid_laura = 5;
SET @mid_pedro = 4;
SET @uid_pedro = 4;

-- -------------------- 1er Trimestre: Laura Hernández --------------------
INSERT INTO evaluaciones_1er_trimestre (paciente_id, medico_id, codigo_reporte, fecha_evaluacion, fecha_estudio, peso_kg, talla_cm, ta_sistolica, ta_diastolica, fum, fpp_usg, embarazo_multiple, estado_feto, fcf_lpm, lcc_mm, edad_gestacional_semanas, estado, created_by, updated_by)
VALUES (@pid_laura, @mid_pedro, 'EV1T-0005-2026', '2026-03-18', '2026-03-18', 55.00, 168.00, 108, 65, '2025-12-28', '2026-10-04', 0, 'Vivo', 160, 52.00, 11.5, 'Completado', @uid_pedro, @uid_pedro);
SET @ev_laura_1t = LAST_INSERT_ID();

INSERT INTO anatomia_fetal (evaluacion_id, estado_exploracion, snc_simetria_plexos, macizo_facial_integro, torax_situs, torax_eje_cardiaco_grados, abdomen_camara_gastrica, extremidades_completas, observaciones_anomalias)
VALUES (@ev_laura_1t, 'Completa', 1, 1, 'Solitus', 46, 1, 1, NULL);

INSERT INTO marcadores_fmf (evaluacion_id, translucencia_nucal_mm, hueso_nasal_presente, ductus_venoso_onda_a, regurgitacion_tricuspidea_ausente, vejiga_fetal_mm, uta_pi_promedio, muesca_bilateral, papp_a_mom, plgf_mom, tamizaje_genetico_tipo, tamizaje_genetico_resultado)
VALUES (@ev_laura_1t, 1.05, 1, 'Positiva', 1, 6.20, 1.15, 0, 1.25, 1.30, 'No realizado', NULL);

INSERT INTO entorno_materno (evaluacion_id, liquido_amniotico, placenta_posicion, placenta_insercion, longitud_cervical_mm, indice_consistencia_cervical_pct, morfologia_uterina_eshre, miomas_visibles, miomas_figo_tipo)
VALUES (@ev_laura_1t, 'Normal', 'Posterior', 'Normal', 42.00, 88, 'U0', 0, NULL);

INSERT INTO impresion_diagnostica (evaluacion_id, riesgo_basal_cromosomopatias, riesgo_ajustado_cromosomopatias, probabilidad_cromosomopatias, riesgo_preeclampsia_temprana, riesgo_enfermedad_placentaria_tardia, riesgo_parto_pretermino)
VALUES (@ev_laura_1t, '1:3200', '1:6800', 'Baja', 'Baja', 'Baja', 'Bajo');

-- -------------------- 2do Trimestre: Laura Hernández --------------------
INSERT INTO evaluaciones_2do_trimestre (paciente_id, medico_id, codigo_reporte, fecha_evaluacion, fecha_estudio, edad_gestacional_semanas, fpp_actual, peso_kg, talla_cm, pam_mmhg, uta_pi_promedio, peso_1er_trimestre_kg, ganancia_peso_kg, estado, created_by, updated_by)
VALUES (@pid_laura, @mid_pedro, 'EV2T-0005-2026', '2026-06-05', '2026-06-05', 22.0, '2026-10-06', 59.50, 168.00, 78.00, 0.88, 55.00, 4.50, 'Completado', @uid_pedro, @uid_pedro);
SET @ev_laura_2t = LAST_INSERT_ID();

INSERT INTO biometria_2do_trimestre (evaluacion_id, estado_feto, fcf_lpm, peso_fetal_estimado_gr, percentil_hadlock, crecimiento_armonico, indice_cefalico_ci, fl_ac_pct, hc_ac_campbell)
VALUES (@ev_laura_2t, 'Vivo', 150, 460, 62, 1, 80.00, 23.00, 1.18);

INSERT INTO anatomia_fetal_2do_trimestre (evaluacion_id, craneo_snc_normal, cara_cuello_normal, corazon_normal, torax_diafragma_normal, abdomen_normal, genitourinario_normal, columna_normal, extremidades_normal, detalles_anomalias)
VALUES (@ev_laura_2t, 1, 1, 1, 1, 1, 1, 1, 1, NULL);

INSERT INTO marcadores_ecograficos_2do_trimestre (evaluacion_id, ventriculomegalia_leve, quistes_plexos_coroideos, pliegue_nucal_aumentado, hueso_nasal_ausente, foco_ecogenico_cardiaco, intestino_hiperecogenico, femur_corto, arteria_umbilical_unica)
VALUES (@ev_laura_2t, 0, 0, 0, 0, 0, 0, 0, 0);

INSERT INTO entorno_placentario_2do_trimestre (evaluacion_id, placenta_posicion, distancia_borde_oci_mm, acretismo_figo_grado, bolsillo_max_liquido_mm, longitud_cervical_mm, indice_consistencia_cervical, funneling_presente, funneling_mm, sludge_intraamniotico, morfologia_uterina_eshre, miomas_visibles, miomas_figo_tipo, miomas_dimensiones_mm, miomas_vascularizacion)
VALUES (@ev_laura_2t, 'Posterior', 48.00, '0', 58, 40.00, 85, 0, NULL, 'No', 'U0', 0, NULL, NULL, NULL);

INSERT INTO impresion_diagnostica_2do_trimestre (evaluacion_id, riesgo_cromosomopatias, riesgo_parto_pretermino, riesgo_preeclampsia, observaciones_medicas)
VALUES (@ev_laura_2t, 'Bajo', 'Bajo', 'Bajo', 'Embarazo de bajo riesgo. Crecimiento y anatomía fetal normales. Sin marcadores ecográficos de aneuploidía. Próximo control en 6 semanas.');

-- -------------------- 3er Trimestre: Laura Hernández --------------------
INSERT INTO evaluaciones_3er_trimestre (paciente_id, medico_id, codigo_reporte, fecha_evaluacion, fecha_estudio, estudio_solicitado, edad_gestacional_semanas, fpp_fum, fpp_usg, peso_kg, talla_cm, ta_sistolica, ta_diastolica, situacion_fetal, presentacion_fetal, posicion_fetal, feto_unico_vivo, fcf_lpm, equipo_ultrasonido, observaciones, estado, created_by, updated_by)
VALUES (@pid_laura, @mid_pedro, 'EV3T-0005-2026', '2026-08-28', '2026-08-28', 'Crecimiento fetal, Bienestar fetal', 34.0, '2026-10-04', '2026-10-06', 65.50, 168.00, 110, 68, 'Longitudinal', 'Cefalico', 'Dorso anterior, polo cefálico izquierdo', 'Vivo', 135, 'Samsung Hera W10', 'Embarazo normoevolutivo de bajo riesgo. Crecimiento fetal en percentil 65. Placenta y líquido amniótico sin alteraciones. Se recomienda control en 3 semanas para valoración final.', 'Completado', @uid_pedro, @uid_pedro);
SET @ev_laura_3t = LAST_INSERT_ID();

INSERT INTO antecedentes_3er_trimestre (evaluacion_id, curva_tolerancia_glucosa, diabetes_gestacional_actual, movimientos_fetales, signos_amenaza_parto_pretermino, plan_nacimiento_definido)
VALUES (@ev_laura_3t, 'Normal', 0, 'Normales', 0, 1);

INSERT INTO crecimiento_3er_trimestre (evaluacion_id, peso_fetal_estimado_gr, percentil_ajustado, clasificacion_crecimiento, estadio_rciu_barcelona)
VALUES (@ev_laura_3t, 2450, 65, 'Adecuado', 'Ninguno');

INSERT INTO doppler_3er_trimestre (evaluacion_id, au_pi, au_flujo_diastolico, acm_pi, dv_onda_a, uta_pi_promedio, ratio_cu_icp, vena_umbilical, alteracion_doppler_detectada)
VALUES (@ev_laura_3t, 0.82, 'Presente', 1.72, 'Positiva', 0.78, 2.10, 'Normal', 0);

INSERT INTO anatomia_liquido_3er_trimestre (evaluacion_id, circular_cordon_cuello, liquido_amniotico_mm, metodo_medicion_liquido, diagnostico_liquido, estructuras_normales)
VALUES (@ev_laura_3t, 'Negativo', 140, 'Phelan', 'Normal', 1);

INSERT INTO evaluacion_placentaria_3er_trimestre (evaluacion_id, localizacion_placentaria, distancia_oci_mm, grosor_placentario_mm, grado_madurez, ecogenicidad, lagunas_vasculares, interfase_miometrial, vasos_puente, zona_retroplacentaria, protrusion_placentaria, vascularizacion_anomala_doppler, insercion_cordon, numero_vasos_umbilicales, calcificaciones, perfusion_vi, perfusion_fi, perfusion_vfi, acretismo_figo_pas, morfologia_uterina_eshre, miomas_visibles, miomas_figo_tipo, miomas_dimensiones_mm, miomas_obstruyen_canal)
VALUES (@ev_laura_3t, 'Posterior', 52.00, 30, 'Grado 0-1', 'Homogenea', 'Ausentes/minimas', 'Intacta', 0, 'Presente', 0, 'Normal', 'Central', '3', 'Ausentes', 33.50, 43.00, 9.00, 'Grado 0', 'U0', 0, NULL, NULL, 0);


-- ============================================================
-- RESUMEN
-- ============================================================
SELECT '====================================================' AS '';
SELECT ' SEED COMPLETO:' AS '';
SELECT '  - 3 usuarios nuevos (admin, jefa, medico)' AS '';
SELECT '  - 3 pacientes nuevos (María, Ana, Laura)' AS '';
SELECT '  - 3 historiales clínicos variados' AS '';

SELECT '  -       3 de 1er Trimestre' AS '';
SELECT '  -       3 de 2do Trimestre' AS '';
SELECT '  -       3 de 3er Trimestre' AS '';
SELECT '  -       9 EVALUACIONES TOTALES' AS '';
SELECT '====================================================' AS '';

SELECT 'USUARIOS:' AS '';
SELECT id, nombre, apellido, email, rol_id FROM usuarios WHERE id IN (2,3,4);

SELECT 'PACIENTES:' AS '';
SELECT id, nombre, apellido, fecha_nacimiento FROM pacientes WHERE id IN (3,4,5);

SELECT 'EVALUACIONES 1ER TRIMESTRE:' AS '';
SELECT id, codigo_reporte, paciente_id, medico_id, fecha_evaluacion, estado FROM evaluaciones_1er_trimestre WHERE codigo_reporte IN ('EV1T-0003-2026','EV1T-0004-2026','EV1T-0005-2026');

SELECT 'EVALUACIONES 2DO TRIMESTRE:' AS '';
SELECT id, codigo_reporte, paciente_id, medico_id, fecha_evaluacion, estado FROM evaluaciones_2do_trimestre WHERE codigo_reporte IN ('EV2T-0003-2026','EV2T-0004-2026','EV2T-0005-2026');

SELECT 'EVALUACIONES 3ER TRIMESTRE:' AS '';
SELECT id, codigo_reporte, paciente_id, medico_id, fecha_evaluacion, estado FROM evaluaciones_3er_trimestre WHERE codigo_reporte IN ('EV3T-0003-2026','EV3T-0004-2026','EV3T-0005-2026');
