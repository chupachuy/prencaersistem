-- ============================================================
-- SEED DATA: 2 evaluaciones por trimestre (feto VIVO)
-- Paciente: Liliana Esquivel Rivera (ID 1)
-- Médico:   Liliana Esquivel Doctora (ID 2)
-- ============================================================

SET @pid = 1;
SET @mid = 2;
SET @uid = 2;

-- ============================================================
-- 1ER TRIMESTRE — Registro #1
-- ============================================================
INSERT INTO evaluaciones_1er_trimestre (paciente_id, medico_id, codigo_reporte, fecha_evaluacion, fecha_estudio, peso_kg, talla_cm, ta_sistolica, ta_diastolica, fum, fpp_usg, embarazo_multiple, estado_feto, fcf_lpm, lcc_mm, edad_gestacional_semanas, estado, created_by, updated_by)
VALUES (@pid, @mid, 'EV1T-0001-2025', '2025-03-15', '2025-03-15', 62.50, 164.00, 110, 70, '2024-12-22', '2025-09-28', 0, 'Vivo', 158, 55.00, 12.0, 'Completado', @uid, @uid);
SET @e1 = LAST_INSERT_ID();

INSERT INTO anatomia_fetal (evaluacion_id, estado_exploracion, snc_simetria_plexos, macizo_facial_integro, torax_situs, torax_eje_cardiaco_grados, abdomen_camara_gastrica, extremidades_completas, observaciones_anomalias)
VALUES (@e1, 'Completa', 1, 1, 'Solitus', 45, 1, 1, NULL);
INSERT INTO marcadores_fmf (evaluacion_id, translucencia_nucal_mm, hueso_nasal_presente, ductus_venoso_onda_a, regurgitacion_tricuspidea_ausente, vejiga_fetal_mm, uta_pi_promedio, muesca_bilateral, papp_a_mom, plgf_mom, tamizaje_genetico_tipo, tamizaje_genetico_resultado)
VALUES (@e1, 1.20, 1, 'Positiva', 1, 6.5, 1.45, 0, 1.05, 1.12, 'No realizado', NULL);
INSERT INTO entorno_materno (evaluacion_id, liquido_amniotico, placenta_posicion, placenta_insercion, longitud_cervical_mm, indice_consistencia_cervical_pct, morfologia_uterina_eshre, miomas_visibles, miomas_figo_tipo)
VALUES (@e1, 'Normal', 'Posterior', 'Normal', 38.00, 75, 'U0', 0, NULL);
INSERT INTO impresion_diagnostica (evaluacion_id, riesgo_basal_cromosomopatias, riesgo_ajustado_cromosomopatias, probabilidad_cromosomopatias, riesgo_preeclampsia_temprana, riesgo_enfermedad_placentaria_tardia, riesgo_parto_pretermino)
VALUES (@e1, '1:2500', '1:5200', 'Baja', 'Baja', 'Baja', 'Bajo');

-- ============================================================
-- 1ER TRIMESTRE — Registro #2
-- ============================================================
INSERT INTO evaluaciones_1er_trimestre (paciente_id, medico_id, codigo_reporte, fecha_evaluacion, fecha_estudio, peso_kg, talla_cm, ta_sistolica, ta_diastolica, fum, fpp_usg, embarazo_multiple, estado_feto, fcf_lpm, lcc_mm, edad_gestacional_semanas, estado, created_by, updated_by)
VALUES (@pid, @mid, 'EV1T-0002-2025', '2025-02-10', '2025-02-10', 63.00, 164.00, 108, 68, '2024-12-10', '2025-09-17', 0, 'Vivo', 165, 19.00, 8.5, 'Completado', @uid, @uid);
SET @e2 = LAST_INSERT_ID();

INSERT INTO anatomia_fetal (evaluacion_id, estado_exploracion, snc_simetria_plexos, macizo_facial_integro, torax_situs, torax_eje_cardiaco_grados, abdomen_camara_gastrica, extremidades_completas, observaciones_anomalias)
VALUES (@e2, 'Completa', 1, 1, 'Solitus', 42, 1, 1, NULL);
INSERT INTO marcadores_fmf (evaluacion_id, translucencia_nucal_mm, hueso_nasal_presente, ductus_venoso_onda_a, regurgitacion_tricuspidea_ausente, vejiga_fetal_mm, uta_pi_promedio, muesca_bilateral, papp_a_mom, plgf_mom, tamizaje_genetico_tipo, tamizaje_genetico_resultado)
VALUES (@e2, 0.95, 1, 'Positiva', 1, 4.8, 1.32, 0, 1.18, 1.24, 'No realizado', NULL);
INSERT INTO entorno_materno (evaluacion_id, liquido_amniotico, placenta_posicion, placenta_insercion, longitud_cervical_mm, indice_consistencia_cervical_pct, morfologia_uterina_eshre, miomas_visibles, miomas_figo_tipo)
VALUES (@e2, 'Normal', 'Anterior', 'Normal', 40.00, 80, 'U0', 0, NULL);
INSERT INTO impresion_diagnostica (evaluacion_id, riesgo_basal_cromosomopatias, riesgo_ajustado_cromosomopatias, probabilidad_cromosomopatias, riesgo_preeclampsia_temprana, riesgo_enfermedad_placentaria_tardia, riesgo_parto_pretermino)
VALUES (@e2, '1:3000', '1:6100', 'Baja', 'Baja', 'Baja', 'Bajo');

-- ============================================================
-- 2DO TRIMESTRE — Registro #1
-- ============================================================
INSERT INTO evaluaciones_2do_trimestre (paciente_id, medico_id, codigo_reporte, fecha_evaluacion, fecha_estudio, edad_gestacional_semanas, fpp_actual, peso_kg, talla_cm, pam_mmhg, uta_pi_promedio, peso_1er_trimestre_kg, ganancia_peso_kg, estado, created_by, updated_by)
VALUES (@pid, @mid, 'EV2T-0001-2025', '2025-05-20', '2025-05-20', 21.0, '2025-09-28', 66.00, 164.00, 82.00, 1.05, 62.50, 3.50, 'Completado', @uid, @uid);
SET @e3 = LAST_INSERT_ID();

INSERT INTO biometria_2do_trimestre (evaluacion_id, estado_feto, fcf_lpm, peso_fetal_estimado_gr, percentil_hadlock, crecimiento_armonico, indice_cefalico_ci, fl_ac_pct, hc_ac_campbell)
VALUES (@e3, 'Vivo', 148, 380, 45, 1, 78.5, 22.0, 1.10);
INSERT INTO anatomia_fetal_2do_trimestre (evaluacion_id, craneo_snc_normal, cara_cuello_normal, corazon_normal, torax_diafragma_normal, abdomen_normal, genitourinario_normal, columna_normal, extremidades_normal, detalles_anomalias)
VALUES (@e3, 1, 1, 1, 1, 1, 1, 1, 1, NULL);
INSERT INTO marcadores_ecograficos_2do_trimestre (evaluacion_id, ventriculomegalia_leve, quistes_plexos_coroideos, pliegue_nucal_aumentado, hueso_nasal_ausente, foco_ecogenico_cardiaco, intestino_hiperecogenico, femur_corto, arteria_umbilical_unica)
VALUES (@e3, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO entorno_placentario_2do_trimestre (evaluacion_id, placenta_posicion, distancia_borde_oci_mm, acretismo_figo_grado, bolsillo_max_liquido_mm, longitud_cervical_mm, indice_consistencia_cervical, funneling_presente, funneling_mm, sludge_intraamniotico, morfologia_uterina_eshre, miomas_visibles, miomas_figo_tipo, miomas_dimensiones_mm, miomas_vascularizacion)
VALUES (@e3, 'Posterior', 35.00, '0', 55, 37.00, 78, 0, NULL, 'No', 'U0', 0, NULL, NULL, 0);
INSERT INTO impresion_diagnostica_2do_trimestre (evaluacion_id, riesgo_cromosomopatias, riesgo_parto_pretermino, riesgo_preeclampsia, observaciones_medicas)
VALUES (@e3, 'Bajo', 'Bajo', 'Bajo', 'Embarazo de evolución normal. Anatomía fetal sin alteraciones.');

-- ============================================================
-- 2DO TRIMESTRE — Registro #2
-- ============================================================
INSERT INTO evaluaciones_2do_trimestre (paciente_id, medico_id, codigo_reporte, fecha_evaluacion, fecha_estudio, edad_gestacional_semanas, fpp_actual, peso_kg, talla_cm, pam_mmhg, uta_pi_promedio, peso_1er_trimestre_kg, ganancia_peso_kg, estado, created_by, updated_by)
VALUES (@pid, @mid, 'EV2T-0002-2025', '2025-06-15', '2025-06-15', 19.5, '2025-09-17', 67.50, 164.00, 85.00, 0.98, 63.00, 4.50, 'Completado', @uid, @uid);
SET @e4 = LAST_INSERT_ID();

INSERT INTO biometria_2do_trimestre (evaluacion_id, estado_feto, fcf_lpm, peso_fetal_estimado_gr, percentil_hadlock, crecimiento_armonico, indice_cefalico_ci, fl_ac_pct, hc_ac_campbell)
VALUES (@e4, 'Vivo', 150, 310, 38, 1, 79.0, 21.5, 1.08);
INSERT INTO anatomia_fetal_2do_trimestre (evaluacion_id, craneo_snc_normal, cara_cuello_normal, corazon_normal, torax_diafragma_normal, abdomen_normal, genitourinario_normal, columna_normal, extremidades_normal, detalles_anomalias)
VALUES (@e4, 1, 1, 1, 1, 1, 1, 1, 1, NULL);
INSERT INTO marcadores_ecograficos_2do_trimestre (evaluacion_id, ventriculomegalia_leve, quistes_plexos_coroideos, pliegue_nucal_aumentado, hueso_nasal_ausente, foco_ecogenico_cardiaco, intestino_hiperecogenico, femur_corto, arteria_umbilical_unica)
VALUES (@e4, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO entorno_placentario_2do_trimestre (evaluacion_id, placenta_posicion, distancia_borde_oci_mm, acretismo_figo_grado, bolsillo_max_liquido_mm, longitud_cervical_mm, indice_consistencia_cervical, funneling_presente, funneling_mm, sludge_intraamniotico, morfologia_uterina_eshre, miomas_visibles, miomas_figo_tipo, miomas_dimensiones_mm, miomas_vascularizacion)
VALUES (@e4, 'Anterior', 42.00, '0', 60, 39.00, 82, 0, NULL, 'No', 'U0', 0, NULL, NULL, 0);
INSERT INTO impresion_diagnostica_2do_trimestre (evaluacion_id, riesgo_cromosomopatias, riesgo_parto_pretermino, riesgo_preeclampsia, observaciones_medicas)
VALUES (@e4, 'Bajo', 'Bajo', 'Bajo', 'Control rutinario. Feto con crecimiento adecuado para edad gestacional.');

-- ============================================================
-- 3ER TRIMESTRE — Registro #1
-- ============================================================
INSERT INTO evaluaciones_3er_trimestre (paciente_id, medico_id, codigo_reporte, fecha_evaluacion, fecha_estudio, estudio_solicitado, edad_gestacional_semanas, fpp_fum, fpp_usg, peso_kg, talla_cm, ta_sistolica, ta_diastolica, situacion_fetal, presentacion_fetal, posicion_fetal, feto_unico_vivo, fcf_lpm, equipo_ultrasonido, observaciones, estado, created_by, updated_by)
VALUES (@pid, @mid, 'EV3T-0001-2025', '2025-08-10', '2025-08-10', 'Crecimiento Fetal, Evaluación placentaria', 33.0, '2025-11-08', '2025-11-01', 71.00, 164.00, 115, 72, 'Longitudinal', 'Cefalico', 'Dorso anterior, polo cefálico derecho', 'Vivo', 142, 'General Electric Volusson Expert', 'Embarazo normoevolutivo. Placenta sin signos de acretismo. Líquido amniótico adecuado.', 'Completado', @uid, @uid);
SET @e5 = LAST_INSERT_ID();

INSERT INTO antecedentes_3er_trimestre (evaluacion_id, curva_tolerancia_glucosa, diabetes_gestacional_actual, movimientos_fetales, signos_amenaza_parto_pretermino, plan_nacimiento_definido)
VALUES (@e5, 'Normal', 0, 'Normales', 0, 1);
INSERT INTO crecimiento_3er_trimestre (evaluacion_id, peso_fetal_estimado_gr, percentil_ajustado, clasificacion_crecimiento, estadio_rciu_barcelona)
VALUES (@e5, 2100, 48, 'Adecuado', 'Ninguno');
INSERT INTO doppler_3er_trimestre (evaluacion_id, au_pi, au_flujo_diastolico, acm_pi, dv_onda_a, uta_pi_promedio, ratio_cu_icp, vena_umbilical, alteracion_doppler_detectada)
VALUES (@e5, 0.92, 'Presente', 1.68, 'Positiva', 0.88, 1.83, 'Normal', 0);
INSERT INTO anatomia_liquido_3er_trimestre (evaluacion_id, circular_cordon_cuello, liquido_amniotico_mm, metodo_medicion_liquido, diagnostico_liquido, estructuras_normales)
VALUES (@e5, 'Negativo', 135, 'Phelan', 'Normal', 1);
INSERT INTO evaluacion_placentaria_3er_trimestre (evaluacion_id, localizacion_placentaria, distancia_oci_mm, grosor_placentario_mm, grado_madurez, ecogenicidad, lagunas_vasculares, interfase_miometrial, vasos_puente, zona_retroplacentaria, protrusion_placentaria, vascularizacion_anomala_doppler, insercion_cordon, numero_vasos_umbilicales, calcificaciones, perfusion_vi, perfusion_fi, perfusion_vfi, acretismo_figo_pas, morfologia_uterina_eshre, miomas_visibles, miomas_figo_tipo, miomas_dimensiones_mm, miomas_obstruyen_canal)
VALUES (@e5, 'Posterior', 38.00, 35, 'Grado 0-1', 'Homogenea', 'Ausentes/minimas', 'Intacta', 0, 'Presente', 0, 'Normal', 'Central', '3', 'Ausentes', 32.50, 42.00, 8.50, 'Grado 0', 'U0', 0, NULL, NULL, 0);

-- ============================================================
-- 3ER TRIMESTRE — Registro #2
-- ============================================================
INSERT INTO evaluaciones_3er_trimestre (paciente_id, medico_id, codigo_reporte, fecha_evaluacion, fecha_estudio, estudio_solicitado, edad_gestacional_semanas, fpp_fum, fpp_usg, peso_kg, talla_cm, ta_sistolica, ta_diastolica, situacion_fetal, presentacion_fetal, posicion_fetal, feto_unico_vivo, fcf_lpm, equipo_ultrasonido, observaciones, estado, created_by, updated_by)
VALUES (@pid, @mid, 'EV3T-0002-2025', '2025-07-25', '2025-07-25', 'Crecimiento Fetal, Bienestar fetal', 30.5, '2025-09-24', '2025-10-02', 69.50, 164.00, 112, 70, 'Longitudinal', 'Cefalico', 'Dorso posterior', 'Vivo', 145, 'General Electric Volusson Expert', 'Crecimiento fetal adecuado. Doppler con índices dentro de percentiles normales. Se recomienda nuevo control en 4 semanas.', 'Completado', @uid, @uid);
SET @e6 = LAST_INSERT_ID();

INSERT INTO antecedentes_3er_trimestre (evaluacion_id, curva_tolerancia_glucosa, diabetes_gestacional_actual, movimientos_fetales, signos_amenaza_parto_pretermino, plan_nacimiento_definido)
VALUES (@e6, 'No realizada', 0, 'Normales', 0, 0);
INSERT INTO crecimiento_3er_trimestre (evaluacion_id, peso_fetal_estimado_gr, percentil_ajustado, clasificacion_crecimiento, estadio_rciu_barcelona)
VALUES (@e6, 1550, 42, 'Adecuado', 'Ninguno');
INSERT INTO doppler_3er_trimestre (evaluacion_id, au_pi, au_flujo_diastolico, acm_pi, dv_onda_a, uta_pi_promedio, ratio_cu_icp, vena_umbilical, alteracion_doppler_detectada)
VALUES (@e6, 0.98, 'Presente', 1.72, 'Positiva', 0.95, 1.75, 'Normal', 0);
INSERT INTO anatomia_liquido_3er_trimestre (evaluacion_id, circular_cordon_cuello, liquido_amniotico_mm, metodo_medicion_liquido, diagnostico_liquido, estructuras_normales)
VALUES (@e6, 'Simple', 128, 'Bolsillo Maximo', 'Normal', 1);
INSERT INTO evaluacion_placentaria_3er_trimestre (evaluacion_id, localizacion_placentaria, distancia_oci_mm, grosor_placentario_mm, grado_madurez, ecogenicidad, lagunas_vasculares, interfase_miometrial, vasos_puente, zona_retroplacentaria, protrusion_placentaria, vascularizacion_anomala_doppler, insercion_cordon, numero_vasos_umbilicales, calcificaciones, perfusion_vi, perfusion_fi, perfusion_vfi, acretismo_figo_pas, morfologia_uterina_eshre, miomas_visibles, miomas_figo_tipo, miomas_dimensiones_mm, miomas_obstruyen_canal)
VALUES (@e6, 'Anterior', 45.00, 32, 'Grado 0-1', 'Homogenea', 'Ausentes/minimas', 'Intacta', 0, 'Presente', 0, 'Normal', 'Central', '3', 'Ausentes', 30.00, 38.50, 7.20, 'Grado 0', 'U0', 0, NULL, NULL, 0);

SELECT 'Seed data complete. 6 evaluaciones (2 per trimestre) insertadas.' AS resultado;
