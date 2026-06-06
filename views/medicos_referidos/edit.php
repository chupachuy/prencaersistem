<?php
$title = "Editar Medico Referido";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><?php echo htmlspecialchars($title); ?></h2>
        <p class="text-muted small mb-0">Dr(a). <?php echo htmlspecialchars($medico['nombre'] . ' ' . $medico['apellido']); ?></p>
    </div>
    <a href="<?php echo Url::to('/medicos-referidos'); ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?php echo Url::to('/medicos-referidos/update'); ?>">
            <input type="hidden" name="id" value="<?php echo $medico['id']; ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($medico['nombre']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Apellido *</label>
                    <input type="text" name="apellido" class="form-control" value="<?php echo htmlspecialchars($medico['apellido']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($medico['email']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Telefono</label>
                    <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($medico['telefono'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Especialidad</label>
                    <input type="text" name="especialidad" class="form-control" value="<?php echo htmlspecialchars($medico['especialidad'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Institucion</label>
                    <input type="text" name="institucion" class="form-control" value="<?php echo htmlspecialchars($medico['institucion'] ?? ''); ?>">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="<?php echo Url::to('/medicos-referidos'); ?>" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-apple btn-apple-primary">
                    <i class="fa-solid fa-save"></i> Actualizar Medico
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
