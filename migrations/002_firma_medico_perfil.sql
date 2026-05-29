-- ============================================================
-- Migración 002: Firma Digital del Médico desde Perfil
-- Agrega columna para firma maestra del médico en usuarios
-- ============================================================

ALTER TABLE usuarios
  ADD COLUMN IF NOT EXISTS ruta_firma VARCHAR(255) NULL COMMENT 'Firma digital del médico (PNG en storage/firmas/medicos/)';
