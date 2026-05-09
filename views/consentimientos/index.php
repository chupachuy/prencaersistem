<?php
$title = "Consentimientos";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h1 class="page-title mb-0">Consentimientos</h1>
    </div>
    <div class="page-header-actions">
        <a href="<?php echo Url::to('/consentimientos/create'); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-plus"></i> Nuevo Consentimiento
        </a>
        <a href="<?php echo Url::to('/consentimientos/catalogo'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-book"></i> Catálogo
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
                        <th>Documento</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($consentimientos)): ?>
                        <?php foreach ($consentimientos as $c): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($c['nombre_documento']); ?></strong>
                                    <?php if ($c['version']): ?>
                                        <small class="text-muted d-block"><?php echo htmlspecialchars($c['version']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($c['paciente_nombre'] . ' ' . $c['paciente_apellido']); ?></td>
                                <td><?php echo htmlspecialchars($c['medico_nombre'] . ' ' . $c['medico_apellido']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($c['fecha_generacion'])); ?></td>
                                <td>
                                    <?php
                                    $estadoClass = match($c['estado']) {
                                        'Completado' => 'success',
                                        'Parcialmente Firmado' => 'warning',
                                        'Revocado' => 'danger',
                                        default => 'info'
                                    };
                                    ?>
                                    <span class="badge bg-<?php echo $estadoClass; ?>"><?php echo htmlspecialchars($c['estado']); ?></span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo Url::to('/consentimientos/show?id=' . $c['id']); ?>" class="btn btn-apple btn-apple-secondary" title="Ver">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <?php if ($c['estado'] !== 'Completado' && $c['estado'] !== 'Revocado'): ?>
                                        <a href="<?php echo Url::to('/consentimientos/firmar?id=' . $c['id']); ?>" class="btn btn-apple btn-apple-secondary" title="Firmar">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <?php endif; ?>
                                        <a href="<?php echo Url::to('/consentimientos/print?id=' . $c['id']); ?>" class="btn btn-apple btn-apple-secondary" target="_blank" title="PDF">
                                            <i class="fa-solid fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay consentimientos registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
