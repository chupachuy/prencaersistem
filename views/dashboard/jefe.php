<?php
$title = "Dashboard - Jefe Médico";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <h1 class="page-title">Bienvenido, Dr/Dra. <?php echo htmlspecialchars($user['nombre']); ?></h1>
    <p class="page-subtitle">Panel de control de Jefe Médico</p>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="stats-card" style="cursor: pointer; transition: all 0.2s;" onclick="window.location.href='<?php echo Url::to('/diagnosticos'); ?>'">
            <div class="stats-icon" style="background: #d1e7dd; color: #367d84;">
                <i class="fa-solid fa-stethoscope"></i>
            </div>
            <h3 class="stats-number">Mis Diagnósticos</h3>
            <p class="stats-label">Crear o ver mis diagnósticos</p>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="stats-card" style="cursor: pointer; transition: all 0.2s;" onclick="window.location.href='<?php echo Url::to('/diagnosticos/todos'); ?>'">
            <div class="stats-icon" style="background: #fff3cd; color: #856404;">
                <i class="fa-solid fa-clipboard-check"></i>
            </div>
            <h3 class="stats-number">Supervisión</h3>
            <p class="stats-label">Ver todos los diagnósticos</p>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="stats-card" style="cursor: pointer; transition: all 0.2s;" onclick="window.location.href='<?php echo Url::to('/asignaciones'); ?>'">
            <div class="stats-icon" style="background: #d1e7dd; color: #367d84;">
                <i class="fa-solid fa-users"></i>
            </div>
            <h3 class="stats-number">Asignaciones</h3>
            <p class="stats-label">Gestionar asignaciones de pacientes</p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>