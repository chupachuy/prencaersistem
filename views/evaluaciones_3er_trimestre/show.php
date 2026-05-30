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
$r('Estudio Solicitado',sv3($ev['estudio_solicitado']));
$r('Equipo Ultrasonográfico',sv3($ev['equipo_ultrasonido']));
$r('Paciente',htmlspecialchars($ev['paciente_nombre'].' '.$ev['paciente_apellido']));
$r('Médico',htmlspecialchars($ev['medico_nombre'].' '.$ev['medico_apellido']));?>
</div></div>

<?php if (!empty($data1er) || !empty($data2do)): ?>
<?php if (!empty($data1er)): ?>
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-folder-open me-2"></i> Checklist Prenacer – Antecedentes 1er Trimestre</div><div class="card-body">
<div class="row">
    <div class="col-md-3"><strong>Riesgo Preeclampsia (FMF):</strong> <?php echo $data1er['riesgo_preeclampsia_temprana'] ?? '—'; ?></div>
    <div class="col-md-3"><strong>Doppler UT PI:</strong> <?php echo !empty($data1er['uta_pi_promedio']) ? $data1er['uta_pi_promedio'] : '—'; ?> <?php echo !empty($data1er['muesca_bilateral'])?'(Muesca bilateral)':''; ?></div>
    <div class="col-md-3"><strong>PAPP-A MoM:</strong> <?php echo !empty($data1er['papp_a_mom'])?$data1er['papp_a_mom']:'—'; ?></div>
    <div class="col-md-3"><strong>PLGF MoM:</strong> <?php echo !empty($data1er['plgf_mom'])?$data1er['plgf_mom']:'—'; ?></div>
    <div class="col-md-4 mt-2"><strong>Tamizaje Genético:</strong> <?php echo !empty($data1er['tamizaje_genetico_tipo'])?$data1er['tamizaje_genetico_tipo'].' — '.($data1er['tamizaje_genetico_resultado']??'—'):'—'; ?></div>
    <div class="col-md-4 mt-2"><strong>Longitud Cervical 1T:</strong> <?php echo !empty($data1er['longitud_cervical_mm'])?$data1er['longitud_cervical_mm'].' mm':'—'; ?></div>
    <div class="col-md-4 mt-2"><strong>Miomas 1T:</strong> <?php echo !empty($data1er['miomas_visibles'])?'Sí (FIGO: '.($data1er['miomas_figo_tipo']??'—').')':'No'; ?></div>
</div></div></div>
<?php endif; ?>
<?php if (!empty($data2do)): ?>
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-folder-open me-2"></i> Checklist Prenacer – Antecedentes 2do Trimestre</div><div class="card-body">
<div class="row">
    <div class="col-md-4"><strong>Morfología Fetal:</strong> <?php
        $morfNormal=true;
        $cmf=['craneo_snc_normal','cara_cuello_normal','corazon_normal','torax_diafragma_normal','abdomen_normal','genitourinario_normal','columna_normal','extremidades_normal'];
        foreach($cmf as $cm) if(isset($data2do[$cm])&&$data2do[$cm]==0){$morfNormal=false;break;}
        echo $morfNormal?'<span class="text-success">Normal</span>':'<span class="text-danger">Alterada</span>';
    ?></div>
    <div class="col-md-4"><strong>Doppler UT PI 2T:</strong> <?php echo !empty($data2do['uta_pi_promedio'])?$data2do['uta_pi_promedio']:'—'; ?></div>
    <div class="col-md-4"><strong>Placenta 2T:</strong> <?php echo $data2do['placenta_posicion']??'—'; ?> | Acretismo: <?php echo $data2do['acretismo_figo_grado']??'—'; ?></div>
    <div class="col-md-4 mt-2"><strong>Longitud Cervical 2T:</strong> <?php echo !empty($data2do['longitud_cervical_mm'])?$data2do['longitud_cervical_mm'].' mm':'—'; ?></div>
    <div class="col-md-4 mt-2"><strong>ICC 2T:</strong> <?php echo !empty($data2do['indice_consistencia_cervical'])?$data2do['indice_consistencia_cervical'].'%':'—'; ?></div>
    <div class="col-md-4 mt-2"><strong>Funneling 2T:</strong> <?php echo !empty($data2do['funneling_presente'])?'Sí ('.$data2do['funneling_mm'].' mm)':'No'; ?></div>
    <div class="col-md-4 mt-2"><strong>Sludge 2T:</strong> <?php echo $data2do['sludge_intraamniotico']??'—'; ?></div>
    <div class="col-md-4 mt-2"><strong>Signos RCIU 2T:</strong> <?php
        $rciu=false;
        if(!empty($data2do['percentil_hadlock'])&&$data2do['percentil_hadlock']<10)$rciu=true;
        if(!empty($data2do['crecimiento_armonico'])&&$data2do['crecimiento_armonico']==0)$rciu=true;
        echo $rciu?'<span class="text-danger">Sí</span>':'<span class="text-success">No</span>';
    ?></div>
</div></div></div>
<?php endif; ?>
<?php endif; ?>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-heart-pulse me-2"></i> Signos Vitales y Estática Fetal</div><div class="card-body">
<?php $r('Condición Fetal',sv3($ev['feto_unico_vivo']));
$r('Edad Gestacional',sv3($ev['edad_gestacional_semanas'],' sem'));
$r('FPP por FUM',$ev['fpp_fum']?date('d/m/Y',strtotime($ev['fpp_fum'])):'<span class="text-muted">—</span>');
$r('FPP por USG',$ev['fpp_usg']?date('d/m/Y',strtotime($ev['fpp_usg'])):'<span class="text-muted">—</span>');
$r('Peso Materno',sv3($ev['peso_kg'],' kg'));$r('Talla Materna',sv3($ev['talla_cm'],' cm'));
$r('TA',sv3($ev['ta_sistolica']).' / '.sv3($ev['ta_diastolica'],' mmHg'));
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
$r('Vena Umbilical',sv3($d['vena_umbilical']));
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
<?php $r('Localización',sv3($pl['localizacion_placentaria']));
$r('Distancia OCI',sv3($pl['distancia_oci_mm'],' mm'));$r('Grosor Placentario',sv3($pl['grosor_placentario_mm'],' mm'));
$r('Grado Madurez',sv3($pl['grado_madurez']));$r('Ecogenicidad',sv3($pl['ecogenicidad']));
$r('Lagunas Vasculares',sv3($pl['lagunas_vasculares']));
$r('Interfase Miometrial',sv3($pl['interfase_miometrial']));
$r('Vasos Puente',yb($pl['vasos_puente']??false));
$r('Zona Retroplacentaria',sv3($pl['zona_retroplacentaria']));
$r('Protrusión Placentaria',yb($pl['protrusion_placentaria']??false));
$r('Vascularización Doppler',sv3($pl['vascularizacion_anomala_doppler']));
$r('Inserción del Cordón',sv3($pl['insercion_cordon']));
$r('N° Vasos Umbilicales',sv3($pl['numero_vasos_umbilicales']));
$r('Calcificaciones',sv3($pl['calcificaciones']));
$r('Acretismo FIGO PAS',sv3($pl['acretismo_figo_pas']));
$r('Perfusión VI/FI/VFI',sv3($pl['perfusion_vi'],'%').' / '.sv3($pl['perfusion_fi'],'%').' / '.sv3($pl['perfusion_vfi'],'%'));
$r('Morfología Uterina',sv3($pl['morfologia_uterina_eshre']));
$r('Miomas Visibles',yb($pl['miomas_visibles']??false));
$r('FIGO Tipo',sv3($pl['miomas_figo_tipo']));
$r('Dimensiones Miomas',sv3($pl['miomas_dimensiones_mm'],' mm'));
$r('Obstruyen Canal',yb($pl['miomas_obstruyen_canal']??false));
?></div></div>
<?php if(!empty($ev['observaciones'])): ?>
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-note-sticky me-2"></i> Observaciones</div><div class="card-body"><?php echo nl2br(htmlspecialchars($ev['observaciones']));?></div></div>
<?php endif; ?>

<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-notes-medical me-2"></i> Historial Clínico</div><div class="card-body">
<?php $r('Hipertensión',yb($h['hipertension_cronica']??false));$r('Diabetes',yb($h['diabetes']??false));
$r('Lupus/LES',yb($h['lupus_les']??false));$r('SAF',yb($h['sindrome_antifosfolipido_saf']??false));
$r('Preeclampsia/RCIU',yb($h['antecedente_preeclampsia_rciu']??false));$r('FIV',yb($h['fertilizacion_in_vitro']??false));
$r('Parto Pretérmino',yb($h['antecedente_parto_pretermino']??false));?>
</div></div></div></div>
<?php if (!empty($imagenes)): ?>
<div class="card mb-4">
    <div class="card-header"><i class="fa-solid fa-images me-2"></i> Imágenes del Estudio</div>
    <div class="card-body">
        <div class="row g-3">
            <?php foreach ($imagenes as $img): ?>
            <div class="col-auto">
                <a href="<?php echo Url::to($img['ruta_imagen']); ?>" target="_blank">
                    <img src="<?php echo Url::to($img['ruta_imagen']); ?>" class="rounded shadow-sm" style="width:150px;height:150px;object-fit:cover;">
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>
<?php require_once __DIR__.'/../layouts/footer.php';?>
