<?php

$title = "Nueva Asignación de Paciente";
require_once __DIR__ . '/../layouts/header.php';

require_once __DIR__ . '/../layouts/sidebar.php';

?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/asignaciones'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0">Nueva Asignación</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-user-plus me-2"></i> Detalles de la Asignación
            </div>
            <div class="card-body">
                <form action="<?php echo Url::to('/asignaciones/store'); ?>" method="POST">
                    
                    <div class="row mb-4">
                        <div class="col-md-6" style="border-right: 1px solid rgba(0,0,0,0.08);">
                            <label for="paciente_id" class="form-label">Paciente <span style="color: #bf2b2b;">*</span></label>
                            <input type="text" class="form-control" id="paciente_id" name="paciente_id" placeholder="ID o nombre del paciente" required>
                            <div style="font-size: 12px; color: var(--apple-gray); margin-top: 6px;">Ingrese el identificador único del paciente.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="medico_id" class="form-label">Médico Especialista <span style="color: #bf2b2b;">*</span></label>
                            <select class="form-select" id="medico_id" name="medico_id" required>
                                <option value="">Seleccione un médico...</option>
                                <?php if (!empty($medicos)): ?>
                                    <?php foreach ($medicos as $medico): ?>
                                        <option value="<?php echo htmlspecialchars($medico['id']); ?>">
                                            <?php echo htmlspecialchars($medico['nombre'] . ' ' . $medico['apellido'] . ' - ' . ($medico['especialidad'] ?: 'General')); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="motivo" class="form-label">Motivo de Asignación <span style="color: #bf2b2b;">*</span></label>
                        <textarea class="form-control" id="motivo" name="motivo" rows="3" placeholder="Evaluación inicial, control mensual..." required></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2" style="padding-top: 16px; border-top: 1px solid rgba(0,0,0,0.04);">
                        <a href="<?php echo Url::to('/asignaciones'); ?>" class="btn btn-apple btn-apple-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-apple btn-apple-primary"><i class="fa-solid fa-link"></i> Confirmar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
