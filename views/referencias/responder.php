<?php
$title = "Responder Referencia";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title">Responder Referencia</h2>
        <p class="text-muted small mb-0"><?php echo htmlspecialchars($referencia['codigo_referencia']); ?> - <span class="badge bg-warning text-dark">Pendiente</span></p>
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
                <p class="mb-1"><strong>Tipo de Estudio:</strong> <?php echo htmlspecialchars($referencia['tipo_estudio']); ?></p>
                <p class="mb-1"><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($referencia['fecha_referencia'])); ?></p>
                <hr>
                <p class="mb-1"><strong>Motivo:</strong></p>
                <p><?php echo nl2br(htmlspecialchars($referencia['motivo_referencia'])); ?></p>
                <?php if ($referencia['observaciones']): ?>
                    <p class="mb-1"><strong>Observaciones:</strong></p>
                    <p><?php echo nl2br(htmlspecialchars($referencia['observaciones'])); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa-solid fa-reply"></i> Su Respuesta</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo Url::to('/referencias/update-respuesta'); ?>" id="formRespuesta">
                    <input type="hidden" name="id" value="<?php echo $referencia['id']; ?>">
                    <input type="hidden" name="accion" id="inputAccion" value="">

                    <div class="mb-3" id="divMotivoRechazo" style="display: none;">
                        <label class="form-label">Motivo del Rechazo *</label>
                        <textarea name="respuesta_motivo" class="form-control" rows="4" placeholder="Indique el motivo por el cual rechaza esta referencia..."></textarea>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-success flex-fill" onclick="confirmarAccion('aceptar')">
                            <i class="fa-solid fa-check-circle"></i> Aceptar Referencia
                        </button>
                        <button type="button" class="btn btn-danger flex-fill" onclick="mostrarRechazo()" id="btnRechazar">
                            <i class="fa-solid fa-times-circle"></i> Rechazar Referencia
                        </button>
                        <button type="submit" class="btn btn-danger flex-fill" style="display: none;" id="btnConfirmarRechazo">
                            <i class="fa-solid fa-triangle-exclamation"></i> Confirmar Rechazo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarAccion(accion) {
    if (confirm('Esta seguro de ' + accion + ' esta referencia?')) {
        document.getElementById('inputAccion').value = accion;
        document.getElementById('formRespuesta').submit();
    }
}

function mostrarRechazo() {
    document.getElementById('divMotivoRechazo').style.display = 'block';
    document.getElementById('btnRechazar').style.display = 'none';
    document.getElementById('btnConfirmarRechazo').style.display = 'block';
    document.getElementById('inputAccion').value = 'rechazar';
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
