<?php

$title = "Editar Usuario";
require_once __DIR__ . '/../layouts/header.php';

require_once __DIR__ . '/../layouts/sidebar.php';

?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/usuarios'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0">Editar Usuario</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-user me-2"></i> Información Personal
            </div>
            <div class="card-body">
                <form action="<?php echo Url::to('/usuarios/update?id=' . $usuario['id']); ?>" method="POST" enctype="multipart/form-data">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre <span style="color: #bf2b2b;">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label">Apellido <span style="color: #bf2b2b;">*</span></label>
                            <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Correo Electrónico <span style="color: #bf2b2b;">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Número de Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>" placeholder="Ej: 555-123-4567">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Dejar en blanco para no cambiar">
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
                                        <option value="<?php echo htmlspecialchars($rol['id']); ?>" <?php echo ($usuario['rol_id'] == $rol['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($rol['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="especialidad" class="form-label">Especialidad Médica</label>
                            <input type="text" class="form-control" id="especialidad" name="especialidad" value="<?php echo htmlspecialchars($usuario['especialidad'] ?? ''); ?>" placeholder="Ej: Pediatría">
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch p-0">
                            <div class="d-flex align-items-center gap-3">
                                <label class="form-check-label mb-0" for="activo">Cuenta Activa</label>
                                <input class="form-check-input" type="checkbox" role="switch" id="activo" name="activo" <?php echo ($usuario['activo'] == 1) ? 'checked' : ''; ?> value="1">
                            </div>
                            <div style="font-size: 12px; color: var(--apple-gray); margin-top: 4px;">Si está desmarcado, el usuario no podrá iniciar sesión.</div>
                        </div>
                    </div>

                    <?php if (in_array($usuario['rol_id'], [3, 4])): ?>
                    <div class="card-header" style="margin: 20px -20px 20px -20px; background: transparent; border-top: 1px solid rgba(0,0,0,0.04);">
                        <i class="fa-solid fa-signature me-2"></i> Firma Digital
                    </div>
                    <div class="mb-3">
                        <?php if (!empty($usuario['ruta_firma'])): ?>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="border: 1px solid var(--apple-border); border-radius: 8px; padding: 8px; background: #fff;">
                                <img src="<?php echo Url::base() . $usuario['ruta_firma']; ?>" alt="Firma actual" style="max-height: 80px; max-width: 300px;">
                            </div>
                            <div>
                                <small class="text-success d-block"><i class="fa-solid fa-circle-check"></i> Firma registrada</small>
                                <label class="d-block mt-1" style="cursor: pointer; color: var(--apple-blue); font-size: 13px;">
                                    <i class="fa-solid fa-arrow-up-from-bracket me-1"></i> Reemplazar
                                    <input type="file" class="d-none" name="firma" accept="image/png,image/jpeg,image/jpg" onchange="this.form.querySelector('[name=accion_firma]').value='reemplazar';">
                                </label>
                                <label class="d-block mt-1" style="cursor: pointer; color: #bf2b2b; font-size: 13px;">
                                    <i class="fa-solid fa-trash-can me-1"></i> Eliminar firma
                                    <input type="checkbox" class="d-none" name="eliminar_firma" value="1">
                                </label>
                            </div>
                        </div>
                        <?php else: ?>
                        <label for="firma" class="form-label">Firma del Médico</label>
                        <input type="file" class="form-control" id="firma" name="firma" accept="image/png,image/jpeg,image/jpg">
                        <small style="color: var(--apple-gray); font-size: 12px;">Formatos aceptados: JPG, PNG. Tamaño máximo: 2 MB.</small>
                        <?php endif; ?>
                        <input type="hidden" name="accion_firma" value="">
                    </div>
                    <?php endif; ?>

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