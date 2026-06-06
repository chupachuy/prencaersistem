-- ============================================================
-- Migración: Añadir antecedentes obstétricos al historial_clinico
-- G (Gravidez), C (Cesáreas), A (Abortos), E (Ectópicos)
-- ============================================================

ALTER TABLE historial_clinico
  ADD COLUMN IF NOT EXISTS num_embarazos INT NULL COMMENT 'Gravidez - G',
  ADD COLUMN IF NOT EXISTS num_cesareas INT NULL COMMENT 'Cesáreas previas - C',
  ADD COLUMN IF NOT EXISTS num_abortos INT NULL COMMENT 'Abortos previos - A',
  ADD COLUMN IF NOT EXISTS num_ectopicos INT NULL COMMENT 'Embarazos ectópicos previos - E';
