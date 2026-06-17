<?php
$title = "Nueva Evaluación 3er Trimestre";
$meses=['','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
$fh=date('j').' de '.$meses[date('n')].' del '.date('Y');
require_once __DIR__.'/../layouts/header.php';
require_once __DIR__.'/../layouts/sidebar.php';
?>
<div class="page-header"><div class="d-flex align-items-center gap-3">
<a href="<?php echo Url::to('/evaluaciones_3er_trimestre');?>" class="btn btn-apple btn-apple-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
<h1 class="page-title mb-0">Nueva Evaluación 3er Trimestre</h1></div>
<div class="page-header-actions"><span class="text-muted"><i class="fa-regular fa-calendar me-1"></i><?php echo $fh;?></span></div></div>
<form action="<?php echo Url::to('/evaluaciones_3er_trimestre/store');?>" method="POST" enctype="multipart/form-data">
<input type="hidden" name="codigo_reporte" value="<?php echo htmlspecialchars($codigo_reporte);?>">

<!-- Datos Generales -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-id-card me-2"></i> Datos Generales</div><div class="card-body">
<div class="row">
<div class="col-md-3 mb-3"><label class="form-label">Código</label><input type="text" class="form-control" value="<?php echo htmlspecialchars($codigo_reporte);?>" readonly></div>
<div class="col-md-3 mb-3"><label for="fecha_evaluacion" class="form-label">Fecha Evaluación *</label><input type="date" class="form-control" name="fecha_evaluacion" value="<?php echo date('Y-m-d');?>" required></div>
<div class="col-md-3 mb-3"><label for="fecha_estudio" class="form-label">Fecha Estudio</label><input type="date" class="form-control" name="fecha_estudio" value="<?php echo date('Y-m-d');?>"></div>
<div class="col-md-3 mb-3"><label for="estudio_solicitado" class="form-label">Estudio Solicitado</label><input type="text" class="form-control" name="estudio_solicitado" placeholder="Ej: Crecimiento Fetal, Evaluación placentaria"></div>
</div>
<div class="row">
<div class="col-md-3 mb-3"><label for="equipo_ultrasonido" class="form-label">Equipo Ultrasonográfico</label><input type="text" class="form-control" name="equipo_ultrasonido" placeholder="Ej: GE Volusson Expert"></div>
<div class="col-md-3 mb-3"><label for="estado" class="form-label">Estado</label><select class="form-select" name="estado"><?php foreach(['Pendiente','En proceso','Completado','Archivado'] as $o):?><option value="<?php echo $o;?>"><?php echo $o;?></option><?php endforeach;?></select></div>
</div>
<div class="row">
<div class="col-md-12 mb-3"><label for="paciente_id" class="form-label">Paciente *</label><select class="form-select" name="paciente_id" required><option value="">Seleccione</option><?php foreach($pacientes as $p):?><option value="<?php echo $p['id'];?>" <?php echo ($paciente_id==$p['id'])?'selected':'';?>><?php echo htmlspecialchars($p['nombre'].' '.$p['apellido']);?></option><?php endforeach;?></select></div>
</div>
</div></div>

<!-- Referencia Médica -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-user-doctor me-2"></i> Referencia Médica</div><div class="card-body">
<div class="row">
<div class="col-md-4 mb-3"><label class="form-label">Médico Solicitante</label><select name="medico_solicitante_id" class="form-select"><option value="">Seleccionar...</option><?php foreach($medicos as $m):?><option value="<?php echo $m['id'];?>" <?php echo Auth::id()==$m['id']?'selected':'';?>><?php echo htmlspecialchars($m['nombre'].' '.$m['apellido'].($m['especialidad']?' - '.$m['especialidad']:''));?></option><?php endforeach;?></select></div>
<div class="col-md-4 mb-3"><label class="form-label">Médico que Realiza <span class="text-danger">*</span></label><select name="medico_id" class="form-select" required><option value="">Seleccionar...</option><?php foreach($medicos as $m):?><option value="<?php echo $m['id'];?>"><?php echo htmlspecialchars($m['nombre'].' '.$m['apellido'].($m['especialidad']?' - '.$m['especialidad']:''));?></option><?php endforeach;?></select></div>
<div class="col-md-4 mb-3"><label class="form-label">Médico Referido</label><select name="medico_referido_id" class="form-select"><option value="">Ninguno</option><?php foreach($medicos as $m):?><option value="<?php echo $m['id'];?>"><?php echo htmlspecialchars($m['nombre'].' '.$m['apellido'].($m['especialidad']?' - '.$m['especialidad']:''));?></option><?php endforeach;?></select></div>
</div>
</div></div>

    <?php if (!empty($historial)): ?>
    <div class="card mb-4">
        <div class="card-header"><i class="fa-solid fa-clipboard-list me-2"></i> Antecedentes Clínicos (Referencia)</div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-3"><strong>G - Embarazos:</strong> <?php echo $historial['num_embarazos'] ?? '—'; ?></div>
                <div class="col-md-3"><strong>C - Cesáreas:</strong> <?php echo $historial['num_cesareas'] ?? '—'; ?></div>
                <div class="col-md-3"><strong>A - Abortos:</strong> <?php echo $historial['num_abortos'] ?? '—'; ?></div>
                <div class="col-md-3"><strong>E - Ectópicos:</strong> <?php echo $historial['num_ectopicos'] ?? '—'; ?></div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4 mb-2"><?php echo ($historial['hipertension_cronica'] ?? false) ? '<span class="text-danger">⚠</span>' : '<span class="text-success">✓</span>'; ?> Hipertensión Crónica</div>
                <div class="col-md-4 mb-2"><?php echo ($historial['diabetes'] ?? false) ? '<span class="text-danger">⚠</span>' : '<span class="text-success">✓</span>'; ?> Diabetes</div>
                <div class="col-md-4 mb-2"><?php echo ($historial['lupus_les'] ?? false) ? '<span class="text-danger">⚠</span>' : '<span class="text-success">✓</span>'; ?> Lupus / LES</div>
                <div class="col-md-4 mb-2"><?php echo ($historial['sindrome_antifosfolipido_saf'] ?? false) ? '<span class="text-danger">⚠</span>' : '<span class="text-success">✓</span>'; ?> SAF</div>
                <div class="col-md-4 mb-2"><?php echo ($historial['antecedente_preeclampsia_rciu'] ?? false) ? '<span class="text-danger">⚠</span>' : '<span class="text-success">✓</span>'; ?> Preeclampsia / RCIU</div>
                <div class="col-md-4 mb-2"><?php echo ($historial['fertilizacion_in_vitro'] ?? false) ? '<span class="text-danger">⚠</span>' : '<span class="text-success">✓</span>'; ?> Fertilización In Vitro</div>
                <div class="col-md-4 mb-2"><?php echo ($historial['antecedente_parto_pretermino'] ?? false) ? '<span class="text-danger">⚠</span>' : '<span class="text-success">✓</span>'; ?> Parto Pretérmino</div>
            </div>
        </div>
    </div>
    <?php endif; ?>

 <!-- Historial Clínico (Antecedentes) -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-notes-medical me-2"></i> Historial Clínico (Antecedentes)</div><div class="card-body">
    <h6 class="text-muted mb-3">Antecedentes Obstétricos (G/C/A/E)</h6>
    <div class="row">
        <div class="col-md-3 mb-3"><label for="num_embarazos" class="form-label">G - Embarazos</label><input type="number" class="form-control" name="num_embarazos" min="0" value="<?php echo htmlspecialchars($historial['num_embarazos'] ?? ''); ?>"></div>
        <div class="col-md-3 mb-3"><label for="num_cesareas" class="form-label">C - Cesáreas</label><input type="number" class="form-control" name="num_cesareas" min="0" value="<?php echo htmlspecialchars($historial['num_cesareas'] ?? ''); ?>"></div>
        <div class="col-md-3 mb-3"><label for="num_abortos" class="form-label">A - Abortos</label><input type="number" class="form-control" name="num_abortos" min="0" value="<?php echo htmlspecialchars($historial['num_abortos'] ?? ''); ?>"></div>
        <div class="col-md-3 mb-3"><label for="num_ectopicos" class="form-label">E - Ectópicos</label><input type="number" class="form-control" name="num_ectopicos" min="0" value="<?php echo htmlspecialchars($historial['num_ectopicos'] ?? ''); ?>"></div>
    </div>
    <hr class="mt-0 mb-3"><h6 class="text-muted mb-3">Antecedentes Médicos</h6>
    <div class="row">
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="hipertension_cronica" <?php echo (!empty($historial)&&$historial['hipertension_cronica'])?'checked':'';?>><label class="form-check-label">Hipertensión Crónica</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="diabetes" <?php echo (!empty($historial)&&$historial['diabetes'])?'checked':'';?>><label class="form-check-label">Diabetes</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="lupus_les" <?php echo (!empty($historial)&&$historial['lupus_les'])?'checked':'';?>><label class="form-check-label">Lupus / LES <i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="LES = Lupus Eritematoso Sistémico, enfermedad autoinmune"></i></label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="sindrome_antifosfolipido_saf" <?php echo (!empty($historial)&&$historial['sindrome_antifosfolipido_saf'])?'checked':'';?>><label class="form-check-label">Síndrome Antifosfolípido <i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="SAF = trastorno autoinmune que favorece trombosis en el embarazo"></i></label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="antecedente_preeclampsia_rciu" <?php echo (!empty($historial)&&$historial['antecedente_preeclampsia_rciu'])?'checked':'';?>><label class="form-check-label">Ant. Preeclampsia/RCIU <i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="RCIU = Restricción del Crecimiento Intrauterino"></i></label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="fertilizacion_in_vitro" <?php echo (!empty($historial)&&$historial['fertilizacion_in_vitro'])?'checked':'';?>><label class="form-check-label">Fertilización In Vitro</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="antecedente_parto_pretermino" <?php echo (!empty($historial)&&$historial['antecedente_parto_pretermino'])?'checked':'';?>><label class="form-check-label">Ant. Parto Pretérmino</label></div></div>
</div></div></div>

    <?php if (!empty($data1er) || !empty($data2do)): ?>
    <div class="accordion mb-4" id="refTrimestresPrevios">
        <?php if (!empty($data1er)): ?>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1er3T">
                    <i class="fa-solid fa-folder-open me-2"></i> Datos del 1er Trimestre (referencia)
                </button>
            </h2>
            <div id="collapse1er3T" class="accordion-collapse collapse" data-bs-parent="#refTrimestresPrevios">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-3"><strong>FPP por USG:</strong> <?php echo !empty($data1er['fpp_usg']) ? date('d/m/Y', strtotime($data1er['fpp_usg'])) : '—'; ?></div>
                        <div class="col-md-3"><strong>EG 1T:</strong> <?php echo !empty($data1er['edad_gestacional_semanas']) ? $data1er['edad_gestacional_semanas'].' sem' : '—'; ?></div>
                        <div class="col-md-3"><strong>Peso:</strong> <?php echo !empty($data1er['peso_kg']) ? $data1er['peso_kg'].' kg' : '—'; ?></div>
                        <div class="col-md-3"><strong>LCC:</strong> <?php echo !empty($data1er['lcc_mm']) ? $data1er['lcc_mm'].' mm' : '—'; ?></div>
                        <div class="col-md-3 mt-2"><strong>Riesgo Preeclampsia (FMF):</strong> <?php echo $data1er['riesgo_preeclampsia_temprana'] ?? '—'; ?></div>
                        <div class="col-md-3 mt-2"><strong>Doppler UT PI:</strong> <?php echo !empty($data1er['uta_pi_promedio']) ? $data1er['uta_pi_promedio'] : '—'; ?> <?php echo !empty($data1er['muesca_bilateral']) ? '<span class="text-danger">(Muesca bilateral)</span>' : ''; ?></div>
                        <div class="col-md-3 mt-2"><strong>PAPP-A MoM:</strong> <?php echo !empty($data1er['papp_a_mom']) ? $data1er['papp_a_mom'] : '—'; ?></div>
                        <div class="col-md-3 mt-2"><strong>PLGF MoM:</strong> <?php echo !empty($data1er['plgf_mom']) ? $data1er['plgf_mom'] : '—'; ?></div>
                        <div class="col-md-4 mt-2"><strong>Tamizaje Genético:</strong> <?php echo !empty($data1er['tamizaje_genetico_tipo']) ? $data1er['tamizaje_genetico_tipo'].' — '.($data1er['tamizaje_genetico_resultado'] ?? '—') : '—'; ?></div>
                        <div class="col-md-4 mt-2"><strong>Longitud Cervical 1T:</strong> <?php echo !empty($data1er['longitud_cervical_mm']) ? $data1er['longitud_cervical_mm'].' mm' : '—'; ?></div>
                        <div class="col-md-4 mt-2"><strong>Miomas 1T:</strong> <?php echo !empty($data1er['miomas_visibles']) ? 'Sí (FIGO: '.($data1er['miomas_figo_tipo'] ?? '—').')' : 'No'; ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($data2do)): ?>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2do3T">
                    <i class="fa-solid fa-folder-open me-2"></i> Datos del 2do Trimestre (referencia)
                </button>
            </h2>
            <div id="collapse2do3T" class="accordion-collapse collapse" data-bs-parent="#refTrimestresPrevios">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-4"><strong>Morfología Fetal:</strong> <?php
                            $morfNormal = true;
                            $camposMorf = ['craneo_snc_normal','cara_cuello_normal','corazon_normal','torax_diafragma_normal','abdomen_normal','genitourinario_normal','columna_normal','extremidades_normal'];
                            foreach($camposMorf as $cm) { if(isset($data2do[$cm]) && $data2do[$cm]==0) { $morfNormal=false; break; } }
                            echo $morfNormal ? '<span class="text-success">Normal</span>' : '<span class="text-danger">Alterada</span>';
                        ?></div>
                        <div class="col-md-4"><strong>Doppler UT PI 2T:</strong> <?php echo !empty($data2do['uta_pi_promedio']) ? $data2do['uta_pi_promedio'] : '—'; ?></div>
                        <div class="col-md-4"><strong>Placenta:</strong> <?php echo $data2do['placenta_posicion'] ?? '—'; ?> | OCI: <?php echo !empty($data2do['distancia_borde_oci_mm']) ? $data2do['distancia_borde_oci_mm'].' mm' : '—'; ?></div>
                        <div class="col-md-4 mt-2"><strong>Acretismo 2T:</strong> <?php echo $data2do['acretismo_figo_grado'] ?? '—'; ?></div>
                        <div class="col-md-4 mt-2"><strong>Longitud Cervical 2T:</strong> <?php echo !empty($data2do['longitud_cervical_mm']) ? $data2do['longitud_cervical_mm'].' mm' : '—'; ?></div>
                        <div class="col-md-4 mt-2"><strong>ICC 2T:</strong> <?php echo !empty($data2do['indice_consistencia_cervical']) ? $data2do['indice_consistencia_cervical'].'%' : '—'; ?></div>
                        <div class="col-md-4 mt-2"><strong>Funneling 2T:</strong> <?php echo !empty($data2do['funneling_presente']) ? 'Sí ('.$data2do['funneling_mm'].' mm)' : 'No'; ?></div>
                        <div class="col-md-4 mt-2"><strong>Sludge 2T:</strong> <?php echo $data2do['sludge_intraamniotico'] ?? '—'; ?></div>
                        <div class="col-md-4 mt-2"><strong>Signos RCIU 2T:</strong> <?php
                            $rciu = false;
                            if(!empty($data2do['percentil_hadlock']) && $data2do['percentil_hadlock']<10) $rciu=true;
                            if(!empty($data2do['crecimiento_armonico']) && $data2do['crecimiento_armonico']==0) $rciu=true;
                            echo $rciu ? '<span class="text-danger">Sí</span>' : '<span class="text-success">No</span>';
                        ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Signos Vitales y Estática Fetal -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-heart-pulse me-2"></i> Signos Vitales y Estática Fetal</div><div class="card-body">
<div class="row">
<div class="col-md-2 mb-3"><label for="feto_unico_vivo" class="form-label">Condición Fetal</label><select class="form-select" name="feto_unico_vivo"><option value="">No evaluado</option><option value="Vivo">Vivo</option><option value="Muerto">Muerto</option></select></div>
<div class="col-md-2 mb-3"><label for="fcf_lpm" class="form-label">FCF (lpm)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="FCF = Frecuencia Cardíaca Fetal en latidos por minuto"></i></label><input type="number" class="form-control" name="fcf_lpm" placeholder="Ej: 140"></div>
<div class="col-md-2 mb-3"><label for="situacion_fetal" class="form-label">Situación Fetal</label><select class="form-select" name="situacion_fetal"><option value="">No evaluado</option><option value="Longitudinal">Longitudinal</option><option value="Transversa">Transversa</option></select></div>
<div class="col-md-2 mb-3"><label for="presentacion_fetal" class="form-label">Presentación</label><select class="form-select" name="presentacion_fetal"><option value="">No evaluado</option><option value="Cefalico">Cefálico</option><option value="Pelvico">Pélvico</option></select></div>
<div class="col-md-4 mb-3"><label for="posicion_fetal" class="form-label">Posición Fetal</label><input type="text" class="form-control" name="posicion_fetal" placeholder="Dorso anterior/posterior, polo cefálico derecho/izquierdo"></div>
</div>
<div class="row">
<div class="col-md-2 mb-3"><label for="edad_gestacional_semanas" class="form-label">EG (sem)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="EG = Edad Gestacional en semanas"></i></label><input type="number" step="0.1" class="form-control" name="edad_gestacional_semanas" placeholder="Ej: 32.0"></div>
<div class="col-md-2 mb-3"><label for="fpp_fum" class="form-label">FPP por FUM<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="FPP = Fecha Probable de Parto · FUM = Fecha de Última Menstruación"></i></label><input type="date" class="form-control" name="fpp_fum"></div>
<div class="col-md-2 mb-3"><label for="fpp_usg" class="form-label">FPP por USG<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="FPP = Fecha Probable de Parto · USG = Ultrasonografía"></i></label><input type="date" class="form-control" name="fpp_usg"></div>
<div class="col-md-2 mb-3"><label for="peso_kg" class="form-label">Peso Materno (kg)</label><input type="number" step="0.01" class="form-control" name="peso_kg" placeholder="Ej: 78.5"></div>
<div class="col-md-2 mb-3"><label for="talla_cm" class="form-label">Talla (cm)</label><input type="number" step="0.01" class="form-control" name="talla_cm" placeholder="Ej: 164"></div>
<div class="col-md-2 mb-3"><label for="ta_sistolica" class="form-label">TA Sistólica</label><input type="number" class="form-control" name="ta_sistolica" placeholder="Ej: 120"></div>
<div class="col-md-2 mb-3"><label for="ta_diastolica" class="form-label">TA Diastólica</label><input type="number" class="form-control" name="ta_diastolica" placeholder="Ej: 80"></div>
</div>
</div></div>

<!-- Antecedentes -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-notes-medical me-2"></i> Antecedentes del 3er Trimestre</div><div class="card-body">
<div class="row">
<div class="col-md-4 mb-3"><label for="curva_tolerancia_glucosa" class="form-label">Curva Tolerancia Glucosa</label><select class="form-select" name="curva_tolerancia_glucosa"><option value="No realizada">No realizada</option><option value="Normal">Normal</option><option value="Alterada">Alterada</option></select></div>
<div class="col-md-4 mb-3"><label class="form-label">&nbsp;</label><div class="form-check"><input class="form-check-input" type="checkbox" name="diabetes_gestacional_actual"><label class="form-check-label">Diabetes Gestacional Actual</label></div></div>
<div class="col-md-4 mb-3"><label for="movimientos_fetales" class="form-label">Movimientos Fetales</label><select class="form-select" name="movimientos_fetales"><option value="Normales">Normales</option><option value="Disminuidos">Disminuidos</option></select></div>
</div>
<div class="row">
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="signos_amenaza_parto_pretermino"><label class="form-check-label">Signos Amenaza Parto Pretérmino</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="plan_nacimiento_definido"><label class="form-check-label">Plan de Nacimiento Definido</label></div></div>
</div>
</div></div>

<!-- Crecimiento y RCIU -->
<div class="card mb-4" id="card-crecimiento"><div class="card-header"><i class="fa-solid fa-weight-scale me-2"></i> Crecimiento y RCIU</div><div class="card-body">
<div class="row">
<div class="col-md-3 mb-3"><label for="peso_fetal_estimado_gr" class="form-label">Peso Fetal Estimado (gr)</label><input type="number" class="form-control" name="peso_fetal_estimado_gr" placeholder="Ej: 2100"></div>
<div class="col-md-3 mb-3"><label for="percentil_ajustado" class="form-label">Percentil Ajustado</label><input type="number" class="form-control" name="percentil_ajustado" placeholder="Ej: 35"></div>
<div class="col-md-3 mb-3"><label for="clasificacion_crecimiento" class="form-label">Clasificación</label><select class="form-select" name="clasificacion_crecimiento"><option value="">No evaluado</option><option value="Adecuado">Adecuado</option><option value="Mayor a lo esperado">Mayor a lo esperado</option><option value="Menor a lo esperado">Menor a lo esperado</option></select></div>
<div class="col-md-3 mb-3"><label for="estadio_rciu_barcelona" class="form-label">RCIU Barcelona<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="RCIU = Restricción Crecimiento Intrauterino · Estadios I-IV según Clasificación de Barcelona (gravedad creciente)"></i></label><select class="form-select" name="estadio_rciu_barcelona"><option value="Ninguno">Ninguno</option><option value="Estadio I">Estadio I</option><option value="Estadio II">Estadio II</option><option value="Estadio III">Estadio III</option><option value="Estadio IV">Estadio IV</option></select></div>
</div>
</div></div>

<!-- Doppler -->
<div class="card mb-4" id="card-doppler"><div class="card-header"><i class="fa-solid fa-wave-square me-2"></i> Doppler / Hemodinamia</div><div class="card-body">
<div class="row">
<div class="col-md-3 mb-3"><label for="au_pi" class="form-label">AU PI<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="AU = Arteria Umbilical · PI = Índice de Pulsatilidad; mide resistencia en el cordón umbilical"></i></label><input type="number" step="0.01" class="form-control" name="au_pi" placeholder="A. Umbilical"></div>
<div class="col-md-3 mb-3"><label for="au_flujo_diastolico" class="form-label">Flujo Diastólico AU<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="AU = Arteria Umbilical; flujo ausente o reverso indica compromiso fetal severo"></i></label><select class="form-select" name="au_flujo_diastolico"><option value="">No evaluado</option><option value="Presente">Presente</option><option value="Ausente">Ausente</option><option value="Reverso">Reverso</option></select></div>
<div class="col-md-3 mb-3"><label for="acm_pi" class="form-label">ACM PI<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="ACM = Arteria Cerebral Media · PI = Índice de Pulsatilidad; disminuido indica redistribución hemodiniámica fetal"></i></label><input type="number" step="0.01" class="form-control" name="acm_pi" placeholder="A. Cerebral Media"></div>
<div class="col-md-3 mb-3"><label for="dv_onda_a" class="form-label">DV Onda A<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="DV = Ductus Venoso · Onda A refleja función cardíaca fetal; reversa indica fallo cardíaco"></i></label><select class="form-select" name="dv_onda_a"><option value="">No evaluado</option><option value="Positiva">Positiva</option><option value="Ausente">Ausente</option><option value="Reversa">Reversa</option></select></div>
</div>
<div class="row">
<div class="col-md-3 mb-3"><label for="uta_pi_promedio" class="form-label">UTA PI Promedio<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="UTA = Arterias Uterinas · PI = Índice de Pulsatilidad; elevado indica resistencia placentaria alta"></i></label><input type="number" step="0.01" class="form-control" name="uta_pi_promedio" placeholder="A. Uterinas"></div>
    <div class="col-md-3 mb-3"><label for="ratio_cu_icp" class="form-label">Ratio CU/ICP<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="CU = Cerebro-Umbilical (AU/ACM PI); ICP = Índice Cerebro-Placentario; &lt;1 indica redistribución"></i></label><input type="number" step="0.01" class="form-control" name="ratio_cu_icp" placeholder="Cerebro-placentario"></div>
<div class="col-md-3 mb-3"><label for="vena_umbilical" class="form-label">Vena Umbilical</label><select class="form-select" name="vena_umbilical"><option value="">No evaluado</option><option value="Normal">Normal</option><option value="Pulsatil">Pulsátil</option></select></div>
<div class="col-md-3 mb-3"><label class="form-label">&nbsp;</label><div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="alteracion_doppler_detectada"><label class="form-check-label">Alteración Doppler Detectada</label></div></div>
</div>
</div></div>

<!-- Anatomía Fetal -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-baby me-2"></i> Anatomía Fetal</div><div class="card-body">
    <div class="row">
        <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="craneo_snc_normal" checked onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Cráneo/SNC sin alteraciones</label><small class="text-muted d-block ms-4">Forma y tamaño normal, SNC íntegro, ventriculomegalia &lt; 10 mm, surcos y giros acordes a edad gestacional</small></div></div>
        <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="cara_cuello_normal" checked onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Cara/Cuello sin alteraciones</label><small class="text-muted d-block ms-4">Órbitas presentes y simétricas, labio superior íntegro, perfil facial normal</small></div></div>
        <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="corazon_normal" checked onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Corazón sin alteraciones</label><small class="text-muted d-block ms-4">Situs solitus, eje cardíaco normal, 4 cámaras, cruce de grandes vasos normal, ritmo regular</small></div></div>
        <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="torax_diafragma_normal" checked onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Tórax/Diafragma sin alteraciones</label><small class="text-muted d-block ms-4">Pulmones con ecogenicidad normal, parénquima homogéneo, diafragma íntegro</small></div></div>
        <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="abdomen_normal" checked onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Abdomen sin alteraciones</label><small class="text-muted d-block ms-4">Estómago presente, intestino sin dilataciones, pared abdominal íntegra, cordón umbilical de inserción normal</small></div></div>
        <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="genitourinario_normal" checked onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Genitourinario sin alteraciones</label><small class="text-muted d-block ms-4">Riñones de tamaño y morfología normal, sin dilatación pielocalicial, vejiga presente</small></div></div>
        <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="columna_normal" checked onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Columna sin alteraciones</label><small class="text-muted d-block ms-4">Alineación normal, arcos vertebrales íntegros, cono medular en posición normal</small></div></div>
        <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="extremidades_normal" checked onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Extremidades sin alteraciones</label><small class="text-muted d-block ms-4">Los 4 miembros presentes, 3 segmentos, manos y pies normales, movimientos activos</small></div></div>
    </div>
    <div class="mt-3"><label for="detalles_anatomia" class="form-label">Detalles de Anomalías</label><textarea class="form-control" name="detalles_anatomia" rows="2" placeholder="Describa hallazgos anormales..."></textarea></div>
</div></div>

<!-- Líquido Amniótico y Cordón -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-droplet me-2"></i> Líquido Amniótico y Cordón Umbilical</div><div class="card-body">
<div class="row">
<div class="col-md-3 mb-3"><label for="circular_cordon_cuello" class="form-label">Circular Cordón</label><select class="form-select" name="circular_cordon_cuello"><option value="Negativo">Negativo</option><option value="Simple">Simple</option><option value="Doble">Doble</option></select></div>
<div class="col-md-3 mb-3"><label for="liquido_amniotico_mm" class="form-label">Líquido Amniótico (mm)</label><input type="number" class="form-control" name="liquido_amniotico_mm" placeholder="Ej: 120"></div>
<div class="col-md-3 mb-3"><label for="metodo_medicion_liquido" class="form-label">Método Medición</label><select class="form-select" name="metodo_medicion_liquido"><option value="Bolsillo Maximo">Bolsillo Máximo</option><option value="Phelan">Phelan</option></select></div>
<div class="col-md-3 mb-3"><label for="diagnostico_liquido" class="form-label">Diagnóstico Líquido</label><select class="form-select" name="diagnostico_liquido"><option value="Normal">Normal</option><option value="Oligohidramnios">Oligohidramnios</option><option value="Polihidramnios">Polihidramnios</option></select></div>
</div>
</div></div>

<!-- Marcadores Ecográficos -->
<div id="marcadoresSection" class="card mb-4" style="display:none;"><div class="card-header"><i class="fa-solid fa-magnifying-glass me-2"></i> Marcadores Ecográficos <span class="badge bg-warning text-dark ms-2">Activado por alteración anatómica</span></div><div class="card-body">
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

<!-- Evaluación Placentaria AJOG 2025 / FIGO 2023 -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-uterus me-2"></i> Evaluación Placentaria (AJOG 2025 / FIGO 2023)</div><div class="card-body">
<!-- Fila 1 -->
<div class="row">
<div class="col-md-4 mb-3"><label for="localizacion_placentaria" class="form-label">Localización</label><select class="form-select" name="localizacion_placentaria"><option value="">No evaluada</option><option value="Anterior">Anterior</option><option value="Posterior">Posterior</option><option value="Fundica">Fúndica</option><option value="Lateral Derecha">Lateral Derecha</option><option value="Lateral Izquierda">Lateral Izquierda</option></select></div>
<div class="col-md-4 mb-3"><label for="distancia_oci_mm" class="form-label">Distancia OCI (mm)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="OCI = Orificio Cervical Interno; &ge;20mm = normal, &lt;20mm = placenta baja/previa"></i></label><input type="number" step="0.01" class="form-control" name="distancia_oci_mm" placeholder="≥20mm = normal"></div>
<div class="col-md-4 mb-3"><label for="grado_madurez" class="form-label">Grado de Madurez</label><select class="form-select" name="grado_madurez"><option value="">No evaluado</option><option value="Grado 0-1">Grado 0-1 (normal ≤34 sem)</option><option value="Grado 2">Grado 2</option><option value="Grado 3">Grado 3</option></select></div>
</div>
<!-- Fila 2 -->
<div class="row">
<div class="col-md-3 mb-3"><label for="grosor_placentario_mm" class="form-label">Grosor Placentario (mm)</label><input type="number" class="form-control" name="grosor_placentario_mm" placeholder="25-50 mm"></div>
<div class="col-md-3 mb-3"><label for="ecogenicidad" class="form-label">Ecogenicidad</label><select class="form-select" name="ecogenicidad"><option value="">No evaluada</option><option value="Homogenea">Homogénea</option><option value="Heterogenea">Heterogénea</option></select></div>
<div class="col-md-3 mb-3"><label for="insercion_cordon" class="form-label">Inserción del Cordón</label><select class="form-select" name="insercion_cordon"><option value="">No evaluada</option><option value="Central">Central</option><option value="Paracentral">Paracentral</option><option value="Marginal">Marginal</option><option value="Velamentosa">Velamentosa</option></select></div>
<div class="col-md-3 mb-3"><label for="numero_vasos_umbilicales" class="form-label">N° Vasos Umbilicales</label><select class="form-select" name="numero_vasos_umbilicales"><option value="">No evaluado</option><option value="3">3 vasos</option><option value="2">2 vasos</option></select></div>
</div>
<!-- Fila 3: Lagunas + Interfase + Zona retroplacentaria -->
<div class="row">
<div class="col-md-4 mb-3"><label for="lagunas_vasculares" class="form-label">Lagunas Vasculares</label><select class="form-select" name="lagunas_vasculares"><option value="Ausentes/minimas">Ausentes / mínimas (Grado 0-1 FIGO)</option><option value="Si">Sí</option><option value="Extensas">Extensas</option></select></div>
<div class="col-md-4 mb-3"><label for="interfase_miometrial" class="form-label">Interfase Miometrio-Placentaria</label><select class="form-select" name="interfase_miometrial"><option value="Intacta">Intacta</option><option value="Adelgazada">Adelgazada</option><option value="Discontinua">Discontinua</option></select></div>
<div class="col-md-4 mb-3"><label for="zona_retroplacentaria" class="form-label">Zona Retroplacentaria</label><select class="form-select" name="zona_retroplacentaria"><option value="">No evaluada</option><option value="Presente">Presente (hipoecoica)</option><option value="Ausente">Ausente</option></select></div>
</div>
<!-- Fila 4: Checkboxes -->
<div class="row">
<div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="vasos_puente"><label class="form-check-label">Vasos Puente Miometriales</label></div></div>
<div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="protrusion_placentaria"><label class="form-check-label">Protrusión Placentaria</label></div></div>
<div class="col-md-6 mb-3"><label for="vascularizacion_anomala_doppler" class="form-label">Vascularización Anómala (Color Doppler)</label><select class="form-select" name="vascularizacion_anomala_doppler"><option value="">No evaluada</option><option value="Normal">Normal — flujo periférico fino</option><option value="Turbulento">Turbulento</option><option value="Extendido a vejiga">Extendido a vejiga</option></select></div>
</div>
<!-- Fila 5: Calcificaciones + Acretismo -->
<div class="row">
<div class="col-md-4 mb-3"><label for="calcificaciones" class="form-label">Calcificaciones</label><select class="form-select" name="calcificaciones"><option value="">No evaluadas</option><option value="Ausentes">Ausentes</option><option value="Moderadas">Moderadas (leves 3er trim)</option><option value="Extensas">Extensas</option></select></div>
<div class="col-md-4 mb-3"><label for="acretismo_figo_pas" class="form-label">Acretismo FIGO (PAS)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="PAS = Placenta Acreta Spectrum · FIGO = clasificación internacional de invasividad placentaria"></i></label><select class="form-select" name="acretismo_figo_pas"><option value="Grado 0">Grado 0 — Normal</option><option value="Grado 1">Grado 1 — Parcial</option><option value="Grado 2">Grado 2 — Invasión</option><option value="Grado 3">Grado 3 — Percretismo</option></select></div>
</div>
<!-- Doppler Placentario 3D -->
<hr><h6 class="text-muted">Índice de Perfusión Placentaria (Doppler 3D)</h6>
<div class="row">
<div class="col-md-4 mb-3"><label for="perfusion_vi" class="form-label">VI (Vascularization Index) %<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="VI = proporción de píxeles vasculares en la placenta (Doppler 3D)"></i></label><input type="number" step="0.01" class="form-control" name="perfusion_vi" placeholder="20-40%"></div>
<div class="col-md-4 mb-3"><label for="perfusion_fi" class="form-label">FI (Flow Index) %<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="FI = intensidad media del flujo en los vasos placentarios (Doppler 3D)"></i></label><input type="number" step="0.01" class="form-control" name="perfusion_fi" placeholder="30-50%"></div>
<div class="col-md-4 mb-3"><label for="perfusion_vfi" class="form-label">VFI (Vascularization-Flow Index) %<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="VFI = combinación de VI y FI; refleja la perfusión global placentaria en 3D"></i></label><input type="number" step="0.01" class="form-control" name="perfusion_vfi" placeholder="5-15%"></div>
</div>
<!-- Miomas Uterinos -->
<hr><h6 class="text-muted">Miomas Uterinos y Morfología</h6>
<div class="row">
<div class="col-md-4 mb-3"><label for="morfologia_uterina_eshre" class="form-label">Morfología Uterina <?php if(!empty($data1er['morfologia_uterina_eshre'])): ?><small class="text-muted">| 1T: <?php echo $data1er['morfologia_uterina_eshre']; ?></small><?php endif; ?></label><select class="form-select" name="morfologia_uterina_eshre"><option value="">No evaluado</option><?php foreach(['U0','U1','U2','U3','U4','U5','U6'] as $o): ?><option><?php echo $o; ?></option><?php endforeach; ?></select></div>
<div class="col-md-4 mb-3"><label class="form-label">Miomas <?php if(!empty($data1er['miomas_visibles'])): ?><small class="text-muted">| 1T: FIGO <?php echo $data1er['miomas_figo_tipo']??'—'; ?></small><?php endif; ?></label><div class="form-check"><input class="form-check-input" type="checkbox" name="miomas_visibles"><label class="form-check-label">Miomas Visibles</label></div></div>
<div class="col-md-4 mb-3"><label for="miomas_figo_tipo" class="form-label">FIGO Tipo</label><select class="form-select" name="miomas_figo_tipo"><option value="">No aplica</option><?php for($i=0;$i<=8;$i++): ?><option value="<?php echo $i; ?>">Tipo <?php echo $i; ?></option><?php endfor; ?></select></div>
<div class="col-md-4 mb-3"><label for="miomas_dimensiones_mm" class="form-label">Dimensiones (mm)</label><input type="text" class="form-control" name="miomas_dimensiones_mm" placeholder="Ej: 30x25"></div>
<div class="col-md-4 mb-3"><label class="form-label">&nbsp;</label><div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="miomas_obstruyen_canal"><label class="form-check-label">Obstruyen Canal de Parto</label></div></div>
</div>
<!-- Observaciones -->
<div class="row"><div class="col-12 mb-3"><label for="observaciones" class="form-label">Observaciones</label><textarea class="form-control" name="observaciones" rows="3" placeholder="Hallazgos adicionales, notas médicas..."></textarea></div></div>
</div></div>

<!-- Imágenes del Estudio -->
<div class="card mb-4">
    <div class="card-header"><i class="fa-solid fa-images me-2"></i> Imágenes del Estudio</div>
    <div class="card-body">
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

<div class="d-flex justify-content-end gap-2 mb-4">
<a href="<?php echo Url::to('/evaluaciones_3er_trimestre');?>" class="btn btn-apple btn-apple-secondary">Cancelar</a>
<button type="submit" class="btn btn-apple btn-apple-primary btn-lg"><i class="fa-solid fa-save"></i> Guardar Evaluación</button></div>
</form>
<script>
(function(){
    var sel = document.querySelector('[name="feto_unico_vivo"]');
    var cardCrec = document.getElementById('card-crecimiento');
    var cardDop = document.getElementById('card-doppler');
    var fcf = document.querySelector('[name="fcf_lpm"]');
    var movFet = document.querySelector('[name="movimientos_fetales"]');
    function toggle() {
        var muerto = sel.value === 'Muerto';
        cardCrec.style.display = muerto ? 'none' : '';
        cardDop.style.display = muerto ? 'none' : '';
        fcf.disabled = muerto;
        if (muerto) { fcf.value = ''; if (movFet) movFet.value = 'Disminuidos'; }
    }
    sel.addEventListener('change', toggle);
    toggle();

    // Uploader de imágenes
    var uploadZone = document.getElementById('uploadZone');
    var imgInput = document.getElementById('imagenesInput');
    var previewGrid = document.getElementById('previewGrid');
    var uploadCount = document.getElementById('uploadCount');
    var selectedFiles = [];

    if (uploadZone && imgInput) {
        uploadZone.addEventListener('click', function() { imgInput.click(); });
        uploadZone.addEventListener('dragover', function(e) { e.preventDefault(); uploadZone.style.borderColor = '#999'; });
        uploadZone.addEventListener('dragleave', function() { uploadZone.style.borderColor = '#ccc'; });
        uploadZone.addEventListener('drop', function(e) { e.preventDefault(); uploadZone.style.borderColor = '#ccc'; handleFiles(e.dataTransfer.files); });
        imgInput.addEventListener('change', function() { handleFiles(imgInput.files); });
    }

    function handleFiles(files) {
        if (selectedFiles.length >= 10) { alert('Máximo 10 imágenes.'); return; }
        for (var i = 0; i < files.length; i++) {
            if (selectedFiles.length >= 10) break;
            var f = files[i];
            if (f.size > 5*1024*1024) { alert('La imagen '+f.name+' excede 5 MB.'); continue; }
            if (['image/jpeg','image/png'].indexOf(f.type) === -1) { alert(f.name+' no es JPG/PNG.'); continue; }
            selectedFiles.push(f);
            var reader = new FileReader();
            reader.onload = (function(file) { return function(e) {
                var col = document.createElement('div');
                col.className = 'col-auto position-relative';
                col.innerHTML = '<img src="'+e.target.result+'" class="rounded" style="width:120px;height:120px;object-fit:cover;"><button type="button" class="btn-close position-absolute top-0 end-0 m-1 bg-white rounded-circle p-1 shadow-sm" style="font-size:10px;width:20px;height:20px;"></button>';
                previewGrid.appendChild(col);
                col.querySelector('.btn-close').addEventListener('click', function() {
                    var idx = selectedFiles.indexOf(file);
                    if (idx > -1) { selectedFiles.splice(idx, 1); }
                    col.remove(); updateCount(); syncFileInput();
                });
                updateCount();
            }; })(f);
            reader.readAsDataURL(f);
        }
        syncFileInput();
    }

    function syncFileInput() {
        var dt = new DataTransfer();
        for (var i = 0; i < selectedFiles.length; i++) dt.items.add(selectedFiles[i]);
        imgInput.files = dt.files;
    }

    function updateCount() {
        if (selectedFiles.length > 0) {
            uploadCount.style.display = 'block';
            uploadCount.textContent = selectedFiles.length + ' imagen(es) seleccionada(s)';
        } else uploadCount.style.display = 'none';
    }
})();

function toggleMarcadores() {
    var checks = document.querySelectorAll('.anat-check');
    var allChecked = true;
    checks.forEach(function(chk) { if (!chk.checked) allChecked = false; });
    var section = document.getElementById('marcadoresSection');
    if (section) {
        section.style.display = allChecked ? 'none' : 'block';
    }
}
</script>
<?php require_once __DIR__.'/../layouts/footer.php';?>
