<?php 
$title = "Pacientes Registrados";
require_once __DIR__ . '/../layouts/header.php'; 
require_once __DIR__ . '/../layouts/sidebar.php'; 
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title"><?php echo htmlspecialchars($title); ?></h1>
            <p class="page-subtitle">Gestión de pacientes del sistema</p>
        </div>
        <a href="<?php echo Url::to('/pacientes/create'); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-plus"></i> Nuevo Paciente
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Fecha Nacimiento</th>
                        <th>Registrado en</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pacientes)): ?>
                        <?php foreach ($pacientes as $paciente): ?>
                            <tr>
                                <td><span style="color: var(--apple-gray); font-weight: 500;">#<?php echo htmlspecialchars($paciente['id']); ?></span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2" style="font-weight: 500;">
                                        <div style="width: 32px; height: 32px; background: var(--apple-bg); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa-solid fa-user" style="color: var(--apple-gray); font-size: 12px;"></i>
                                        </div>
                                        <?php echo htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellido']); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                        $fn = new DateTime($paciente['fecha_nacimiento'] ?? 'now');
                                        echo $fn->format('d/m/Y'); 
                                    ?>
                                </td>
                                <td style="color: var(--apple-gray);">
                                    <?php 
                                        $fc = new DateTime($paciente['created_at'] ?? 'now');
                                        echo $fc->format('d/m/Y H:i'); 
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5" style="color: var(--apple-gray);">
                                <i class="fa-solid fa-users-slash fa-3x mb-3" style="opacity: 0.3;"></i>
                                <h5 style="font-weight: 600;">No hay pacientes registrados</h5>
                                <p class="mb-0">Comience agregando nuevos pacientes al sistema.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
