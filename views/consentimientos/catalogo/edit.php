<?php
$title = "Editar Documento — Catálogo";
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/consentimientos/catalogo'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0">Editar: <?php echo htmlspecialchars($documento['nombre_documento']); ?></h1>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-file-medical me-2"></i> Datos del Documento
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo Url::to('/consentimientos/catalogo/update'); ?>">
                    <input type="hidden" name="id" value="<?php echo $documento['id']; ?>">
                    <div class="mb-3">
                        <label for="nombre_documento" class="form-label">Nombre del Documento *</label>
                        <input type="text" class="form-control" id="nombre_documento" name="nombre_documento" required value="<?php echo htmlspecialchars($documento['nombre_documento']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="version" class="form-label">Versión</label>
                        <input type="text" class="form-control" id="version" name="version" value="<?php echo htmlspecialchars($documento['version'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="requiere_firma_medico" name="requiere_firma_medico" <?php echo $documento['requiere_firma_medico'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="requiere_firma_medico">Requiere firma del médico</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad_testigos" class="form-label">Cantidad de Testigos</label>
                        <input type="number" class="form-control" id="cantidad_testigos" name="cantidad_testigos" value="<?php echo (int) $documento['cantidad_testigos']; ?>" min="0" max="5">
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo Url::to('/consentimientos/catalogo'); ?>" class="btn btn-apple btn-apple-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-apple btn-apple-primary">
                            <i class="fa-solid fa-save"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
