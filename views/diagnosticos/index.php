<?php 
$title = isset($todos) && $todos ? "Todos los Diagnósticos" : "Mis Diagnósticos";
require_once __DIR__ . '/../layouts/header.php'; 
require_once __DIR__ . '/../layouts/sidebar.php'; 
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title"><?php echo htmlspecialchars($title); ?></h1>
            <p class="page-subtitle">Historial médico y diagnósticos registrados</p>
        </div>
        <a href="<?php echo Url::to('/diagnosticos/create'); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-plus"></i> Nuevo Diagnóstico
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Diagnóstico</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($diagnosticos)): ?>
                        <?php foreach ($diagnosticos as $diag): ?>
                            <tr>
                                <td><span style="color: var(--apple-gray); font-weight: 500;">#<?php echo htmlspecialchars($diag['id']); ?></span></td>
                                <td style="color: var(--apple-gray); font-size: 13px;">
                                    <?php 
                                        $fecha = new DateTime($diag['fecha'] ?? 'now');
                                        echo $fecha->format('d/m/Y H:i'); 
                                    ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2" style="font-weight: 500;">
                                        <div style="width: 28px; height: 28px; background: #e2e3e5; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa-solid fa-user" style="font-size: 10px; color: #666;"></i>
                                        </div>
                                        <?php echo htmlspecialchars($diag['paciente'] ?? 'No especificado'); ?>
                                    </div>
                                </td>
                                <td style="color: var(--apple-gray);">
                                    <?php echo htmlspecialchars($diag['medico'] ?? 'N/A'); ?>
                                </td>
                                <td>
                                    <span style="max-width: 180px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--apple-gray); font-size: 13px;" title="<?php echo htmlspecialchars($diag['descripcion'] ?? ''); ?>">
                                        <?php echo htmlspecialchars($diag['descripcion'] ?? 'Sin descripción'); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="<?php echo Url::to('/diagnosticos/show?id=' . urlencode($diag['id'])); ?>" class="action-btn action-btn-view" title="Ver">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>
                                        <a href="<?php echo Url::to('/diagnosticos/edit?id=' . urlencode($diag['id'])); ?>" class="action-btn action-btn-edit" title="Editar">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5" style="color: var(--apple-gray);">
                                <i class="fa-solid fa-notes-medical fa-3x mb-3" style="opacity: 0.3;"></i>
                                <h5 style="font-weight: 600;">No hay diagnósticos registrados</h5>
                                <p class="mb-0">Aún no se han creado registros médicos aquí.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
