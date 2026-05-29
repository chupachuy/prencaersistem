<?php
$title = "Editar Mi Perfil";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/perfil'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0">Editar Mi Perfil</h1>
    </div>
</div>

<form action="<?php echo Url::to('/perfil/update'); ?>" method="POST" id="formPerfil">
    <div class="card mb-4">
        <div class="card-header"><i class="fa-solid fa-user me-2"></i> Datos Personales</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre *</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="apellido" class="form-label">Apellido *</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($user['apellido'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($user['telefono'] ?? ''); ?>">
                </div>
                <?php if (!empty($user['especialidad'])): ?>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Especialidad</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['especialidad']); ?>" readonly>
                    <small class="text-muted">Gestionado por el administrador</small>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><i class="fa-solid fa-signature me-2"></i> Firma Digital</div>
        <div class="card-body">
            <div class="mb-3">
                <canvas id="firmaCanvas" width="500" height="150" style="width:100%; max-width:500px; cursor:crosshair; border:1px solid #dee2e6; border-radius:0.375rem;"></canvas>
                <input type="hidden" name="firma_data" id="firmaData">
                <input type="hidden" name="accion_firma" id="accionFirma" value="">
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-danger btn-sm" id="btnLimpiarFirma">
                    <i class="fa-solid fa-eraser"></i> Limpiar firma
                </button>
            </div>
            <?php if (!empty($user['ruta_firma'])): ?>
            <div class="mt-3">
                <small class="text-success"><i class="fa-solid fa-circle-check"></i> Firma registrada</small>
            </div>
            <?php else: ?>
            <div class="mt-3">
                <small class="text-muted">Aún no has registrado tu firma digital</small>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="<?php echo Url::to('/perfil'); ?>" class="btn btn-apple btn-apple-secondary">Cancelar</a>
        <button type="submit" class="btn btn-apple btn-apple-primary btn-lg">
            <i class="fa-solid fa-save"></i> Guardar Cambios
        </button>
    </div>
</form>

<script>
(function() {
    var canvas = document.getElementById('firmaCanvas');
    var ctx = canvas.getContext('2d');
    var drawing = false;
    var hasDrawn = false;
    var dataInput = document.getElementById('firmaData');
    var accionInput = document.getElementById('accionFirma');

    ctx.strokeStyle = '#000';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';

    <?php if (!empty($user['ruta_firma'])): ?>
    var firmaActual = '<?php echo Url::base() . $user['ruta_firma']; ?>';
    var img = new Image();
    img.onload = function() {
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
    };
    img.src = firmaActual;
    <?php endif; ?>

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
        hasDrawn = true;
        ctx.beginPath();
        var pos = getPos(e);
        ctx.moveTo(pos.x, pos.y);
    }

    function draw(e) {
        if (!drawing) return;
        e.preventDefault();
        var pos = getPos(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
    }

    function stopDraw() {
        if (!drawing) return;
        drawing = false;
        ctx.closePath();
        dataInput.value = canvas.toDataURL('image/png');
    }

    canvas.addEventListener('mousedown', startDraw);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDraw);
    canvas.addEventListener('mouseleave', stopDraw);
    canvas.addEventListener('touchstart', startDraw);
    canvas.addEventListener('touchmove', draw);
    canvas.addEventListener('touchend', stopDraw);

    document.getElementById('btnLimpiarFirma').addEventListener('click', function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasDrawn = false;
        dataInput.value = '';
        accionInput.value = 'limpiar';
    });

    document.getElementById('formPerfil').addEventListener('submit', function() {
        if (!hasDrawn && !accionInput.value) {
            <?php if (!empty($user['ruta_firma'])): ?>
            dataInput.value = canvas.toDataURL('image/png');
            <?php endif; ?>
        }
    });
})();
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
