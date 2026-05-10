<?php
$title="Ver Evaluación 3er Trimestre";
require_once __DIR__.'/../layouts/header.php';
require_once __DIR__.'/../layouts/sidebar.php';
$ev=$evaluacion;$a=$antecedentes;$c=$crecimiento;$d=$doppler;$an=$anatomia;$pl=$placentaria;$h=$historial;
function sv3($v,$s=''){return($v===null||$v==='')?'<span class="text-muted">—</span>':htmlspecialchars($v).$s;}
function yb($v,$t='Sí'){return$v?'<span class="badge bg-warning">'.$t.'</span>':'<span class="badge bg-success">No</span>';}
function gn($v,$t='Normal'){return$v?'<span class="badge bg-success">'.$t.'</span>':'<span class="badge bg-danger">Alterado</span>';}
?>
<div class="page-header"><div class="d-flex align-items-center gap-3">
<a href="<?php echo Url::to('/evaluaciones_3er_trimestre');?>" class="btn btn-apple btn-apple-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
<h1 class="page-title mb-0"><?php echo htmlspecialchars($ev['codigo_reporte']);?></h1></div>
<div class="page-header-actions"><?php $ec=match($ev['estado']){'Completado'=>'success','En proceso'=>'warning','Archivado'=>'secondary',default=>'info'};?>
<span class="badge bg-<?php echo $ec;?> me-2"><?php echo htmlspecialchars($ev['estado']);?></span>
<a href="<?php echo Url::to('/evaluaciones_3er_trimestre/edit?id='.$ev['id']);?>" class="btn btn-apple btn-apple-secondary"><i class="fa-solid fa-edit"></i> Editar</a>
<a href="<?php echo Url::to('/evaluaciones_3er_trimestre/print?id='.$ev['id']);?>" class="btn btn-apple btn-apple-primary" target="_blank"><i class="fa-solid fa-print"></i> Imprimir</a></div></div>

<div class="row"><div class="col-lg-6">
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-id-card me-2"></i> Datos Generales</div><div class="card-body">
<?php $r=function($l,$v){echo'<div class="row mb-2"><div class="col-md-4 fw-bold">'.$l.':</div><div class="col-md-8">'.$v.'</div></div>';};
$r('Código',htmlspecialchars($ev['codigo_reporte']));$r('Fecha Evaluación',date('d/m/Y',strtotime($ev['fecha_evaluacion'])));
$r('Fecha Estudio',$ev['fecha_estudio']?date('d/m/Y',strtotime($ev['fecha_estudio'])):'<span class="text-muted">—</span>');
$r('Paciente',htmlspecialchars($ev['paciente_nombre'].' '.$ev['paciente_apellido']));
$r('Médico',htmlspecialchars($ev['medico_nombre'].' '.$ev['medico_apellido']));?>
</div></div>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-heart-pulse me-2"></i> Signos Vitales y Estática Fetal</div><div class="card-body">
<?php $r('Edad Gestacional',sv3($ev['edad_gestacional_semanas'],' sem'));$r('Peso Materno',sv3($ev['peso_kg'],' kg'));
$r('TA Sistólica',sv3($ev['ta_sistolica'],' mmHg'));$r('TA Diastólica',sv3($ev['ta_diastolica'],' mmHg'));
$r('FCF',sv3($ev['fcf_lpm'],' lpm'));$r('Situación Fetal',sv3($ev['situacion_fetal']));
$r('Presentación',sv3($ev['presentacion_fetal']));$r('Posición Fetal',sv3($ev['posicion_fetal']));?>
</div></div>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-notes-medical me-2"></i> Antecedentes</div><div class="card-body">
<?php $r('Curva Tolerancia',sv3($a['curva_tolerancia_glucosa']));$r('Diabetes Gestacional',yb($a['diabetes_gestacional_actual']));
$r('Movimientos Fetales',sv3($a['movimientos_fetales']));$r('Amenaza Parto Pret.',yb($a['signos_amenaza_parto_pretermino']));
$r('Plan Nacimiento',yb($a['plan_nacimiento_definido']));?>
</div></div>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-wave-square me-2"></i> Doppler</div><div class="card-body">
<?php $r('AU PI',sv3($d['au_pi']));$r('Flujo Diastólico AU',sv3($d['au_flujo_diastolico']));
$r('ACM PI',sv3($d['acm_pi']));$r('DV Onda A',sv3($d['dv_onda_a']));
$r('UTA PI Promedio',sv3($d['uta_pi_promedio']));$r('Ratio CU/ICP',sv3($d['ratio_cu_icp']));
$r('Alteración Doppler',yb($d['alteracion_doppler_detectada']));?>
</div></div></div>

<div class="col-lg-6">
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-weight-scale me-2"></i> Crecimiento y RCIU</div><div class="card-body">
<?php $r('Peso Fetal Estimado',sv3($c['peso_fetal_estimado_gr'],' gr'));$r('Percentil Ajustado',sv3($c['percentil_ajustado']));
$r('Clasificación',sv3($c['clasificacion_crecimiento']));
$ri=$c['estadio_rciu_barcelona']??'Ninguno';$rc=$ri=='Ninguno'?'success':($ri=='Estadio I'?'warning':'danger');
$r('RCIU Barcelona','<span class="badge bg-'.$rc.'">'.htmlspecialchars($ri).'</span>');?>
</div></div>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-droplet me-2"></i> Anatomía y Líquido</div><div class="card-body">
<?php $r('Circular Cordón',sv3($an['circular_cordon_cuello']));$r('Líquido Amniótico',sv3($an['liquido_amniotico_mm'],' mm'));
$r('Método Medición',sv3($an['metodo_medicion_liquido']));$r('Diagnóstico Líquido',sv3($an['diagnostico_liquido']));
$r('Estructuras Normales',gn($an['estructuras_normales']??true));?>
</div></div>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-uterus me-2"></i> Evaluación Placentaria</div><div class="card-body">
<?php $r('Distancia OCI',sv3($pl['distancia_oci_mm'],' mm'));$r('Grosor Placentario',sv3($pl['grosor_placentario_mm'],' mm'));
$r('Grado Madurez',sv3($pl['grado_madurez']));$r('Lagunas Vasculares',sv3($pl['lagunas_vasculares']));
$r('Interfase Miometrial',sv3($pl['interfase_miometrial']));$r('Vasos Puente',yb($pl['vasos_puente']??false));
$r('Acretismo FIGO PAS',sv3($pl['acretismo_figo_pas']));?>
</div></div>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-notes-medical me-2"></i> Historial Clínico</div><div class="card-body">
<?php $r('Hipertensión',yb($h['hipertension_cronica']??false));$r('Diabetes',yb($h['diabetes']??false));
$r('Lupus/LES',yb($h['lupus_les']??false));$r('SAF',yb($h['sindrome_antifosfolipido_saf']??false));
$r('Preeclampsia/RCIU',yb($h['antecedente_preeclampsia_rciu']??false));$r('FIV',yb($h['fertilizacion_in_vitro']??false));
$r('Parto Pretérmino',yb($h['antecedente_parto_pretermino']??false));?>
</div></div></div></div>
<?php require_once __DIR__.'/../layouts/footer.php';?>
