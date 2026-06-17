<?php
$title = "Editar Paciente";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/pacientes'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0">Editar Paciente</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-user me-2"></i> Datos Personales
            </div>
            <div class="card-body">
                <form action="<?php echo Url::to('/pacientes/update'); ?>" method="POST">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($paciente['id']); ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre <span style="color: #bf2b2b;">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Ej. Juan" value="<?php echo htmlspecialchars($paciente['nombre']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label">Apellido <span style="color: #bf2b2b;">*</span></label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required placeholder="Ej. Pérez" value="<?php echo htmlspecialchars($paciente['apellido']); ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span style="color: #bf2b2b;">*</span></label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required value="<?php echo htmlspecialchars($paciente['fecha_nacimiento']); ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Ej. paciente@correo.com" value="<?php echo htmlspecialchars($paciente['email'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Número de Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ej. 555-123-4567" value="<?php echo htmlspecialchars($paciente['telefono'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="direccion" class="form-label">Dirección</label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="2" placeholder="Dirección del paciente"><?php echo htmlspecialchars($paciente['direccion'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="tipo_seguimiento" class="form-label">Tipo de Seguimiento</label>
                        <select class="form-select" id="tipo_seguimiento" name="tipo_seguimiento">
                            <?php $tipo = $paciente['tipo_seguimiento'] ?? 'Propia'; ?>
                            <option value="Propia" <?php echo $tipo === 'Propia' ? 'selected' : ''; ?>>Propia</option>
                            <option value="Referida" <?php echo $tipo === 'Referida' ? 'selected' : ''; ?>>Referida</option>
                            <option value="IMSS" <?php echo $tipo === 'IMSS' ? 'selected' : ''; ?>>IMSS</option>
                            <option value="ISSSTE" <?php echo $tipo === 'ISSSTE' ? 'selected' : ''; ?>>ISSSTE</option>
                        </select>
                    </div>

                    <h5 class="border-bottom pb-2 mb-3 text-muted"><i class="fa-solid fa-baby me-2"></i> Antecedentes Obstétricos</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="num_embarazos" class="form-label">G - Embarazos</label>
                            <input type="number" class="form-control" id="num_embarazos" name="num_embarazos" min="0" placeholder="Ej: 2" value="<?php echo htmlspecialchars($historial['num_embarazos'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="num_cesareas" class="form-label">C - Cesáreas</label>
                            <input type="number" class="form-control" id="num_cesareas" name="num_cesareas" min="0" placeholder="Ej: 1" value="<?php echo htmlspecialchars($historial['num_cesareas'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="num_abortos" class="form-label">A - Abortos</label>
                            <input type="number" class="form-control" id="num_abortos" name="num_abortos" min="0" placeholder="Ej: 0" value="<?php echo htmlspecialchars($historial['num_abortos'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="num_ectopicos" class="form-label">E - Ectópicos</label>
                            <input type="number" class="form-control" id="num_ectopicos" name="num_ectopicos" min="0" placeholder="Ej: 0" value="<?php echo htmlspecialchars($historial['num_ectopicos'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo Url::to('/pacientes'); ?>" class="btn btn-apple btn-apple-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-apple btn-apple-primary"><i class="fa-solid fa-save"></i> Actualizar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
