<?php

$title = "Crear Usuario";
require_once __DIR__ . '/../layouts/header.php';

require_once __DIR__ . '/../layouts/sidebar.php';

?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/usuarios'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0">Nuevo Usuario</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-user me-2"></i> Información Personal
            </div>
            <div class="card-body">
                <form action="<?php echo Url::to('/usuarios/store'); ?>" method="POST">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre <span style="color: #bf2b2b;">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label">Apellido <span style="color: #bf2b2b;">*</span></label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Correo Electrónico <span style="color: #bf2b2b;">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Contraseña <span style="color: #bf2b2b;">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>

                    <div class="card-header" style="margin: 20px -20px 20px -20px; background: transparent; border-top: 1px solid rgba(0,0,0,0.04);">
                        <i class="fa-solid fa-briefcase me-2"></i> Información Profesional
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="rol_id" class="form-label">Rol del Sistema <span style="color: #bf2b2b;">*</span></label>
                            <select class="form-select" id="rol_id" name="rol_id" required>
                                <option value="">Seleccione un rol...</option>
                                <?php if (!empty($roles)): ?>
                                    <?php foreach ($roles as $rol): ?>
                                        <option value="<?php echo htmlspecialchars($rol['id']); ?>"><?php echo htmlspecialchars($rol['nombre']); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="especialidad" class="form-label">Especialidad Médica</label>
                            <input type="text" class="form-control" id="especialidad" name="especialidad" placeholder="Ej: Pediatría">
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch p-0">
                            <div class="d-flex align-items-center gap-3">
                                <label class="form-check-label mb-0" for="activo">Cuenta Activa</label>
                                <input class="form-check-input" type="checkbox" role="switch" id="activo" name="activo" checked value="1">
                            </div>
                            <div style="font-size: 12px; color: var(--apple-gray); margin-top: 4px;">Si está desmarcado, el usuario no podrá iniciar sesión.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo Url::to('/usuarios'); ?>" class="btn btn-apple btn-apple-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-apple btn-apple-primary"><i class="fa-solid fa-save"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
