<?php
$title = "Cambiar Estado de Referencia";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$esExterno = !empty($referencia['medico_referido_externo_id']);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title">Cambiar Estado</h2>
        <p class="text-muted small mb-0"><?php echo htmlspecialchars($referencia['codigo_referencia']); ?> - 
            <span class="badge bg-<?php echo $referencia['estado'] === 'Pendiente' ? 'warning text-dark' : ($referencia['estado'] === 'Aceptada' ? 'success' : 'danger'); ?>">
                <?php echo htmlspecialchars($referencia['estado']); ?>
            </span>
        </p>
    </div>
    <a href="<?php echo Url::to('/referencias/show?id=' . $referencia['id']); ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa-solid fa-info-circle"></i> Informacion de la Referencia</h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Paciente:</strong> <?php echo htmlspecialchars($referencia['paciente_nombre'] . ' ' . $referencia['paciente_apellido']); ?></p>
                <p class="mb-1"><strong>Medico Solicitante:</strong> Dr(a). <?php echo htmlspecialchars($referencia['solicitante_nombre'] . ' ' . $referencia['solicitante_apellido']); ?></p>
                <?php if ($esExterno): ?>
                    <p class="mb-1"><strong>Medico Referido:</strong> Dr(a). <?php echo htmlspecialchars($referencia['ref_ext_nombre'] . ' ' . $referencia['ref_ext_apellido']); ?> <span class="badge bg-secondary">Externo</span></p>
                    <?php if ($referencia['ref_ext_institucion']): ?>
                        <small class="text-muted"><i class="fa-solid fa-hospital"></i> <?php echo htmlspecialchars($referencia['ref_ext_institucion']); ?></small>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="mb-1"><strong>Medico Referido:</strong> Dr(a). <?php echo htmlspecialchars($referencia['referido_nombre'] . ' ' . $referencia['referido_apellido']); ?></p>
                <?php endif; ?>
                <p class="mb-1 mt-2"><strong>Tipo de Estudio:</strong> <?php echo htmlspecialchars($referencia['tipo_estudio']); ?></p>
                <p class="mb-1"><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($referencia['fecha_referencia'])); ?></p>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa-solid fa-pen-to-square"></i> Cambiar Estado</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo Url::to('/referencias/update-estado'); ?>" id="formEstado">
                    <input type="hidden" name="id" value="<?php echo $referencia['id']; ?>">
                    <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Nuevo Estado *</label>
                        <select name="nuevo_estado" id="selectEstado" class="form-select" required onchange="toggleMotivo()">
                            <option value="">Seleccionar...</option>
                            <?php if ($referencia['estado'] === 'Pendiente'): ?>
                                <option value="Aceptada">Aceptada</option>
                                <option value="Rechazada">Rechazada</option>
                            <?php endif; ?>
                            <?php if ($referencia['estado'] === 'Pendiente' || $referencia['estado'] === 'Aceptada'): ?>
                                <option value="Completada">Completada</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-3" id="divMotivoRechazo" style="display: none;">
                        <label class="form-label">Motivo del Rechazo *</label>
                        <textarea name="respuesta_motivo" class="form-control" rows="3" placeholder="Indique el motivo del rechazo..."></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-apple btn-apple-primary">
                            <i class="fa-solid fa-save"></i> Guardar Cambio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleMotivo() {
    var estado = document.getElementById('selectEstado').value;
    document.getElementById('divMotivoRechazo').style.display = (estado === 'Rechazada') ? 'block' : 'none';
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
