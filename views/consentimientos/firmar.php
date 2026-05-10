<?php
$title = "Firmar Consentimiento";
$datosDinamicos = $consentimiento['datos_dinamicos'] ? json_decode($consentimiento['datos_dinamicos'], true) : [];
$firmasPorRol = [];
foreach ($firmas as $f) {
    $firmasPorRol[$f['rol_firmante']] = $f;
}
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/consentimientos'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0">Firmar: <?php echo htmlspecialchars($consentimiento['nombre_documento']); ?></h1>
    </div>
    <div class="page-header-actions">
        <?php
        $estadoClass = match($consentimiento['estado']) {
            'Completado' => 'success',
            'Parcialmente Firmado' => 'warning',
            'Revocado' => 'danger',
            default => 'info'
        };
        ?>
        <span class="badge bg-<?php echo $estadoClass; ?> me-2"><?php echo htmlspecialchars($consentimiento['estado']); ?></span>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-circle-info me-2"></i> Datos del Documento
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Documento:</strong> <?php echo htmlspecialchars($consentimiento['nombre_documento']); ?>
                    <?php if ($consentimiento['version']): ?>
                        <small class="text-muted">(<?php echo htmlspecialchars($consentimiento['version']); ?>)</small>
                    <?php endif; ?>
                </div>
                <div class="mb-2">
                    <strong>Paciente:</strong> <?php echo htmlspecialchars($consentimiento['paciente_nombre'] . ' ' . $consentimiento['paciente_apellido']); ?>
                </div>
                <div class="mb-2">
                    <strong>Médico:</strong> <?php echo htmlspecialchars($consentimiento['medico_nombre'] . ' ' . $consentimiento['medico_apellido']); ?>
                </div>
                <div class="mb-2">
                    <strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($consentimiento['fecha_generacion'])); ?>
                </div>

                <?php if ($datosDinamicos): ?>
                <hr>
                <div class="mb-2"><strong>Redes Sociales:</strong></div>
                <?php if (!empty($datosDinamicos['facebook'])): ?>
                    <div class="mb-1"><i class="fa-brands fa-facebook me-1"></i> <?php echo htmlspecialchars($datosDinamicos['facebook']); ?></div>
                <?php endif; ?>
                <?php if (!empty($datosDinamicos['instagram'])): ?>
                    <div class="mb-1"><i class="fa-brands fa-instagram me-1"></i> <?php echo htmlspecialchars($datosDinamicos['instagram']); ?></div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-check-circle me-2"></i> Firmas
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fa-solid <?php echo isset($firmasPorRol['Paciente']) ? 'fa-circle-check text-success' : 'fa-circle text-muted'; ?> me-1"></i>
                        Paciente
                        <?php if (isset($firmasPorRol['Paciente'])): ?>
                            <small class="text-success">— Firmado</small>
                        <?php endif; ?>
                    </li>

                    <?php if ($consentimiento['requiere_firma_medico']): ?>
                    <li class="mb-2">
                        <i class="fa-solid <?php echo isset($firmasPorRol['Medico']) ? 'fa-circle-check text-success' : 'fa-circle text-muted'; ?> me-1"></i>
                        Médico
                        <?php if (isset($firmasPorRol['Medico'])): ?>
                            <small class="text-success">— Firmado</small>
                        <?php endif; ?>
                    </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $consentimiento['cantidad_testigos']; $i++): ?>
                    <li class="mb-2">
                        <i class="fa-solid <?php echo isset($firmasPorRol['Testigo ' . $i]) ? 'fa-circle-check text-success' : 'fa-circle text-muted'; ?> me-1"></i>
                        Testigo <?php echo $i; ?>
                        <?php if (isset($firmasPorRol['Testigo ' . $i])): ?>
                            <small class="text-success">— Firmado por <?php echo htmlspecialchars($firmasPorRol['Testigo ' . $i]['nombre_firmante']); ?></small>
                        <?php endif; ?>
                    </li>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>

        <?php if ($consentimiento['estado'] === 'Completado'): ?>
        <div class="d-grid gap-2">
            <a href="<?php echo Url::to('/consentimientos/print?id=' . $consentimiento['id']); ?>" class="btn btn-apple btn-apple-primary" target="_blank">
                <i class="fa-solid fa-download"></i> Descargar PDF
            </a>
            <a href="<?php echo Url::to('/consentimientos/show?id=' . $consentimiento['id']); ?>" class="btn btn-apple btn-apple-secondary">
                <i class="fa-solid fa-eye"></i> Ver Completo
            </a>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-8">
        <?php if (!empty($consentimiento['contenido'])): ?>
        <div class="card mb-4 border-primary">
            <div class="card-header bg-primary bg-opacity-10 text-primary">
                <i class="fa-solid fa-file-lines me-2"></i> Contenido del Consentimiento
                <small class="text-muted float-end">Lea cuidadosamente antes de firmar</small>
            </div>
            <div class="card-body" style="max-height: 450px; overflow-y: auto; font-size: 14px; line-height: 1.7;">
                <?php echo $consentimiento['contenido']; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($consentimiento['estado'] !== 'Completado' && $consentimiento['estado'] !== 'Revocado'): ?>
        <form method="POST" action="<?php echo Url::to('/consentimientos/storeFirma'); ?>" id="formFirmas">
            <input type="hidden" name="asignacion_id" value="<?php echo $consentimiento['id']; ?>">

            <!-- Paciente -->
            <?php if (!isset($firmasPorRol['Paciente'])): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-regular fa-user me-2"></i> Firma del Paciente
                </div>
                <div class="card-body">
                    <input type="hidden" name="firmantes[]" value="Paciente">
                    <input type="hidden" name="acciones[]" value="Aceptacion">
                    <div class="mb-3">
                        <label class="form-label">Nombre completo *</label>
                        <input type="text" class="form-control" name="nombres[]" value="<?php echo htmlspecialchars($consentimiento['paciente_nombre'] . ' ' . $consentimiento['paciente_apellido']); ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Firma *</label>
                        <canvas class="signature-canvas border rounded" data-index="0" width="500" height="150" style="width:100%; cursor:crosshair;"></canvas>
                        <input type="hidden" name="firmas_data[]" class="signature-data" data-index="0">
                        <small class="text-muted">Firma aquí con el mouse o dedo (táctil)</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger clear-canvas mt-1" data-canvas-index="0">
                        <i class="fa-solid fa-eraser"></i> Limpiar
                    </button>
                </div>
            </div>
            <?php endif; ?>

            <!-- Evaluación Ginecológica: Denegación -->
            <?php if (strpos(strtolower($consentimiento['nombre_documento']), 'evaluación ginecológica') !== false): ?>
            <div class="card mb-4 border-danger" id="denegacionCard">
                <div class="card-header bg-danger text-white">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> Denegación del Procedimiento
                </div>
                <div class="card-body">
                    <input type="hidden" name="firmantes[]" value="Paciente">
                    <input type="hidden" name="acciones[]" value="Denegacion">
                    <div class="mb-3">
                        <label class="form-label">Nombre del paciente *</label>
                        <input type="text" class="form-control" name="nombres[]" placeholder="Nombre de quien rechaza" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Firma de Denegación *</label>
                        <canvas class="signature-canvas border border-danger rounded" data-index="1" width="500" height="150" style="width:100%; cursor:crosshair;"></canvas>
                        <input type="hidden" name="firmas_data[]" class="signature-data" data-index="1">
                        <small class="text-danger">Al firmar aquí, rechaza el procedimiento de evaluación ginecológica.</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger clear-canvas mt-1" data-canvas-index="1">
                        <i class="fa-solid fa-eraser"></i> Limpiar
                    </button>
                </div>
            </div>
            <?php endif; ?>

            <!-- Médico -->
            <?php if ($consentimiento['requiere_firma_medico'] && !isset($firmasPorRol['Medico'])): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-user-doctor me-2"></i> Firma del Médico
                </div>
                <div class="card-body">
                    <input type="hidden" name="firmantes[]" value="Medico">
                    <input type="hidden" name="acciones[]" value="Aceptacion">
                    <div class="mb-3">
                        <label class="form-label">Nombre completo *</label>
                        <input type="text" class="form-control" name="nombres[]" value="<?php echo htmlspecialchars($consentimiento['medico_nombre'] . ' ' . $consentimiento['medico_apellido']); ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Firma *</label>
                        <canvas class="signature-canvas border rounded" data-index="99" width="500" height="150" style="width:100%; cursor:crosshair;"></canvas>
                        <input type="hidden" name="firmas_data[]" class="signature-data" data-index="99">
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger clear-canvas mt-1" data-canvas-index="99">
                        <i class="fa-solid fa-eraser"></i> Limpiar
                    </button>
                </div>
            </div>
            <?php endif; ?>

            <!-- Testigos -->
            <?php for ($i = 1; $i <= $consentimiento['cantidad_testigos']; $i++): ?>
            <?php if (!isset($firmasPorRol['Testigo ' . $i])): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-user-group me-2"></i> Testigo <?php echo $i; ?>
                </div>
                <div class="card-body">
                    <input type="hidden" name="firmantes[]" value="Testigo <?php echo $i; ?>">
                    <input type="hidden" name="acciones[]" value="Aceptacion">
                    <div class="mb-3">
                        <label class="form-label">Nombre completo del testigo *</label>
                        <input type="text" class="form-control" name="nombres[]" placeholder="Nombre y apellido del testigo" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Firma del Testigo *</label>
                        <canvas class="signature-canvas border rounded" data-index="<?php echo 100 + $i; ?>" width="500" height="150" style="width:100%; cursor:crosshair;"></canvas>
                        <input type="hidden" name="firmas_data[]" class="signature-data" data-index="<?php echo 100 + $i; ?>">
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger clear-canvas mt-1" data-canvas-index="<?php echo 100 + $i; ?>">
                        <i class="fa-solid fa-eraser"></i> Limpiar
                    </button>
                </div>
            </div>
            <?php endif; ?>
            <?php endfor; ?>

            <div class="d-flex justify-content-end gap-2 mt-4 mb-4">
                <a href="<?php echo Url::to('/consentimientos'); ?>" class="btn btn-apple btn-apple-secondary">Cancelar</a>
                <button type="submit" class="btn btn-apple btn-apple-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Firmas
                </button>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>

<script>
(function() {
    var canvases = document.querySelectorAll('.signature-canvas');

    canvases.forEach(function(canvas) {
        var ctx = canvas.getContext('2d');
        var drawing = false;
        var dataInput = document.querySelector('.signature-data[data-index="' + canvas.dataset.index + '"]');

        function getPos(e) {
            var rect = canvas.getBoundingClientRect();
            var scaleX = canvas.width / rect.width;
            var scaleY = canvas.height / rect.height;
            var clientX, clientY;
            if (e.touches) {
                clientX = e.touches[0].clientX;
                clientY = e.touches[0].clientY;
            } else {
                clientX = e.clientX;
                clientY = e.clientY;
            }
            return {
                x: (clientX - rect.left) * scaleX,
                y: (clientY - rect.top) * scaleY
            };
        }

        function startDraw(e) {
            e.preventDefault();
            drawing = true;
            ctx.beginPath();
            var pos = getPos(e);
            ctx.moveTo(pos.x, pos.y);
        }

        function draw(e) {
            if (!drawing) return;
            e.preventDefault();
            var pos = getPos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.strokeStyle = '#000';
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.stroke();
        }

        function stopDraw(e) {
            if (!drawing) return;
            drawing = false;
            ctx.closePath();
            if (dataInput) {
                dataInput.value = canvas.toDataURL('image/png');
            }
        }

        canvas.addEventListener('mousedown', startDraw);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDraw);
        canvas.addEventListener('mouseleave', stopDraw);
        canvas.addEventListener('touchstart', startDraw);
        canvas.addEventListener('touchmove', draw);
        canvas.addEventListener('touchend', stopDraw);
    });

    document.querySelectorAll('.clear-canvas').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var idx = this.dataset.canvasIndex;
            var canvas = document.querySelector('.signature-canvas[data-index="' + idx + '"]');
            var dataInput = document.querySelector('.signature-data[data-index="' + idx + '"]');
            if (canvas) {
                var ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }
            if (dataInput) {
                dataInput.value = '';
            }
        });
    });
})();
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
