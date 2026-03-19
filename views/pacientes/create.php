<?php
$title = "Nuevo Paciente";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/pacientes'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0">Nuevo Paciente</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-user me-2"></i> Datos Personales
            </div>
            <div class="card-body">
                <form action="<?php echo Url::to('/pacientes/store'); ?>" method="POST">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre <span style="color: #bf2b2b;">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Ej. Juan">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label">Apellido <span style="color: #bf2b2b;">*</span></label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required placeholder="Ej. Pérez">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span style="color: #bf2b2b;">*</span></label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo Url::to('/pacientes'); ?>" class="btn btn-apple btn-apple-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-apple btn-apple-primary"><i class="fa-solid fa-save"></i> Guardar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
