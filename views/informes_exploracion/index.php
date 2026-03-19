<?php 
$title = "Informes de Exploración";
require_once __DIR__ . '/../layouts/header.php'; 
require_once __DIR__ . '/../layouts/sidebar.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><?php echo htmlspecialchars($title); ?></h2>
        <p class="text-muted small mb-0">Informes de exploración estructural - 3 por trimestre</p>
    </div>
    <a href="<?php echo Url::to('/informes_exploracion/create'); ?>" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="fa-solid fa-plus"></i> Nuevo Informe
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo Url::to('/informes_exploracion'); ?>" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Paciente</label>
                <input type="number" name="paciente_id" class="form-control" placeholder="ID Paciente" value="<?php echo htmlspecialchars($_GET['paciente_id'] ?? ''); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Trimestre</label>
                <select name="trimestre" class="form-select">
                    <option value="">Todos</option>
                    <option value="1" <?php echo (isset($_GET['trimestre']) && $_GET['trimestre'] == '1') ? 'selected' : ''; ?>>Trimestre 1</option>
                    <option value="2" <?php echo (isset($_GET['trimestre']) && $_GET['trimestre'] == '2') ? 'selected' : ''; ?>>Trimestre 2</option>
                    <option value="3" <?php echo (isset($_GET['trimestre']) && $_GET['trimestre'] == '3') ? 'selected' : ''; ?>>Trimestre 3</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="Pendiente" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="En proceso" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'En proceso') ? 'selected' : ''; ?>>En proceso</option>
                    <option value="Completado" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'Completado') ? 'selected' : ''; ?>>Completado</option>
                    <option value="Archivado" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'Archivado') ? 'selected' : ''; ?>>Archivado</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="<?php echo Url::to('/informes_exploracion'); ?>" class="btn btn-outline-secondary w-100">Limpiar</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Trimestre</th>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Médico Referido</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($informes)): ?>
                        <?php foreach ($informes as $informe): ?>
                            <tr>
                                <td><span class="fw-bold text-primary"><?php echo htmlspecialchars($informe['codigo_informe']); ?></span></td>
                                <td><span class="badge bg-info">Trimestre <?php echo htmlspecialchars($informe['trimestre']); ?></span></td>
                                <td><?php echo date('d/m/Y', strtotime($informe['fecha_informe'])); ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-user-injured text-secondary"></i>
                                        <span class="fw-medium"><?php echo htmlspecialchars($informe['paciente_nombre'] . ' ' . $informe['paciente_apellido']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-user-doctor text-primary"></i>
                                        <span><?php echo htmlspecialchars($informe['medico_ref_nombre'] . ' ' . $informe['medico_ref_apellido']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $estadoClass = [
                                        'Pendiente' => 'bg-warning',
                                        'En proceso' => 'bg-primary',
                                        'Completado' => 'bg-success',
                                        'Archivado' => 'bg-secondary'
                                    ];
                                    ?>
                                    <span class="badge <?php echo $estadoClass[$informe['estado']] ?? 'bg-secondary'; ?>">
                                        <?php echo htmlspecialchars($informe['estado']); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="<?php echo Url::to('/informes_exploracion/show?id=' . $informe['id']); ?>" class="btn btn-sm btn-light text-primary" title="Ver Detalles">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>
                                        <a href="<?php echo Url::to('/informes_exploracion/edit?id=' . $informe['id']); ?>" class="btn btn-sm btn-light text-warning" title="Editar">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-file-medical-alt fa-3x mb-3 text-light"></i>
                                <h5>No hay informes de exploración</h5>
                                <p class="mb-0">Comience creando el primer informe de exploración.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
