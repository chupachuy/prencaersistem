<?php
$title = "Catálogo de Consentimientos";
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/consentimientos'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0">Catálogo de Consentimientos</h1>
    </div>
    <div class="page-header-actions">
        <a href="<?php echo Url::to('/consentimientos/catalogo/create'); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-plus"></i> Nuevo Documento
        </a>
    </div>
</div>

<?php if (Session::get('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo Session::get('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php Session::remove('success'); ?>
<?php endif; ?>

<?php if (Session::get('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo Session::get('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php Session::remove('error'); ?>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Versión</th>
                        <th>Firma Médico</th>
                        <th>Testigos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($documentos)): ?>
                        <?php foreach ($documentos as $doc): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($doc['nombre_documento']); ?></strong></td>
                                <td><?php echo $doc['version'] ? htmlspecialchars($doc['version']) : '-'; ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $doc['requiere_firma_medico'] ? 'success' : 'secondary'; ?>">
                                        <?php echo $doc['requiere_firma_medico'] ? 'Sí' : 'No'; ?>
                                    </span>
                                </td>
                                <td><?php echo (int) $doc['cantidad_testigos']; ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo Url::to('/consentimientos/catalogo/edit?id=' . $doc['id']); ?>" class="btn btn-apple btn-apple-secondary" title="Editar">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <form method="POST" action="<?php echo Url::to('/consentimientos/catalogo/delete'); ?>" style="display:inline;" onsubmit="return confirm('¿Eliminar este documento?');">
                                            <input type="hidden" name="id" value="<?php echo $doc['id']; ?>">
                                            <button type="submit" class="btn btn-apple btn-apple-danger btn-sm" title="Eliminar">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay documentos en el catálogo</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
