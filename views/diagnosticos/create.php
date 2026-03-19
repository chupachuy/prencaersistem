<?php

$title = "Nuevo Diagnóstico";
require_once __DIR__ . '/../layouts/header.php';

require_once __DIR__ . '/../layouts/sidebar.php';

?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/diagnosticos'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0">Nuevo Diagnóstico</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-user me-2"></i> Datos del Paciente
            </div>
            <div class="card-body">
                <form action="<?php echo Url::to('/diagnosticos/store'); ?>" method="POST">
                    
                    <div class="mb-4">
                        <label for="paciente" class="form-label">Paciente <span style="color: #bf2b2b;">*</span></label>
                        <input type="text" class="form-control" id="paciente" name="paciente" placeholder="Nombre o ID del paciente" required>
                    </div>

                    <?php if (isset($roleId) && ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_JEFE)): ?>
                        <div class="mb-4">
                            <label for="medico_id" class="form-label">Médico Asignado <span style="color: #bf2b2b;">*</span></label>
                            <select class="form-select" id="medico_id" name="medico_id" required>
                                <option value="">Seleccione un médico</option>
                                <?php foreach ($medicos as $medico): ?>
                                    <option value="<?php echo $medico['id']; ?>"><?php echo htmlspecialchars($medico['nombre'] . ' ' . $medico['apellido']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="card-header" style="margin: 20px -20px 20px -20px; background: transparent; border-top: 1px solid rgba(0,0,0,0.04);">
                        <i class="fa-solid fa-stethoscope me-2"></i> Detalles Médicos
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción del Diagnóstico <span style="color: #bf2b2b;">*</span></label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" placeholder="Describa el diagnóstico, síntomas y observaciones..." required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="tratamiento" class="form-label">Tratamiento Recomendado</label>
                        <textarea class="form-control" id="tratamiento" name="tratamiento" rows="3" placeholder="Medicamentos, dosis y recomendaciones..."></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo Url::to('/diagnosticos'); ?>" class="btn btn-apple btn-apple-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-apple btn-apple-primary"><i class="fa-solid fa-save"></i> Guardar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
