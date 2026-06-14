<?php
$title = "Evaluaciones";
$meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
$fecha_hoy = date('j') . ' de ' . $meses[date('n')] . ' del ' . date('Y');
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <div>
            <h1 class="page-title">Evaluaciones</h1>
            <p class="page-subtitle">Evaluaciones prenatales por trimestre</p>
        </div>
    </div>
    <div class="page-header-actions">
        <span class="text-muted" style="font-size: 13px;">
            <i class="fa-regular fa-calendar me-1"></i>
            <?php echo $fecha_hoy; ?>
        </span>
    </div>
</div>

<style>
    .nav-tabs-apple {
        border-bottom: 2px solid var(--apple-border);
        gap: 4px;
    }
    .nav-tabs-apple .nav-link {
        border: none;
        border-radius: 10px 10px 0 0;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 500;
        color: var(--apple-gray);
        background: transparent;
        transition: color 0.2s, background 0.2s;
        margin-bottom: -2px;
    }
    .nav-tabs-apple .nav-link:hover {
        color: var(--apple-text);
        background: var(--apple-bg);
        border: none;
    }
    .nav-tabs-apple .nav-link.active {
        color: var(--apple-blue);
        background: var(--apple-card);
        border: none;
        border-bottom: 2px solid var(--apple-blue);
    }
    .nav-tabs-apple .nav-link .badge-count {
        background: var(--apple-bg);
        color: var(--apple-gray);
        font-weight: 500;
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 12px;
    }
    .badge-archivado {
        background: #e2e3e5;
        color: #41464b;
    }
    .tab-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
</style>

<ul class="nav nav-tabs nav-tabs-apple mb-3" id="evalTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab-trim1" data-bs-toggle="tab" data-bs-target="#trim1" type="button" role="tab" data-trimestre="1">
            <i class="fa-solid fa-file-invoice me-1"></i> 1er Trimestre
            <span class="badge-count ms-2"><?php echo count($evaluaciones1); ?></span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-trim2" data-bs-toggle="tab" data-bs-target="#trim2" type="button" role="tab" data-trimestre="2">
            <i class="fa-solid fa-file-invoice me-1"></i> 2do Trimestre
            <span class="badge-count ms-2"><?php echo count($evaluaciones2); ?></span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-trim3" data-bs-toggle="tab" data-bs-target="#trim3" type="button" role="tab" data-trimestre="3">
            <i class="fa-solid fa-file-invoice me-1"></i> 3er Trimestre
            <span class="badge-count ms-2"><?php echo count($evaluaciones3); ?></span>
        </button>
    </li>
</ul>

<div class="tab-content" id="evalTabsContent">
    <?php
    function renderTabla($evaluaciones, $trimestre) {
        $prefix = '/evaluaciones_' . $trimestre . '_trimestre';
        $tabId = $trimestre === '1er' ? '1' : ($trimestre === '2do' ? '2' : '3');
        ?>
        <div class="tab-pane fade <?php echo $trimestre === '1er' ? 'show active' : ''; ?>" id="trim<?php echo $tabId; ?>" role="tabpanel">
            <div class="tab-header-row">
                <div class="text-muted" style="font-size: 13px;">
                    <?php echo count($evaluaciones); ?> evaluacion<?php echo count($evaluaciones) !== 1 ? 'es' : ''; ?>
                </div>
                <a href="<?php echo Url::to($prefix . '/create'); ?>" class="btn btn-apple btn-apple-primary" style="font-size: 13px; padding: 6px 14px;">
                    <i class="fa-solid fa-plus"></i> Nueva Evaluacion
                </a>
            </div>
            <div class="card">
                <div class="card-body" style="padding: 0;">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Fecha</th>
                                    <th>Paciente</th>
                                    <th>Medico</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($evaluaciones)): ?>
                                    <?php foreach ($evaluaciones as $ev): ?>
                                        <?php
                                        $estado = $ev['estado'] ?? 'Pendiente';
                                        $badgeClass = match($estado) {
                                            'Completado' => 'badge-success',
                                            'En proceso' => 'badge-warning',
                                            'Archivado' => 'badge-archivado',
                                            default => 'badge-info'
                                        };
                                        ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($ev['codigo_reporte']); ?></strong></td>
                                            <td style="color: var(--apple-gray);"><?php echo date('d/m/Y', strtotime($ev['fecha_evaluacion'])); ?></td>
                                            <td><?php echo htmlspecialchars($ev['paciente_nombre'] . ' ' . $ev['paciente_apellido']); ?></td>
                                            <td style="color: var(--apple-gray);"><?php echo htmlspecialchars($ev['medico_nombre'] . ' ' . $ev['medico_apellido']); ?></td>
                                            <td><span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($estado); ?></span></td>
                                            <td class="text-center">
                                                <a href="<?php echo Url::to($prefix . '/show?id=' . $ev['id']); ?>" class="action-btn action-btn-view" title="Ver">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                <a href="<?php echo Url::to($prefix . '/edit?id=' . $ev['id']); ?>" class="action-btn action-btn-edit" title="Editar">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a href="<?php echo Url::to($prefix . '/pdf?id=' . $ev['id']); ?>" class="action-btn action-btn-view" target="_blank" title="Descargar PDF">
                                                    <i class="fa-solid fa-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4" style="color: var(--apple-gray);">
                                            <i class="fa-solid fa-folder-open fa-2x mb-2" style="opacity: 0.25; display: block;"></i>
                                            No hay evaluaciones registradas
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php renderTabla($evaluaciones1, '1er'); ?>
    <?php renderTabla($evaluaciones2, '2do'); ?>
    <?php renderTabla($evaluaciones3, '3er'); ?>
</div>

<script>
(function() {
    var STORAGE_KEY = 'evalTabActivo';
    var tabs = document.querySelectorAll('#evalTabs button[data-bs-toggle="tab"]');

    function activarTab(trimestre) {
        var targetId = 'tab-trim' + trimestre;
        var tab = document.getElementById(targetId);
        if (tab && !tab.classList.contains('active')) {
            var bsTab = new bootstrap.Tab(tab);
            bsTab.show();
        }
    }

    tabs.forEach(function(tab) {
        tab.addEventListener('shown.bs.tab', function(e) {
            var t = e.target.getAttribute('data-trimestre');
            if (t) {
                localStorage.setItem(STORAGE_KEY, t);
            }
        });
    });

    var saved = localStorage.getItem(STORAGE_KEY);
    if (saved) {
        activarTab(saved);
    }
})();
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
