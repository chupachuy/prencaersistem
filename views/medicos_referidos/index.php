<?php
$title = "Medicos Referidos";
$meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
$fecha_hoy = date('j') . ' de ' . $meses[date('n')] . ' del ' . date('Y');
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$erroresImport = Session::getFlash('import_errores');
?>

<?php if ($erroresImport): ?>
<div class="alert" style="background: #fff3cd; color: #856404; border-radius: 12px; margin-bottom: 16px;" role="alert">
    <strong><i class="fa-solid fa-triangle-exclamation me-2"></i> Errores durante la importacion:</strong>
    <ul class="mb-0 mt-1" style="font-size: 13px;">
        <?php foreach ($erroresImport as $err): ?>
            <li><?php echo htmlspecialchars($err); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h1 class="page-title mb-0">Medicos Referidos</h1>
    </div>
    <div class="page-header-actions">
        <span class="text-muted me-3">
            <i class="fa-regular fa-calendar me-1"></i>
            <?php echo $fecha_hoy; ?>
        </span>
        <a href="<?php echo Url::to('/medicos-referidos/create'); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-plus"></i> Nuevo Medico
        </a>
        <a href="<?php echo Url::to('/medicos-referidos/importar'); ?>" class="btn btn-outline-primary">
            <i class="fa-solid fa-file-csv"></i> Importar CSV
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($medicos)): ?>
                    <div class="text-center py-5">
                        <i class="fa-solid fa-user-doctor fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay medicos externos registrados.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Especialidad</th>
                                    <th>Institucion</th>
                                    <th>Telefono</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($medicos as $medico): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 14px;">
                                                    <?php echo strtoupper(substr($medico['nombre'], 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars('Dr(a). ' . $medico['nombre'] . ' ' . $medico['apellido']); ?></strong>
                                                    <br><small class="text-muted">Externo</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($medico['email']); ?></td>
                                        <td><?php echo htmlspecialchars($medico['especialidad'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($medico['institucion'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($medico['telefono'] ?? '-'); ?></td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="<?php echo Url::to('/medicos-referidos/edit?id=' . $medico['id']); ?>" class="btn btn-sm btn-apple btn-apple-secondary" title="Editar">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form method="POST" action="<?php echo Url::to('/medicos-referidos/delete'); ?>" style="display:inline;" onsubmit="return confirm('Esta seguro de eliminar este medico?');">
                                                    <input type="hidden" name="id" value="<?php echo $medico['id']; ?>">
                                                    <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
