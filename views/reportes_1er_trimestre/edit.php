<?php
$title = "Editar Reporte 1er Trimestre";
$meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
$fecha_hoy = date('j') . ' de ' . $meses[date('n')] . ' del ' . date('Y');
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/reportes_1er_trimestre'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0">Editar Reporte 1er Trimestre</h1>
    </div>
    <div class="page-header-actions">
        <span class="text-muted">
            <i class="fa-regular fa-calendar me-1"></i>
            <?php echo $fecha_hoy; ?>
        </span>
    </div>
</div>

<form action="<?php echo Url::to('/reportes_1er_trimestre/update'); ?>" method="POST">
    <input type="hidden" name="id" value="<?php echo $reporte['id']; ?>">
    
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-id-card me-2"></i> Datos del Reporte
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="codigo_reporte" class="form-label">Código del Reporte</label>
                        <input type="text" class="form-control" id="codigo_reporte" name="codigo_reporte" value="<?php echo htmlspecialchars($reporte['codigo_reporte']); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_reporte" class="form-label">Fecha del Reporte</label>
                        <input type="date" class="form-control" id="fecha_reporte" name="fecha_reporte" value="<?php echo $reporte['fecha_reporte']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="lugar" class="form-label">Lugar</label>
                        <input type="text" class="form-control" id="lugar" name="lugar" value="<?php echo htmlspecialchars($reporte['lugar'] ?? ''); ?>">
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-user me-2"></i> Datos del Paciente y Médico
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="paciente_id" class="form-label">Paciente *</label>
                        <select class="form-select" id="paciente_id" name="paciente_id" required>
                            <option value="">Seleccionar paciente...</option>
                            <?php foreach ($pacientes as $paciente): ?>
                                <option value="<?php echo $paciente['id']; ?>" <?php echo ($reporte['paciente_id'] == $paciente['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellido']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="medico_referido_id" class="form-label">Médico Referido</label>
                        <select class="form-select" id="medico_referido_id" name="medico_referido_id">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($medicos as $medico): ?>
                                <option value="<?php echo $medico['id']; ?>" <?php echo ($reporte['medico_referido_id'] == $medico['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($medico['nombre'] . ' ' . $medico['apellido']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-heart-pulse me-2"></i> Signos Vitales
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="peso" class="form-label">Peso (kg)</label>
                            <input type="number" step="0.01" class="form-control" id="peso" name="peso" value="<?php echo htmlspecialchars($reporte['peso'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="talla" class="form-label">Talla (cm)</label>
                            <input type="number" step="0.01" class="form-control" id="talla" name="talla" value="<?php echo htmlspecialchars($reporte['talla'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="presion_sistolica" class="form-label">Presión Sistólica (mmHg)</label>
                            <input type="number" class="form-control" id="presion_sistolica" name="presion_sistolica" value="<?php echo htmlspecialchars($reporte['presion_sistolica'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="presion_diastolica" class="form-label">Presión Diastólica (mmHg)</label>
                            <input type="number" class="form-control" id="presion_diastolica" name="presion_diastolica" value="<?php echo htmlspecialchars($reporte['presion_diastolica'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-person-breastfeeding me-2"></i> Historia Obstétrica
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="gesta" class="form-label">Gesta <small class="text-muted">(N. de embarazos)</small></label>
                            <input type="number" class="form-control" id="gesta" name="gesta" value="<?php echo htmlspecialchars($reporte['gesta'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="para" class="form-label">Para <small class="text-muted">(partos vaginales)</small></label>
                            <input type="number" class="form-control" id="para" name="para" value="<?php echo htmlspecialchars($reporte['para'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="abortos" class="form-label">Abortos</label>
                            <input type="number" class="form-control" id="abortos" name="abortos" value="<?php echo htmlspecialchars($reporte['abortos'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-calendar me-2"></i> Fechas Obstétricas
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="fecha_ultima_regla" class="form-label">Fecha Última Regla (FUR)</label>
                        <input type="date" class="form-control" id="fecha_ultima_regla" name="fecha_ultima_regla" value="<?php echo $reporte['fecha_ultima_regla'] ?? ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="edad_gestacional_fum" class="form-label">Edad Gestacional por FUR (semanas)</label>
                        <input type="number" step="0.1" class="form-control" id="edad_gestacional_fum" name="edad_gestacional_fum" value="<?php echo htmlspecialchars($reporte['edad_gestacional_fum'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="fecha_probable_parto_fum" class="form-label">FPP por FUR</label>
                        <input type="date" class="form-control" id="fecha_probable_parto_fum" name="fecha_probable_parto_fum" value="<?php echo $reporte['fecha_probable_parto_fum'] ?? ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="equipo_estudio" class="form-label">Datos del Equipo de Estudio</label>
                        <textarea class="form-control" id="equipo_estudio" name="equipo_estudio" rows="3" maxlength="500"><?php echo htmlspecialchars($reporte['equipo_estudio'] ?? ''); ?></textarea>
                        <small class="text-muted">Máximo 500 caracteres</small>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa Ultrasound"></i> Datos Ecográficos
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="longitud_craneo_cauda" class="form-label">Longitud Craneo-Cauda (mm)</label>
                        <input type="number" step="0.01" class="form-control" id="longitud_craneo_cauda" name="longitud_craneo_cauda" value="<?php echo htmlspecialchars($reporte['longitud_craneo_cauda'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="edad_gestacional_usg" class="form-label">Edad Gestacional por USG (semanas)</label>
                        <input type="number" step="0.1" class="form-control" id="edad_gestacional_usg" name="edad_gestacional_usg" value="<?php echo htmlspecialchars($reporte['edad_gestacional_usg'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="fecha_probable_parto_usg" class="form-label">Fecha Probable de Parto calculada por Ultrasonido</label>
                        <input type="date" class="form-control" id="fecha_probable_parto_usg" name="fecha_probable_parto_usg" value="<?php echo $reporte['fecha_probable_parto_usg'] ?? ''; ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="equipo_usg" class="form-label">Equipo de Ultrasonografía</label>
                            <input type="text" class="form-control" id="equipo_usg" name="equipo_usg" value="<?php echo htmlspecialchars($reporte['equipo_usg'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="transductor_tipo" class="form-label">Tipo de Transductor</label>
                            <input type="text" class="form-control" id="transductor_tipo" name="transductor_tipo" value="<?php echo htmlspecialchars($reporte['transductor_tipo'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-baby me-2"></i> Exploración Estructural Fetal
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="craneo" class="form-label">Cráneo</label>
                        <textarea class="form-control" id="craneo" name="craneo" rows="2"><?php echo htmlspecialchars($reporte['craneo'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="sistema_nervioso_central" class="form-label">Sistema Nervioso Central</label>
                        <textarea class="form-control" id="sistema_nervioso_central" name="sistema_nervioso_central" rows="2"><?php echo htmlspecialchars($reporte['sistema_nervioso_central'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cuello" class="form-label">Cuello</label>
                        <textarea class="form-control" id="cuello" name="cuello" rows="2"><?php echo htmlspecialchars($reporte['cuello'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cara" class="form-label">Cara</label>
                        <textarea class="form-control" id="cara" name="cara" rows="2"><?php echo htmlspecialchars($reporte['cara'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="columna" class="form-label">Columna</label>
                        <textarea class="form-control" id="columna" name="columna" rows="2"><?php echo htmlspecialchars($reporte['columna'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="torax" class="form-label">Tórax</label>
                        <textarea class="form-control" id="torax" name="torax" rows="2"><?php echo htmlspecialchars($reporte['torax'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="corazon" class="form-label">Corazón</label>
                        <textarea class="form-control" id="corazon" name="corazon" rows="2"><?php echo htmlspecialchars($reporte['corazon'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="abdomen" class="form-label">Abdomen</label>
                        <textarea class="form-control" id="abdomen" name="abdomen" rows="2"><?php echo htmlspecialchars($reporte['abdomen'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="extremidades" class="form-label">Extremidades</label>
                        <textarea class="form-control" id="extremidades" name="extremidades" rows="2"><?php echo htmlspecialchars($reporte['extremidades'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="liquido_amniotico" class="form-label">Líquido Amniótico</label>
                        <textarea class="form-control" id="liquido_amniotico" name="liquido_amniotico" rows="2"><?php echo htmlspecialchars($reporte['liquido_amniotico'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="decidua" class="form-label">Decidua</label>
                        <textarea class="form-control" id="decidua" name="decidua" rows="2"><?php echo htmlspecialchars($reporte['decidua'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cervix" class="form-label">Cérvix</label>
                        <textarea class="form-control" id="cervix" name="cervix" rows="2"><?php echo htmlspecialchars($reporte['cervix'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-clipboard-check me-2"></i> Estado
                </div>
                <div class="card-body">
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="activo" name="activo" <?php echo ($reporte['activo'] ?? 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="activo">Activo</label>
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado del Reporte</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="Pendiente" <?php echo ($reporte['estado'] ?? 'Pendiente') == 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                            <option value="En proceso" <?php echo ($reporte['estado'] ?? '') == 'En proceso' ? 'selected' : ''; ?>>En proceso</option>
                            <option value="Completado" <?php echo ($reporte['estado'] ?? '') == 'Completado' ? 'selected' : ''; ?>>Completado</option>
                            <option value="Archivado" <?php echo ($reporte['estado'] ?? '') == 'Archivado' ? 'selected' : ''; ?>>Archivado</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo Url::to('/reportes_1er_trimestre'); ?>" class="btn btn-apple btn-apple-secondary">Cancelar</a>
                <button type="submit" class="btn btn-apple btn-apple-primary">
                    <i class="fa-solid fa-save"></i> Actualizar
                </button>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const furInput = document.getElementById("fecha_ultima_regla");
    const fppFumInput = document.getElementById("fecha_probable_parto_fum");
    const fppUsgInput = document.getElementById("fecha_probable_parto_usg");

    function calcularFPP() {
        if (!furInput) return;
        const furValue = furInput.value;
        if (!furValue) return;

        const furDate = new Date(furValue + 'T00:00:00');
        const fppDate = new Date(furDate);
        fppDate.setDate(fppDate.getDate() + 280);
        const yyyy = fppDate.getFullYear();
        const mm = String(fppDate.getMonth() + 1).padStart(2, '0');
        const dd = String(fppDate.getDate()).padStart(2, '0');
        const fppStr = yyyy + '-' + mm + '-' + dd;

        if (fppFumInput) fppFumInput.value = fppStr;
        if (fppUsgInput) fppUsgInput.value = fppStr;
    }

    if (furInput) {
        furInput.addEventListener("change", calcularFPP);
    }

    calcularFPP();
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
