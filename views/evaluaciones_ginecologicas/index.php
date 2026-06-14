<?php
$title = "Ultrasonido Ginecológico Endovaginal";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
$roleId = Session::get('user_role_id');
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h1 class="page-title mb-0">Ultrasonido Ginecológico Endovaginal</h1>
    </div>
    <?php if ($roleId == Auth::ROLE_MEDICO || $roleId == Auth::ROLE_JEFE || $roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_ADMINISTRADOR): ?>
    <div class="page-header-actions">
        <a href="<?php echo Url::to('/evaluaciones_ginecologicas/create'); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-plus"></i> Nuevo USG Ginecológico
        </a>
    </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Médico Realiza</th>
                        <th>Médico Solicita</th>
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
                                <td><?php echo $ev['medico_solicitante_nombre'] ? htmlspecialchars($ev['medico_solicitante_nombre'] . ' ' . $ev['medico_solicitante_apellido']) : '—'; ?></td>
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
                                        <a href="<?php echo Url::to('/evaluaciones_ginecologicas/show?id=' . $ev['id']); ?>" class="action-btn action-btn-view" title="Ver">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="<?php echo Url::to('/evaluaciones_ginecologicas/edit?id=' . $ev['id']); ?>" class="action-btn action-btn-edit" title="Editar">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <a href="<?php echo Url::to('/evaluaciones_ginecologicas/pdf?id=' . $ev['id']); ?>" class="action-btn action-btn-view" target="_blank" title="Descargar PDF">
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                        <form method="POST" action="<?php echo Url::to('/evaluaciones_ginecologicas/delete'); ?>" style="display:inline" onsubmit="return confirm('¿Eliminar esta evaluación?');">
                                            <input type="hidden" name="id" value="<?php echo $ev['id']; ?>">
                                            <button type="submit" class="action-btn action-btn-delete" title="Eliminar">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay ultrasonidos ginecológicos registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
