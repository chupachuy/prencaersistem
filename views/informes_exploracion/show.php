<?php 
$title = "Ver Informe de Exploración";
require_once __DIR__ . '/../layouts/header.php'; 
require_once __DIR__ . '/../layouts/sidebar.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><?php echo htmlspecialchars($title); ?></h2>
        <p class="text-muted small mb-0">Informe: <span class="text-primary fw-bold"><?php echo htmlspecialchars($informe['codigo_informe']); ?></span></p>
    </div>
    <div>
        <a href="<?php echo Url::to('/informes_exploracion/edit?id=' . $informe['id']); ?>" class="btn btn-warning">
            <i class="fa-solid fa-pen-to-square"></i> Editar
        </a>
        <a href="<?php echo Url::to('/informes_exploracion'); ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa-solid fa-file-medical-alt"></i> Datos del Informe - Trimestre <?php echo $informe['trimestre']; ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Fecha del Informe</label>
                        <p class="form-control-plaintext"><?php echo date('d/m/Y', strtotime($informe['fecha_informe'])); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Estado</label>
                        <p>
                            <?php 
                            $estadoClass = [
                                'Pendiente' => 'bg-warning',
                                'En proceso' => 'bg-primary',
                                'Completado' => 'bg-success',
                                'Archivado' => 'bg-secondary'
                            ];
                            ?>
                            <span class="badge <?php echo $estadoClass[$informe['estado']] ?? 'bg-secondary'; ?>">
                                <?php echo htmlspecialchars($informe['estado']); ?>
                            </span>
                        </p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label text-muted">Estudio Solicitado</label>
                        <p class="form-control-plaintext"><?php echo htmlspecialchars($informe['estudio_solicitado'] ?? 'No especificado'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa-solid fa-ultrasound"></i> Datos del Ultrasonido (USG)</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Fecha de Publicación del Parto (USG)</label>
                        <p class="form-control-plaintext">
                            <?php echo !empty($informe['fecha_publicacion_parto_usg']) ? date('d/m/Y', strtotime($informe['fecha_publicacion_parto_usg'])) : 'No especificada'; ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Fecha Probable de Parto (USG)</label>
                        <p class="form-control-plaintext">
                            <?php echo !empty($informe['fecha_probable_parto_usg']) ? date('d/m/Y', strtotime($informe['fecha_probable_parto_usg'])) : 'No especificada'; ?>
                        </p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label text-muted">Resumen del Ultrasonido</label>
                        <div class="p-3 bg-light rounded">
                            <?php echo nl2br(htmlspecialchars($informe['resumen_ultrasonido'] ?? 'Sin resumen')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="fa-solid fa-stethoscope"></i> Diagnósticos</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($diagnosticos)): ?>
                    <?php foreach ($diagnosticos as $diag): ?>
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <?php if (!empty($diag['codigo_diagnostico'])): ?>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($diag['codigo_diagnostico']); ?></span>
                                    <?php endif; ?>
                                    <strong><?php echo htmlspecialchars($diag['titulo']); ?></strong>
                                </div>
                                <small class="text-muted"><?php echo date('d/m/Y', strtotime($diag['fecha_diagnostico'])); ?></small>
                            </div>
                            <?php if (!empty($diag['descripcion'])): ?>
                                <p class="mb-0 text-muted"><?php echo nl2br(htmlspecialchars($diag['descripcion'])); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center">No hay diagnósticos registrados</p>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($informe['observaciones'])): ?>
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fa-solid fa-comment"></i> Observaciones</h5>
            </div>
            <div class="card-body">
                <?php echo nl2br(htmlspecialchars($informe['observaciones'])); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fa-solid fa-circle-info"></i> Información General</h5>
            </div>
            <div class="card-body">
                <p><strong>Código:</strong><br><?php echo htmlspecialchars($informe['codigo_informe']); ?></p>
                <p><strong>Trimestre:</strong><br>Trimestre <?php echo $informe['trimestre']; ?></p>
                <p><strong>Fecha de Creación:</strong><br><?php echo date('d/m/Y H:i', strtotime($informe['created_at'])); ?></p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa-solid fa-user-injured"></i> Paciente</h5>
            </div>
            <div class="card-body">
                <p><strong>Nombre:</strong><br><?php echo htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellido']); ?></p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa-solid fa-user-doctor"></i> Médicos</h5>
            </div>
            <div class="card-body">
                <p><strong>Médico Creador:</strong><br><?php echo htmlspecialchars(($medico['nombre'] ?? '') . ' ' . ($medico['apellido'] ?? '')); ?></p>
                <p><strong>Médico Referido:</strong><br><?php echo htmlspecialchars(($medicoReferido['nombre'] ?? '') . ' ' . ($medicoReferido['apellido'] ?? '')); ?></p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
