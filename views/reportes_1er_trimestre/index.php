<?php
$title = "Reportes 1er Trimestre";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h1 class="page-title mb-0">Reportes 1er Trimestre</h1>
    </div>
    <div class="page-header-actions">
        <a href="<?php echo Url::to('/reportes_1er_trimestre/create'); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-plus"></i> Nuevo Reporte
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
            <table class="table table-hover" id="tableReportes">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reportes)): ?>
                        <?php foreach ($reportes as $reporte): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reporte['codigo_reporte']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($reporte['fecha_reporte'])); ?></td>
                                <td><?php echo htmlspecialchars($reporte['paciente_nombre'] . ' ' . $reporte['paciente_apellido']); ?></td>
                                <td><?php echo htmlspecialchars($reporte['medico_nombre'] . ' ' . $reporte['medico_apellido']); ?></td>
                                <td>
                                    <?php 
                                    $estadoClass = match($reporte['estado']) {
                                        'Completado' => 'success',
                                        'En proceso' => 'warning',
                                        'Archivado' => 'secondary',
                                        default => 'info'
                                    };
                                    ?>
                                    <span class="badge bg-<?php echo $estadoClass; ?>"><?php echo htmlspecialchars($reporte['estado']); ?></span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo Url::to('/reportes_1er_trimestre/show?id=' . $reporte['id']); ?>" class="btn btn-apple btn-apple-secondary" title="Ver">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="<?php echo Url::to('/reportes_1er_trimestre/edit?id=' . $reporte['id']); ?>" class="btn btn-apple btn-apple-secondary" title="Editar">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <a href="<?php echo Url::to('/reportes_1er_trimestre/print?id=' . $reporte['id']); ?>" class="btn btn-apple btn-apple-secondary" target="_blank" title="Imprimir">
                                            <i class="fa-solid fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay reportes registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
