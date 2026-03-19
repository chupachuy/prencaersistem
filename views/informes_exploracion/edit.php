<?php 
$title = "Editar Informe de Exploración";
require_once __DIR__ . '/../layouts/header.php'; 
require_once __DIR__ . '/../layouts/sidebar.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><?php echo htmlspecialchars($title); ?></h2>
        <p class="text-muted small mb-0">Informe: <span class="text-primary fw-bold"><?php echo htmlspecialchars($informe['codigo_informe']); ?></span></p>
    </div>
    <div>
        <a href="<?php echo Url::to('/informes_exploracion/show?id=' . $informe['id']); ?>" class="btn btn-info">
            <i class="fa-regular fa-eye"></i> Ver Informe
        </a>
        <a href="<?php echo Url::to('/informes_exploracion'); ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<?php if (Session::has('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo Session::get('success'); Session::remove('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (Session::has('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo Session::get('error'); Session::remove('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo Url::to('/informes_exploracion/update'); ?>">
    <input type="hidden" name="id" value="<?php echo $informe['id']; ?>">
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa-solid fa-file-medical-alt"></i> Datos del Informe - Trimestre <?php echo $informe['trimestre']; ?></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha del Informe *</label>
                            <input type="date" name="fecha_informe" class="form-control" value="<?php echo htmlspecialchars($informe['fecha_informe']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="Pendiente" <?php echo $informe['estado'] == 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="En proceso" <?php echo $informe['estado'] == 'En proceso' ? 'selected' : ''; ?>>En proceso</option>
                                <option value="Completado" <?php echo $informe['estado'] == 'Completado' ? 'selected' : ''; ?>>Completado</option>
                                <option value="Archivado" <?php echo $informe['estado'] == 'Archivado' ? 'selected' : ''; ?>>Archivado</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Estudio Solicitado</label>
                            <input type="text" name="estudio_solicitado" class="form-control" value="<?php echo htmlspecialchars($informe['estudio_solicitado'] ?? ''); ?>" placeholder="Ej: Ultrasonido estructural">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fa-solid fa Ultrasound"></i> Datos del Ultrasonido (USG)</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Publicación del Parto (USG)</label>
                            <input type="date" name="fecha_publicacion_parto_usg" class="form-control" value="<?php echo htmlspecialchars($informe['fecha_publicacion_parto_usg'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha Probable de Parto (USG)</label>
                            <input type="date" name="fecha_probable_parto_usg" class="form-control" value="<?php echo htmlspecialchars($informe['fecha_probable_parto_usg'] ?? ''); ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Resumen del Ultrasonido</label>
                            <textarea name="resumen_ultrasonido" class="form-control" rows="6" placeholder="Escriba los hallazgos del ultrasonido..."><?php echo htmlspecialchars($informe['resumen_ultrasonido'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fa-solid fa-stethoscope"></i> Diagnósticos</h5>
                </div>
                <div class="card-body">
                    <div id="diagnosticos-container">
                        <?php if (!empty($diagnosticos)): ?>
                            <?php foreach ($diagnosticos as $index => $diag): ?>
                                <div class="diagnostico-item border rounded p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <label class="form-label">Código</label>
                                            <input type="text" name="diagnostico_codigo[]" class="form-control" value="<?php echo htmlspecialchars($diag['codigo_diagnostico'] ?? ''); ?>" placeholder="Código">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Título *</label>
                                            <input type="text" name="diagnostico_titulo[]" class="form-control" value="<?php echo htmlspecialchars($diag['titulo']); ?>" required>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="form-label">Fecha</label>
                                            <input type="date" name="diagnostico_fecha[]" class="form-control" value="<?php echo htmlspecialchars($diag['fecha_diagnostico']); ?>">
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label class="form-label">Descripción</label>
                                            <textarea name="diagnostico_descripcion[]" class="form-control" rows="2"><?php echo htmlspecialchars($diag['descripcion'] ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="diagnostico-item border rounded p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">Código</label>
                                        <input type="text" name="diagnostico_codigo[]" class="form-control" placeholder="Código">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Título *</label>
                                        <input type="text" name="diagnostico_titulo[]" class="form-control" required>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">Fecha</label>
                                        <input type="date" name="diagnostico_fecha[]" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Descripción</label>
                                        <textarea name="diagnostico_descripcion[]" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary" onclick="agregarDiagnostico()">
                        <i class="fa-solid fa-plus"></i> Agregar Diagnóstico
                    </button>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fa-solid fa-comment"></i> Observaciones</h5>
                </div>
                <div class="card-body">
                    <textarea name="observaciones" class="form-control" rows="4" placeholder="Observaciones adicionales..."><?php echo htmlspecialchars($informe['observaciones'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fa-solid fa-circle-info"></i> Información</h5>
                </div>
                <div class="card-body">
                    <p><strong>Código:</strong> <?php echo htmlspecialchars($informe['codigo_informe']); ?></p>
                    <p><strong>Trimestre:</strong> <?php echo $informe['trimestre']; ?></p>
                    <p><strong>Creado:</strong> <?php echo date('d/m/Y H:i', strtotime($informe['created_at'])); ?></p>
                </div>
            </div>

            <?php if ($roleId == 1 || $roleId == 2): ?>
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa-solid fa-user-doctor"></i> Referencia Médica</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Médico Referido</label>
                        <select name="medico_referido_id" class="form-select">
                            <?php if (!empty($medicos)): ?>
                                <?php foreach ($medicos as $medico): ?>
                                    <option value="<?php echo $medico['id']; ?>" <?php echo $informe['medico_referido_id'] == $medico['id'] ? 'selected' : ''; ?>>
                                        Dr(a). <?php echo htmlspecialchars($medico['nombre'] . ' ' . $medico['apellido']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fa-solid fa-save"></i> Guardar Cambios
                </button>
                <a href="<?php echo Url::to('/informes_exploracion'); ?>" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </div>
</form>

<script>
function agregarDiagnostico() {
    const container = document.getElementById('diagnosticos-container');
    const html = `
        <div class="diagnostico-item border rounded p-3 mb-3">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="form-label">Código</label>
                    <input type="text" name="diagnostico_codigo[]" class="form-control" placeholder="Código">
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">Título *</label>
                    <input type="text" name="diagnostico_titulo[]" class="form-control" required>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="diagnostico_fecha[]" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-12 mb-2">
                    <label class="form-label">Descripción</label>
                    <textarea name="diagnostico_descripcion[]" class="form-control" rows="2"></textarea>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
