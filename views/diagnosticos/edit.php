<?php

$title = "Editar Diagnóstico #" . htmlspecialchars($diagnostico['id'] ?? '');
require_once __DIR__ . '/../layouts/header.php';

require_once __DIR__ . '/../layouts/sidebar.php';

?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?php echo Url::to('/diagnosticos'); ?>" class="btn btn-light border btn-sm"><i class="fa-solid fa-arrow-left"></i> Historial</a>
    <div>
        <h2 class="page-title mb-0">Editar Diagnóstico #<?php echo htmlspecialchars($diagnostico['id']); ?></h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="<?php echo Url::to('/diagnosticos/update'); ?>" method="POST">
                    
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($diagnostico['id']); ?>">

                    <h5 class="mb-4 text-primary border-bottom pb-2"><i class="fa-solid fa-clipboard-user me-2"></i> Datos del Paciente</h5>
                    
                    <div class="mb-4">
                        <label for="paciente" class="form-label fw-medium text-muted small">Nombre / ID del Paciente <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="paciente" name="paciente" value="<?php echo htmlspecialchars($diagnostico['paciente'] ?? ''); ?>" placeholder="Nombre completo del paciente o número de identificación" required autofocus>
                    </div>

                    <?php if (isset($roleId) && ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_JEFE)): ?>
                        <div class="mb-4">
                            <label for="medico_id" class="form-label fw-medium text-muted small">Médico Asignado <span class="text-danger">*</span></label>
                            <select class="form-select" id="medico_id" name="medico_id" required>
                                <option value="">Seleccione un médico</option>
                                <?php foreach ($medicos as $medico): ?>
                                    <option value="<?php echo $medico['id']; ?>" <?php echo ($medico['id'] == $diagnostico['medico_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($medico['nombre'] . ' ' . $medico['apellido']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <h5 class="mb-4 text-primary border-bottom pb-2 mt-5"><i class="fa-solid fa-stethoscope me-2"></i> Detalles Médicos</h5>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label fw-medium text-muted small">Descripción del Diagnóstico <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" placeholder="Describa el diagnóstico, síntomas y observaciones..." required><?php echo htmlspecialchars($diagnostico['descripcion'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="tratamiento" class="form-label fw-medium text-muted small">Receta / Tratamiento Recomendado</label>
                        <textarea class="form-control" id="tratamiento" name="tratamiento" rows="3" placeholder="Medicamentos, dosis y recomendaciones adicionales..."><?php echo htmlspecialchars($diagnostico['tratamiento'] ?? ''); ?></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="<?php echo Url::to('/diagnosticos'); ?>" class="btn btn-light border">Cancelar</a>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Actualizar Diagnóstico</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
