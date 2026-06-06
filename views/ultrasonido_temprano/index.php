<?php
$title = "Ultrasonido Obstétrico Temprano";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h1 class="page-title mb-0">Ultrasonido Obstétrico Temprano</h1>
    </div>
    <div class="page-header-actions">
        <a href="<?php echo Url::to('/ultrasonido_temprano/create'); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-plus"></i> Nuevo Ultrasonido
        </a>
    </div>
</div>

<?php if ($flash = Session::getFlash('success')): ?>
    <div class="alert" style="background: #d1e7dd; color: #367d84; border-radius: 12px;" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i> <?php echo htmlspecialchars($flash); ?>
    </div>
<?php endif; ?>
<?php if ($flash = Session::getFlash('error')): ?>
    <div class="alert" style="background: #ffebe6; color: #bf2b2b; border-radius: 12px;" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo htmlspecialchars($flash); ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>EG</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($evaluaciones)): ?>
                        <?php foreach ($evaluaciones as $ev): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($ev['codigo_reporte']); ?></strong></td>
                                <td><?php echo date('d/m/Y', strtotime($ev['fecha_estudio'])); ?></td>
                                <td><?php echo htmlspecialchars($ev['paciente_nombre'] . ' ' . $ev['paciente_apellido']); ?></td>
                                <td><?php echo htmlspecialchars($ev['medico_nombre'] . ' ' . $ev['medico_apellido']); ?></td>
                                <td>
                                    <?php if ($ev['edad_gest_semanas'] !== null): ?>
                                        <?php echo $ev['edad_gest_semanas']; ?>s <?php echo $ev['edad_gest_dias'] ?? 0; ?>d
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $estadoClass = match($ev['estado']) {
                                        'Completado' => 'success',
                                        'En proceso' => 'warning',
                                        'Archivado' => 'secondary',
                                        default => 'info'
                                    };
                                    ?>
                                    <span class="badge bg-<?php echo $estadoClass; ?>"><?php echo htmlspecialchars($ev['estado']); ?></span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo Url::to('/ultrasonido_temprano/show?id=' . $ev['id']); ?>" class="action-btn action-btn-view" title="Ver">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="<?php echo Url::to('/ultrasonido_temprano/edit?id=' . $ev['id']); ?>" class="action-btn action-btn-edit" title="Editar">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <a href="<?php echo Url::to('/ultrasonido_temprano/print?id=' . $ev['id']); ?>" class="action-btn action-btn-view" target="_blank" title="Imprimir">
                                            <i class="fa-solid fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay ultrasonidos registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
