<?php
$title = "Mi Perfil";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <h1 class="page-title">Mi Perfil</h1>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-user me-2"></i> Información Personal
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <p style="font-weight: 600; margin: 0;"><?php echo htmlspecialchars($user['nombre'] ?? ''); ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Apellido</label>
                    <p style="font-weight: 600; margin: 0;"><?php echo htmlspecialchars($user['apellido'] ?? ''); ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <p style="font-weight: 600; margin: 0;"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                </div>
                <?php if (!empty($user['especialidad'])): ?>
                <div class="mb-3">
                    <label class="form-label">Especialidad</label>
                    <p style="font-weight: 600; margin: 0;"><?php echo htmlspecialchars($user['especialidad']); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
