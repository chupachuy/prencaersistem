<?php
$title = "Consultas";
$meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
$fecha_hoy = date('j') . ' de ' . $meses[date('n')] . ' del ' . date('Y');
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h1 class="page-title mb-0">Consultas</h1>
    </div>
    <div class="page-header-actions">
        <span class="text-muted me-3">
            <i class="fa-regular fa-calendar me-1"></i>
            <?php echo $fecha_hoy; ?>
        </span>
        <a href="<?php echo Url::to('/consultas/create'); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-plus"></i> Nueva Consulta
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($consultas)): ?>
                    <div class="text-center py-5">
                        <i class="fa-solid fa-calendar-xmark fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay consultas registradas.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Paciente</th>
                                    <th>Motivo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($consultas as $consulta): ?>
                                    <tr>
                                        <td><?php echo $consulta['id_consulta']; ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($consulta['fecha_consulta'])); ?></td>
                                        <td><?php echo htmlspecialchars($consulta['paciente_nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($consulta['motivo_consulta'] ?? '-'); ?></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="<?php echo Url::to('/consultas/show?id=' . $consulta['id_consulta']); ?>" class="btn btn-sm btn-apple btn-apple-secondary" title="Ver">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                <?php 
                                                $reporte1t = $reportesData[$consulta['id_consulta']] ?? null;
                                                if (!$reporte1t): 
                                                ?>
                                                    <a href="<?php echo Url::to('/reporte_1er_trimestre/create?consulta_id=' . $consulta['id_consulta']); ?>" class="btn btn-sm btn-apple btn-apple-primary" title="Reporte 1er Trimestre">
                                                        <i class="fa-solid fa-baby"></i> 1er Trimestre
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?php echo Url::to('/reporte_1er_trimestre/edit?id=' . $reporte1t['id_reporte_1t']); ?>" class="btn btn-sm btn-apple btn-apple-secondary" title="Editar">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>
                                                    <a href="<?php echo Url::to('/reporte_1er_trimestre/print?id=' . $reporte1t['id_reporte_1t']); ?>" class="btn btn-sm btn-apple btn-apple-secondary" target="_blank" title="Ver PDF">
                                                        <i class="fa-solid fa-print"></i>
                                                    </a>
                                                <?php endif; ?>
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
