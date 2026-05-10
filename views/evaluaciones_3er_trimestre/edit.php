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
<form action="<?php echo Url::to('/evaluaciones_3er_trimestre/update');?>" method="POST"><input type="hidden" name="id" value="<?php echo $ev['id'];?>">

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-id-card me-2"></i> Datos Generales</div><div class="card-body">
<div class="row"><div class="col-md-3 mb-3"><label class="form-label">Código</label><input type="text" class="form-control" value="<?php echo htmlspecialchars($ev['codigo_reporte']);?>" readonly></div>
<div class="col-md-3 mb-3"><label for="fecha_evaluacion" class="form-label">Fecha Evaluación *</label><input type="date" class="form-control" name="fecha_evaluacion" value="<?php echo htmlspecialchars($ev['fecha_evaluacion']);?>" readonly></div>
<div class="col-md-3 mb-3"><label for="fecha_estudio" class="form-label">Fecha Estudio</label><input type="date" class="form-control" name="fecha_estudio" value="<?php echo htmlspecialchars($ev['fecha_estudio']??'');?>"></div>
<div class="col-md-3 mb-3"><label for="estado" class="form-label">Estado</label><select class="form-select" name="estado"><?php foreach(['Pendiente','En proceso','Completado','Archivado'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($ev['estado']??'Pendiente',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div></div>
<div class="row"><div class="col-md-6 mb-3"><label for="paciente_id" class="form-label">Paciente *</label><select class="form-select" name="paciente_id" required><option value="">Seleccione</option><?php foreach($pacientes as $p):?><option value="<?php echo $p['id'];?>" <?php echo $sel($ev['paciente_id'],$p['id']);?>><?php echo htmlspecialchars($p['nombre'].' '.$p['apellido']);?></option><?php endforeach;?></select></div>
<div class="col-md-6 mb-3"><label for="medico_id" class="form-label">Médico *</label><select class="form-select" name="medico_id" required><option value="">Seleccione</option><?php foreach($medicos as $m):?><option value="<?php echo $m['id'];?>" <?php echo $sel($ev['medico_id'],$m['id']);?>><?php echo htmlspecialchars($m['nombre'].' '.$m['apellido'].($m['especialidad']?' - '.$m['especialidad']:''));?></option><?php endforeach;?></select></div></div>
</div></div>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-heart-pulse me-2"></i> Signos Vitales y Estática Fetal</div><div class="card-body">
<div class="row"><div class="col-md-3 mb-3"><label for="edad_gestacional_semanas" class="form-label">Edad Gestacional (sem)</label><input type="number" step="0.1" class="form-control" name="edad_gestacional_semanas" value="<?php echo htmlspecialchars($ev['edad_gestacional_semanas']??'');?>"></div>
<div class="col-md-3 mb-3"><label for="peso_kg" class="form-label">Peso Materno (kg)</label><input type="number" step="0.01" class="form-control" name="peso_kg" value="<?php echo htmlspecialchars($ev['peso_kg']??'');?>"></div>
<div class="col-md-3 mb-3"><label for="ta_sistolica" class="form-label">TA Sistólica</label><input type="number" class="form-control" name="ta_sistolica" value="<?php echo htmlspecialchars($ev['ta_sistolica']??'');?>"></div>
<div class="col-md-3 mb-3"><label for="ta_diastolica" class="form-label">TA Diastólica</label><input type="number" class="form-control" name="ta_diastolica" value="<?php echo htmlspecialchars($ev['ta_diastolica']??'');?>"></div></div>
<div class="row"><div class="col-md-3 mb-3"><label for="fcf_lpm" class="form-label">FCF (lpm)</label><input type="number" class="form-control" name="fcf_lpm" value="<?php echo htmlspecialchars($ev['fcf_lpm']??'');?>"></div>
<div class="col-md-3 mb-3"><label for="situacion_fetal" class="form-label">Situación Fetal</label><select class="form-select" name="situacion_fetal"><option value="">No evaluado</option><?php foreach(['Longitudinal','Transversa'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($ev['situacion_fetal']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-3 mb-3"><label for="presentacion_fetal" class="form-label">Presentación</label><select class="form-select" name="presentacion_fetal"><option value="">No evaluado</option><?php foreach(['Cefalico','Pelvico'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($ev['presentacion_fetal']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-3 mb-3"><label for="posicion_fetal" class="form-label">Posición Fetal</label><input type="text" class="form-control" name="posicion_fetal" value="<?php echo htmlspecialchars($ev['posicion_fetal']??'');?>"></div></div>
</div></div>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-notes-medical me-2"></i> Antecedentes</div><div class="card-body">
<div class="row"><div class="col-md-4 mb-3"><label for="curva_tolerancia_glucosa" class="form-label">Curva Tolerancia Glucosa</label><select class="form-select" name="curva_tolerancia_glucosa"><?php foreach(['No realizada','Normal','Alterada'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($a['curva_tolerancia_glucosa']??'No realizada',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-4 mb-3"><div class="form-check mt-4"><input class="form-check-input" type="checkbox" name="diabetes_gestacional_actual" <?php echo $chk($a['diabetes_gestacional_actual']??false);?>><label class="form-check-label">Diabetes Gestacional Actual</label></div></div>
<div class="col-md-4 mb-3"><label for="movimientos_fetales" class="form-label">Movimientos Fetales</label><select class="form-select" name="movimientos_fetales"><?php foreach(['Normales','Disminuidos'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($a['movimientos_fetales']??'Normales',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div></div>
<div class="row"><div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="signos_amenaza_parto_pretermino" <?php echo $chk($a['signos_amenaza_parto_pretermino']??false);?>><label class="form-check-label">Signos Amenaza Parto Pretérmino</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="plan_nacimiento_definido" <?php echo $chk($a['plan_nacimiento_definido']??false);?>><label class="form-check-label">Plan de Nacimiento Definido</label></div></div></div>
</div></div>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-weight-scale me-2"></i> Crecimiento y RCIU</div><div class="card-body">
<div class="row"><div class="col-md-3 mb-3"><label class="form-label">Peso Fetal (gr)</label><input type="number" class="form-control" name="peso_fetal_estimado_gr" value="<?php echo htmlspecialchars($c['peso_fetal_estimado_gr']??'');?>"></div>
<div class="col-md-3 mb-3"><label class="form-label">Percentil Ajustado</label><input type="number" class="form-control" name="percentil_ajustado" value="<?php echo htmlspecialchars($c['percentil_ajustado']??'');?>"></div>
<div class="col-md-3 mb-3"><label class="form-label">Clasificación</label><select class="form-select" name="clasificacion_crecimiento"><option value="">No evaluado</option><?php foreach(['Adecuado','Mayor a lo esperado','Menor a lo esperado'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($c['clasificacion_crecimiento']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-3 mb-3"><label class="form-label">RCIU Barcelona</label><select class="form-select" name="estadio_rciu_barcelona"><?php foreach(['Ninguno','Estadio I','Estadio II','Estadio III','Estadio IV'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($c['estadio_rciu_barcelona']??'Ninguno',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div></div>
</div></div>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-wave-square me-2"></i> Doppler</div><div class="card-body">
<div class="row"><div class="col-md-3 mb-3"><label class="form-label">AU PI</label><input type="number" step="0.01" class="form-control" name="au_pi" value="<?php echo htmlspecialchars($d['au_pi']??'');?>"></div>
<div class="col-md-3 mb-3"><label class="form-label">Flujo Diastólico AU</label><select class="form-select" name="au_flujo_diastolico"><option value="">No evaluado</option><?php foreach(['Presente','Ausente','Reverso'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($d['au_flujo_diastolico']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-3 mb-3"><label class="form-label">ACM PI</label><input type="number" step="0.01" class="form-control" name="acm_pi" value="<?php echo htmlspecialchars($d['acm_pi']??'');?>"></div>
<div class="col-md-3 mb-3"><label class="form-label">DV Onda A</label><select class="form-select" name="dv_onda_a"><option value="">No evaluado</option><?php foreach(['Positiva','Ausente','Reversa'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($d['dv_onda_a']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div></div>
<div class="row"><div class="col-md-3 mb-3"><label class="form-label">UTA PI Promedio</label><input type="number" step="0.01" class="form-control" name="uta_pi_promedio" value="<?php echo htmlspecialchars($d['uta_pi_promedio']??'');?>"></div>
<div class="col-md-3 mb-3"><label class="form-label">Ratio CU/ICP</label><input type="number" step="0.01" class="form-control" name="ratio_cu_icp" value="<?php echo htmlspecialchars($d['ratio_cu_icp']??'');?>"></div>
<div class="col-md-3 mb-3"><div class="form-check mt-4"><input class="form-check-input" type="checkbox" name="alteracion_doppler_detectada" <?php echo $chk($d['alteracion_doppler_detectada']??false);?>><label class="form-check-label">Alteración Doppler</label></div></div></div>
</div></div>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-droplet me-2"></i> Anatomía y Líquido</div><div class="card-body">
<div class="row"><div class="col-md-3 mb-3"><label class="form-label">Circular Cordón</label><select class="form-select" name="circular_cordon_cuello"><?php foreach(['Negativo','Simple','Doble'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($an['circular_cordon_cuello']??'Negativo',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-3 mb-3"><label class="form-label">Líquido Amniótico (mm)</label><input type="number" class="form-control" name="liquido_amniotico_mm" value="<?php echo htmlspecialchars($an['liquido_amniotico_mm']??'');?>"></div>
<div class="col-md-3 mb-3"><label class="form-label">Método Medición</label><select class="form-select" name="metodo_medicion_liquido"><?php foreach(['Bolsillo Maximo','Phelan'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($an['metodo_medicion_liquido']??'Bolsillo Maximo',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-3 mb-3"><label class="form-label">Diagnóstico Líquido</label><select class="form-select" name="diagnostico_liquido"><?php foreach(['Normal','Oligohidramnios','Polihidramnios'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($an['diagnostico_liquido']??'Normal',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div></div>
<div class="row"><div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="estructuras_normales" <?php echo $chk($an['estructuras_normales']??true);?>><label class="form-check-label">Estructuras Generales Normales</label></div></div></div>
</div></div>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-uterus me-2"></i> Evaluación Placentaria (PAS)</div><div class="card-body">
<div class="row"><div class="col-md-4 mb-3"><label class="form-label">Distancia OCI (mm)</label><input type="number" step="0.01" class="form-control" name="distancia_oci_mm" value="<?php echo htmlspecialchars($pl['distancia_oci_mm']??'');?>"></div>
<div class="col-md-4 mb-3"><label class="form-label">Grosor Placentario (mm)</label><input type="number" class="form-control" name="grosor_placentario_mm" value="<?php echo htmlspecialchars($pl['grosor_placentario_mm']??'');?>"></div>
<div class="col-md-4 mb-3"><label class="form-label">Grado Madurez</label><select class="form-select" name="grado_madurez"><option value="">No evaluado</option><?php foreach(['Grado 0-1','Grado 2','Grado 3'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($pl['grado_madurez']??'',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div></div>
<div class="row"><div class="col-md-4 mb-3"><label class="form-label">Lagunas Vasculares</label><select class="form-select" name="lagunas_vasculares"><?php foreach(['Ausentes/minimas','Si','Extensas'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($pl['lagunas_vasculares']??'Ausentes/minimas',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-4 mb-3"><label class="form-label">Interfase Miometrial</label><select class="form-select" name="interfase_miometrial"><?php foreach(['Intacta','Adelgazada','Discontinua'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($pl['interfase_miometrial']??'Intacta',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div>
<div class="col-md-4 mb-3"><label class="form-label">Acretismo FIGO (PAS)</label><select class="form-select" name="acretismo_figo_pas"><?php foreach(['Grado 0','Grado 1','Grado 2','Grado 3'] as $o):?><option value="<?php echo $o;?>" <?php echo $sel($pl['acretismo_figo_pas']??'Grado 0',$o);?>><?php echo $o;?></option><?php endforeach;?></select></div></div>
<div class="row"><div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="vasos_puente" <?php echo $chk($pl['vasos_puente']??false);?>><label class="form-check-label">Vasos Puente</label></div></div></div>
</div></div>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-notes-medical me-2"></i> Historial Clínico</div><div class="card-body"><div class="row">
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="hipertension_cronica" <?php echo $chk($h['hipertension_cronica']??false);?>><label class="form-check-label">Hipertensión Crónica</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="diabetes" <?php echo $chk($h['diabetes']??false);?>><label class="form-check-label">Diabetes</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="lupus_les" <?php echo $chk($h['lupus_les']??false);?>><label class="form-check-label">Lupus / LES</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="sindrome_antifosfolipido_saf" <?php echo $chk($h['sindrome_antifosfolipido_saf']??false);?>><label class="form-check-label">Síndrome Antifosfolípido</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="antecedente_preeclampsia_rciu" <?php echo $chk($h['antecedente_preeclampsia_rciu']??false);?>><label class="form-check-label">Ant. Preeclampsia/RCIU</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="fertilizacion_in_vitro" <?php echo $chk($h['fertilizacion_in_vitro']??false);?>><label class="form-check-label">Fertilización In Vitro</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="antecedente_parto_pretermino" <?php echo $chk($h['antecedente_parto_pretermino']??false);?>><label class="form-check-label">Ant. Parto Pretérmino</label></div></div>
</div></div></div>

<div class="card mb-4"><div class="card-body"><div class="row align-items-end">
<div class="col-md-8 text-end">
<form action="<?php echo Url::to('/evaluaciones_3er_trimestre/delete');?>" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');"><input type="hidden" name="id" value="<?php echo $ev['id'];?>"><button type="submit" class="btn btn-apple btn-apple-danger me-2"><i class="fa-solid fa-trash"></i> Eliminar</button></form>
<a href="<?php echo Url::to('/evaluaciones_3er_trimestre');?>" class="btn btn-apple btn-apple-secondary me-2">Cancelar</a>
<button type="submit" class="btn btn-apple btn-apple-primary btn-lg"><i class="fa-solid fa-save"></i> Actualizar</button></div></div></div></div>
</form>
<?php require_once __DIR__.'/../layouts/footer.php';?>
