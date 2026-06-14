-- Migración: Añadir médico_solicitante_id y médico_referido_id a módulos que no lo tenían
-- Fecha: 2026-06

-- 1. ultrasonido_temprano (no tenía ninguno)
ALTER TABLE ultrasonido_temprano
  ADD COLUMN medico_solicitante_id INT NULL AFTER medico_id,
  ADD COLUMN medico_referido_id INT NULL AFTER medico_solicitante_id,
  ADD FOREIGN KEY fk_ust_solicitante (medico_solicitante_id) REFERENCES usuarios(id),
  ADD FOREIGN KEY fk_ust_referido (medico_referido_id) REFERENCES usuarios(id);

-- 2. evaluaciones_1er_trimestre (no tenía ninguno)
ALTER TABLE evaluaciones_1er_trimestre
  ADD COLUMN medico_solicitante_id INT NULL AFTER medico_id,
  ADD COLUMN medico_referido_id INT NULL AFTER medico_solicitante_id,
  ADD FOREIGN KEY fk_ev1t_solicitante (medico_solicitante_id) REFERENCES usuarios(id),
  ADD FOREIGN KEY fk_ev1t_referido (medico_referido_id) REFERENCES usuarios(id);

-- 3. evaluaciones_2do_trimestre (no tenía ninguno)
ALTER TABLE evaluaciones_2do_trimestre
  ADD COLUMN medico_solicitante_id INT NULL AFTER medico_id,
  ADD COLUMN medico_referido_id INT NULL AFTER medico_solicitante_id,
  ADD FOREIGN KEY fk_ev2t_solicitante (medico_solicitante_id) REFERENCES usuarios(id),
  ADD FOREIGN KEY fk_ev2t_referido (medico_referido_id) REFERENCES usuarios(id);

-- 4. evaluaciones_3er_trimestre (no tenía ninguno)
ALTER TABLE evaluaciones_3er_trimestre
  ADD COLUMN medico_solicitante_id INT NULL AFTER medico_id,
  ADD COLUMN medico_referido_id INT NULL AFTER medico_solicitante_id,
  ADD FOREIGN KEY fk_ev3t_solicitante (medico_solicitante_id) REFERENCES usuarios(id),
  ADD FOREIGN KEY fk_ev3t_referido (medico_referido_id) REFERENCES usuarios(id);

-- 5. evaluaciones_ginecologicas (ya tiene medico_solicitante_id, solo falta referido)
ALTER TABLE evaluaciones_ginecologicas
  ADD COLUMN medico_referido_id INT NULL AFTER medico_solicitante_id,
  ADD FOREIGN KEY fk_evg_referido (medico_referido_id) REFERENCES usuarios(id);
