<?php
$title = "Mi Perfil";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h1 class="page-title mb-0">Mi Perfil</h1>
        <a href="<?php echo Url::to('/perfil/edit'); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-pen-to-square"></i> Editar Perfil
        </a>
    </div>
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
                <div class="mb-3">
                    <label class="form-label">Firma Digital</label>
                    <?php if (!empty($user['ruta_firma'])): ?>
                        <p><span class="badge bg-success"><i class="fa-solid fa-circle-check"></i> Registrada</span></p>
                        <img src="<?php echo Url::base() . $user['ruta_firma']; ?>" alt="Firma" style="max-width:300px; border:1px solid #dee2e6; border-radius:0.25rem; padding:8px;">
                    <?php else: ?>
                        <p><span class="badge bg-secondary">No registrada</span></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
