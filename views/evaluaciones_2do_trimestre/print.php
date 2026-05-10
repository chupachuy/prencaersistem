<?php
$ev=$evaluacion;$b=$biometria;$a=$anatomia;$m=$marcadores;$en=$entorno;$d=$diagnostica;$h=$historial;
function v($x,$s=''){return ($x===null||$x==='')?'—':htmlspecialchars($x).$s;}
function fd($x){return $x?date('d/m/Y',strtotime($x)):'—';}
function si($x){return $x?'Sí':'No';}
?>
<!DOCTYPE html><html lang="es">
<head><meta charset="UTF-8"><title>Evaluación 2do Trimestre — <?php echo htmlspecialchars($ev['codigo_reporte']); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>body{font-family:Helvetica,Arial,sans-serif;padding:30px;color:#333;}
.document{max-width:800px;margin:0 auto;}.doc-header{text-align:center;margin-bottom:30px;border-bottom:2px solid #333;padding-bottom:15px;}
.doc-header h1{font-size:20px;margin:0;}.doc-header .code{font-size:13px;color:#666;margin-top:5px;}
.section{margin-bottom:25px;}.section h2{font-size:13px;background:#f5f5f5;padding:6px 10px;margin:0 0 10px 0;border-left:4px solid #333;}
.row-item{display:flex;margin-bottom:4px;font-size:12px;}.label{width:170px;font-weight:bold;}.value{flex:1;}
.two-col{display:flex;gap:30px;}.col{flex:1;}.footer{margin-top:40px;font-size:10px;color:#999;text-align:center;border-top:1px solid #eee;padding-top:10px;}
@media print{body{padding:15px;}}</style></head>
<body><div class="document">
<div class="doc-header"><h1>EVALUACIÓN 2DO TRIMESTRE</h1><div class="code"><?php echo htmlspecialchars($ev['codigo_reporte']); ?></div></div>

<div class="section"><h2>Datos Generales</h2>
<div class="row-item"><div class="label">Fecha Evaluación:</div><div class="value"><?php echo fd($ev['fecha_evaluacion']); ?></div></div>
<div class="row-item"><div class="label">Fecha Estudio:</div><div class="value"><?php echo fd($ev['fecha_estudio']); ?></div></div>
<div class="row-item"><div class="label">Paciente:</div><div class="value"><?php echo htmlspecialchars($ev['paciente_nombre'].' '.$ev['paciente_apellido']); ?></div></div>
<div class="row-item"><div class="label">Médico:</div><div class="value"><?php echo htmlspecialchars($ev['medico_nombre'].' '.$ev['medico_apellido']); ?></div></div>
<div class="row-item"><div class="label">Estado:</div><div class="value"><?php echo htmlspecialchars($ev['estado']); ?></div></div>
</div>

<div class="two-col"><div class="col">
<div class="section"><h2>Datos Clínicos</h2>
<div class="row-item"><div class="label">Peso:</div><div class="value"><?php echo v($ev['peso_kg'],' kg'); ?></div></div>
<div class="row-item"><div class="label">Talla:</div><div class="value"><?php echo v($ev['talla_cm'],' cm'); ?></div></div>
<div class="row-item"><div class="label">PAM:</div><div class="value"><?php echo v($ev['pam_mmhg'],' mmHg'); ?></div></div>
<div class="row-item"><div class="label">UTA PI:</div><div class="value"><?php echo v($ev['uta_pi_promedio']); ?></div></div>
<div class="row-item"><div class="label">Edad Gestacional:</div><div class="value"><?php echo v($ev['edad_gestacional_semanas'],' sem'); ?></div></div>
<div class="row-item"><div class="label">FPP Actual:</div><div class="value"><?php echo fd($ev['fpp_actual']); ?></div></div>
</div>
<div class="section"><h2>Biometría y Crecimiento</h2>
<div class="row-item"><div class="label">Estado Feto:</div><div class="value"><?php echo htmlspecialchars($b['estado_feto']??'Vivo'); ?></div></div>
<div class="row-item"><div class="label">FCF:</div><div class="value"><?php echo v($b['fcf_lpm'],' lpm'); ?></div></div>
<div class="row-item"><div class="label">Peso Fetal:</div><div class="value"><?php echo v($b['peso_fetal_estimado_gr'],' gr'); ?></div></div>
<div class="row-item"><div class="label">Percentil Hadlock:</div><div class="value"><?php echo v($b['percentil_hadlock']); ?></div></div>
<div class="row-item"><div class="label">Crec. Armónico:</div><div class="value"><?php echo si($b['crecimiento_armonico']??true); ?></div></div>
<div class="row-item"><div class="label">Índice Cefálico:</div><div class="value"><?php echo v($b['indice_cefalico_ci']); ?></div></div>
<div class="row-item"><div class="label">FL/AC:</div><div class="value"><?php echo v($b['fl_ac_pct'],'%'); ?></div></div>
<div class="row-item"><div class="label">HC/AC:</div><div class="value"><?php echo v($b['hc_ac_campbell']); ?></div></div>
</div>
</div><div class="col">
<div class="section"><h2>Anatomía Fetal</h2>
<div class="row-item"><div class="label">Cráneo/SNC:</div><div class="value"><?php echo si($a['craneo_snc_normal']??true); ?></div></div>
<div class="row-item"><div class="label">Cara/Cuello:</div><div class="value"><?php echo si($a['cara_cuello_normal']??true); ?></div></div>
<div class="row-item"><div class="label">Corazón:</div><div class="value"><?php echo si($a['corazon_normal']??true); ?></div></div>
<div class="row-item"><div class="label">Tórax/Diafragma:</div><div class="value"><?php echo si($a['torax_diafragma_normal']??true); ?></div></div>
<div class="row-item"><div class="label">Abdomen:</div><div class="value"><?php echo si($a['abdomen_normal']??true); ?></div></div>
<div class="row-item"><div class="label">Genitourinario:</div><div class="value"><?php echo si($a['genitourinario_normal']??true); ?></div></div>
<div class="row-item"><div class="label">Columna:</div><div class="value"><?php echo si($a['columna_normal']??true); ?></div></div>
<div class="row-item"><div class="label">Extremidades:</div><div class="value"><?php echo si($a['extremidades_normal']??true); ?></div></div>
<?php if(!empty($a['detalles_anomalias'])): ?><div class="row-item"><div class="label">Detalles:</div><div class="value"><?php echo nl2br(htmlspecialchars($a['detalles_anomalias'])); ?></div></div><?php endif; ?>
</div>
<div class="section"><h2>Marcadores Ecográficos</h2>
<div class="row-item"><div class="label">Ventriculomegalia:</div><div class="value"><?php echo si($m['ventriculomegalia_leve']??false); ?></div></div>
<div class="row-item"><div class="label">Quistes Plexos:</div><div class="value"><?php echo si($m['quistes_plexos_coroideos']??false); ?></div></div>
<div class="row-item"><div class="label">Pliegue Nucal:</div><div class="value"><?php echo si($m['pliegue_nucal_aumentado']??false); ?></div></div>
<div class="row-item"><div class="label">Hueso Nasal:</div><div class="value"><?php echo ($m['hueso_nasal_ausente']??false)?'Ausente':'Presente'; ?></div></div>
<div class="row-item"><div class="label">Foco Ecogénico:</div><div class="value"><?php echo si($m['foco_ecogenico_cardiaco']??false); ?></div></div>
<div class="row-item"><div class="label">Intestino Hiperec:</div><div class="value"><?php echo si($m['intestino_hiperecogenico']??false); ?></div></div>
<div class="row-item"><div class="label">Fémur Corto:</div><div class="value"><?php echo si($m['femur_corto']??false); ?></div></div>
<div class="row-item"><div class="label">AU Única:</div><div class="value"><?php echo si($m['arteria_umbilical_unica']??false); ?></div></div>
</div>
</div></div>

<div class="two-col"><div class="col">
<div class="section"><h2>Entorno Placentario</h2>
<div class="row-item"><div class="label">Posición Placenta:</div><div class="value"><?php echo v($en['placenta_posicion']); ?></div></div>
<div class="row-item"><div class="label">Dist. Borde OCI:</div><div class="value"><?php echo v($en['distancia_borde_oci_mm'],' mm'); ?></div></div>
<div class="row-item"><div class="label">Acretismo FIGO:</div><div class="value"><?php echo v($en['acretismo_figo_grado']); ?></div></div>
<div class="row-item"><div class="label">Bolsillo Máx. Líq.:</div><div class="value"><?php echo v($en['bolsillo_max_liquido_mm'],' mm'); ?></div></div>
<div class="row-item"><div class="label">Longitud Cervical:</div><div class="value"><?php echo v($en['longitud_cervical_mm'],' mm'); ?></div></div>
<div class="row-item"><div class="label">Índice Consistencia:</div><div class="value"><?php echo v($en['indice_consistencia_cervical']); ?></div></div>
<div class="row-item"><div class="label">Funneling:</div><div class="value"><?php echo ($en['funneling_presente']??false)?'Presente '.v($en['funneling_mm'],' mm'):'Ausente'; ?></div></div>
<div class="row-item"><div class="label">Sludge:</div><div class="value"><?php echo v($en['sludge_intraamniotico']); ?></div></div>
</div>
</div><div class="col">
<div class="section"><h2>Impresión Diagnóstica</h2>
<div class="row-item"><div class="label">Cromosomopatías:</div><div class="value"><?php echo v($d['riesgo_cromosomopatias']); ?></div></div>
<div class="row-item"><div class="label">Parto Pretérmino:</div><div class="value"><?php echo v($d['riesgo_parto_pretermino']); ?></div></div>
<div class="row-item"><div class="label">Preeclampsia:</div><div class="value"><?php echo v($d['riesgo_preeclampsia']); ?></div></div>
<?php if(!empty($d['observaciones_medicas'])): ?><div class="row-item"><div class="label">Observaciones:</div><div class="value"><?php echo nl2br(htmlspecialchars($d['observaciones_medicas'])); ?></div></div><?php endif; ?>
</div>
<div class="section"><h2>Historial Clínico</h2>
<div class="row-item"><div class="label">Hipertensión:</div><div class="value"><?php echo si($h['hipertension_cronica']??false); ?></div></div>
<div class="row-item"><div class="label">Diabetes:</div><div class="value"><?php echo si($h['diabetes']??false); ?></div></div>
<div class="row-item"><div class="label">Lupus/LES:</div><div class="value"><?php echo si($h['lupus_les']??false); ?></div></div>
<div class="row-item"><div class="label">SAF:</div><div class="value"><?php echo si($h['sindrome_antifosfolipido_saf']??false); ?></div></div>
<div class="row-item"><div class="label">Preeclampsia/RCIU:</div><div class="value"><?php echo si($h['antecedente_preeclampsia_rciu']??false); ?></div></div>
<div class="row-item"><div class="label">FIV:</div><div class="value"><?php echo si($h['fertilizacion_in_vitro']??false); ?></div></div>
<div class="row-item"><div class="label">Parto Pretérmino:</div><div class="value"><?php echo si($h['antecedente_parto_pretermino']??false); ?></div></div>
</div>
</div></div>

<div class="footer">Documento generado el <?php echo date('d/m/Y H:i'); ?> — PreNacer Sistema de Gestión Médico</div>
</div></body></html>
