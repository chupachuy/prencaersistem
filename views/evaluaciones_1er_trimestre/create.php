<?php
$title = "Nueva Evaluación 1er Trimestre";
$meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
$fecha_hoy = date('j') . ' de ' . $meses[date('n')] . ' del ' . date('Y');
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/evaluaciones_1er_trimestre'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0">Nueva Evaluación 1er Trimestre</h1>
    </div>
    <div class="page-header-actions">
        <span class="text-muted"><i class="fa-regular fa-calendar me-1"></i><?php echo $fecha_hoy; ?></span>


<form action="<?php echo Url::to('/evaluaciones_1er_trimestre/store'); ?>" method="POST" id="formEvaluacion">
    <input type="hidden" name="codigo_reporte" value="<?php echo htmlspecialchars($codigo_reporte); ?>">

    <!-- 1. Datos Generales -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-id-card me-2"></i> Datos Generales
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Código de Reporte</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($codigo_reporte); ?>" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fecha_evaluacion" class="form-label">Fecha de Evaluación *</label>
                    <input type="date" class="form-control" id="fecha_evaluacion" name="fecha_evaluacion" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fecha_estudio" class="form-label">Fecha de Estudio</label>
                    <input type="date" class="form-control" id="fecha_estudio" name="fecha_estudio">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="paciente_id" class="form-label">Paciente *</label>
                    <select class="form-select" id="paciente_id" name="paciente_id" required>
                        <option value="">Seleccione un paciente</option>
                        <?php foreach ($pacientes as $p): ?>
                            <option value="<?php echo $p['id']; ?>" <?php echo ($paciente_id == $p['id']) ? 'selected' : ''; ?>>
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
                            <option value="<?php echo $m['id']; ?>">
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
        <div class="card-header">
            <i class="fa-solid fa-heart-pulse me-2"></i> Signos Vitales
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="peso_kg" class="form-label">Peso (kg)</label>
                    <input type="number" step="0.01" class="form-control" id="peso_kg" name="peso_kg" placeholder="Ej: 65.5">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="talla_cm" class="form-label">Talla (cm)</label>
                    <input type="number" step="0.01" class="form-control" id="talla_cm" name="talla_cm" placeholder="Ej: 160">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="ta_sistolica" class="form-label">TA Sistólica (mmHg)</label>
                    <input type="number" class="form-control" id="ta_sistolica" name="ta_sistolica" placeholder="Ej: 120">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="ta_diastolica" class="form-label">TA Diastólica (mmHg)</label>
                    <input type="number" class="form-control" id="ta_diastolica" name="ta_diastolica" placeholder="Ej: 80">
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Datos Obstétricos -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-calendar-check me-2"></i> Datos Obstétricos y Biometría Fetal
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="fum" class="form-label">FUM (Fecha Última Regla)</label>
                    <input type="date" class="form-control" id="fum" name="fum">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fpp_usg" class="form-label">FPP por USG</label>
                    <input type="date" class="form-control" id="fpp_usg" name="fpp_usg">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="edad_gestacional_semanas" class="form-label">Edad Gestacional (semanas)</label>
                    <input type="number" step="0.1" class="form-control" id="edad_gestacional_semanas" name="edad_gestacional_semanas" placeholder="Ej: 12.3">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="lcc_mm" class="form-label">LCC (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="lcc_mm" name="lcc_mm" placeholder="Longitud Cráneo Cauda">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="fcf_lpm" class="form-label">FCF (lpm)</label>
                    <input type="number" class="form-control" id="fcf_lpm" name="fcf_lpm" placeholder="Frecuencia Cardiaca Fetal">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="estado_feto" class="form-label">Estado del Feto</label>
                    <select class="form-select" id="estado_feto" name="estado_feto">
                        <option value="Vivo">Vivo</option>
                        <option value="Muerto">Muerto</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="embarazo_multiple" name="embarazo_multiple">
                        <label class="form-check-label" for="embarazo_multiple">Embarazo Múltiple</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Historial Clínico -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-notes-medical me-2"></i> Historial Clínico (Antecedentes)
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="hipertension_cronica" name="hipertension_cronica" <?php echo (!empty($historial) && $historial['hipertension_cronica']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="hipertension_cronica">Hipertensión Crónica</label>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="diabetes" name="diabetes" <?php echo (!empty($historial) && $historial['diabetes']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="diabetes">Diabetes</label>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="lupus_les" name="lupus_les" <?php echo (!empty($historial) && $historial['lupus_les']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="lupus_les">Lupus / LES</label>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="sindrome_antifosfolipido_saf" name="sindrome_antifosfolipido_saf" <?php echo (!empty($historial) && $historial['sindrome_antifosfolipido_saf']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="sindrome_antifosfolipido_saf">Síndrome Antifosfolípido (SAF)</label>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="antecedente_preeclampsia_rciu" name="antecedente_preeclampsia_rciu" <?php echo (!empty($historial) && $historial['antecedente_preeclampsia_rciu']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="antecedente_preeclampsia_rciu">Antecedente Preeclampsia / RCIU</label>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="fertilizacion_in_vitro" name="fertilizacion_in_vitro" <?php echo (!empty($historial) && $historial['fertilizacion_in_vitro']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="fertilizacion_in_vitro">Fertilización In Vitro</label>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="antecedente_parto_pretermino" name="antecedente_parto_pretermino" <?php echo (!empty($historial) && $historial['antecedente_parto_pretermino']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="antecedente_parto_pretermino">Antecedente Parto Pretérmino</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Anatomía Fetal -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-baby me-2"></i> Anatomía Fetal
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="estado_exploracion" class="form-label">Estado de Exploración</label>
                    <select class="form-select" id="estado_exploracion" name="estado_exploracion">
                        <option value="Completa">Completa</option>
                        <option value="Incompleta">Incompleta</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="torax_situs" class="form-label">Situs Torácico</label>
                    <select class="form-select" id="torax_situs" name="torax_situs">
                        <option value="Solitus">Solitus</option>
                        <option value="Inversus">Inversus</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="torax_eje_cardiaco_grados" class="form-label">Eje Cardíaco (grados)</label>
                    <input type="number" class="form-control" id="torax_eje_cardiaco_grados" name="torax_eje_cardiaco_grados" placeholder="Ej: 45">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="snc_simetria_plexos" name="snc_simetria_plexos" checked>
                        <label class="form-check-label" for="snc_simetria_plexos">SNC: Simetría de Plexos (Signo de Mariposa)</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="macizo_facial_integro" name="macizo_facial_integro" checked>
                        <label class="form-check-label" for="macizo_facial_integro">Macizo Facial Íntegro</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="abdomen_camara_gastrica" name="abdomen_camara_gastrica" checked>
                        <label class="form-check-label" for="abdomen_camara_gastrica">Abdomen: Cámara Gástrica Visible</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="extremidades_completas" name="extremidades_completas" checked>
                        <label class="form-check-label" for="extremidades_completas">Extremidades Completas</label>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <label for="observaciones_anomalias" class="form-label">Observaciones / Anomalías</label>
                <textarea class="form-control" id="observaciones_anomalias" name="observaciones_anomalias" rows="2" placeholder="Describa hallazgos anormales..."></textarea>
            </div>
        </div>
    </div>

    <!-- 6. Marcadores FMF -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-ruler me-2"></i> Marcadores FMF (Fetal Medicine Foundation)
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="translucencia_nucal_mm" class="form-label">Translucencia Nucal (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="translucencia_nucal_mm" name="translucencia_nucal_mm" placeholder="Ej: 1.5">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="vejiga_fetal_mm" class="form-label">Vejiga Fetal (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="vejiga_fetal_mm" name="vejiga_fetal_mm" placeholder="Ej: 5.0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="uta_pi_promedio" class="form-label">UTA PI Promedio</label>
                    <input type="number" step="0.01" class="form-control" id="uta_pi_promedio" name="uta_pi_promedio" placeholder="Índice Pulsatilidad A. Uterinas">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="ductus_venoso_onda_a" class="form-label">Ductus Venoso (Onda A)</label>
                    <select class="form-select" id="ductus_venoso_onda_a" name="ductus_venoso_onda_a">
                        <option value="">No evaluado</option>
                        <option value="Positiva">Positiva</option>
                        <option value="Reversa">Reversa</option>
                        <option value="Ausente">Ausente</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="hueso_nasal_presente" name="hueso_nasal_presente" checked>
                        <label class="form-check-label" for="hueso_nasal_presente">Hueso Nasal Presente</label>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="regurgitacion_tricuspidea_ausente" name="regurgitacion_tricuspidea_ausente" checked>
                        <label class="form-check-label" for="regurgitacion_tricuspidea_ausente">Regurgitación Tricuspídea Ausente</label>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="muesca_bilateral" name="muesca_bilateral">
                        <label class="form-check-label" for="muesca_bilateral">Muesca Bilateral (A. Uterinas)</label>
                    </div>
                </div>
            </div>
            <hr>
            <h6 class="text-muted">Marcadores Bioquímicos y Tamizaje Genético</h6>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="papp_a_mom" class="form-label">PAPP-A (MoM)</label>
                    <input type="number" step="0.01" class="form-control" id="papp_a_mom" name="papp_a_mom" placeholder="Ej: 1.05">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="plgf_mom" class="form-label">PLGF (MoM)</label>
                    <input type="number" step="0.01" class="form-control" id="plgf_mom" name="plgf_mom" placeholder="Ej: 0.95">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="tamizaje_genetico_tipo" class="form-label">Tamizaje Genético</label>
                    <select class="form-select" id="tamizaje_genetico_tipo" name="tamizaje_genetico_tipo">
                        <option value="No realizado">No realizado</option>
                        <option value="DNA Fetal">DNA Fetal</option>
                        <option value="Combinado 1T">Combinado 1T</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="tamizaje_genetico_resultado" class="form-label">Resultado Tamizaje</label>
                    <select class="form-select" id="tamizaje_genetico_resultado" name="tamizaje_genetico_resultado">
                        <option value="">No evaluado</option>
                        <option value="Bajo Riesgo">Bajo Riesgo</option>
                        <option value="Alto Riesgo">Alto Riesgo</option>
                        <option value="No concluyente">No concluyente</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- 7. Entorno Materno -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-uterus me-2"></i> Entorno Materno (Útero-Placentario)
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="liquido_amniotico" class="form-label">Líquido Amniótico</label>
                    <select class="form-select" id="liquido_amniotico" name="liquido_amniotico">
                        <option value="Normal">Normal</option>
                        <option value="Anormal">Anormal</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="placenta_posicion" class="form-label">Posición Placenta</label>
                    <select class="form-select" id="placenta_posicion" name="placenta_posicion">
                        <option value="">No evaluado</option>
                        <option value="Anterior">Anterior</option>
                        <option value="Posterior">Posterior</option>
                        <option value="Lateral Derecho">Lateral Derecho</option>
                        <option value="Lateral Izquierdo">Lateral Izquierdo</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="placenta_insercion" class="form-label">Inserción Placenta</label>
                    <select class="form-select" id="placenta_insercion" name="placenta_insercion">
                        <option value="">No evaluado</option>
                        <option value="Normal">Normal</option>
                        <option value="Baja Temprana">Baja Temprana</option>
                        <option value="Previa Temprana">Previa Temprana</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="longitud_cervical_mm" class="form-label">Longitud Cervical (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="longitud_cervical_mm" name="longitud_cervical_mm" placeholder="Ej: 35.0">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="indice_consistencia_cervical_pct" class="form-label">Índice Consistencia Cervical (%)</label>
                    <input type="number" class="form-control" id="indice_consistencia_cervical_pct" name="indice_consistencia_cervical_pct" placeholder="Ej: 85">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="morfologia_uterina_eshre" class="form-label">Morfología Uterina (ESHRE-ESGE)</label>
                    <select class="form-select" id="morfologia_uterina_eshre" name="morfologia_uterina_eshre">
                        <option value="">No evaluado</option>
                        <option value="U0">U0 - Normal</option>
                        <option value="U1">U1 - Útero dismórfico</option>
                        <option value="U2">U2 - Útero septado</option>
                        <option value="U3">U3 - Útero bicorpóreo</option>
                        <option value="U4">U4 - Hemi-útero</option>
                        <option value="U5">U5 - Útero aplásico</option>
                        <option value="U6">U6 - Malformaciones no clasificadas</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="miomas_visibles" name="miomas_visibles">
                        <label class="form-check-label" for="miomas_visibles">Miomas Visibles</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="miomas_figo_tipo" class="form-label">Miomas (Tipo FIGO)</label>
                    <input type="text" class="form-control" id="miomas_figo_tipo" name="miomas_figo_tipo" placeholder="Ej: Tipo 3, Tipo 5">
                </div>
            </div>
        </div>
    </div>

    <!-- 8. Impresión Diagnóstica -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-clipboard-check me-2"></i> Impresión Diagnóstica (Semáforos de Riesgo)
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="riesgo_basal_cromosomopatias" class="form-label">Riesgo Basal Cromosomopatías</label>
                    <input type="text" class="form-control" id="riesgo_basal_cromosomopatias" name="riesgo_basal_cromosomopatias" placeholder="Ej: 1:250">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="riesgo_ajustado_cromosomopatias" class="form-label">Riesgo Ajustado Cromosomopatías</label>
                    <input type="text" class="form-control" id="riesgo_ajustado_cromosomopatias" name="riesgo_ajustado_cromosomopatias" placeholder="Ej: 1:1500">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="probabilidad_cromosomopatias" class="form-label">Probabilidad Cromosomopatías</label>
                    <select class="form-select" id="probabilidad_cromosomopatias" name="probabilidad_cromosomopatias">
                        <option value="">No evaluado</option>
                        <option value="Baja">Baja</option>
                        <option value="Intermedia">Intermedia</option>
                        <option value="Alta">Alta</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="riesgo_preeclampsia_temprana" class="form-label">Riesgo Preeclampsia Temprana</label>
                    <select class="form-select" id="riesgo_preeclampsia_temprana" name="riesgo_preeclampsia_temprana">
                        <option value="">No evaluado</option>
                        <option value="Baja">Baja</option>
                        <option value="Alta">Alta</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="riesgo_enfermedad_placentaria_tardia" class="form-label">Riesgo Enf. Placentaria Tardía</label>
                    <select class="form-select" id="riesgo_enfermedad_placentaria_tardia" name="riesgo_enfermedad_placentaria_tardia">
                        <option value="">No evaluado</option>
                        <option value="Baja">Baja</option>
                        <option value="Alta">Alta</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="riesgo_parto_pretermino" class="form-label">Riesgo Parto Pretérmino</label>
                    <select class="form-select" id="riesgo_parto_pretermino" name="riesgo_parto_pretermino">
                        <option value="">No evaluado</option>
                        <option value="Bajo">Bajo</option>
                        <option value="Alto">Alto</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Estado y Submit -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label for="estado" class="form-label">Estado del Reporte</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="Pendiente">Pendiente</option>
                        <option value="En proceso">En proceso</option>
                        <option value="Completado">Completado</option>
                        <option value="Archivado">Archivado</option>
                    </select>
                </div>
                <div class="col-md-8 text-end">
                    <a href="<?php echo Url::to('/evaluaciones_1er_trimestre'); ?>" class="btn btn-apple btn-apple-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-apple btn-apple-primary btn-lg">
                        <i class="fa-solid fa-save"></i> Guardar Evaluación
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
