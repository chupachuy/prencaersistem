-- ============================================================
-- Migración 003: Geolocalización automática para firmas
-- Agrega columnas de geolocalización por IP a registro_firmas
-- ============================================================

ALTER TABLE registro_firmas
  ADD COLUMN IF NOT EXISTS geo_pais VARCHAR(100) NULL COMMENT 'País (desde IP)',
  ADD COLUMN IF NOT EXISTS geo_region VARCHAR(100) NULL COMMENT 'Región/Estado',
  ADD COLUMN IF NOT EXISTS geo_ciudad VARCHAR(100) NULL COMMENT 'Ciudad',
  ADD COLUMN IF NOT EXISTS geo_latitud DECIMAL(10,6) NULL,
  ADD COLUMN IF NOT EXISTS geo_longitud DECIMAL(10,6) NULL,
  ADD COLUMN IF NOT EXISTS geo_proveedor VARCHAR(150) NULL COMMENT 'ISP / Proveedor de internet';
