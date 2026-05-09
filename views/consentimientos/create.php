<?php
$title = "Nuevo Consentimiento";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/consentimientos'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0">Nuevo Consentimiento</h1>
    </div>
</div>

<form action="<?php echo Url::to('/consentimientos/store'); ?>" method="POST">
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-file-signature me-2"></i> Datos del Consentimiento
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="documento_id" class="form-label">Tipo de Documento *</label>
                        <select class="form-select" id="documento_id" name="documento_id" required>
                            <option value="">Seleccionar tipo de documento...</option>
                            <?php foreach ($documentos as $doc): ?>
                                <option value="<?php echo $doc['id']; ?>"
                                    data-testigos="<?php echo $doc['cantidad_testigos']; ?>"
                                    data-firma-medico="<?php echo $doc['requiere_firma_medico']; ?>">
                                    <?php echo htmlspecialchars($doc['nombre_documento']); ?>
                                    <?php if ($doc['version']): ?>
                                        (<?php echo htmlspecialchars($doc['version']); ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted" id="docInfo"></small>
                    </div>

                    <div class="mb-3">
                        <label for="paciente_id" class="form-label">Paciente *</label>
                        <select class="form-select" id="paciente_id" name="paciente_id" required>
                            <option value="">Seleccionar paciente...</option>
                            <?php foreach ($pacientes as $paciente): ?>
                                <option value="<?php echo $paciente['id']; ?>" <?php echo (isset($paciente_id) && $paciente_id == $paciente['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellido']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="medico_id" class="form-label">Médico *</label>
                        <select class="form-select" id="medico_id" name="medico_id" required>
                            <option value="">Seleccionar médico...</option>
                            <?php foreach ($medicos as $medico): ?>
                                <option value="<?php echo $medico['id']; ?>">
                                    <?php echo htmlspecialchars($medico['nombre'] . ' ' . $medico['apellido']); ?>
                                    <?php if ($medico['especialidad']): ?>
                                        - <?php echo htmlspecialchars($medico['especialidad']); ?>
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card mb-4" id="redesSocialesCard" style="display:none;">
                <div class="card-header">
                    <i class="fa-solid fa-hashtag me-2"></i> Datos de Redes Sociales (Aviso de Privacidad)
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="datos_facebook" class="form-label">Facebook</label>
                        <input type="text" class="form-control" id="datos_facebook" placeholder="@usuario o URL">
                    </div>
                    <div class="mb-3">
                        <label for="datos_instagram" class="form-label">Instagram</label>
                        <input type="text" class="form-control" id="datos_instagram" placeholder="@usuario o URL">
                    </div>
                    <input type="hidden" name="datos_dinamicos" id="datosDinamicos">
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-circle-info me-2"></i> Resumen
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Firmas requeridas:</strong>
                        <ul class="mt-2" id="resumenFirmas">
                            <li><i class="fa-regular fa-user me-1"></i> Paciente (obligatorio)</li>
                            <li class="medico-line"><i class="fa-solid fa-user-doctor me-1"></i> Médico</li>
                            <li class="testigos-line" id="testigosCount" style="display:none;"><i class="fa-solid fa-users me-1"></i> <span id="testigosNum">0</span> testigo(s)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo Url::to('/consentimientos'); ?>" class="btn btn-apple btn-apple-secondary">Cancelar</a>
                <button type="submit" class="btn btn-apple btn-apple-primary">
                    <i class="fa-solid fa-arrow-right"></i> Continuar a Firma
                </button>
            </div>
        </div>
    </div>
</form>

<script>
document.getElementById('documento_id').addEventListener('change', function() {
    var opt = this.options[this.selectedIndex];
    var testigos = parseInt(opt.getAttribute('data-testigos')) || 0;
    var firmaMedico = opt.getAttribute('data-firma-medico') === '1';
    var docName = opt.text.trim();

    document.getElementById('docInfo').textContent = 
        'Testigos requeridos: ' + testigos + ' | Firma médico: ' + (firmaMedico ? 'Sí' : 'No');

    // Resumen
    document.getElementById('testigosNum').textContent = testigos;
    document.getElementById('testigosCount').style.display = testigos > 0 ? '' : 'none';
    document.querySelector('.medico-line').style.display = firmaMedico ? '' : 'none';

    // Aviso de Privacidad
    var isAviso = docName.toLowerCase().indexOf('aviso de privacidad') >= 0;
    document.getElementById('redesSocialesCard').style.display = isAviso ? '' : 'none';
});

document.querySelector('form').addEventListener('submit', function() {
    var fb = document.getElementById('datos_facebook').value.trim();
    var ig = document.getElementById('datos_instagram').value.trim();
    if (fb || ig) {
        document.getElementById('datosDinamicos').value = JSON.stringify({facebook: fb, instagram: ig});
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
