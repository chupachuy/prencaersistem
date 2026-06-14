<?php
$title = "Bitácora de Auditoría";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <h1 class="page-title">Bitácora de Auditoría</h1>
    <p class="page-subtitle">Registro de acciones realizadas en el sistema</p>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Acción</th>
                <th>Descripción</th>
                <th>Módulo</th>
                <th>IP</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($registros)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No hay registros en la bitácora.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($registros as $r): ?>
                    <tr>
                        <td style="white-space:nowrap;"><?php echo date('d/m/Y H:i', strtotime($r['created_at'])); ?></td>
                        <td><?php echo htmlspecialchars($r['nombre'] . ' ' . $r['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($r['accion']); ?></td>
                        <td><?php echo htmlspecialchars($r['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($r['modulo']); ?></td>
                        <td style="font-family:monospace;font-size:12px;"><?php echo htmlspecialchars($r['ip']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($totalPages > 1): ?>
<nav>
    <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>">&laquo; Anterior</a>
            </li>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>">Siguiente &raquo;</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>

<div class="text-muted mt-2" style="font-size:13px;">
    Total de registros: <?php echo $total; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
