-- Migración: Agregar anatomía fetal detallada y marcadores ecográficos a 3er Trimestre
-- Tabla: anatomia_liquido_3er_trimestre

ALTER TABLE anatomia_liquido_3er_trimestre
  ADD COLUMN craneo_snc_normal BOOLEAN DEFAULT TRUE AFTER estructuras_normales,
  ADD COLUMN cara_cuello_normal BOOLEAN DEFAULT TRUE AFTER craneo_snc_normal,
  ADD COLUMN corazon_normal BOOLEAN DEFAULT TRUE AFTER cara_cuello_normal,
  ADD COLUMN torax_diafragma_normal BOOLEAN DEFAULT TRUE AFTER corazon_normal,
  ADD COLUMN abdomen_normal BOOLEAN DEFAULT TRUE AFTER torax_diafragma_normal,
  ADD COLUMN genitourinario_normal BOOLEAN DEFAULT TRUE AFTER abdomen_normal,
  ADD COLUMN columna_normal BOOLEAN DEFAULT TRUE AFTER genitourinario_normal,
  ADD COLUMN extremidades_normal BOOLEAN DEFAULT TRUE AFTER columna_normal,
  ADD COLUMN detalles_anatomia TEXT NULL AFTER extremidades_normal,
  ADD COLUMN ventriculomegalia_leve BOOLEAN DEFAULT FALSE AFTER detalles_anatomia,
  ADD COLUMN quistes_plexos_coroideos BOOLEAN DEFAULT FALSE AFTER ventriculomegalia_leve,
  ADD COLUMN pliegue_nucal_aumentado BOOLEAN DEFAULT FALSE AFTER quistes_plexos_coroideos,
  ADD COLUMN hueso_nasal_ausente BOOLEAN DEFAULT FALSE AFTER pliegue_nucal_aumentado,
  ADD COLUMN foco_ecogenico_cardiaco BOOLEAN DEFAULT FALSE AFTER hueso_nasal_ausente,
  ADD COLUMN intestino_hiperecogenico BOOLEAN DEFAULT FALSE AFTER foco_ecogenico_cardiaco,
  ADD COLUMN femur_corto BOOLEAN DEFAULT FALSE AFTER intestino_hiperecogenico,
  ADD COLUMN arteria_umbilical_unica BOOLEAN DEFAULT FALSE AFTER femur_corto;
