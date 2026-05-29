<?php
$title = "Nueva Evaluación 2do Trimestre";
$meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
$fecha_hoy = date('j') . ' de ' . $meses[date('n')] . ' del ' . date('Y');
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/evaluaciones_2do_trimestre'); ?>" class="btn btn-apple btn-apple-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
        <h1 class="page-title mb-0">Nueva Evaluación 2do Trimestre</h1>
    </div>
    <div class="page-header-actions"><span class="text-muted"><i class="fa-regular fa-calendar me-1"></i><?php echo $fecha_hoy; ?></span></div>
</div>

<form action="<?php echo Url::to('/evaluaciones_2do_trimestre/store'); ?>" method="POST">
    <input type="hidden" name="codigo_reporte" value="<?php echo htmlspecialchars($codigo_reporte); ?>">

    <!-- Datos Generales -->
    <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-id-card me-2"></i> Datos Generales</div><div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3"><label class="form-label">Código</label><input type="text" class="form-control" value="<?php echo htmlspecialchars($codigo_reporte); ?>" readonly></div>
            <div class="col-md-3 mb-3"><label for="fecha_evaluacion" class="form-label">Fecha Evaluación *</label><input type="date" class="form-control" id="fecha_evaluacion" name="fecha_evaluacion" value="<?php echo date('Y-m-d'); ?>" required></div>
            <div class="col-md-3 mb-3"><label for="fecha_estudio" class="form-label">Fecha Estudio</label><input type="date" class="form-control" id="fecha_estudio" name="fecha_estudio" value="<?php echo date('Y-m-d');?>"></div>
            <div class="col-md-3 mb-3"><label for="estado" class="form-label">Estado</label><select class="form-select" name="estado"><?php foreach(['Pendiente','En proceso','Completado','Archivado'] as $o): ?><option value="<?php echo $o; ?>"><?php echo $o; ?></option><?php endforeach; ?></select></div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3"><label for="paciente_id" class="form-label">Paciente *</label><select class="form-select" id="paciente_id" name="paciente_id" required><option value="">Seleccione un paciente</option><?php foreach($pacientes as $p): ?><option value="<?php echo $p['id']; ?>" <?php echo ($paciente_id == $p['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['nombre'].' '.$p['apellido']); ?></option><?php endforeach; ?></select></div>
            <div class="col-md-6 mb-3"><label for="medico_id" class="form-label">Médico *</label><select class="form-select" id="medico_id" name="medico_id" required><option value="">Seleccione un médico</option><?php foreach($medicos as $m): ?><option value="<?php echo $m['id']; ?>"><?php echo htmlspecialchars($m['nombre'].' '.$m['apellido'].($m['especialidad'] ? ' - '.$m['especialidad'] : '')); ?></option><?php endforeach; ?></select></div>
        </div>
    </div></div>

    <?php if (!empty($data1er)): ?>
    <div class="accordion mb-4" id="ref1erTrimestre2T">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1er2T">
                    <i class="fa-solid fa-folder-open me-2"></i> Datos del 1er Trimestre (referencia)
                </button>
            </h2>
            <div id="collapse1er2T" class="accordion-collapse collapse" data-bs-parent="#ref1erTrimestre2T">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-3"><strong>FPP por USG:</strong> <?php echo !empty($data1er['fpp_usg']) ? date('d/m/Y', strtotime($data1er['fpp_usg'])) : '—'; ?></div>
                        <div class="col-md-3"><strong>Edad Gestacional 1T:</strong> <?php echo !empty($data1er['edad_gestacional_semanas']) ? $data1er['edad_gestacional_semanas'].' sem' : '—'; ?></div>
                        <div class="col-md-3"><strong>Peso:</strong> <?php echo !empty($data1er['peso_kg']) ? $data1er['peso_kg'].' kg' : '—'; ?></div>
                        <div class="col-md-3"><strong>Talla:</strong> <?php echo !empty($data1er['talla_cm']) ? $data1er['talla_cm'].' cm' : '—'; ?></div>
                        <div class="col-md-3 mt-2"><strong>TA Sistólica:</strong> <?php echo !empty($data1er['ta_sistolica']) ? $data1er['ta_sistolica'].' mmHg' : '—'; ?></div>
                        <div class="col-md-3 mt-2"><strong>TA Diastólica:</strong> <?php echo !empty($data1er['ta_diastolica']) ? $data1er['ta_diastolica'].' mmHg' : '—'; ?></div>
                        <div class="col-md-3 mt-2"><strong>LCC:</strong> <?php echo !empty($data1er['lcc_mm']) ? $data1er['lcc_mm'].' mm' : '—'; ?></div>
                        <div class="col-md-3 mt-2"><strong>FUM:</strong> <?php echo !empty($data1er['fum']) ? date('d/m/Y', strtotime($data1er['fum'])) : '—'; ?></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3"><strong>Riesgo Preeclampsia (FMF):</strong> <?php echo $data1er['riesgo_preeclampsia_temprana'] ?? '—'; ?></div>
                        <div class="col-md-3"><strong>Riesgo Cromosomopatías:</strong> <?php echo $data1er['probabilidad_cromosomopatias'] ?? '—'; ?></div>
                        <div class="col-md-3"><strong>Riesgo Parto Pretérmino:</strong> <?php echo $data1er['riesgo_parto_pretermino'] ?? '—'; ?></div>
                        <div class="col-md-3"><strong>Miomas:</strong> <?php echo !empty($data1er['miomas_visibles']) ? 'Sí (FIGO: '.($data1er['miomas_figo_tipo'] ?? '—').')' : 'No'; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Datos Clínicos -->
    <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-heart-pulse me-2"></i> Datos Clínicos y Obstétricos</div><div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3"><label for="peso_kg" class="form-label">Peso (kg) <?php if(!empty($data1er['peso_kg'])): ?><small class="text-muted">| 1T: <?php echo $data1er['peso_kg']; ?> kg</small><?php endif; ?></label><input type="number" step="0.01" class="form-control" name="peso_kg" placeholder="Ej: 72.0"></div>
            <div class="col-md-3 mb-3"><label for="talla_cm" class="form-label">Talla (cm)</label><input type="number" step="0.01" class="form-control" name="talla_cm" placeholder="Ej: 165"></div>
            <div class="col-md-3 mb-3"><label for="pam_mmhg" class="form-label">PAM (mmHg)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="PAM = Presión Arterial Media: (sistólica + 2×diastólica) ÷ 3"></i></label><input type="number" step="0.01" class="form-control" name="pam_mmhg" placeholder="Presión Arterial Media"></div>
            <div class="col-md-3 mb-3"><label for="uta_pi_promedio" class="form-label">UTA PI Promedio<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="UTA = Arterias Uterinas · PI = Índice de Pulsatilidad (resistencia vascular)"></i></label><input type="number" step="0.01" class="form-control" name="uta_pi_promedio" placeholder="Índice Pulsatilidad"></div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3"><label for="edad_gestacional_semanas" class="form-label">Edad Gestacional (semanas)</label><input type="number" step="0.1" class="form-control" name="edad_gestacional_semanas" placeholder="Ej: 20.0"></div>
            <div class="col-md-4 mb-3"><label for="fpp_actual" class="form-label">FPP Actual</label><input type="date" class="form-control" name="fpp_actual"></div>
            <div class="col-md-4 mb-3"><label for="estado_feto" class="form-label">Estado del Feto</label><select class="form-select" name="estado_feto"><option value="Vivo">Vivo</option><option value="Muerto">Muerto</option></select></div>
        </div>
    </div></div>

    <!-- Biometría y Crecimiento -->
    <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-weight-scale me-2"></i> Biometría y Crecimiento Fetal</div><div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3"><label for="fcf_lpm" class="form-label">FCF (lpm)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="FCF = Frecuencia Cardíaca Fetal en latidos por minuto"></i></label><input type="number" class="form-control" name="fcf_lpm" placeholder="Ej: 150"></div>
            <div class="col-md-3 mb-3"><label for="peso_fetal_estimado_gr" class="form-label">Peso Fetal Estimado (gr)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="PFE = peso del feto calculado mediante biometría fórmula de Hadlock"></i></label><input type="number" class="form-control" name="peso_fetal_estimado_gr" placeholder="Ej: 512"></div>
            <div class="col-md-3 mb-3"><label for="percentil_hadlock" class="form-label">Percentil Hadlock<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="Percentil según tablas Hadlock; &lt;p10 = restricción de crecimiento"></i></label><input type="number" class="form-control" name="percentil_hadlock" placeholder="Ej: 50"></div>
            <div class="col-md-3 mb-3"><label class="form-label">&nbsp;</label><div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="crecimiento_armonico" checked><label class="form-check-label">Crecimiento Armónico</label></div></div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3"><label for="indice_cefalico_ci" class="form-label">Índice Cefálico (CI)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="CI = (DB/OF)×100; mide la forma de la cabeza fetal"></i></label><input type="number" step="0.01" class="form-control" name="indice_cefalico_ci"></div>
            <div class="col-md-4 mb-3"><label for="fl_ac_pct" class="form-label">FL/AC (%)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="FL = Fémur · AC = Circunferencia Abdominal; ratio para detectar femur corto relativo"></i></label><input type="number" step="0.01" class="form-control" name="fl_ac_pct"></div>
            <div class="col-md-4 mb-3"><label for="hc_ac_campbell" class="form-label">HC/AC (Campbell)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="HC = Circunferencia Cefálica · AC = Abdominal; ratio de Campbell para simetría de crecimiento"></i></label><input type="number" step="0.01" class="form-control" name="hc_ac_campbell"></div>
        </div>
    </div></div>

    <!-- Anatomía Fetal -->
    <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-baby me-2"></i> Anatomía Fetal</div><div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="craneo_snc_normal" checked><label class="form-check-label">Cráneo/SNC Normal</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="cara_cuello_normal" checked><label class="form-check-label">Cara/Cuello Normal</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="corazon_normal" checked><label class="form-check-label">Corazón Normal</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="torax_diafragma_normal" checked><label class="form-check-label">Tórax/Diafragma Normal</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="abdomen_normal" checked><label class="form-check-label">Abdomen Normal</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="genitourinario_normal" checked><label class="form-check-label">Genitourinario Normal</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="columna_normal" checked><label class="form-check-label">Columna Normal</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="extremidades_normal" checked><label class="form-check-label">Extremidades Normal</label></div></div>
        </div>
        <div class="mt-3"><label for="detalles_anomalias" class="form-label">Detalles de Anomalías</label><textarea class="form-control" name="detalles_anomalias" rows="2" placeholder="Describa hallazgos anormales..."></textarea></div>
    </div></div>

    <!-- Marcadores Ecográficos -->
    <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-magnifying-glass me-2"></i> Marcadores Ecográficos</div><div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="ventriculomegalia_leve"><label class="form-check-label">Ventriculomegalia Leve</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="quistes_plexos_coroideos"><label class="form-check-label">Quistes Plexos Coroideos</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="pliegue_nucal_aumentado"><label class="form-check-label">Pliegue Nucal Aumentado</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="hueso_nasal_ausente"><label class="form-check-label">Hueso Nasal Ausente</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="foco_ecogenico_cardiaco"><label class="form-check-label">Foco Ecogénico Cardíaco</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="intestino_hiperecogenico"><label class="form-check-label">Intestino Hiperecogénico</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="femur_corto"><label class="form-check-label">Fémur Corto</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="arteria_umbilical_unica"><label class="form-check-label">Arteria Umbilical Única</label></div></div>
        </div>
    </div></div>

    <!-- Entorno Placentario -->
    <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-uterus me-2"></i> Entorno Placentario</div><div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3"><label for="placenta_posicion" class="form-label">Posición Placenta</label><select class="form-select" name="placenta_posicion"><option value="">No evaluado</option><?php foreach(['Anterior','Posterior','Lateral Derecho','Lateral Izquierdo'] as $o): ?><option><?php echo $o; ?></option><?php endforeach; ?></select></div>
            <div class="col-md-4 mb-3"><label for="distancia_borde_oci_mm" class="form-label">Distancia Borde OCI (mm)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="OCI = Orificio Cervical Interno; distancia de la placenta al OCI (&ge;20mm = normal)"></i></label><input type="number" step="0.01" class="form-control" name="distancia_borde_oci_mm" placeholder="Ej: 25.0"></div>
            <div class="col-md-4 mb-3"><label for="acretismo_figo_grado" class="form-label">Acretismo FIGO</label><select class="form-select" name="acretismo_figo_grado"><option value="">No evaluado</option><option value="0">0 - Normal</option><option value="1">1 - Parcial</option><option value="2">2 - Invasión</option><option value="3">3 - Percretismo</option></select></div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3"><label for="bolsillo_max_liquido_mm" class="form-label">Bolsillo Máx. Líquido (mm)</label><input type="number" class="form-control" name="bolsillo_max_liquido_mm" placeholder="Ej: 50"></div>
            <div class="col-md-3 mb-3"><label for="longitud_cervical_mm" class="form-label">Longitud Cervical (mm)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="Medida del cuello uterino; &lt;25mm = riesgo de parto pretérmino"></i></label><input type="number" step="0.01" class="form-control" name="longitud_cervical_mm" placeholder="Ej: 35.0"></div>
            <div class="col-md-3 mb-3"><label for="indice_consistencia_cervical" class="form-label">Índice Consistencia Cervical<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="ICC = Ratio AP/Long del cérvix; valora la elasticidad del cuello uterino"></i></label><input type="number" class="form-control" name="indice_consistencia_cervical" placeholder="Ej: 80"></div>
            <div class="col-md-3 mb-3"><label for="sludge_intraamniotico" class="form-label">Sludge Intraamniótico<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="Sludge = material ecogénico flotante en el líquido amniótico; asociado a parto pretérmino"></i></label><select class="form-select" name="sludge_intraamniotico"><option value="">No evaluado</option><option value="No">No</option><option value="Si">Sí</option><option value="Dudoso">Dudoso</option></select></div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="funneling_presente"><label class="form-check-label">Funneling Presente <i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="Funneling = embudo del OCI; apertura del orificio cervical interno, indicador de parto pretérmino"></i></label></div></div>
            <div class="col-md-4 mb-2"><label for="funneling_mm" class="form-label">Funneling (mm)</label><input type="number" step="0.01" class="form-control form-control-sm" name="funneling_mm" placeholder="Ej: 8.0"></div>
        </div>
        <hr><h6 class="text-muted">Miomas Uterinos y Morfología</h6>
        <div class="row">
            <div class="col-md-4 mb-3"><label for="morfologia_uterina_eshre" class="form-label">Morfología Uterina (ESHRE-ESGE) <?php if(!empty($data1er['morfologia_uterina_eshre'])): ?><small class="text-muted">| 1T: <?php echo $data1er['morfologia_uterina_eshre']; ?></small><?php endif; ?></label><select class="form-select" name="morfologia_uterina_eshre"><option value="">No evaluado</option><?php foreach(['U0','U1','U2','U3','U4','U5','U6'] as $o): ?><option><?php echo $o; ?></option><?php endforeach; ?></select></div>
            <div class="col-md-4 mb-3"><label class="form-label">Miomas <?php if(!empty($data1er['miomas_visibles'])): ?><small class="text-muted">| 1T: Sí (FIGO: <?php echo $data1er['miomas_figo_tipo']??'—'; ?>)</small><?php endif; ?></label><div class="form-check"><input class="form-check-input" type="checkbox" name="miomas_visibles"><label class="form-check-label">Miomas Visibles</label></div></div>
            <div class="col-md-4 mb-3"><label for="miomas_figo_tipo" class="form-label">FIGO Tipo</label><select class="form-select" name="miomas_figo_tipo"><option value="">No aplica</option><?php for($i=0;$i<=8;$i++): ?><option value="<?php echo $i; ?>">Tipo <?php echo $i; ?></option><?php endfor; ?></select></div>
            <div class="col-md-4 mb-3"><label for="miomas_dimensiones_mm" class="form-label">Dimensiones (mm)</label><input type="text" class="form-control" name="miomas_dimensiones_mm" placeholder="Ej: 25x20"></div>
            <div class="col-md-4 mb-3"><label for="miomas_vascularizacion" class="form-label">Vascularización</label><input type="text" class="form-control" name="miomas_vascularizacion" placeholder="Ej: Moderada"></div>
        </div>
    </div></div>

    <!-- Historial Clínico -->
    <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-notes-medical me-2"></i> Historial Clínico</div><div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="hipertension_cronica" <?php echo (!empty($historial) && $historial['hipertension_cronica'])?'checked':''; ?>><label class="form-check-label">Hipertensión Crónica</label></div></div>
            <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="diabetes" <?php echo (!empty($historial) && $historial['diabetes'])?'checked':''; ?>><label class="form-check-label">Diabetes</label></div></div>
            <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="lupus_les" <?php echo (!empty($historial) && $historial['lupus_les'])?'checked':''; ?>><label class="form-check-label">Lupus / LES <i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="LES = Lupus Eritematoso Sistémico, enfermedad autoinmune"></i></label></div></div>
            <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="sindrome_antifosfolipido_saf" <?php echo (!empty($historial) && $historial['sindrome_antifosfolipido_saf'])?'checked':''; ?>><label class="form-check-label">Síndrome Antifosfolípido <i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="SAF = trastorno autoinmune que favorece trombosis y complicaciones en el embarazo"></i></label></div></div>
            <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="antecedente_preeclampsia_rciu" <?php echo (!empty($historial) && $historial['antecedente_preeclampsia_rciu'])?'checked':''; ?>><label class="form-check-label">Ant. Preeclampsia/RCIU <i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="RCIU = Restricción del Crecimiento Intrauterino"></i></label></div></div>
            <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="fertilizacion_in_vitro" <?php echo (!empty($historial) && $historial['fertilizacion_in_vitro'])?'checked':''; ?>><label class="form-check-label">Fertilización In Vitro</label></div></div>
            <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="antecedente_parto_pretermino" <?php echo (!empty($historial) && $historial['antecedente_parto_pretermino'])?'checked':''; ?>><label class="form-check-label">Ant. Parto Pretérmino</label></div></div>
        </div>
    </div></div>

    <!-- Impresión Diagnóstica -->
    <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-clipboard-check me-2"></i> Impresión Diagnóstica</div><div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3"><label for="riesgo_cromosomopatias" class="form-label">Riesgo Cromosomopatías</label><select class="form-select" name="riesgo_cromosomopatias"><option value="">No evaluado</option><?php foreach(['Bajo','Intermedio','Alto'] as $o): ?><option value="<?php echo $o; ?>"><?php echo $o; ?></option><?php endforeach; ?></select></div>
            <div class="col-md-4 mb-3"><label for="riesgo_parto_pretermino" class="form-label">Riesgo Parto Pretérmino</label><select class="form-select" name="riesgo_parto_pretermino"><option value="">No evaluado</option><?php foreach(['Bajo','Intermedio','Alto','Muy Alto'] as $o): ?><option value="<?php echo $o; ?>"><?php echo $o; ?></option><?php endforeach; ?></select></div>
            <div class="col-md-4 mb-3"><label for="riesgo_preeclampsia" class="form-label">Riesgo Preeclampsia</label><select class="form-select" name="riesgo_preeclampsia"><option value="">No evaluado</option><?php foreach(['Bajo','Intermedio','Alto','Muy Alto'] as $o): ?><option value="<?php echo $o; ?>"><?php echo $o; ?></option><?php endforeach; ?></select></div>
        </div>
        <div class="mt-2"><label for="observaciones_medicas" class="form-label">Observaciones Médicas</label><textarea class="form-control" name="observaciones_medicas" rows="2" placeholder="Notas y recomendaciones..."></textarea></div>
    </div></div>

    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="<?php echo Url::to('/evaluaciones_2do_trimestre'); ?>" class="btn btn-apple btn-apple-secondary">Cancelar</a>
        <button type="submit" class="btn btn-apple btn-apple-primary btn-lg"><i class="fa-solid fa-save"></i> Guardar Evaluación</button>
    </div>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
