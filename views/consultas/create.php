<?php
$title = "Nueva Consulta";
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
        <h1 class="page-title mb-0">Nueva Consulta</h1>
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
                <form action="<?php echo Url::to('/consultas/store'); ?>" method="POST">
                    <div class="mb-4">
                        <label for="id_paciente" class="form-label">Paciente <span style="color: #bf2b2b;">*</span></label>
                        <select class="form-select" id="id_paciente" name="id_paciente" required>
                            <option value="">Seleccionar paciente...</option>
                            <?php foreach ($pacientes as $paciente): ?>
                                <option value="<?php echo $paciente['id']; ?>">
                                    <?php echo htmlspecialchars($paciente['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="motivo_consulta" class="form-label">Motivo de Consulta</label>
                        <input type="text" class="form-control" id="motivo_consulta" name="motivo_consulta" placeholder="Ej. Control prenatal, Dolor abdominal, etc.">
                    </div>

                    <div class="mb-4">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3" placeholder="Observaciones iniciales..."></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo Url::to('/consultas'); ?>" class="btn btn-apple btn-apple-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-apple btn-apple-primary">
                            <i class="fa-solid fa-save"></i> Guardar y Continuar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
