<?php
$title = "Dashboard - Médico";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <h1 class="page-title">Bienvenido, Dr/Dra. <?php echo htmlspecialchars($user['nombre']); ?></h1>
    <p class="page-subtitle">Panel Médico</p>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="stats-card" style="cursor: pointer; transition: all 0.2s;" onclick="window.location.href='<?php echo Url::to('/asignaciones/mis-pacientes'); ?>'">
            <div class="stats-icon" style="background: #d1e7dd; color: #367d84;">
                <i class="fa-solid fa-bed-pulse"></i>
            </div>
            <h3 class="stats-number">Pacientes</h3>
            <p class="stats-label">Ver mis pacientes asignados</p>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="stats-card" style="cursor: pointer; transition: all 0.2s;" onclick="window.location.href='<?php echo Url::to('/diagnosticos'); ?>'">
            <div class="stats-icon" style="background: #d1e7dd; color: #367d84;">
                <i class="fa-solid fa-file-medical"></i>
            </div>
            <h3 class="stats-number">Diagnósticos</h3>
            <p class="stats-label">Ver historial de diagnósticos</p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>