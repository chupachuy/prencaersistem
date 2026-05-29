-- ============================================================
-- Migración 001: Dependencias Cross-Trimestre
-- Agrega campos para soportar flujo de datos entre trimestres
-- ============================================================

-- 1. marcadores_fmf: marcadores bioquímicos y tamizaje genético
ALTER TABLE marcadores_fmf
  ADD COLUMN IF NOT EXISTS papp_a_mom DECIMAL(5,2) NULL COMMENT 'PAPP-A MoM',
  ADD COLUMN IF NOT EXISTS plgf_mom DECIMAL(5,2) NULL COMMENT 'PLGF MoM',
  ADD COLUMN IF NOT EXISTS tamizaje_genetico_tipo ENUM('DNA Fetal','Combinado 1T','No realizado') DEFAULT 'No realizado',
  ADD COLUMN IF NOT EXISTS tamizaje_genetico_resultado ENUM('Bajo Riesgo','Alto Riesgo','No concluyente') NULL;

-- 2. entorno_placentario_2do_trimestre: miomas y morfología uterina para 2T
ALTER TABLE entorno_placentario_2do_trimestre
  ADD COLUMN IF NOT EXISTS morfologia_uterina_eshre ENUM('U0','U1','U2','U3','U4','U5','U6') NULL COMMENT 'Clasificación ESHRE-ESGE',
  ADD COLUMN IF NOT EXISTS miomas_visibles BOOLEAN DEFAULT FALSE,
  ADD COLUMN IF NOT EXISTS miomas_figo_tipo VARCHAR(50) NULL COMMENT 'Clasificación FIGO 0-8',
  ADD COLUMN IF NOT EXISTS miomas_dimensiones_mm VARCHAR(100) NULL,
  ADD COLUMN IF NOT EXISTS miomas_vascularizacion VARCHAR(50) NULL;

-- 3. evaluacion_placentaria_3er_trimestre: miomas y morfología uterina para 3T
ALTER TABLE evaluacion_placentaria_3er_trimestre
  ADD COLUMN IF NOT EXISTS morfologia_uterina_eshre ENUM('U0','U1','U2','U3','U4','U5','U6') NULL COMMENT 'Clasificación ESHRE-ESGE',
  ADD COLUMN IF NOT EXISTS miomas_visibles BOOLEAN DEFAULT FALSE,
  ADD COLUMN IF NOT EXISTS miomas_figo_tipo VARCHAR(50) NULL COMMENT 'Clasificación FIGO 0-8',
  ADD COLUMN IF NOT EXISTS miomas_dimensiones_mm VARCHAR(100) NULL,
  ADD COLUMN IF NOT EXISTS miomas_obstruyen_canal BOOLEAN DEFAULT FALSE;

-- 4. evaluaciones_2do_trimestre: ganancia de peso
ALTER TABLE evaluaciones_2do_trimestre
  ADD COLUMN IF NOT EXISTS peso_1er_trimestre_kg DECIMAL(5,2) NULL COMMENT 'Peso base del 1er trimestre',
  ADD COLUMN IF NOT EXISTS ganancia_peso_kg DECIMAL(5,2) NULL COMMENT 'Incremento respecto al 1er trimestre';

-- 5. antecedentes_3er_trimestre: checklist Prenacer auto-calculado
ALTER TABLE antecedentes_3er_trimestre
  ADD COLUMN IF NOT EXISTS checklist_riesgo_preeclampsia_1t VARCHAR(20) NULL COMMENT 'Riesgo preeclampsia FMF del 1T',
  ADD COLUMN IF NOT EXISTS checklist_doppler_uterino_1t_pi DECIMAL(4,2) NULL COMMENT 'Doppler uterino 1T PI promedio',
  ADD COLUMN IF NOT EXISTS checklist_doppler_uterino_1t_muesca BOOLEAN NULL COMMENT 'Doppler uterino 1T muesca bilateral',
  ADD COLUMN IF NOT EXISTS checklist_papp_a_mom DECIMAL(5,2) NULL COMMENT 'PAPP-A MoM 1T',
  ADD COLUMN IF NOT EXISTS checklist_plgf_mom DECIMAL(5,2) NULL COMMENT 'PLGF MoM 1T',
  ADD COLUMN IF NOT EXISTS checklist_tamizaje_genetico_resultado VARCHAR(50) NULL COMMENT 'Tamizaje genético 1T',
  ADD COLUMN IF NOT EXISTS checklist_longitud_cervical_1t_mm DECIMAL(5,2) NULL COMMENT 'Longitud cervical 1T',
  ADD COLUMN IF NOT EXISTS checklist_morfologia_fetal_2t_normal BOOLEAN NULL COMMENT 'Morfología fetal 2T (Normal/Alterada)',
  ADD COLUMN IF NOT EXISTS checklist_doppler_uterino_2t_pi DECIMAL(4,2) NULL COMMENT 'Doppler uterino 2T PI',
  ADD COLUMN IF NOT EXISTS checklist_placenta_2t_posicion VARCHAR(50) NULL COMMENT 'Placenta 2T posición',
  ADD COLUMN IF NOT EXISTS checklist_placenta_2t_acretismo VARCHAR(10) NULL COMMENT 'Placenta 2T acretismo FIGO',
  ADD COLUMN IF NOT EXISTS checklist_longitud_cervical_2t_mm DECIMAL(5,2) NULL COMMENT 'Longitud cervical 2T',
  ADD COLUMN IF NOT EXISTS checklist_funneling_2t_presente BOOLEAN NULL COMMENT 'Funneling 2T presente',
  ADD COLUMN IF NOT EXISTS checklist_sludge_2t VARCHAR(10) NULL COMMENT 'Sludge intraamniótico 2T',
  ADD COLUMN IF NOT EXISTS checklist_icc_2t_pct INT NULL COMMENT 'ICC 2T porcentaje',
  ADD COLUMN IF NOT EXISTS checklist_rciu_2t_signos BOOLEAN NULL COMMENT 'Signos tempranos RCIU 2T';
