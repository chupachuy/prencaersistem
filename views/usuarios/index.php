<?php
$title = "Gestión de Usuarios";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Personal y Usuarios</h1>
            <p class="page-subtitle">Administra los usuarios del sistema</p>
        </div>
        <a href="<?php echo Url::to('/usuarios/create'); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-plus"></i> Nuevo Usuario
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Especialidad</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($medicos)): ?>
                        <?php foreach ($medicos as $userObj): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width: 35px; height: 35px; background: var(--apple-blue); color: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600;">
                                            <?php echo strtoupper(substr($userObj['nombre'] ?? 'U', 0, 1) . substr($userObj['apellido'] ?? '', 0, 1)); ?>
                                        </div>
                                        <span style="font-weight: 500;"><?php echo htmlspecialchars($userObj['nombre'] . ' ' . $userObj['apellido']); ?></span>
                                    </div>
                                </td>
                                <td style="color: var(--apple-gray);"><?php echo htmlspecialchars($userObj['email']); ?></td>
                                <td>
                                    <span class="badge" style="background: #d1e7dd; color: #367d84;"><?php echo htmlspecialchars($userObj['rol_nombre'] ?? 'N/A'); ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($userObj['especialidad'])): ?>
                                        <span class="badge" style="background: #e2e3e5; color: #41464b;"><?php echo htmlspecialchars($userObj['especialidad']); ?></span>
                                    <?php else: ?>
                                        <span style="color: var(--apple-gray); font-size: 13px;">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td style="color: var(--apple-gray); font-size: 13px;">
                                    <?php echo isset($userObj['created_at']) ? date('d/m/Y', strtotime($userObj['created_at'])) : 'N/A'; ?>
                                </td>
                                <td>
                                    <?php if ($userObj['activo'] == 1): ?>
                                        <span class="badge badge-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo Url::to('/usuarios/edit?id=' . $userObj['id']); ?>" class="action-btn action-btn-edit" title="Editar"><i class="fa-solid fa-pen-to-square"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4" style="color: var(--apple-gray);">
                                <i class="fa-solid fa-users fa-2x mb-2" style="opacity: 0.3;"></i>
                                <p class="mb-0">No se encontraron usuarios.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>