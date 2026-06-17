<?php
$title="Editar Evaluación 3er Trimestre";
require_once __DIR__.'/../layouts/header.php';
require_once __DIR__.'/../layouts/sidebar.php';
$chk=fn($v)=>($v==1||$v===true)?'checked':'';
$sel=fn($v,$t)=>($v==$t)?'selected':'';
$ev=$evaluacion;$a=$antecedentes;$c=$crecimiento;$d=$doppler;$an=$anatomia;$pl=$placentaria;$h=$historial;
?><div class="page-header"><div class="d-flex align-items-center gap-3">
<a href="<?php echo Url::to('/evaluaciones_3er_trimestre');?>" class="btn btn-apple btn-apple-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
<h1 class="page-title mb-0">Editar: <?php echo htmlspecialchars($ev['codigo_reporte']);?></h1></div></div>
<form action="<?php echo Url::to('/evaluaciones_3er_trimestre/update');?>" method="POST" enctype="multipart/form-data"><input type="hidden" name="id" value="<?php echo $ev['id'];?>">

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-id-card me-2"></i> Datos Generales</div><div class="card-body">
<div class="row"><div class="col-md-3 mb-3"><label class="form-label">Código</label><input type="text" class="form-control" value="<?php echo htmlspecialchars($ev['codigo_reporte']);?>" readonly></div>
<div class="col-md-3 mb-3"><label for="fecha_evaluacion" class="form-label">Fecha Evaluación *</label><input type="date" class="form-control" name="fecha_evaluacion" value="<?php echo htmlspecialchars($ev['fecha_evaluacion']);?>" readonly></div>
<div class="col-md-3 mb-3"><label for="fecha_estudio" class="form-label">Fecha Estudio</label><input type="date" class="form-control" name="fecha_estudio" value="<?php echo htmlspecialchars($ev['fecha_estudio']??'');?>"></div>
<div class="col-md-3 mb-3"><label for="estudio_solicitado" class="form-label">Estudio Solicitado</label><input type="text" class="form-control" name="estudio_solicitado" value="<?php echo htmlspecialchars($ev['estudio_solicitado']??'');?>" placeholder="Ej: Crecimiento Fetal"></div></div>
<div class="row"><div class="col-md-3 mb-3"><label for="equipo_ultrasonido" class="form-label">Equipo Ultrasonográfico</label><input type="text" class="form-control" name="equipo_ultrasonido" value="<?php echo htmlspecialchars($ev['equipo_ultrasonido']??'');?>" placeholder="Ej: GE Volusson Expert"></div>
<div class="col-md-3 mb-3"><label for="estado" class="form-label">Estado</label><select class="form-select" name="estado"><?php foreach(['Pendiente','En proceso','Completado','Archivado'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($ev['estado']??'Pendiente',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div></div>
<div class="row"><div class="col-md-12 mb-3"><label for="paciente_id" class="form-label">Paciente *</label><select class="form-select" name="paciente_id" required><option value="">Seleccione</option><?php foreach($pacientes as $p):?><option value="<?php echo $p['id'];?>" <?php echo $sel($ev['paciente_id'],$p['id']);?>><?php echo htmlspecialchars($p['nombre'].' '.$p['apellido']);?></option><?php endforeach;?></select></div></div>
</div></div>

<!-- Referencia Médica -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-user-doctor me-2"></i> Referencia Médica</div><div class="card-body">
<div class="row">
<div class="col-md-4 mb-3"><label class="form-label">Médico Solicitante</label><select name="medico_solicitante_id" class="form-select"><option value="">Seleccionar...</option><?php foreach($medicos as $m):?><option value="<?php echo $m['id'];?>" <?php echo $sel($ev['medico_solicitante_id']??'',$m['id']);?>><?php echo htmlspecialchars($m['nombre'].' '.$m['apellido'].($m['especialidad']?' - '.$m['especialidad']:''));?></option><?php endforeach;?></select></div>
<div class="col-md-4 mb-3"><label class="form-label">Médico que Realiza <span class="text-danger">*</span></label><select name="medico_id" class="form-select" required><option value="">Seleccionar...</option><?php foreach($medicos as $m):?><option value="<?php echo $m['id'];?>" <?php echo $sel($ev['medico_id'],$m['id']);?>><?php echo htmlspecialchars($m['nombre'].' '.$m['apellido'].($m['especialidad']?' - '.$m['especialidad']:''));?></option><?php endforeach;?></select></div>
<div class="col-md-4 mb-3"><label class="form-label">Médico Referido</label><select name="medico_referido_id" class="form-select"><option value="">Ninguno</option><?php foreach($medicos as $m):?><option value="<?php echo $m['id'];?>" <?php echo $sel($ev['medico_referido_id']??'',$m['id']);?>><?php echo htmlspecialchars($m['nombre'].' '.$m['apellido'].($m['especialidad']?' - '.$m['especialidad']:''));?></option><?php endforeach;?></select></div>
</div>
</div></div>

<?php if (!empty($data1er) || !empty($data2do)): ?>
<div class="accordion mb-4" id="refTrimestresPrevios">
    <?php if (!empty($data1er)): ?>
    <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1er3T"><i class="fa-solid fa-folder-open me-2"></i> Datos del 1er Trimestre</button></h2>
    <div id="collapse1er3T" class="accordion-collapse collapse" data-bs-parent="#refTrimestresPrevios"><div class="accordion-body">
    <div class="row">
        <div class="col-md-3"><strong>FPP USG:</strong> <?php echo !empty($data1er['fpp_usg'])?date('d/m/Y',strtotime($data1er['fpp_usg'])):'—';?></div>
        <div class="col-md-3"><strong>EG 1T:</strong> <?php echo !empty($data1er['edad_gestacional_semanas'])?$data1er['edad_gestacional_semanas'].' sem':'—';?></div>
        <div class="col-md-3"><strong>Peso 1T:</strong> <?php echo !empty($data1er['peso_kg'])?$data1er['peso_kg'].' kg':'—';?></div>
        <div class="col-md-3"><strong>LCC:</strong> <?php echo !empty($data1er['lcc_mm'])?$data1er['lcc_mm'].' mm':'—';?></div>
        <div class="col-md-4 mt-2"><strong>Riesgo Preeclampsia:</strong> <?php echo $data1er['riesgo_preeclampsia_temprana']??'—';?></div>
        <div class="col-md-4 mt-2"><strong>Doppler UT PI:</strong> <?php echo !empty($data1er['uta_pi_promedio'])?$data1er['uta_pi_promedio']:'—';?> <?php echo !empty($data1er['muesca_bilateral'])?'<span class="text-danger">(Muesca)</span>':'';?></div>
        <div class="col-md-4 mt-2"><strong>Tamizaje Genético:</strong> <?php echo !empty($data1er['tamizaje_genetico_tipo'])?$data1er['tamizaje_genetico_tipo'].' — '.($data1er['tamizaje_genetico_resultado']??'—'):'—';?></div>
    </div></div></div></div><?php endif;?>
    <?php if (!empty($data2do)): ?>
    <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2do3T"><i class="fa-solid fa-folder-open me-2"></i> Datos del 2do Trimestre</button></h2>
    <div id="collapse2do3T" class="accordion-collapse collapse" data-bs-parent="#refTrimestresPrevios"><div class="accordion-body">
    <div class="row">
        <div class="col-md-4"><strong>Morfología Fetal:</strong> <?php
            $morfNormal=true;
            $cmf=['craneo_snc_normal','cara_cuello_normal','corazon_normal','torax_diafragma_normal','abdomen_normal','genitourinario_normal','columna_normal','extremidades_normal'];
            foreach($cmf as $cm)if(isset($data2do[$cm])&&$data2do[$cm]==0){$morfNormal=false;break;}
            echo $morfNormal?'<span class="text-success">Normal</span>':'<span class="text-danger">Alterada</span>';
        ?></div>
        <div class="col-md-4"><strong>Doppler UT PI 2T:</strong> <?php echo !empty($data2do['uta_pi_promedio'])?$data2do['uta_pi_promedio']:'—';?></div>
        <div class="col-md-4"><strong>Placenta:</strong> <?php echo $data2do['placenta_posicion']??'—';?></div>
        <div class="col-md-4 mt-2"><strong>Long. Cervical 2T:</strong> <?php echo !empty($data2do['longitud_cervical_mm'])?$data2do['longitud_cervical_mm'].' mm':'—';?></div>
        <div class="col-md-4 mt-2"><strong>Funneling:</strong> <?php echo !empty($data2do['funneling_presente'])?'Sí ('.$data2do['funneling_mm'].' mm)':'No';?></div>
        <div class="col-md-4 mt-2"><strong>Signos RCIU:</strong> <?php $rciu=false;if(!empty($data2do['percentil_hadlock'])&&$data2do['percentil_hadlock']<10)$rciu=true;if(!empty($data2do['crecimiento_armonico'])&&$data2do['crecimiento_armonico']==0)$rciu=true;echo $rciu?'<span class="text-danger">Sí</span>':'<span class="text-success">No</span>';?></div>
    </div></div></div></div><?php endif;?>
</div>
<?php endif; ?>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-heart-pulse me-2"></i> Signos Vitales y Estática Fetal</div><div class="card-body">
<div class="row"><div class="col-md-3 mb-3"><label for="feto_unico_vivo" class="form-label">Condición Fetal</label><select class="form-select" name="feto_unico_vivo"><option value="">No evaluado</option><?php foreach(['Vivo','Muerto'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($ev['feto_unico_vivo']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-2 mb-3"><label for="fcf_lpm" class="form-label">FCF (lpm)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="FCF = Frecuencia Cardíaca Fetal en latidos por minuto"></i></label><input type="number" class="form-control" name="fcf_lpm" value="<?php echo htmlspecialchars($ev['fcf_lpm']??'');?>"></div>
<div class="col-md-2 mb-3"><label for="situacion_fetal" class="form-label">Situación Fetal</label><select class="form-select" name="situacion_fetal"><option value="">No evaluado</option><?php foreach(['Longitudinal','Transversa'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($ev['situacion_fetal']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-2 mb-3"><label for="presentacion_fetal" class="form-label">Presentación</label><select class="form-select" name="presentacion_fetal"><option value="">No evaluado</option><?php foreach(['Cefalico','Pelvico'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($ev['presentacion_fetal']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-3 mb-3"><label for="posicion_fetal" class="form-label">Posición Fetal</label><input type="text" class="form-control" name="posicion_fetal" value="<?php echo htmlspecialchars($ev['posicion_fetal']??'');?>"></div></div>
<div class="row"><div class="col-md-2 mb-3"><label for="edad_gestacional_semanas" class="form-label">EG (sem)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="EG = Edad Gestacional en semanas"></i></label><input type="number" step="0.1" class="form-control" name="edad_gestacional_semanas" value="<?php echo htmlspecialchars($ev['edad_gestacional_semanas']??'');?>"></div>
<div class="col-md-2 mb-3"><label for="fpp_fum" class="form-label">FPP por FUM<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="FPP = Fecha Probable de Parto · FUM = Fecha de Última Menstruación"></i></label><input type="date" class="form-control" name="fpp_fum" value="<?php echo htmlspecialchars($ev['fpp_fum']??'');?>"></div>
<div class="col-md-2 mb-3"><label for="fpp_usg" class="form-label">FPP por USG<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="FPP = Fecha Probable de Parto · USG = Ultrasonografía"></i></label><input type="date" class="form-control" name="fpp_usg" value="<?php echo htmlspecialchars($ev['fpp_usg']??'');?>"></div>
<div class="col-md-2 mb-3"><label for="peso_kg" class="form-label">Peso Materno (kg)</label><input type="number" step="0.01" class="form-control" name="peso_kg" value="<?php echo htmlspecialchars($ev['peso_kg']??'');?>"></div>
<div class="col-md-2 mb-3"><label for="talla_cm" class="form-label">Talla (cm)</label><input type="number" step="0.01" class="form-control" name="talla_cm" value="<?php echo htmlspecialchars($ev['talla_cm']??'');?>"></div>
<div class="col-md-2 mb-3"><label for="ta_sistolica" class="form-label">TA Sistólica</label><input type="number" class="form-control" name="ta_sistolica" value="<?php echo htmlspecialchars($ev['ta_sistolica']??'');?>"></div>
<div class="col-md-2 mb-3"><label for="ta_diastolica" class="form-label">TA Diastólica</label><input type="number" class="form-control" name="ta_diastolica" value="<?php echo htmlspecialchars($ev['ta_diastolica']??'');?>"></div></div>
</div></div>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-notes-medical me-2"></i> Antecedentes</div><div class="card-body">
<div class="row"><div class="col-md-4 mb-3"><label for="curva_tolerancia_glucosa" class="form-label">Curva Tolerancia Glucosa</label><select class="form-select" name="curva_tolerancia_glucosa"><?php foreach(['No realizada','Normal','Alterada'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($a['curva_tolerancia_glucosa']??'No realizada',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-4 mb-3"><div class="form-check mt-4"><input class="form-check-input" type="checkbox" name="diabetes_gestacional_actual" <?php echo $chk($a['diabetes_gestacional_actual']??false);?>><label class="form-check-label">Diabetes Gestacional Actual</label></div></div>
<div class="col-md-4 mb-3"><label for="movimientos_fetales" class="form-label">Movimientos Fetales</label><select class="form-select" name="movimientos_fetales"><?php foreach(['Normales','Disminuidos'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($a['movimientos_fetales']??'Normales',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div></div>
<div class="row"><div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="signos_amenaza_parto_pretermino" <?php echo $chk($a['signos_amenaza_parto_pretermino']??false);?>><label class="form-check-label">Signos Amenaza Parto Pretérmino</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="plan_nacimiento_definido" <?php echo $chk($a['plan_nacimiento_definido']??false);?>><label class="form-check-label">Plan de Nacimiento Definido</label></div></div></div>
</div></div>

<div class="card mb-4" id="card-crecimiento"><div class="card-header"><i class="fa-solid fa-weight-scale me-2"></i> Crecimiento y RCIU</div><div class="card-body">
<div class="row"><div class="col-md-3 mb-3"><label class="form-label">Peso Fetal (gr)</label><input type="number" class="form-control" name="peso_fetal_estimado_gr" value="<?php echo htmlspecialchars($c['peso_fetal_estimado_gr']??'');?>"></div>
<div class="col-md-3 mb-3"><label class="form-label">Percentil Ajustado</label><input type="number" class="form-control" name="percentil_ajustado" value="<?php echo htmlspecialchars($c['percentil_ajustado']??'');?>"></div>
<div class="col-md-3 mb-3"><label class="form-label">Clasificación</label><select class="form-select" name="clasificacion_crecimiento"><option value="">No evaluado</option><?php foreach(['Adecuado','Mayor a lo esperado','Menor a lo esperado'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($c['clasificacion_crecimiento']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-3 mb-3"><label class="form-label">RCIU Barcelona<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="RCIU = Restricción Crecimiento Intrauterino · Estadios I-IV según Clasificación de Barcelona"></i></label><select class="form-select" name="estadio_rciu_barcelona"><?php foreach(['Ninguno','Estadio I','Estadio II','Estadio III','Estadio IV'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($c['estadio_rciu_barcelona']??'Ninguno',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div></div>
</div></div>

<div class="card mb-4" id="card-doppler"><div class="card-header"><i class="fa-solid fa-wave-square me-2"></i> Doppler</div><div class="card-body">
<div class="row"><div class="col-md-3 mb-3"><label class="form-label">AU PI<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="AU = Arteria Umbilical · PI = Índice de Pulsatilidad; mide resistencia en el cordón umbilical"></i></label><input type="number" step="0.01" class="form-control" name="au_pi" value="<?php echo htmlspecialchars($d['au_pi']??'');?>"></div>
<div class="col-md-3 mb-3"><label class="form-label">Flujo Diastólico AU<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="AU = Arteria Umbilical; flujo ausente o reverso indica compromiso fetal severo"></i></label><select class="form-select" name="au_flujo_diastolico"><option value="">No evaluado</option><?php foreach(['Presente','Ausente','Reverso'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($d['au_flujo_diastolico']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-3 mb-3"><label class="form-label">ACM PI<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="ACM = Arteria Cerebral Media · PI = Índice de Pulsatilidad; disminuido indica redistribución hemodínámica"></i></label><input type="number" step="0.01" class="form-control" name="acm_pi" value="<?php echo htmlspecialchars($d['acm_pi']??'');?>"></div>
<div class="col-md-3 mb-3"><label class="form-label">DV Onda A<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="DV = Ductus Venoso · Onda A refleja función cardíaca fetal; reversa indica fallo cardíaco"></i></label><select class="form-select" name="dv_onda_a"><option value="">No evaluado</option><?php foreach(['Positiva','Ausente','Reversa'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($d['dv_onda_a']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div></div>
<div class="row"><div class="col-md-3 mb-3"><label class="form-label">UTA PI Promedio<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="UTA = Arterias Uterinas · PI = Índice de Pulsatilidad; elevado indica resistencia placentaria alta"></i></label><input type="number" step="0.01" class="form-control" name="uta_pi_promedio" value="<?php echo htmlspecialchars($d['uta_pi_promedio']??'');?>"></div>
<div class="col-md-3 mb-3"><label class="form-label">Ratio CU/ICP<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="CU = Cerebro-Umbilical (AU/ACM PI); ICP = Índice Cerebro-Placentario; &lt;1 indica redistribución"></i></label><input type="number" step="0.01" class="form-control" name="ratio_cu_icp" value="<?php echo htmlspecialchars($d['ratio_cu_icp']??'');?>"></div>
<div class="col-md-3 mb-3"><label class="form-label">Vena Umbilical</label><select class="form-select" name="vena_umbilical"><option value="">No evaluado</option><?php foreach(['Normal','Pulsatil'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($d['vena_umbilical']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-3 mb-3"><div class="form-check mt-4"><input class="form-check-input" type="checkbox" name="alteracion_doppler_detectada" <?php echo $chk($d['alteracion_doppler_detectada']??false);?>><label class="form-check-label">Alteración Doppler</label></div></div></div>
</div></div>

<!-- Anatomía Fetal -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-baby me-2"></i> Anatomía Fetal</div><div class="card-body">
    <div class="row">
        <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="craneo_snc_normal" <?php echo $chk($an['craneo_snc_normal']??true); ?> onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Cráneo/SNC sin alteraciones</label><small class="text-muted d-block ms-4">Forma y tamaño normal, SNC íntegro, ventriculomegalia &lt; 10 mm, surcos y giros acordes a edad gestacional</small></div></div>
        <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="cara_cuello_normal" <?php echo $chk($an['cara_cuello_normal']??true); ?> onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Cara/Cuello sin alteraciones</label><small class="text-muted d-block ms-4">Órbitas presentes y simétricas, labio superior íntegro, perfil facial normal</small></div></div>
        <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="corazon_normal" <?php echo $chk($an['corazon_normal']??true); ?> onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Corazón sin alteraciones</label><small class="text-muted d-block ms-4">Situs solitus, eje cardíaco normal, 4 cámaras, cruce de grandes vasos normal, ritmo regular</small></div></div>
        <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="torax_diafragma_normal" <?php echo $chk($an['torax_diafragma_normal']??true); ?> onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Tórax/Diafragma sin alteraciones</label><small class="text-muted d-block ms-4">Pulmones con ecogenicidad normal, parénquima homogéneo, diafragma íntegro</small></div></div>
        <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="abdomen_normal" <?php echo $chk($an['abdomen_normal']??true); ?> onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Abdomen sin alteraciones</label><small class="text-muted d-block ms-4">Estómago presente, intestino sin dilataciones, pared abdominal íntegra, cordón umbilical de inserción normal</small></div></div>
        <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="genitourinario_normal" <?php echo $chk($an['genitourinario_normal']??true); ?> onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Genitourinario sin alteraciones</label><small class="text-muted d-block ms-4">Riñones de tamaño y morfología normal, sin dilatación pielocalicial, vejiga presente</small></div></div>
        <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="columna_normal" <?php echo $chk($an['columna_normal']??true); ?> onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Columna sin alteraciones</label><small class="text-muted d-block ms-4">Alineación normal, arcos vertebrales íntegros, cono medular en posición normal</small></div></div>
        <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="extremidades_normal" <?php echo $chk($an['extremidades_normal']??true); ?> onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Extremidades sin alteraciones</label><small class="text-muted d-block ms-4">Los 4 miembros presentes, 3 segmentos, manos y pies normales, movimientos activos</small></div></div>
    </div>
    <div class="mt-3"><label for="detalles_anatomia" class="form-label">Detalles de Anomalías</label><textarea class="form-control" name="detalles_anatomia" rows="2"><?php echo htmlspecialchars($an['detalles_anatomia']??''); ?></textarea></div>
</div></div>

<!-- Líquido Amniótico y Cordón -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-droplet me-2"></i> Líquido Amniótico y Cordón Umbilical</div><div class="card-body">
<div class="row"><div class="col-md-3 mb-3"><label class="form-label">Circular Cordón</label><select class="form-select" name="circular_cordon_cuello"><?php foreach(['Negativo','Simple','Doble'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($an['circular_cordon_cuello']??'Negativo',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-3 mb-3"><label class="form-label">Líquido Amniótico (mm)</label><input type="number" class="form-control" name="liquido_amniotico_mm" value="<?php echo htmlspecialchars($an['liquido_amniotico_mm']??'');?>"></div>
<div class="col-md-3 mb-3"><label class="form-label">Método Medición</label><select class="form-select" name="metodo_medicion_liquido"><?php foreach(['Bolsillo Maximo','Phelan'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($an['metodo_medicion_liquido']??'Bolsillo Maximo',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-3 mb-3"><label class="form-label">Diagnóstico Líquido</label><select class="form-select" name="diagnostico_liquido"><?php foreach(['Normal','Oligohidramnios','Polihidramnios'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($an['diagnostico_liquido']??'Normal',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div></div>
</div></div>

<!-- Marcadores Ecográficos -->
<?php
$todosSinAlt3t = true;
$anatFields3t = ['craneo_snc_normal','cara_cuello_normal','corazon_normal','torax_diafragma_normal','abdomen_normal','genitourinario_normal','columna_normal','extremidades_normal'];
foreach ($anatFields3t as $f) { if (!($an[$f] ?? true)) { $todosSinAlt3t = false; break; } }
?>
<div id="marcadoresSection" class="card mb-4" style="<?php echo $todosSinAlt3t ? 'display:none;' : ''; ?>"><div class="card-header"><i class="fa-solid fa-magnifying-glass me-2"></i> Marcadores Ecográficos <span class="badge bg-warning text-dark ms-2">Activado por alteración anatómica</span></div><div class="card-body">
    <div class="row">
        <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="ventriculomegalia_leve" <?php echo $chk(false); ?>><label class="form-check-label">Ventriculomegalia Leve</label></div></div>
        <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="quistes_plexos_coroideos" <?php echo $chk(false); ?>><label class="form-check-label">Quistes Plexos Coroideos</label></div></div>
        <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="pliegue_nucal_aumentado" <?php echo $chk(false); ?>><label class="form-check-label">Pliegue Nucal Aumentado</label></div></div>
        <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="hueso_nasal_ausente" <?php echo $chk(false); ?>><label class="form-check-label">Hueso Nasal Ausente</label></div></div>
        <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="foco_ecogenico_cardiaco" <?php echo $chk(false); ?>><label class="form-check-label">Foco Ecogénico Cardíaco</label></div></div>
        <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="intestino_hiperecogenico" <?php echo $chk(false); ?>><label class="form-check-label">Intestino Hiperecogénico</label></div></div>
        <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="femur_corto" <?php echo $chk(false); ?>><label class="form-check-label">Fémur Corto</label></div></div>
        <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="arteria_umbilical_unica" <?php echo $chk(false); ?>><label class="form-check-label">Arteria Umbilical Única</label></div></div>
    </div>
</div></div>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-uterus me-2"></i> Evaluación Placentaria (AJOG 2025 / FIGO 2023)</div><div class="card-body">
<div class="row"><div class="col-md-4 mb-3"><label class="form-label">Localización</label><select class="form-select" name="localizacion_placentaria"><option value="">No evaluada</option><?php foreach(['Anterior','Posterior','Fundica','Lateral Derecha','Lateral Izquierda'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($pl['localizacion_placentaria']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-4 mb-3"><label class="form-label">Distancia OCI (mm)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="OCI = Orificio Cervical Interno; &ge;20mm = normal, &lt;20mm = placenta baja/previa"></i></label><input type="number" step="0.01" class="form-control" name="distancia_oci_mm" value="<?php echo htmlspecialchars($pl['distancia_oci_mm']??'');?>"></div>
<div class="col-md-4 mb-3"><label class="form-label">Grado Madurez</label><select class="form-select" name="grado_madurez"><option value="">No evaluado</option><?php foreach(['Grado 0-1','Grado 2','Grado 3'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($pl['grado_madurez']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div></div>
<div class="row"><div class="col-md-3 mb-3"><label class="form-label">Grosor Placentario (mm)</label><input type="number" class="form-control" name="grosor_placentario_mm" value="<?php echo htmlspecialchars($pl['grosor_placentario_mm']??'');?>"></div>
<div class="col-md-3 mb-3"><label class="form-label">Ecogenicidad</label><select class="form-select" name="ecogenicidad"><option value="">No evaluada</option><?php foreach(['Homogenea','Heterogenea'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($pl['ecogenicidad']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-3 mb-3"><label class="form-label">Inserción del Cordón</label><select class="form-select" name="insercion_cordon"><option value="">No evaluada</option><?php foreach(['Central','Paracentral','Marginal','Velamentosa'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($pl['insercion_cordon']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-3 mb-3"><label class="form-label">N° Vasos Umbilicales</label><select class="form-select" name="numero_vasos_umbilicales"><option value="">No evaluado</option><?php foreach(['3','2'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($pl['numero_vasos_umbilicales']??'',$o);?>><?php echo $o;?> vasos</option><?php endforeach;?></select></div></div>
<div class="row"><div class="col-md-4 mb-3"><label class="form-label">Lagunas Vasculares</label><select class="form-select" name="lagunas_vasculares"><?php foreach(['Ausentes/minimas','Si','Extensas'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($pl['lagunas_vasculares']??'Ausentes/minimas',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-4 mb-3"><label class="form-label">Interfase Miometrial</label><select class="form-select" name="interfase_miometrial"><?php foreach(['Intacta','Adelgazada','Discontinua'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($pl['interfase_miometrial']??'Intacta',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-4 mb-3"><label class="form-label">Zona Retroplacentaria</label><select class="form-select" name="zona_retroplacentaria"><option value="">No evaluada</option><?php foreach(['Presente','Ausente'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($pl['zona_retroplacentaria']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div></div>
<div class="row"><div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="vasos_puente" <?php echo $chk($pl['vasos_puente']??false);?>><label class="form-check-label">Vasos Puente</label></div></div>
<div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="protrusion_placentaria" <?php echo $chk($pl['protrusion_placentaria']??false);?>><label class="form-check-label">Protrusión Placentaria</label></div></div>
<div class="col-md-6 mb-3"><label class="form-label">Vascularización Doppler</label><select class="form-select" name="vascularizacion_anomala_doppler"><option value="">No evaluada</option><?php foreach(['Normal','Turbulento','Extendido a vejiga'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($pl['vascularizacion_anomala_doppler']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div></div>
<div class="row"><div class="col-md-4 mb-3"><label class="form-label">Calcificaciones</label><select class="form-select" name="calcificaciones"><option value="">No evaluadas</option><?php foreach(['Ausentes','Moderadas','Extensas'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($pl['calcificaciones']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-4 mb-3"><label class="form-label">Acretismo FIGO (PAS)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="PAS = Placenta Acreta Spectrum · FIGO = clasificación internacional de invasividad placentaria"></i></label><select class="form-select" name="acretismo_figo_pas"><?php foreach(['Grado 0','Grado 1','Grado 2','Grado 3'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($pl['acretismo_figo_pas']??'Grado 0',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div></div>
<hr><h6 class="text-muted">Índice de Perfusión Placentaria (Doppler 3D)</h6>
<div class="row"><div class="col-md-4 mb-3"><label class="form-label">VI %<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="VI = Vascularization Index; proporción de píxeles vasculares (Doppler 3D)"></i></label><input type="number" step="0.01" class="form-control" name="perfusion_vi" value="<?php echo htmlspecialchars($pl['perfusion_vi']??'');?>" placeholder="20-40%"></div>
<div class="col-md-4 mb-3"><label class="form-label">FI %<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="FI = Flow Index; intensidad media del flujo en los vasos placentarios (Doppler 3D)"></i></label><input type="number" step="0.01" class="form-control" name="perfusion_fi" value="<?php echo htmlspecialchars($pl['perfusion_fi']??'');?>" placeholder="30-50%"></div>
<div class="col-md-4 mb-3"><label class="form-label">VFI %<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="VFI = Vascularization-Flow Index; combinación de VI y FI; refleja la perfusión global placentaria"></i></label><input type="number" step="0.01" class="form-control" name="perfusion_vfi" value="<?php echo htmlspecialchars($pl['perfusion_vfi']??'');?>" placeholder="5-15%"></div></div>
<hr><h6 class="text-muted">Miomas Uterinos y Morfología</h6>
<div class="row">
    <div class="col-md-4 mb-3"><label class="form-label">Morfología Uterina <?php if(!empty($data1er['morfologia_uterina_eshre'])):?><small class="text-muted">| 1T: <?php echo $data1er['morfologia_uterina_eshre'];?></small><?php endif;?></label><select class="form-select" name="morfologia_uterina_eshre"><option value="">No evaluado</option><?php foreach(['U0','U1','U2','U3','U4','U5','U6'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($pl['morfologia_uterina_eshre']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
    <div class="col-md-4 mb-3"><label class="form-label">Miomas <?php if(!empty($data1er['miomas_visibles'])):?><small class="text-muted">| 1T: FIGO <?php echo $data1er['miomas_figo_tipo']??'—';?></small><?php endif;?></label><div class="form-check"><input class="form-check-input" type="checkbox" name="miomas_visibles" <?php echo $chk($pl['miomas_visibles']??false);?>><label class="form-check-label">Miomas Visibles</label></div></div>
    <div class="col-md-4 mb-3"><label class="form-label">FIGO Tipo</label><select class="form-select" name="miomas_figo_tipo"><option value="">No aplica</option><?php for($i=0;$i<=8;$i++):?><option value="<?php echo $i;?>" <?php echo $sel($pl['miomas_figo_tipo']??'',$i);?>>Tipo <?php echo $i;?></option><?php endfor;?></select></div>
    <div class="col-md-4 mb-3"><label class="form-label">Dimensiones (mm)</label><input type="text" class="form-control" name="miomas_dimensiones_mm" value="<?php echo htmlspecialchars($pl['miomas_dimensiones_mm']??'');?>" placeholder="Ej: 30x25"></div>
    <div class="col-md-4 mb-3"><div class="form-check mt-4"><input class="form-check-input" type="checkbox" name="miomas_obstruyen_canal" <?php echo $chk($pl['miomas_obstruyen_canal']??false);?>><label class="form-check-label">Obstruyen Canal de Parto</label></div></div>
</div>
<div class="row"><div class="col-12 mb-3"><label class="form-label">Observaciones</label><textarea class="form-control" name="observaciones" rows="3"><?php echo htmlspecialchars($ev['observaciones']??'');?></textarea></div></div>
</div></div>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-notes-medical me-2"></i> Historial Clínico</div><div class="card-body"><div class="row">
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="hipertension_cronica" <?php echo $chk($h['hipertension_cronica']??false);?>><label class="form-check-label">Hipertensión Crónica</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="diabetes" <?php echo $chk($h['diabetes']??false);?>><label class="form-check-label">Diabetes</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="lupus_les" <?php echo $chk($h['lupus_les']??false);?>><label class="form-check-label">Lupus / LES <i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="LES = Lupus Eritematoso Sistémico, enfermedad autoinmune"></i></label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="sindrome_antifosfolipido_saf" <?php echo $chk($h['sindrome_antifosfolipido_saf']??false);?>><label class="form-check-label">Síndrome Antifosfolípido <i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="SAF = trastorno autoinmune que favorece trombosis en el embarazo"></i></label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="antecedente_preeclampsia_rciu" <?php echo $chk($h['antecedente_preeclampsia_rciu']??false);?>><label class="form-check-label">Ant. Preeclampsia/RCIU <i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="RCIU = Restricción del Crecimiento Intrauterino"></i></label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="fertilizacion_in_vitro" <?php echo $chk($h['fertilizacion_in_vitro']??false);?>><label class="form-check-label">Fertilización In Vitro</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="antecedente_parto_pretermino" <?php echo $chk($h['antecedente_parto_pretermino']??false);?>><label class="form-check-label">Ant. Parto Pretérmino</label></div></div>
</div></div></div>

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

<div class="card mb-4"><div class="card-body"><div class="row align-items-end">
<div class="col-md-8 text-end">
<form action="<?php echo Url::to('/evaluaciones_3er_trimestre/delete');?>" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');"><input type="hidden" name="id" value="<?php echo $ev['id'];?>"><button type="submit" class="btn btn-apple btn-apple-danger me-2"><i class="fa-solid fa-trash"></i> Eliminar</button></form>
<a href="<?php echo Url::to('/evaluaciones_3er_trimestre');?>" class="btn btn-apple btn-apple-secondary me-2">Cancelar</a>
<button type="submit" class="btn btn-apple btn-apple-primary btn-lg"><i class="fa-solid fa-save"></i> Actualizar</button></div></div></div></div>
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
    var eliminarInput = document.getElementById('imagenesEliminar');
    var selectedFiles = [];
    var deletedIds = [];

    if (uploadZone && imgInput) {
        uploadZone.addEventListener('click', function() { imgInput.click(); });
        uploadZone.addEventListener('dragover', function(e) { e.preventDefault(); uploadZone.style.borderColor = '#999'; });
        uploadZone.addEventListener('dragleave', function() { uploadZone.style.borderColor = '#ccc'; });
        uploadZone.addEventListener('drop', function(e) { e.preventDefault(); uploadZone.style.borderColor = '#ccc'; handleFiles(e.dataTransfer.files); });
        imgInput.addEventListener('change', function() { handleFiles(imgInput.files); });
    }

    var delBtns = document.querySelectorAll('.delete-existing');
    for (var d = 0; d < delBtns.length; d++) {
        delBtns[d].addEventListener('click', function() {
            var id = parseInt(this.dataset.id);
            if (id) { deletedIds.push(id); eliminarInput.value = deletedIds.join(','); }
            this.closest('.col-auto').remove();
        });
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
            uploadCount.textContent = selectedFiles.length + ' imagen(es) nueva(s) seleccionada(s)';
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
