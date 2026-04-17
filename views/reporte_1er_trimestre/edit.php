<?php
$title = "Editar Reporte 1er Trimestre";
$meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
$fecha_hoy = date('j') . ' de ' . $meses[date('n')] . ' del ' . date('Y');
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/consultas'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <div>
            <h1 class="page-title mb-0">Editar Reporte 1er Trimestre</h1>
            <small class="text-muted">Paciente: <?php echo htmlspecialchars($consulta['paciente_nombre'] ?? ''); ?></small>
        </div>
    </div>
    <div class="page-header-actions">
        <span class="text-muted">
            <i class="fa-regular fa-calendar me-1"></i>
            <?php echo $fecha_hoy; ?>
        </span>
    </div>
</div>

<form action="<?php echo Url::to('/reporte_1er_trimestre/update'); ?>" method="POST">
    <input type="hidden" name="id_reporte_1t" value="<?php echo $reporte['id_reporte_1t']; ?>">
    <input type="hidden" name="id_consulta" value="<?php echo $reporte['id_consulta']; ?>">

    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-calendar me-2"></i> Fechas Obstétricas
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="fecha_ultima_regla" class="form-label">Fecha Última Regla (FUR)</label>
                        <input type="date" class="form-control" id="fecha_ultima_regla" name="fecha_ultima_regla" value="<?php echo $reporte['fecha_ultima_regla'] ?? ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="fpp_fum" class="form-label">FPP calculada por FUR</label>
                        <input type="date" class="form-control" id="fpp_fum" name="fpp_fum" value="<?php echo $reporte['fpp_fum'] ?? ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="fpp_usg" class="form-label">FPP calculada por USG</label>
                        <input type="date" class="form-control" id="fpp_usg" name="fpp_usg" value="<?php echo $reporte['fpp_usg'] ?? ''; ?>">
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-ruler me-2"></i> Medidas Ecográficas
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="longitud_craneo_cauda_mm" class="form-label">Longitud Craneo-Cauda (mm)</label>
                            <input type="number" step="0.01" class="form-control" id="longitud_craneo_cauda_mm" name="longitud_craneo_cauda_mm" value="<?php echo $reporte['longitud_craneo_cauda_mm'] ?? ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="translucencia_nucal_mm" class="form-label">Translucencia Nucal (mm)</label>
                            <input type="number" step="0.01" class="form-control" id="translucencia_nucal_mm" name="translucencia_nucal_mm" value="<?php echo $reporte['translucencia_nucal_mm'] ?? ''; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="frecuencia_cardiaca_fetal" class="form-label">Frecuencia Cardíaca Fetal (lpm)</label>
                            <input type="number" class="form-control" id="frecuencia_cardiaca_fetal" name="frecuencia_cardiaca_fetal" value="<?php echo $reporte['frecuencia_cardiaca_fetal'] ?? ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitud_cervical_mm" class="form-label">Longitud Cervical (mm)</label>
                            <input type="number" step="0.01" class="form-control" id="longitud_cervical_mm" name="longitud_cervical_mm" value="<?php echo $reporte['longitud_cervical_mm'] ?? ''; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> Evaluaciones de Riesgo
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="riesgo_cromosomopatias" class="form-label">Riesgo Cromosomopatías</label>
                        <select class="form-select" id="riesgo_cromosomopatias" name="riesgo_cromosomopatias">
                            <option value="">Seleccionar...</option>
                            <option value="Baja Probabilidad" <?php echo ($reporte['riesgo_cromosomopatias'] ?? '') === 'Baja Probabilidad' ? 'selected' : ''; ?>>Baja Probabilidad</option>
                            <option value="Alta Probabilidad" <?php echo ($reporte['riesgo_cromosomopatias'] ?? '') === 'Alta Probabilidad' ? 'selected' : ''; ?>>Alta Probabilidad</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="riesgo_placentaria_temprana" class="form-label">Riesgo Placentaria Temprana</label>
                        <select class="form-select" id="riesgo_placentaria_temprana" name="riesgo_placentaria_temprana">
                            <option value="">Seleccionar...</option>
                            <option value="Baja" <?php echo ($reporte['riesgo_placentaria_temprana'] ?? '') === 'Baja' ? 'selected' : ''; ?>>Baja</option>
                            <option value="Alta" <?php echo ($reporte['riesgo_placentaria_temprana'] ?? '') === 'Alta' ? 'selected' : ''; ?>>Alta</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="riesgo_placentaria_tardia" class="form-label">Riesgo Placentaria Tardía</label>
                        <select class="form-select" id="riesgo_placentaria_tardia" name="riesgo_placentaria_tardia">
                            <option value="">Seleccionar...</option>
                            <option value="Baja" <?php echo ($reporte['riesgo_placentaria_tardia'] ?? '') === 'Baja' ? 'selected' : ''; ?>>Baja</option>
                            <option value="Alta" <?php echo ($reporte['riesgo_placentaria_tardia'] ?? '') === 'Alta' ? 'selected' : ''; ?>>Alta</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="riesgo_parto_pretermino" class="form-label">Riesgo Parto Pretermino</label>
                        <select class="form-select" id="riesgo_parto_pretermino" name="riesgo_parto_pretermino">
                            <option value="">Seleccionar...</option>
                            <option value="Baja" <?php echo ($reporte['riesgo_parto_pretermino'] ?? '') === 'Baja' ? 'selected' : ''; ?>>Baja</option>
                            <option value="Alta" <?php echo ($reporte['riesgo_parto_pretermino'] ?? '') === 'Alta' ? 'selected' : ''; ?>>Alta</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-child me-2"></i> Exploración Anatómica
                    <small class="text-muted ms-2">(Marcar si está normal)</small>
                </div>
                <div class="card-body">
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="hueso_nasal_presente" name="hueso_nasal_presente" <?php echo ($reporte['hueso_nasal_presente'] ?? 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="hueso_nasal_presente">Hueso Nasal Presente</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="anatomia_craneo_snc_normal" name="anatomia_craneo_snc_normal" <?php echo ($reporte['anatomia_craneo_snc_normal'] ?? 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="anatomia_craneo_snc_normal">Anatomía Craneo/SNC Normal</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="anatomia_corazon_normal" name="anatomia_corazon_normal" <?php echo ($reporte['anatomia_corazon_normal'] ?? 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="anatomia_corazon_normal">Anatomía Corazón Normal</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="anatomia_abdomen_normal" name="anatomia_abdomen_normal" <?php echo ($reporte['anatomia_abdomen_normal'] ?? 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="anatomia_abdomen_normal">Anatomía Abdomen Normal</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="anatomia_extremidades_normal" name="anatomia_extremidades_normal" <?php echo ($reporte['anatomia_extremidades_normal'] ?? 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="anatomia_extremidades_normal">Anatomía Extremidades Normal</label>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-place-of-worship me-2"></i> Placenta y Líquido Amniótico
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="localizacion_placenta" class="form-label">Localización Placenta</label>
                        <input type="text" class="form-control" id="localizacion_placenta" name="localizacion_placenta" value="<?php echo $reporte['localizacion_placenta'] ?? ''; ?>">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="liquido_amniotico_normal" name="liquido_amniotico_normal" <?php echo ($reporte['liquido_amniotico_normal'] ?? 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="liquido_amniotico_normal">Líquido Amniótico Normal</label>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-comment-medical me-2"></i> Observaciones
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="observaciones_comentarios" class="form-label">Comentarios Adicionales</label>
                        <textarea class="form-control" id="observaciones_comentarios" name="observaciones_comentarios" rows="4"><?php echo htmlspecialchars($reporte['observaciones_comentarios'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo Url::to('/reporte_1er_trimestre/print?id=' . $reporte['id_reporte_1t']); ?>" class="btn btn-apple btn-apple-secondary" target="_blank">
                    <i class="fa-solid fa-print"></i> Ver PDF
                </a>
                <a href="<?php echo Url::to('/consultas'); ?>" class="btn btn-apple btn-apple-secondary">Cancelar</a>
                <button type="submit" class="btn btn-apple btn-apple-primary">
                    <i class="fa-solid fa-save"></i> Actualizar
                </button>
            </div>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
