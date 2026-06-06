<?php
$title = "Nueva Referencia";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><?php echo htmlspecialchars($title); ?></h2>
        <p class="text-muted small mb-0">Remitir un paciente a otro medico para estudio</p>
    </div>
    <a href="<?php echo Url::to('/referencias'); ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?php echo Url::to('/referencias/store'); ?>">
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="text-primary mb-3"><i class="fa-solid fa-user-injured"></i> Datos del Paciente</h5>
                </div>

                <?php if ($roleId == 1 || $roleId == 2 || $roleId == 3): ?>
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

                <?php if ($roleId == 4): ?>
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
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="text-primary mb-3"><i class="fa-solid fa-user-doctor"></i> Datos de la Referencia</h5>
                </div>

                <?php if ($roleId == 1 || $roleId == 2): ?>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Medico Solicitante *</label>
                    <select name="medico_solicitante_id" class="form-select" required>
                        <option value="">Seleccionar medico solicitante...</option>
                        <?php if (!empty($medicos)): ?>
                            <?php foreach ($medicos as $medico): ?>
                                <option value="<?php echo $medico['id']; ?>" <?php echo $medico['id'] == $userId ? 'selected' : ''; ?>>
                                    Dr(a). <?php echo htmlspecialchars($medico['nombre'] . ' ' . $medico['apellido']); ?> - <?php echo htmlspecialchars($medico['especialidad'] ?? 'General'); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <?php endif; ?>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Medico Referido *</label>
                    <select name="medico_referido_tipo" class="form-select" required>
                        <option value="">Seleccionar medico destino...</option>
                        <?php if (!empty($medicos)): ?>
                        <optgroup label="── Internos ──">
                            <?php foreach ($medicos as $medico): ?>
                                <option value="int:<?php echo $medico['id']; ?>">
                                    Dr(a). <?php echo htmlspecialchars($medico['nombre'] . ' ' . $medico['apellido']); ?> - <?php echo htmlspecialchars($medico['especialidad'] ?? 'General'); ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                        <?php endif; ?>
                        <?php if (!empty($medicosExternos)): ?>
                        <optgroup label="── Externos ──">
                            <?php foreach ($medicosExternos as $ext): ?>
                                <option value="ext:<?php echo $ext['id']; ?>">
                                    Dr(a). <?php echo htmlspecialchars($ext['nombre'] . ' ' . $ext['apellido']); ?> - <?php echo htmlspecialchars($ext['especialidad'] ?? 'General'); ?> (<?php echo htmlspecialchars($ext['institucion'] ?? 'Externo'); ?>)
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Tipo de Estudio *</label>
                    <input type="text" name="tipo_estudio" class="form-control" placeholder="Ej: Ultrasonido Doppler, Ecografia morfologica, etc." required>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Motivo de la Referencia *</label>
                    <textarea name="motivo_referencia" class="form-control" rows="3" placeholder="Describa el motivo clinico de la referencia..." required></textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2" placeholder="Observaciones adicionales (opcional)"></textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Vincular a Informe de Exploracion (opcional)</label>
                    <select name="informe_exploracion_id" class="form-select">
                        <option value="">Sin vinculacion...</option>
                        <?php if (!empty($informes)): ?>
                            <?php foreach ($informes as $informe): 
                                $pacienteModel = new Paciente();
                                $pac = $pacienteModel->findByIdOrName($informe['paciente_id']);
                                $pacNom = $pac ? $pac['nombre'] . ' ' . $pac['apellido'] : 'Paciente #' . $informe['paciente_id'];
                            ?>
                                <option value="<?php echo $informe['id']; ?>">
                                    <?php echo htmlspecialchars($informe['codigo_informe'] . ' - ' . $pacNom . ' (T' . $informe['trimestre'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo Url::to('/referencias'); ?>" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-apple btn-apple-primary">
                    <i class="fa-solid fa-paper-plane"></i> Enviar Referencia
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
