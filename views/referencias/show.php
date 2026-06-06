<?php
$title = "Detalle de Referencia";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$estadoClase = '';
switch ($referencia['estado']) {
    case 'Pendiente': $estadoClase = 'bg-warning text-dark'; break;
    case 'Aceptada': $estadoClase = 'bg-success'; break;
    case 'Rechazada': $estadoClase = 'bg-danger'; break;
    case 'Completada': $estadoClase = 'bg-primary'; break;
}

$puedeResponder = !$esExterno && $referencia['estado'] === 'Pendiente' && $referencia['medico_referido_id'] == $userId;
$puedeCambiar = $esExterno && $referencia['estado'] !== 'Completada' && (
    $roleId == 1 || $roleId == 2 || $roleId == 3 || $referencia['created_by'] == $userId
);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title">Referencia <?php echo htmlspecialchars($referencia['codigo_referencia']); ?></h2>
        <p class="text-muted small mb-0">
            <span class="badge <?php echo $estadoClase; ?> me-2"><?php echo htmlspecialchars($referencia['estado']); ?></span>
            Creada el <?php echo date('d/m/Y', strtotime($referencia['fecha_referencia'])); ?>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo Url::to('/referencias'); ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <?php if ($puedeResponder): ?>
        <a href="<?php echo Url::to('/referencias/responder?id=' . $referencia['id']); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-reply"></i> Responder
        </a>
        <?php elseif ($puedeCambiar): ?>
        <a href="<?php echo Url::to('/referencias/cambiar-estado?id=' . $referencia['id']); ?>" class="btn btn-outline-warning">
            <i class="fa-solid fa-pen-to-square"></i> Cambiar Estado
        </a>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa-solid fa-user-injured"></i> Paciente</h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Nombre:</strong> <?php echo htmlspecialchars($referencia['paciente_nombre'] . ' ' . $referencia['paciente_apellido']); ?></p>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa-solid fa-user-doctor"></i> Medicos</h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Medico Solicitante:</strong> Dr(a). <?php echo htmlspecialchars($referencia['solicitante_nombre'] . ' ' . $referencia['solicitante_apellido']); ?></p>
                <?php if ($referencia['solicitante_especialidad']): ?>
                    <small class="text-muted"><?php echo htmlspecialchars($referencia['solicitante_especialidad']); ?></small>
                <?php endif; ?>
                <hr>
                <?php if ($esExterno): ?>
                    <p class="mb-1"><strong>Medico Referido:</strong> Dr(a). <?php echo htmlspecialchars($referencia['ref_ext_nombre'] . ' ' . $referencia['ref_ext_apellido']); ?> <small class="text-muted">(Externo)</small></p>
                    <?php if ($referencia['ref_ext_especialidad']): ?>
                        <small class="text-muted"><?php echo htmlspecialchars($referencia['ref_ext_especialidad']); ?></small>
                    <?php endif; ?>
                    <?php if ($referencia['ref_ext_institucion']): ?>
                        <br><small class="text-muted"><i class="fa-solid fa-hospital"></i> <?php echo htmlspecialchars($referencia['ref_ext_institucion']); ?></small>
                    <?php endif; ?>
                    <?php if ($referencia['ref_ext_email']): ?>
                        <br><small class="text-muted"><i class="fa-solid fa-envelope"></i> <?php echo htmlspecialchars($referencia['ref_ext_email']); ?></small>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="mb-1"><strong>Medico Referido:</strong> Dr(a). <?php echo htmlspecialchars($referencia['referido_nombre'] . ' ' . $referencia['referido_apellido']); ?></p>
                    <?php if ($referencia['referido_especialidad']): ?>
                        <small class="text-muted"><?php echo htmlspecialchars($referencia['referido_especialidad']); ?></small>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa-solid fa-file-medical-alt"></i> Detalles del Estudio</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tipo de Estudio</label>
                        <p><?php echo htmlspecialchars($referencia['tipo_estudio']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fecha de Referencia</label>
                        <p><?php echo date('d/m/Y', strtotime($referencia['fecha_referencia'])); ?></p>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Motivo de la Referencia</label>
                    <p><?php echo nl2br(htmlspecialchars($referencia['motivo_referencia'])); ?></p>
                </div>

                <?php if ($referencia['observaciones']): ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">Observaciones</label>
                    <p><?php echo nl2br(htmlspecialchars($referencia['observaciones'])); ?></p>
                </div>
                <?php endif; ?>

                <?php if ($referencia['informe_exploracion_id']): ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">Informe Vinculado</label>
                    <p>
                        <a href="<?php echo Url::to('/informes_exploracion/show?id=' . $referencia['informe_exploracion_id']); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-external-link"></i> Ver Informe #<?php echo $referencia['informe_exploracion_id']; ?>
                        </a>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ($referencia['estado'] !== 'Pendiente'): ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa-solid fa-reply"></i> Respuesta</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fecha de Respuesta</label>
                        <p><?php echo $referencia['fecha_respuesta'] ? date('d/m/Y', strtotime($referencia['fecha_respuesta'])) : '-'; ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Estado</label>
                        <p><span class="badge <?php echo $estadoClase; ?>"><?php echo htmlspecialchars($referencia['estado']); ?></span></p>
                    </div>
                </div>

                <?php if ($referencia['respuesta_motivo']): ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">Motivo</label>
                    <p><?php echo nl2br(htmlspecialchars($referencia['respuesta_motivo'])); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
