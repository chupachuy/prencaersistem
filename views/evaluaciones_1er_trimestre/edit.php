<?php
$title = "Editar Evaluación 1er Trimestre";
$meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
$fecha_hoy = date('j') . ' de ' . $meses[date('n')] . ' del ' . date('Y');
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$chk = function($val) { return ($val == 1 || $val === true) ? 'checked' : ''; };
$sel = function($val, $target) { return ($val == $target) ? 'selected' : ''; };
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/evaluaciones_1er_trimestre'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0">Editar: <?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></h1>
    </div>
</div>

<form action="<?php echo Url::to('/evaluaciones_1er_trimestre/update'); ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $evaluacion['id']; ?>">

    <!-- 1. Datos Generales -->
    <div class="card mb-4">
        <div class="card-header"><i class="fa-solid fa-id-card me-2"></i> Datos Generales</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Código de Reporte</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?>" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fecha_evaluacion" class="form-label">Fecha de Evaluación *</label>
                    <input type="date" class="form-control" id="fecha_evaluacion" name="fecha_evaluacion" value="<?php echo htmlspecialchars($evaluacion['fecha_evaluacion']); ?>" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fecha_estudio" class="form-label">Fecha de Estudio</label>
                    <input type="date" class="form-control" id="fecha_estudio" name="fecha_estudio" value="<?php echo htmlspecialchars($evaluacion['fecha_estudio'] ?? ''); ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="paciente_id" class="form-label">Paciente *</label>
                    <select class="form-select" id="paciente_id" name="paciente_id" required>
                        <option value="">Seleccione un paciente</option>
                        <?php foreach ($pacientes as $p): ?>
                            <option value="<?php echo $p['id']; ?>" <?php echo $sel($evaluacion['paciente_id'], $p['id']); ?>>
                                <?php echo htmlspecialchars($p['nombre'] . ' ' . $p['apellido']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="medico_id" class="form-label">Médico *</label>
                    <select class="form-select" id="medico_id" name="medico_id" required>
                        <option value="">Seleccione un médico</option>
                        <?php foreach ($medicos as $m): ?>
                            <option value="<?php echo $m['id']; ?>" <?php echo $sel($evaluacion['medico_id'], $m['id']); ?>>
                                <?php echo htmlspecialchars($m['nombre'] . ' ' . $m['apellido'] . ($m['especialidad'] ? ' - ' . $m['especialidad'] : '')); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Signos Vitales -->
    <div class="card mb-4">
        <div class="card-header"><i class="fa-solid fa-heart-pulse me-2"></i> Signos Vitales</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="peso_kg" class="form-label">Peso (kg)</label>
                    <input type="number" step="0.01" class="form-control" id="peso_kg" name="peso_kg" value="<?php echo htmlspecialchars($evaluacion['peso_kg'] ?? ''); ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="talla_cm" class="form-label">Talla (cm)</label>
                    <input type="number" step="0.01" class="form-control" id="talla_cm" name="talla_cm" value="<?php echo htmlspecialchars($evaluacion['talla_cm'] ?? ''); ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="ta_sistolica" class="form-label">TA Sistólica (mmHg)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="TA = Tensión Arterial (presión sistólica)"></i></label>
                    <input type="number" class="form-control" id="ta_sistolica" name="ta_sistolica" value="<?php echo htmlspecialchars($evaluacion['ta_sistolica'] ?? ''); ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="ta_diastolica" class="form-label">TA Diastólica (mmHg)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="TA = Tensión Arterial (presión diastólica)"></i></label>
                    <input type="number" class="form-control" id="ta_diastolica" name="ta_diastolica" value="<?php echo htmlspecialchars($evaluacion['ta_diastolica'] ?? ''); ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Datos Obstétricos -->
    <div class="card mb-4">
        <div class="card-header"><i class="fa-solid fa-calendar-check me-2"></i> Datos Obstétricos y Biometría Fetal</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="fum" class="form-label">FUM<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="FUM = Fecha de Última Menstruación, usada para calcular la edad gestacional"></i></label>
                    <input type="date" class="form-control" id="fum" name="fum" value="<?php echo htmlspecialchars($evaluacion['fum'] ?? ''); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fpp_usg" class="form-label">FPP por USG<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="FPP = Fecha Probable de Parto · USG = Ultrasonografía (ecografía)"></i></label>
                    <input type="date" class="form-control" id="fpp_usg" name="fpp_usg" value="<?php echo htmlspecialchars($evaluacion['fpp_usg'] ?? ''); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="edad_gestacional_semanas" class="form-label">Edad Gestacional (semanas)</label>
                    <input type="number" step="0.1" class="form-control" id="edad_gestacional_semanas" name="edad_gestacional_semanas" value="<?php echo htmlspecialchars($evaluacion['edad_gestacional_semanas'] ?? ''); ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="lcc_mm" class="form-label">LCC (mm)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="LCC = Longitud Cráneo-Caudal (medida del embrión de cabeza a cola)"></i></label>
                    <input type="number" step="0.01" class="form-control" id="lcc_mm" name="lcc_mm" value="<?php echo htmlspecialchars($evaluacion['lcc_mm'] ?? ''); ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="fcf_lpm" class="form-label">FCF (lpm)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="FCF = Frecuencia Cardíaca Fetal en latidos por minuto"></i></label>
                    <input type="number" class="form-control" id="fcf_lpm" name="fcf_lpm" value="<?php echo htmlspecialchars($evaluacion['fcf_lpm'] ?? ''); ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="estado_feto" class="form-label">Estado del Feto</label>
                    <select class="form-select" id="estado_feto" name="estado_feto">
                        <option value="Vivo" <?php echo $sel($evaluacion['estado_feto'], 'Vivo'); ?>>Vivo</option>
                        <option value="Muerto" <?php echo $sel($evaluacion['estado_feto'], 'Muerto'); ?>>Muerto</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="embarazo_multiple" name="embarazo_multiple" <?php echo $chk($evaluacion['embarazo_multiple']); ?>>
                        <label class="form-check-label" for="embarazo_multiple">Embarazo Múltiple</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Historial Clínico -->
    <div class="card mb-4">
        <div class="card-header"><i class="fa-solid fa-notes-medical me-2"></i> Historial Clínico</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="hipertension_cronica" <?php echo $chk($historial['hipertension_cronica'] ?? false); ?>><label class="form-check-label">Hipertensión Crónica</label></div></div>
                <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="diabetes" <?php echo $chk($historial['diabetes'] ?? false); ?>><label class="form-check-label">Diabetes</label></div></div>
                <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="lupus_les" <?php echo $chk($historial['lupus_les'] ?? false); ?>><label class="form-check-label">Lupus / LES</label></div></div>
                <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="sindrome_antifosfolipido_saf" <?php echo $chk($historial['sindrome_antifosfolipido_saf'] ?? false); ?>><label class="form-check-label">Síndrome Antifosfolípido (SAF) <i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="SAF = Síndrome Antifosfolípido, trastorno autoinmune que favorece trombosis en el embarazo"></i></label></div></div>
                <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="antecedente_preeclampsia_rciu" <?php echo $chk($historial['antecedente_preeclampsia_rciu'] ?? false); ?>><label class="form-check-label">Antecedente Preeclampsia / RCIU <i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="RCIU = Restricción del Crecimiento Intrauterino"></i></label></div></div>
                <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="fertilizacion_in_vitro" <?php echo $chk($historial['fertilizacion_in_vitro'] ?? false); ?>><label class="form-check-label">Fertilización In Vitro</label></div></div>
                <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="antecedente_parto_pretermino" <?php echo $chk($historial['antecedente_parto_pretermino'] ?? false); ?>><label class="form-check-label">Antecedente Parto Pretérmino</label></div></div>
            </div>
        </div>
    </div>

    <!-- 5. Anatomía Fetal -->
    <div class="card mb-4" id="card-anatomia">
        <div class="card-header"><i class="fa-solid fa-baby me-2"></i> Anatomía Fetal</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="estado_exploracion" class="form-label">Estado de Exploración</label>
                    <select class="form-select" id="estado_exploracion" name="estado_exploracion">
                        <option value="Completa" <?php echo $sel($anatomia['estado_exploracion'] ?? 'Completa', 'Completa'); ?>>Completa</option>
                        <option value="Incompleta" <?php echo $sel($anatomia['estado_exploracion'] ?? '', 'Incompleta'); ?>>Incompleta</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="torax_situs" class="form-label">Situs Torácico</label>
                    <select class="form-select" id="torax_situs" name="torax_situs">
                        <option value="Solitus" <?php echo $sel($anatomia['torax_situs'] ?? 'Solitus', 'Solitus'); ?>>Solitus</option>
                        <option value="Inversus" <?php echo $sel($anatomia['torax_situs'] ?? '', 'Inversus'); ?>>Inversus</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="torax_eje_cardiaco_grados" class="form-label">Eje Cardíaco (grados)</label>
                    <input type="number" class="form-control" id="torax_eje_cardiaco_grados" name="torax_eje_cardiaco_grados" value="<?php echo htmlspecialchars($anatomia['torax_eje_cardiaco_grados'] ?? ''); ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="snc_simetria_plexos" <?php echo $chk($anatomia['snc_simetria_plexos'] ?? true); ?>><label class="form-check-label">SNC: Simetría de Plexos <i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="SNC = Sistema Nervioso Central"></i></label></div></div>
                <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="macizo_facial_integro" <?php echo $chk($anatomia['macizo_facial_integro'] ?? true); ?>><label class="form-check-label">Macizo Facial Íntegro</label></div></div>
                <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="abdomen_camara_gastrica" <?php echo $chk($anatomia['abdomen_camara_gastrica'] ?? true); ?>><label class="form-check-label">Abdomen: Cámara Gástrica</label></div></div>
                <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="extremidades_completas" <?php echo $chk($anatomia['extremidades_completas'] ?? true); ?>><label class="form-check-label">Extremidades Completas</label></div></div>
            </div>
            <div class="mt-3">
                <label for="observaciones_anomalias" class="form-label">Observaciones / Anomalías</label>
                <textarea class="form-control" id="observaciones_anomalias" name="observaciones_anomalias" rows="2"><?php echo htmlspecialchars($anatomia['observaciones_anomalias'] ?? ''); ?></textarea>
            </div>
        </div>
    </div>

    <!-- 6. Marcadores FMF -->
    <div class="card mb-4" id="card-marcadores">
        <div class="card-header"><i class="fa-solid fa-ruler me-2"></i> Marcadores FMF</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="translucencia_nucal_mm" class="form-label">Translucencia Nucal (mm)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="TN = Acumulación de líquido en la nuca fetal; valor normal &lt;3mm entre 11-14 sem"></i></label>
                    <input type="number" step="0.01" class="form-control" id="translucencia_nucal_mm" name="translucencia_nucal_mm" value="<?php echo htmlspecialchars($marcadores['translucencia_nucal_mm'] ?? ''); ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="vejiga_fetal_mm" class="form-label">Vejiga Fetal (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="vejiga_fetal_mm" name="vejiga_fetal_mm" value="<?php echo htmlspecialchars($marcadores['vejiga_fetal_mm'] ?? ''); ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="uta_pi_promedio" class="form-label">UTA PI Promedio<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="UTA = Arterias Uterinas · PI = Índice de Pulsatilidad (resistencia vascular)"></i></label>
                    <input type="number" step="0.01" class="form-control" id="uta_pi_promedio" name="uta_pi_promedio" value="<?php echo htmlspecialchars($marcadores['uta_pi_promedio'] ?? ''); ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="ductus_venoso_onda_a" class="form-label">Ductus Venoso (Onda A)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="Ductus Venoso = vaso fetal que conecta la vena umbilical con la cava inferior; Onda A refleja función cardíaca"></i></label>
                    <select class="form-select" id="ductus_venoso_onda_a" name="ductus_venoso_onda_a">
                        <option value="" <?php echo $sel($marcadores['ductus_venoso_onda_a'] ?? '', ''); ?>>No evaluado</option>
                        <option value="Positiva" <?php echo $sel($marcadores['ductus_venoso_onda_a'] ?? '', 'Positiva'); ?>>Positiva</option>
                        <option value="Reversa" <?php echo $sel($marcadores['ductus_venoso_onda_a'] ?? '', 'Reversa'); ?>>Reversa</option>
                        <option value="Ausente" <?php echo $sel($marcadores['ductus_venoso_onda_a'] ?? '', 'Ausente'); ?>>Ausente</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="hueso_nasal_presente" <?php echo $chk($marcadores['hueso_nasal_presente'] ?? true); ?>><label class="form-check-label">Hueso Nasal Presente</label></div></div>
                <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="regurgitacion_tricuspidea_ausente" <?php echo $chk($marcadores['regurgitacion_tricuspidea_ausente'] ?? true); ?>><label class="form-check-label">Regurgitación Tricuspídea Ausente</label></div></div>
                <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="muesca_bilateral" <?php echo $chk($marcadores['muesca_bilateral'] ?? false); ?>><label class="form-check-label">Muesca Bilateral (A. Uterinas)</label></div></div>
            </div>
            <hr>
            <h6 class="text-muted">Marcadores Bioquímicos y Tamizaje Genético</h6>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="papp_a_mom" class="form-label">PAPP-A (MoM)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="PAPP-A = Proteína Plasmática A asociada al embarazo · MoM = Múltiplo de la Mediana"></i></label>
                    <input type="number" step="0.01" class="form-control" id="papp_a_mom" name="papp_a_mom" value="<?php echo htmlspecialchars($marcadores['papp_a_mom'] ?? ''); ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="plgf_mom" class="form-label">PLGF (MoM)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="PLGF = Factor de Crecimiento Placentario · MoM = Múltiplo de la Mediana"></i></label>
                    <input type="number" step="0.01" class="form-control" id="plgf_mom" name="plgf_mom" value="<?php echo htmlspecialchars($marcadores['plgf_mom'] ?? ''); ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="tamizaje_genetico_tipo" class="form-label">Tamizaje Genético</label>
                    <select class="form-select" id="tamizaje_genetico_tipo" name="tamizaje_genetico_tipo">
                        <option value="No realizado" <?php echo $sel($marcadores['tamizaje_genetico_tipo'] ?? 'No realizado', 'No realizado'); ?>>No realizado</option>
                        <option value="DNA Fetal" <?php echo $sel($marcadores['tamizaje_genetico_tipo'] ?? '', 'DNA Fetal'); ?>>DNA Fetal</option>
                        <option value="Combinado 1T" <?php echo $sel($marcadores['tamizaje_genetico_tipo'] ?? '', 'Combinado 1T'); ?>>Combinado 1T</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="tamizaje_genetico_resultado" class="form-label">Resultado Tamizaje</label>
                    <select class="form-select" id="tamizaje_genetico_resultado" name="tamizaje_genetico_resultado">
                        <option value="" <?php echo $sel($marcadores['tamizaje_genetico_resultado'] ?? '', ''); ?>>No evaluado</option>
                        <option value="Bajo Riesgo" <?php echo $sel($marcadores['tamizaje_genetico_resultado'] ?? '', 'Bajo Riesgo'); ?>>Bajo Riesgo</option>
                        <option value="Alto Riesgo" <?php echo $sel($marcadores['tamizaje_genetico_resultado'] ?? '', 'Alto Riesgo'); ?>>Alto Riesgo</option>
                        <option value="No concluyente" <?php echo $sel($marcadores['tamizaje_genetico_resultado'] ?? '', 'No concluyente'); ?>>No concluyente</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- 7. Entorno Materno -->
    <div class="card mb-4">
        <div class="card-header"><i class="fa-solid fa-uterus me-2"></i> Entorno Materno</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="liquido_amniotico" class="form-label">Líquido Amniótico</label>
                    <select class="form-select" name="liquido_amniotico">
                        <option value="Normal" <?php echo $sel($entorno['liquido_amniotico'] ?? 'Normal', 'Normal'); ?>>Normal</option>
                        <option value="Anormal" <?php echo $sel($entorno['liquido_amniotico'] ?? '', 'Anormal'); ?>>Anormal</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="placenta_posicion" class="form-label">Posición Placenta</label>
                    <select class="form-select" name="placenta_posicion">
                        <option value="">No evaluado</option>
                        <?php foreach (['Anterior','Posterior','Lateral Derecho','Lateral Izquierdo'] as $opt): ?>
                            <option value="<?php echo $opt; ?>" <?php echo $sel($entorno['placenta_posicion'] ?? '', $opt); ?>><?php echo $opt; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="placenta_insercion" class="form-label">Inserción Placenta</label>
                    <select class="form-select" name="placenta_insercion">
                        <option value="">No evaluado</option>
                        <?php foreach (['Normal','Baja Temprana','Previa Temprana'] as $opt): ?>
                            <option value="<?php echo $opt; ?>" <?php echo $sel($entorno['placenta_insercion'] ?? '', $opt); ?>><?php echo $opt; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="longitud_cervical_mm" class="form-label">Longitud Cervical (mm)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="Medida del cuello uterino por ecografía transvaginal; valor normal &gt;25mm"></i></label>
                    <input type="number" step="0.01" class="form-control" name="longitud_cervical_mm" value="<?php echo htmlspecialchars($entorno['longitud_cervical_mm'] ?? ''); ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="indice_consistencia_cervical_pct" class="form-label">Índice Consistencia Cervical (%)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="ICC = Ratio entre diámetro anteroposterior y longitudinal del cérvix"></i></label>
                    <input type="number" class="form-control" name="indice_consistencia_cervical_pct" value="<?php echo htmlspecialchars($entorno['indice_consistencia_cervical_pct'] ?? ''); ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="morfologia_uterina_eshre" class="form-label">Morfología Uterina (ESHRE)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="ESHRE-ESGE = Clasificación europea de anomalías uterinas (U0=Normal, U1-U6=anomalías)"></i></label>
                    <select class="form-select" name="morfologia_uterina_eshre">
                        <option value="">No evaluado</option>
                        <?php foreach (['U0','U1','U2','U3','U4','U5','U6'] as $opt): ?>
                            <option value="<?php echo $opt; ?>" <?php echo $sel($entorno['morfologia_uterina_eshre'] ?? '', $opt); ?>><?php echo $opt; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="miomas_visibles" <?php echo $chk($entorno['miomas_visibles'] ?? false); ?>>
                        <label class="form-check-label">Miomas Visibles</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="miomas_figo_tipo" class="form-label">Miomas (Tipo FIGO)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="FIGO = Clasificación internacional de miomas (0=submucoso, 8=subseroso pediculado)"></i></label>
                    <input type="text" class="form-control" name="miomas_figo_tipo" value="<?php echo htmlspecialchars($entorno['miomas_figo_tipo'] ?? ''); ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- 8. Impresión Diagnóstica -->
    <div class="card mb-4" id="card-riesgos">
        <div class="card-header"><i class="fa-solid fa-clipboard-check me-2"></i> Impresión Diagnóstica</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="riesgo_basal_cromosomopatias" class="form-label">Riesgo Basal Cromosomopatías</label>
                    <input type="text" class="form-control" name="riesgo_basal_cromosomopatias" value="<?php echo htmlspecialchars($diagnostica['riesgo_basal_cromosomopatias'] ?? ''); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="riesgo_ajustado_cromosomopatias" class="form-label">Riesgo Ajustado Cromosomopatías</label>
                    <input type="text" class="form-control" name="riesgo_ajustado_cromosomopatias" value="<?php echo htmlspecialchars($diagnostica['riesgo_ajustado_cromosomopatias'] ?? ''); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="probabilidad_cromosomopatias" class="form-label">Probabilidad Cromosomopatías</label>
                    <select class="form-select" name="probabilidad_cromosomopatias">
                        <option value="">No evaluado</option>
                        <?php foreach (['Baja','Intermedia','Alta'] as $opt): ?>
                            <option value="<?php echo $opt; ?>" <?php echo $sel($diagnostica['probabilidad_cromosomopatias'] ?? '', $opt); ?>><?php echo $opt; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="riesgo_preeclampsia_temprana" class="form-label">Riesgo Preeclampsia Temprana</label>
                    <select class="form-select" name="riesgo_preeclampsia_temprana">
                        <option value="">No evaluado</option>
                        <?php foreach (['Baja','Alta'] as $opt): ?>
                            <option value="<?php echo $opt; ?>" <?php echo $sel($diagnostica['riesgo_preeclampsia_temprana'] ?? '', $opt); ?>><?php echo $opt; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="riesgo_enfermedad_placentaria_tardia" class="form-label">Riesgo Enf. Placentaria Tardía</label>
                    <select class="form-select" name="riesgo_enfermedad_placentaria_tardia">
                        <option value="">No evaluado</option>
                        <?php foreach (['Baja','Alta'] as $opt): ?>
                            <option value="<?php echo $opt; ?>" <?php echo $sel($diagnostica['riesgo_enfermedad_placentaria_tardia'] ?? '', $opt); ?>><?php echo $opt; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="riesgo_parto_pretermino" class="form-label">Riesgo Parto Pretérmino</label>
                    <select class="form-select" name="riesgo_parto_pretermino">
                        <option value="">No evaluado</option>
                        <?php foreach (['Bajo','Alto'] as $opt): ?>
                            <option value="<?php echo $opt; ?>" <?php echo $sel($diagnostica['riesgo_parto_pretermino'] ?? '', $opt); ?>><?php echo $opt; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Imágenes del Estudio -->
    <div class="card mb-4">
        <div class="card-header"><i class="fa-solid fa-images me-2"></i> Imágenes del Estudio</div>
        <div class="card-body">
            <?php if (!empty($imagenes)): ?>
            <div class="mb-3">
                <h6 class="text-muted small mb-2">Imágenes actuales (clic en × para eliminar)</h6>
                <div class="row g-2" id="existingImages">
                    <?php foreach ($imagenes as $img): ?>
                    <div class="col-auto position-relative" data-img-id="<?php echo $img['id']; ?>">
                        <img src="<?php echo Url::to($img['ruta_imagen']); ?>" class="rounded" style="width:120px;height:120px;object-fit:cover;">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-1 bg-white rounded-circle p-1 shadow-sm delete-existing" style="font-size:10px;width:20px;height:20px;" data-id="<?php echo $img['id']; ?>"></button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="upload-zone border border-2 border-dashed rounded-3 p-4 text-center" id="uploadZone" style="border-color:#ccc!important;cursor:pointer;">
                <i class="fa-solid fa-cloud-arrow-up fa-2x text-muted mb-3 d-block"></i>
                <p class="text-muted mb-1">Arrastra imágenes o haz clic para seleccionar</p>
                <small class="text-muted">Máximo 10 imágenes · 5 MB por imagen · JPG, PNG</small>
            </div>
            <input type="file" id="imagenesInput" name="imagenes[]" multiple accept="image/jpeg,image/png" style="display:none;">
            <input type="hidden" id="imagenesEliminar" name="imagenes_eliminar" value="">
            <div class="row mt-3 g-2" id="previewGrid"></div>
            <div id="uploadCount" class="text-muted mt-2 small" style="display:none;"></div>
        </div>
    </div>

    <!-- Estado y Submit -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label for="estado" class="form-label">Estado del Reporte</label>
                    <select class="form-select" name="estado">
                        <?php foreach (['Pendiente','En proceso','Completado','Archivado'] as $opt): ?>
                            <option value="<?php echo $opt; ?>" <?php echo $sel($evaluacion['estado'] ?? 'Pendiente', $opt); ?>><?php echo $opt; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-8 text-end">
                    <form action="<?php echo Url::to('/evaluaciones_1er_trimestre/delete'); ?>" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta evaluación?');">
                        <input type="hidden" name="id" value="<?php echo $evaluacion['id']; ?>">
                        <button type="submit" class="btn btn-apple btn-apple-danger me-2"><i class="fa-solid fa-trash"></i> Eliminar</button>
                    </form>
                    <a href="<?php echo Url::to('/evaluaciones_1er_trimestre'); ?>" class="btn btn-apple btn-apple-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-apple btn-apple-primary btn-lg"><i class="fa-solid fa-save"></i> Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const fumInput = document.getElementById("fum");
    const fechaEvalInput = document.getElementById("fecha_evaluacion");
    const egInput = document.getElementById("edad_gestacional_semanas");
    const fppUsgInput = document.getElementById("fpp_usg");

    const estadoFetoSelect = document.getElementById("estado_feto");
    const fcfInput = document.getElementById("fcf_lpm");
    const cardAnatomia = document.getElementById("card-anatomia");
    const cardMarcadores = document.getElementById("card-marcadores");
    const cardRiesgos = document.getElementById("card-riesgos");

    // Crear banner de alerta dinámico
    const alertDiv = document.createElement("div");
    alertDiv.className = "alert alert-warning d-none mt-3 border-start border-warning border-4 shadow-sm";
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center gap-3">
            <i class="fa-solid fa-circle-exclamation fs-3 text-warning"></i>
            <div>
                <h5 class="alert-heading mb-1 fw-bold">Óbito Fetal Detectado</h5>
                <p class="mb-0 text-secondary">El estado del feto ha sido configurado como <strong>Muerto</strong>. Las secciones de anatomía fetal avanzada, marcadores genéticos FMF y semáforos de riesgo han sido omitidos para este reporte. La FCF ha sido fijada en 0.</p>
            </div>
        </div>
    `;

    if (estadoFetoSelect) {
        const obsCardBody = estadoFetoSelect.closest(".card-body");
        if (obsCardBody) {
            obsCardBody.appendChild(alertDiv);
        }
    }

    function evaluarEstadoFeto() {
        if (!estadoFetoSelect) return;
        
        const estado = estadoFetoSelect.value;
        if (estado === "Muerto") {
            if (fcfInput) {
                fcfInput.value = "0";
                fcfInput.setAttribute("readonly", true);
            }
            if (cardAnatomia) cardAnatomia.classList.add("d-none");
            if (cardMarcadores) cardMarcadores.classList.add("d-none");
            if (cardRiesgos) cardRiesgos.classList.add("d-none");
            alertDiv.classList.remove("d-none");
        } else {
            if (fcfInput) {
                fcfInput.removeAttribute("readonly");
                if (fcfInput.value === "0") {
                    fcfInput.value = "";
                }
            }
            if (cardAnatomia) cardAnatomia.classList.remove("d-none");
            if (cardMarcadores) cardMarcadores.classList.remove("d-none");
            if (cardRiesgos) cardRiesgos.classList.remove("d-none");
            alertDiv.classList.add("d-none");
        }
    }

    function calcularEdadGestacional() {
        if (!fumInput) return;

        const fumValue = fumInput.value;

        if (fumValue && fppUsgInput) {
            const fumDate = new Date(fumValue + 'T00:00:00');
            const fppDate = new Date(fumDate);
            fppDate.setDate(fppDate.getDate() + 280);
            const yyyy = fppDate.getFullYear();
            const mm = String(fppDate.getMonth() + 1).padStart(2, '0');
            const dd = String(fppDate.getDate()).padStart(2, '0');
            fppUsgInput.value = yyyy + '-' + mm + '-' + dd;
        }

        if (!fechaEvalInput || !egInput) return;
        const fechaEvalValue = fechaEvalInput.value;

        if (fumValue && fechaEvalValue) {
            const fumDate = new Date(fumValue + 'T00:00:00');
            const evalDate = new Date(fechaEvalValue + 'T00:00:00');
            
            const diffTime = evalDate - fumDate;
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays >= 0) {
                const weeks = Math.floor(diffDays / 7);
                const days = diffDays % 7;
                const edadGestacional = (weeks + days / 10).toFixed(1);
                egInput.value = edadGestacional;
            } else {
                egInput.value = "";
            }
        }
    }

    if (fumInput) {
        fumInput.addEventListener("change", calcularEdadGestacional);
    }
    if (fechaEvalInput) {
        fechaEvalInput.addEventListener("change", calcularEdadGestacional);
    }
    if (estadoFetoSelect) {
        estadoFetoSelect.addEventListener("change", evaluarEstadoFeto);
    }
    
    // Ejecutar cálculos e inspecciones iniciales
    calcularEdadGestacional();
    evaluarEstadoFeto();

    // Uploader de imágenes
    const uploadZone = document.getElementById('uploadZone');
    const imgInput = document.getElementById('imagenesInput');
    const previewGrid = document.getElementById('previewGrid');
    const uploadCount = document.getElementById('uploadCount');
    const eliminarInput = document.getElementById('imagenesEliminar');
    let selectedFiles = [];
    let deletedIds = [];

    if (uploadZone && imgInput) {
        uploadZone.addEventListener('click', () => imgInput.click());
        uploadZone.addEventListener('dragover', (e) => { e.preventDefault(); uploadZone.style.borderColor = '#999'; });
        uploadZone.addEventListener('dragleave', () => { uploadZone.style.borderColor = '#ccc'; });
        uploadZone.addEventListener('drop', (e) => { e.preventDefault(); uploadZone.style.borderColor = '#ccc'; handleFiles(e.dataTransfer.files); });
        imgInput.addEventListener('change', () => handleFiles(imgInput.files));
    }

    // Manejar eliminación de imágenes existentes
    document.querySelectorAll('.delete-existing').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = parseInt(this.dataset.id);
            if (id) { deletedIds.push(id); eliminarInput.value = deletedIds.join(','); }
            this.closest('.col-auto').remove();
        });
    });

    function handleFiles(files) {
        const total = selectedFiles.length + (document.querySelectorAll('#existingImages .col-auto').length || 0) - deletedIds.length;
        if (total >= 10) { alert('Máximo 10 imágenes en total.'); return; }
        for (let f of files) {
            if (total + selectedFiles.length >= 10) break;
            if (f.size > 5 * 1024 * 1024) { alert('La imagen ' + f.name + ' excede 5 MB.'); continue; }
            if (!['image/jpeg','image/png'].includes(f.type)) { alert(f.name + ' no es JPG/PNG.'); continue; }
            selectedFiles.push(f);
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-auto position-relative';
                col.innerHTML = '<img src="' + e.target.result + '" class="rounded" style="width:120px;height:120px;object-fit:cover;"><button type="button" class="btn-close position-absolute top-0 end-0 m-1 bg-white rounded-circle p-1 shadow-sm" style="font-size:10px;width:20px;height:20px;"></button>';
                previewGrid.appendChild(col);
                col.querySelector('.btn-close').addEventListener('click', function() {
                    const i = selectedFiles.indexOf(f);
                    if (i > -1) { selectedFiles.splice(i, 1); }
                    col.remove();
                    updateCount();
                    syncFileInput();
                });
                updateCount();
            };
            reader.readAsDataURL(f);
        }
        syncFileInput();
    }

    function syncFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        imgInput.files = dt.files;
    }

    function updateCount() {
        if (selectedFiles.length > 0) {
            uploadCount.style.display = 'block';
            uploadCount.textContent = selectedFiles.length + ' imagen(es) nueva(s) seleccionada(s)';
        } else {
            uploadCount.style.display = 'none';
        }
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
