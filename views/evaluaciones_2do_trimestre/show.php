<?php
$title = "Ver Evaluación 2do Trimestre";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
function sv($v,$s=''){return ($v===null||$v==='')?'<span class="text-muted">—</span>':htmlspecialchars($v).$s;}
function sb($v,$t='Normal',$f='Alterado'){return $v?'<span class="badge bg-success">'.$t.'</span>':'<span class="badge bg-danger">'.$f.'</span>';}
function sym($v){return $v?'<span class="badge bg-danger">Sí</span>':'<span class="badge bg-success">No</span>';}
$rlabels=['Bajo'=>'success','Intermedio'=>'warning','Alto'=>'danger','Muy Alto'=>'danger'];
$ev=$evaluacion;
?>
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/evaluaciones_2do_trimestre'); ?>" class="btn btn-apple btn-apple-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
        <h1 class="page-title mb-0"><?php echo htmlspecialchars($ev['codigo_reporte']); ?></h1>
    </div>
    <div class="page-header-actions">
        <?php $ec=match($ev['estado']){'Completado'=>'success','En proceso'=>'warning','Archivado'=>'secondary',default=>'info'};?>
        <span class="badge bg-<?php echo $ec; ?> me-2"><?php echo htmlspecialchars($ev['estado']); ?></span>
        <a href="<?php echo Url::to('/evaluaciones_2do_trimestre/edit?id='.$ev['id']); ?>" class="btn btn-apple btn-apple-secondary"><i class="fa-solid fa-edit"></i> Editar</a>
        <a href="<?php echo Url::to('/evaluaciones_2do_trimestre/print?id='.$ev['id']); ?>" class="btn btn-apple btn-apple-primary" target="_blank"><i class="fa-solid fa-print"></i> Imprimir</a>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-id-card me-2"></i> Datos Generales</div><div class="card-body">
            <div class="row mb-2"><div class="col-md-4 fw-bold">Código:</div><div class="col-md-8"><?php echo htmlspecialchars($ev['codigo_reporte']); ?></div></div>
            <div class="row mb-2"><div class="col-md-4 fw-bold">Fecha Evaluación:</div><div class="col-md-8"><?php echo date('d/m/Y',strtotime($ev['fecha_evaluacion'])); ?></div></div>
            <div class="row mb-2"><div class="col-md-4 fw-bold">Fecha Estudio:</div><div class="col-md-8"><?php echo $ev['fecha_estudio']?date('d/m/Y',strtotime($ev['fecha_estudio'])):'<span class="text-muted">—</span>'; ?></div></div>
            <div class="row mb-2"><div class="col-md-4 fw-bold">Paciente:</div><div class="col-md-8"><?php echo htmlspecialchars($ev['paciente_nombre'].' '.$ev['paciente_apellido']); ?></div></div>
            <div class="row mb-2"><div class="col-md-4 fw-bold">Médico:</div><div class="col-md-8"><?php echo htmlspecialchars($ev['medico_nombre'].' '.$ev['medico_apellido']); ?></div></div>
        </div></div>
        <?php if (!empty($data1er)): ?>
        <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-folder-open me-2"></i> Datos del 1er Trimestre (referencia)</div><div class="card-body">
        <div class="row">
            <div class="col-md-3"><strong>FPP USG:</strong> <?php echo !empty($data1er['fpp_usg']) ? date('d/m/Y', strtotime($data1er['fpp_usg'])) : '—'; ?></div>
            <div class="col-md-3"><strong>EG 1T:</strong> <?php echo !empty($data1er['edad_gestacional_semanas']) ? $data1er['edad_gestacional_semanas'].' sem' : '—'; ?></div>
            <div class="col-md-3"><strong>Peso 1T:</strong> <?php echo !empty($data1er['peso_kg']) ? $data1er['peso_kg'].' kg' : '—'; ?></div>
            <div class="col-md-3"><strong>Talla 1T:</strong> <?php echo !empty($data1er['talla_cm']) ? $data1er['talla_cm'].' cm' : '—'; ?></div>
            <div class="col-md-4 mt-2"><strong>Riesgo Preeclampsia:</strong> <?php echo $data1er['riesgo_preeclampsia_temprana'] ?? '—'; ?></div>
            <div class="col-md-4 mt-2"><strong>Riesgo Cromosomopatías:</strong> <?php echo $data1er['probabilidad_cromosomopatias'] ?? '—'; ?></div>
            <div class="col-md-4 mt-2"><strong>Riesgo Parto Pretérmino:</strong> <?php echo $data1er['riesgo_parto_pretermino'] ?? '—'; ?></div>
        </div>
        <?php if (!empty($evaluacion['ganancia_peso_kg'])): ?>
        <hr><div class="row"><div class="col-12"><strong>Ganancia de Peso:</strong> <span class="text-primary"><?php echo $evaluacion['ganancia_peso_kg']; ?> kg</span> (desde 1T: <?php echo $evaluacion['peso_1er_trimestre_kg'] ?? '—'; ?> kg → actual: <?php echo $evaluacion['peso_kg'] ?? '—'; ?> kg)</div></div>
        <?php endif; ?>
        </div></div>
        <?php endif; ?>
        <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-heart-pulse me-2"></i> Datos Clínicos</div><div class="card-body">
            <div class="row mb-2"><div class="col-md-4 fw-bold">Peso:</div><div class="col-md-8"><?php echo sv($ev['peso_kg'],' kg'); ?></div></div>
            <div class="row mb-2"><div class="col-md-4 fw-bold">Talla:</div><div class="col-md-8"><?php echo sv($ev['talla_cm'],' cm'); ?></div></div>
            <div class="row mb-2"><div class="col-md-4 fw-bold">PAM:</div><div class="col-md-8"><?php echo sv($ev['pam_mmhg'],' mmHg'); ?></div></div>
            <div class="row mb-2"><div class="col-md-4 fw-bold">UTA PI:</div><div class="col-md-8"><?php echo sv($ev['uta_pi_promedio']); ?></div></div>
            <div class="row mb-2"><div class="col-md-4 fw-bold">Edad Gest.:</div><div class="col-md-8"><?php echo sv($ev['edad_gestacional_semanas'],' sem'); ?></div></div>
            <div class="row mb-2"><div class="col-md-4 fw-bold">FPP Actual:</div><div class="col-md-8"><?php echo $ev['fpp_actual']?date('d/m/Y',strtotime($ev['fpp_actual'])):'<span class="text-muted">—</span>'; ?></div></div>
        </div></div>
        <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-weight-scale me-2"></i> Biometría y Crecimiento</div><div class="card-body">
            <div class="row mb-2"><div class="col-md-5 fw-bold">Estado Feto:</div><div class="col-md-7"><?php echo htmlspecialchars($biometria['estado_feto']??'—'); ?></div></div>
            <div class="row mb-2"><div class="col-md-5 fw-bold">FCF:</div><div class="col-md-7"><?php echo sv($biometria['fcf_lpm'],' lpm'); ?></div></div>
            <div class="row mb-2"><div class="col-md-5 fw-bold">Peso Fetal Estimado:</div><div class="col-md-7"><?php echo sv($biometria['peso_fetal_estimado_gr'],' gr'); ?></div></div>
            <div class="row mb-2"><div class="col-md-5 fw-bold">Percentil Hadlock:</div><div class="col-md-7"><?php echo sv($biometria['percentil_hadlock']); ?></div></div>
            <div class="row mb-2"><div class="col-md-5 fw-bold">Crec. Armónico:</div><div class="col-md-7"><?php echo sb($biometria['crecimiento_armonico']??true,'Armónico','No Armónico'); ?></div></div>
            <div class="row mb-2"><div class="col-md-5 fw-bold">Índice Cefálico:</div><div class="col-md-7"><?php echo sv($biometria['indice_cefalico_ci']); ?></div></div>
            <div class="row mb-2"><div class="col-md-5 fw-bold">FL/AC:</div><div class="col-md-7"><?php echo sv($biometria['fl_ac_pct'],'%'); ?></div></div>
            <div class="row mb-2"><div class="col-md-5 fw-bold">HC/AC:</div><div class="col-md-7"><?php echo sv($biometria['hc_ac_campbell']); ?></div></div>
        </div></div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-baby me-2"></i> Anatomía Fetal</div><div class="card-body">
            <div class="row mb-2"><div class="col-md-6 fw-bold">Cráneo/SNC:</div><div class="col-md-6"><?php echo sb($anatomia['craneo_snc_normal']??true); ?></div></div>
            <div class="row mb-2"><div class="col-md-6 fw-bold">Cara/Cuello:</div><div class="col-md-6"><?php echo sb($anatomia['cara_cuello_normal']??true); ?></div></div>
            <div class="row mb-2"><div class="col-md-6 fw-bold">Corazón:</div><div class="col-md-6"><?php echo sb($anatomia['corazon_normal']??true); ?></div></div>
            <div class="row mb-2"><div class="col-md-6 fw-bold">Tórax/Diafrag.:</div><div class="col-md-6"><?php echo sb($anatomia['torax_diafragma_normal']??true); ?></div></div>
            <div class="row mb-2"><div class="col-md-6 fw-bold">Abdomen:</div><div class="col-md-6"><?php echo sb($anatomia['abdomen_normal']??true); ?></div></div>
            <div class="row mb-2"><div class="col-md-6 fw-bold">Genitourinario:</div><div class="col-md-6"><?php echo sb($anatomia['genitourinario_normal']??true); ?></div></div>
            <div class="row mb-2"><div class="col-md-6 fw-bold">Columna:</div><div class="col-md-6"><?php echo sb($anatomia['columna_normal']??true); ?></div></div>
            <div class="row mb-2"><div class="col-md-6 fw-bold">Extremidades:</div><div class="col-md-6"><?php echo sb($anatomia['extremidades_normal']??true); ?></div></div>
            <?php if(!empty($anatomia['detalles_anomalias'])): ?><div class="mt-2 p-2 bg-light rounded"><small><?php echo nl2br(htmlspecialchars($anatomia['detalles_anomalias'])); ?></small></div><?php endif; ?>
        </div></div>
        <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-magnifying-glass me-2"></i> Marcadores Ecográficos</div><div class="card-body">
            <div class="row mb-2"><div class="col-md-6">Ventriculomegalia:</div><div class="col-md-6"><?php echo sym($marcadores['ventriculomegalia_leve']??false); ?></div></div>
            <div class="row mb-2"><div class="col-md-6">Quistes Plexos:</div><div class="col-md-6"><?php echo sym($marcadores['quistes_plexos_coroideos']??false); ?></div></div>
            <div class="row mb-2"><div class="col-md-6">Pliegue Nucal:</div><div class="col-md-6"><?php echo sym($marcadores['pliegue_nucal_aumentado']??false); ?></div></div>
            <div class="row mb-2"><div class="col-md-6">Hueso Nasal:</div><div class="col-md-6"><?php echo ($marcadores['hueso_nasal_ausente']??false) ? '<span class="badge bg-danger">Ausente</span>' : '<span class="badge bg-success">Presente</span>'; ?></div></div>
            <div class="row mb-2"><div class="col-md-6">Foco Ecogénico:</div><div class="col-md-6"><?php echo sym($marcadores['foco_ecogenico_cardiaco']??false); ?></div></div>
            <div class="row mb-2"><div class="col-md-6">Intestino Hiperec.:</div><div class="col-md-6"><?php echo sym($marcadores['intestino_hiperecogenico']??false); ?></div></div>
            <div class="row mb-2"><div class="col-md-6">Fémur Corto:</div><div class="col-md-6"><?php echo sym($marcadores['femur_corto']??false); ?></div></div>
            <div class="row mb-2"><div class="col-md-6">AU Única:</div><div class="col-md-6"><?php echo sym($marcadores['arteria_umbilical_unica']??false); ?></div></div>
        </div></div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-uterus me-2"></i> Entorno Placentario</div><div class="card-body">
            <div class="row mb-2"><div class="col-md-5 fw-bold">Posición Placenta:</div><div class="col-md-7"><?php echo sv($entorno['placenta_posicion']); ?></div></div>
            <div class="row mb-2"><div class="col-md-5 fw-bold">Dist. Borde OCI:</div><div class="col-md-7"><?php echo sv($entorno['distancia_borde_oci_mm'],' mm'); ?></div></div>
            <div class="row mb-2"><div class="col-md-5 fw-bold">Acretismo FIGO:</div><div class="col-md-7"><?php echo sv($entorno['acretismo_figo_grado']); ?></div></div>
            <div class="row mb-2"><div class="col-md-5 fw-bold">Bolsillo Máx. Líq.:</div><div class="col-md-7"><?php echo sv($entorno['bolsillo_max_liquido_mm'],' mm'); ?></div></div>
            <div class="row mb-2"><div class="col-md-5 fw-bold">Longitud Cervical:</div><div class="col-md-7"><?php echo sv($entorno['longitud_cervical_mm'],' mm'); ?></div></div>
            <div class="row mb-2"><div class="col-md-5 fw-bold">Índice Consistencia:</div><div class="col-md-7"><?php echo sv($entorno['indice_consistencia_cervical']); ?></div></div>
            <div class="row mb-2"><div class="col-md-5 fw-bold">Funneling:</div><div class="col-md-7"><?php echo ($entorno['funneling_presente']??false)?'<span class="badge bg-danger">Presente '.sv($entorno['funneling_mm'],' mm').'</span>':'<span class="badge bg-success">Ausente</span>'; ?></div></div>
            <div class="row mb-2"><div class="col-md-5 fw-bold">Sludge:</div><div class="col-md-7"><?php echo sv($entorno['sludge_intraamniotico']); ?></div></div>
        </div></div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-clipboard-check me-2"></i> Impresión Diagnóstica</div><div class="card-body">
            <div class="row mb-2"><div class="col-md-5 fw-bold">Cromosomopatías:</div><div class="col-md-7"><?php $r=$diagnostica['riesgo_cromosomopatias']??''; if($r)echo '<span class="badge bg-'.$rlabels[$r].'">'.$r.'</span>'; else echo'<span class="text-muted">—</span>'; ?></div></div>
            <div class="row mb-2"><div class="col-md-5 fw-bold">Parto Pretérmino:</div><div class="col-md-7"><?php $r=$diagnostica['riesgo_parto_pretermino']??''; if($r)echo '<span class="badge bg-'.($rlabels[$r]??'secondary').'">'.$r.'</span>'; else echo'<span class="text-muted">—</span>'; ?></div></div>
            <div class="row mb-2"><div class="col-md-5 fw-bold">Preeclampsia:</div><div class="col-md-7"><?php $r=$diagnostica['riesgo_preeclampsia']??''; if($r)echo '<span class="badge bg-'.($rlabels[$r]??'secondary').'">'.$r.'</span>'; else echo'<span class="text-muted">—</span>'; ?></div></div>
            <?php if(!empty($diagnostica['observaciones_medicas'])): ?><div class="mt-2 p-2 bg-light rounded"><small><?php echo nl2br(htmlspecialchars($diagnostica['observaciones_medicas'])); ?></small></div><?php endif; ?>
        </div></div>
        <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-notes-medical me-2"></i> Historial Clínico</div><div class="card-body">
            <div class="row mb-2"><div class="col-md-6">Hipertensión</div><div class="col-md-6"><?php echo sym($historial['hipertension_cronica']??false); ?></div></div>
            <div class="row mb-2"><div class="col-md-6">Diabetes</div><div class="col-md-6"><?php echo sym($historial['diabetes']??false); ?></div></div>
            <div class="row mb-2"><div class="col-md-6">Lupus/LES</div><div class="col-md-6"><?php echo sym($historial['lupus_les']??false); ?></div></div>
            <div class="row mb-2"><div class="col-md-6">SAF</div><div class="col-md-6"><?php echo sym($historial['sindrome_antifosfolipido_saf']??false); ?></div></div>
            <div class="row mb-2"><div class="col-md-6">Preeclampsia/RCIU</div><div class="col-md-6"><?php echo sym($historial['antecedente_preeclampsia_rciu']??false); ?></div></div>
            <div class="row mb-2"><div class="col-md-6">FIV</div><div class="col-md-6"><?php echo sym($historial['fertilizacion_in_vitro']??false); ?></div></div>
            <div class="row mb-2"><div class="col-md-6">Parto Pretérmino</div><div class="col-md-6"><?php echo sym($historial['antecedente_parto_pretermino']??false); ?></div></div>
        </div></div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
