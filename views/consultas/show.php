<?php
$title = "Ver Consulta";
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
            <h1 class="page-title mb-0">Consulta</h1>
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

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-stethoscope me-2"></i> Datos de la Consulta
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Paciente</label>
                    <p class="form-control-plaintext fw-bold"><?php echo htmlspecialchars($consulta['paciente_nombre'] ?? ''); ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Fecha de Consulta</label>
                    <p class="form-control-plaintext"><?php echo $consulta['fecha_consulta'] ? date('d/m/Y H:i', strtotime($consulta['fecha_consulta'])) : '-'; ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Motivo de Consulta</label>
                    <p class="form-control-plaintext"><?php echo htmlspecialchars($consulta['motivo_consulta'] ?? '-'); ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Observaciones</label>
                    <p class="form-control-plaintext"><?php echo nl2br(htmlspecialchars($consulta['observaciones'] ?? '-')); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
