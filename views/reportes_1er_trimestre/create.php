<?php
$title = "Nuevo Reporte 1er Trimestre";
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
        <h1 class="page-title mb-0">Nuevo Reporte 1er Trimestre</h1>
    </div>
    <div class="page-header-actions">
        <span class="text-muted">
            <i class="fa-regular fa-calendar me-1"></i>
            <?php echo $fecha_hoy; ?>
        </span>
    </div>
</div>

<form action="<?php echo Url::to('/reportes_1er_trimestre/store'); ?>" method="POST">
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-id-card me-2"></i> Datos del Reporte
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="codigo_reporte" class="form-label">Código del Reporte</label>
                        <input type="text" class="form-control" id="codigo_reporte" name="codigo_reporte" value="<?php echo $codigo_reporte ?? ''; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_reporte" class="form-label">Fecha del Reporte</label>
                        <input type="date" class="form-control" id="fecha_reporte" name="fecha_reporte" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="lugar" class="form-label">Lugar</label>
                        <input type="text" class="form-control" id="lugar" name="lugar" placeholder="Lugar de realización">
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
                                <option value="<?php echo $paciente['id']; ?>" <?php echo (isset($paciente_id) && $paciente_id == $paciente['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellido']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="medico_id" class="form-label">Médico *</label>
                        <select class="form-select" id="medico_id" name="medico_id" required>
                            <option value="">Seleccionar médico...</option>
                            <?php foreach ($medicos as $medico): ?>
                                <option value="<?php echo $medico['id']; ?>">
                                    <?php echo htmlspecialchars($medico['nombre'] . ' ' . $medico['apellido']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="medico_referido_id" class="form-label">Médico Referido</label>
                        <select class="form-select" id="medico_referido_id" name="medico_referido_id">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($medicos as $medico): ?>
                                <option value="<?php echo $medico['id']; ?>">
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
                            <input type="number" step="0.01" class="form-control" id="peso" name="peso" placeholder="0.00">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="talla" class="form-label">Talla (cm)</label>
                            <input type="number" step="0.01" class="form-control" id="talla" name="talla" placeholder="0.00">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="presion_sistolica" class="form-label">Presión Sistólica (mmHg)</label>
                            <input type="number" class="form-control" id="presion_sistolica" name="presion_sistolica" placeholder="120">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="presion_diastolica" class="form-label">Presión Diastólica (mmHg)</label>
                            <input type="number" class="form-control" id="presion_diastolica" name="presion_diastolica" placeholder="80">
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
                            <input type="number" class="form-control" id="gesta" name="gesta" placeholder="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="para" class="form-label">Para <small class="text-muted">(partos vaginales)</small></label>
                            <input type="number" class="form-control" id="para" name="para" placeholder="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="abortos" class="form-label">Abortos</label>
                            <input type="number" class="form-control" id="abortos" name="abortos" placeholder="0">
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
                        <input type="date" class="form-control" id="fecha_ultima_regla" name="fecha_ultima_regla">
                    </div>
                    <div class="mb-3">
                        <label for="edad_gestacional_fum" class="form-label">Edad Gestacional por FUR (semanas)</label>
                        <input type="number" step="0.1" class="form-control" id="edad_gestacional_fum" name="edad_gestacional_fum" placeholder="0.0">
                    </div>
                    <div class="mb-3">
                        <label for="fecha_probable_parto_fum" class="form-label">FPP por FUR</label>
                        <input type="date" class="form-control" id="fecha_probable_parto_fum" name="fecha_probable_parto_fum">
                    </div>
                    <div class="mb-3">
                        <label for="equipo_estudio" class="form-label">Datos del Equipo de Estudio</label>
                        <textarea class="form-control" id="equipo_estudio" name="equipo_estudio" rows="3" maxlength="500" placeholder="Se realizó estudio ultrasonográfico de alta definición, utilizando un equipo..."></textarea>
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
                        <input type="number" step="0.01" class="form-control" id="longitud_craneo_cauda" name="longitud_craneo_cauda" placeholder="0.00">
                    </div>
                    <div class="mb-3">
                        <label for="edad_gestacional_usg" class="form-label">Edad Gestacional por USG (semanas)</label>
                        <input type="number" step="0.1" class="form-control" id="edad_gestacional_usg" name="edad_gestacional_usg" placeholder="0.0">
                    </div>
                    <div class="mb-3">
                        <label for="fecha_probable_parto_usg" class="form-label">Fecha Probable de Parto calculada por Ultrasonido</label>
                        <input type="date" class="form-control" id="fecha_probable_parto_usg" name="fecha_probable_parto_usg">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="equipo_usg" class="form-label">Equipo de Ultrasonografía</label>
                            <input type="text" class="form-control" id="equipo_usg" name="equipo_usg" placeholder="Equipo utilizado">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="transductor_tipo" class="form-label">Tipo de Transductor</label>
                            <input type="text" class="form-control" id="transductor_tipo" name="transductor_tipo" placeholder="Tipo de transductor">
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
                        <textarea class="form-control" id="craneo" name="craneo" rows="2" placeholder="Forma normal, se observó línea media y plexos coroides que llenan ambos ventrículos. Sin pérdida de continuidad en los huesos del cráneo."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="sistema_nervioso_central" class="form-label">Sistema Nervioso Central</label>
                        <textarea class="form-control" id="sistema_nervioso_central" name="sistema_nervioso_central" rows="2" placeholder="Se logra visualizar, en el corte sagital, el 3er y 4to Ventrículo, así como la Cisterna Magna, que aparentan normalidad."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cuello" class="form-label">Cuello</label>
                        <textarea class="form-control" id="cuello" name="cuello" rows="2" placeholder="De apariencia normal, con Translucencia Nucal de..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cara" class="form-label">Cara</label>
                        <textarea class="form-control" id="cara" name="cara" rows="2" placeholder="En corte sagital se observó perfil normal, ambas órbitas y cristalinos presentes normales. Hueso nasal normal."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="columna" class="form-label">Columna</label>
                        <textarea class="form-control" id="columna" name="columna" rows="2" placeholder="Unión cráneo vertebral normal, curvatura normal, movilidad normal."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="torax" class="form-label">Tórax</label>
                        <textarea class="form-control" id="torax" name="torax" rows="2" placeholder="Área pulmonar integra, sin derrames o masas visibles."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="corazon" class="form-label">Corazón</label>
                        <textarea class="form-control" id="corazon" name="corazon" rows="2" placeholder="Frecuencia cardiaca presente de... latidos por minuto. Área pulmonar íntegra. Curvaturas costales normales. Se visualizan las cuatro cámaras."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="abdomen" class="form-label">Abdomen</label>
                        <textarea class="form-control" id="abdomen" name="abdomen" rows="2" placeholder="Cámara gástrica presente en el cuadrante superior izquierdo del abdomen. Pared abdominal íntegra."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="extremidades" class="form-label">Extremidades</label>
                        <textarea class="form-control" id="extremidades" name="extremidades" rows="2" placeholder="Se observan cuatro extremidades simétricas y móviles; cada una con tres segmentos."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="liquido_amniotico" class="form-label">Líquido Amniótico</label>
                        <textarea class="form-control" id="liquido_amniotico" name="liquido_amniotico" rows="2" placeholder="Cualitativamente normal."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="decidua" class="form-label">Decidua</label>
                        <textarea class="form-control" id="decidua" name="decidua" rows="2" placeholder="Corporal Posterior. Sin zonas de desprendimiento, de apariencia homogénea."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cervix" class="form-label">Cérvix</label>
                        <textarea class="form-control" id="cervix" name="cervix" rows="2" placeholder="De apariencia normal, longitud cervical (38 mm)."></textarea>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-clipboard-check me-2"></i> Estado
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado del Reporte</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="Pendiente">Pendiente</option>
                            <option value="En proceso">En proceso</option>
                            <option value="Completado">Completado</option>
                            <option value="Archivado">Archivado</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo Url::to('/reportes_1er_trimestre'); ?>" class="btn btn-apple btn-apple-secondary">Cancelar</a>
                <button type="submit" class="btn btn-apple btn-apple-primary">
                    <i class="fa-solid fa-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
