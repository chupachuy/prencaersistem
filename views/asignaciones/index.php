<?php 
$title = isset($mis_pacientes) && $mis_pacientes ? "Mis Pacientes Asignados" : "Asignaciones de Pacientes";
require_once __DIR__ . '/../layouts/header.php'; 
require_once __DIR__ . '/../layouts/sidebar.php'; 
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title"><?php echo htmlspecialchars($title); ?></h1>
            <p class="page-subtitle">Gestión de asignaciones médicas y pacientes</p>
        </div>
        <?php if (isset($roleId) && ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_ADMINISTRADOR || $roleId == Auth::ROLE_JEFE)): ?>
        <a href="<?php echo Url::to('/asignaciones/create'); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-user-plus"></i> Nueva Asignación
        </a>
        <?php endif; ?>
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
                        <?php if (!isset($mis_pacientes)): ?>
                        <th>Médico</th>
                        <?php endif; ?>
                        <th>Motivo</th>
                        <th class="text-center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($asignaciones)): ?>
                        <?php foreach ($asignaciones as $asignacion): ?>
                            <tr>
                                <td><span style="color: var(--apple-gray); font-weight: 500;">#<?php echo htmlspecialchars($asignacion['id']); ?></span></td>
                                <td style="color: var(--apple-gray); font-size: 13px;">
                                    <?php 
                                        $fecha = new DateTime($asignacion['fecha_asignacion'] ?? 'now');
                                        echo $fecha->format('d/m/Y'); 
                                    ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2" style="font-weight: 500;">
                                        <div style="width: 28px; height: 28px; background: #e2e3e5; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa-solid fa-user" style="font-size: 10px; color: #666;"></i>
                                        </div>
                                        <?php echo htmlspecialchars($asignacion['paciente'] ?? 'Paciente ' . ($asignacion['paciente_id'] ?? '')); ?>
                                    </div>
                                </td>
                                
                                <?php if (!isset($mis_pacientes)): ?>
                                <td style="color: var(--apple-gray);">
                                    <?php echo htmlspecialchars($asignacion['medico'] ?? 'N/A'); ?>
                                </td>
                                <?php endif; ?>

                                <td style="color: var(--apple-gray); font-size: 13px; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php echo htmlspecialchars($asignacion['motivo'] ?? 'Control rutinario'); ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success">Activo</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?php echo isset($mis_pacientes) ? '5' : '6'; ?>" class="text-center py-5" style="color: var(--apple-gray);">
                                <i class="fa-solid fa-clipboard-list fa-3x mb-3" style="opacity: 0.3;"></i>
                                <h5 style="font-weight: 600;">No hay pacientes asignados</h5>
                                <p class="mb-0">Actualmente no existen registros de asignaciones médicas.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
