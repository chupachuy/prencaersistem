<?php 
$title = "Nuevo Informe de Exploración";
require_once __DIR__ . '/../layouts/header.php'; 
require_once __DIR__ . '/../layouts/sidebar.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><?php echo htmlspecialchars($title); ?></h2>
        <p class="text-muted small mb-0">Crear nuevo informe de exploración estructural</p>
    </div>
    <a href="<?php echo Url::to('/informes_exploracion'); ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?php echo Url::to('/informes_exploracion/store'); ?>">
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="text-primary mb-3"><i class="fa-solid fa-user-injured"></i> Datos del Paciente</h5>
                </div>
                
                <?php if ($roleId == 1 || $roleId == 2): ?>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Paciente *</label>
                    <select name="paciente_id" class="form-select" required>
                        <option value="">Seleccionar paciente...</option>
                        <?php if (!empty($pacientes)): ?>
                            <?php foreach ($pacientes as $paciente): ?>
                                <option value="<?php echo $paciente['id']; ?>">
                                    <?php echo htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellido']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <?php endif; ?>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Trimestre *</label>
                    <select name="trimestre" class="form-select" required>
                        <option value="">Seleccionar trimestre...</option>
                        <option value="1">Trimestre 1 (Semanas 1-12)</option>
                        <option value="2">Trimestre 2 (Semanas 13-26)</option>
                        <option value="3">Trimestre 3 (Semanas 27-40)</option>
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="text-primary mb-3"><i class="fa-solid fa-user-doctor"></i> Referencia Médica</h5>
                </div>
                
                <?php if ($roleId == 1 || $roleId == 2): ?>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Médico Referido *</label>
                    <select name="medico_referido_id" class="form-select" required>
                        <option value="">Seleccionar médico...</option>
                        <?php if (!empty($medicos)): ?>
                            <?php foreach ($medicos as $medico): ?>
                                <option value="<?php echo $medico['id']; ?>">
                                    Dr(a). <?php echo htmlspecialchars($medico['nombre'] . ' ' . $medico['apellido']); ?> - <?php echo htmlspecialchars($medico['especialidad'] ?? 'General'); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-muted">El Super Admin es quien refiere al paciente a un médico específico</small>
                </div>
                <?php endif; ?>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Estudio Solicitado</label>
                    <input type="text" name="estudio_solicitado" class="form-control" placeholder="Ej: Ultrasonido estructural, Doppler, etc.">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo Url::to('/informes_exploracion'); ?>" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Crear Informe
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
