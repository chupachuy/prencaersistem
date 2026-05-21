<?php
$ev=$evaluacion;$a=$antecedentes;$c=$crecimiento;$d=$doppler;$an=$anatomia;$pl=$placentaria;$h=$historial;
function vx($x,$s=''){return($x===null||$x==='')?'—':htmlspecialchars($x).$s;}
function fd($x){return$x?date('d/m/Y',strtotime($x)):'—';}
function si($x){return$x?'Sí':'No';}
?><!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Evaluación 3er Trimestre — <?php echo htmlspecialchars($ev['codigo_reporte']);?></title>
<style>body{font-family:Helvetica,Arial,sans-serif;padding:30px;color:#333;}.document{max-width:800px;margin:0 auto;}
.doc-header{text-align:center;margin-bottom:30px;border-bottom:2px solid #333;padding-bottom:15px;}.doc-header h1{font-size:20px;margin:0;}
.section{margin-bottom:25px;}.section h2{font-size:13px;background:#f5f5f5;padding:6px 10px;margin:0 0 10px 0;border-left:4px solid #333;}
.row-item{display:flex;margin-bottom:4px;font-size:12px;}.label{width:170px;font-weight:bold;flex-shrink:0;}.value{flex:1;}
.two-col{display:flex;gap:30px;}.col{flex:1;}.footer{margin-top:40px;font-size:10px;color:#999;text-align:center;border-top:1px solid #eee;padding-top:10px;}
@media print{body{padding:15px;}}</style></head><body><div class="document">
<div class="doc-header"><h1>EVALUACIÓN 3ER TRIMESTRE</h1><div style="font-size:13px;color:#666;margin-top:5px;"><?php echo htmlspecialchars($ev['codigo_reporte']);?></div></div>

<div class="section"><h2>Datos Generales</h2>
<div class="row-item"><div class="label">Fecha Evaluación:</div><div class="value"><?php echo fd($ev['fecha_evaluacion']);?></div></div>
<div class="row-item"><div class="label">Fecha Estudio:</div><div class="value"><?php echo fd($ev['fecha_estudio']);?></div></div>
<div class="row-item"><div class="label">Paciente:</div><div class="value"><?php echo htmlspecialchars($ev['paciente_nombre'].' '.$ev['paciente_apellido']);?></div></div>
<div class="row-item"><div class="label">Médico:</div><div class="value"><?php echo htmlspecialchars($ev['medico_nombre'].' '.$ev['medico_apellido']);?></div></div>
<div class="row-item"><div class="label">Estado:</div><div class="value"><?php echo htmlspecialchars($ev['estado']);?></div></div></div>

<?php if (!empty($data1er) || !empty($data2do)): ?>
<?php if (!empty($data1er)): ?>
<div class="section"><h2>Checklist Prenacer – Antecedentes 1er Trimestre</h2>
<div class="two-col"><div class="col">
<div class="row-item"><div class="label">Riesgo Preeclampsia:</div><div class="value"><?php echo vx($data1er['riesgo_preeclampsia_temprana']??null);?></div></div>
<div class="row-item"><div class="label">Doppler UT PI:</div><div class="value"><?php echo vx($data1er['uta_pi_promedio']??null);?> <?php echo !empty($data1er['muesca_bilateral'])?'(Muesca bilateral)':'';?></div></div>
<div class="row-item"><div class="label">PAPP-A MoM:</div><div class="value"><?php echo vx($data1er['papp_a_mom']??null);?></div></div>
<div class="row-item"><div class="label">PLGF MoM:</div><div class="value"><?php echo vx($data1er['plgf_mom']??null);?></div></div>
</div><div class="col">
<div class="row-item"><div class="label">Tamizaje Genético:</div><div class="value"><?php echo !empty($data1er['tamizaje_genetico_tipo'])?vx($data1er['tamizaje_genetico_tipo']).' — '.vx($data1er['tamizaje_genetico_resultado']??null):'—';?></div></div>
<div class="row-item"><div class="label">Longitud Cervical:</div><div class="value"><?php echo !empty($data1er['longitud_cervical_mm'])?$data1er['longitud_cervical_mm'].' mm':'—';?></div></div>
<div class="row-item"><div class="label">Miomas:</div><div class="value"><?php echo !empty($data1er['miomas_visibles'])?'Sí (FIGO: '.($data1er['miomas_figo_tipo']??'—').')':'No';?></div></div>
</div></div></div>
<?php endif; ?>
<?php if (!empty($data2do)): ?>
<div class="section"><h2>Checklist Prenacer – Antecedentes 2do Trimestre</h2>
<div class="two-col"><div class="col">
<div class="row-item"><div class="label">Morfología Fetal:</div><div class="value"><?php
    $morfNormal=true;
    $cmf=['craneo_snc_normal','cara_cuello_normal','corazon_normal','torax_diafragma_normal','abdomen_normal','genitourinario_normal','columna_normal','extremidades_normal'];
    foreach($cmf as $cm) if(isset($data2do[$cm])&&$data2do[$cm]==0){$morfNormal=false;break;}
    echo $morfNormal?'Normal':'<strong>Alterada</strong>';
?></div></div>
<div class="row-item"><div class="label">Doppler UT PI:</div><div class="value"><?php echo vx($data2do['uta_pi_promedio']??null);?></div></div>
<div class="row-item"><div class="label">Placenta:</div><div class="value"><?php echo vx($data2do['placenta_posicion']??null);?> | Acretismo: <?php echo vx($data2do['acretismo_figo_grado']??null);?></div></div>
<div class="row-item"><div class="label">Longitud Cervical:</div><div class="value"><?php echo !empty($data2do['longitud_cervical_mm'])?$data2do['longitud_cervical_mm'].' mm':'—';?></div></div>
</div><div class="col">
<div class="row-item"><div class="label">ICC:</div><div class="value"><?php echo !empty($data2do['indice_consistencia_cervical'])?$data2do['indice_consistencia_cervical'].'%':'—';?></div></div>
<div class="row-item"><div class="label">Funneling:</div><div class="value"><?php echo !empty($data2do['funneling_presente'])?'Sí ('.$data2do['funneling_mm'].' mm)':'No';?></div></div>
<div class="row-item"><div class="label">Sludge:</div><div class="value"><?php echo vx($data2do['sludge_intraamniotico']??null);?></div></div>
<div class="row-item"><div class="label">Signos RCIU:</div><div class="value"><?php
    $rciu=false;
    if(!empty($data2do['percentil_hadlock'])&&$data2do['percentil_hadlock']<10)$rciu=true;
    if(!empty($data2do['crecimiento_armonico'])&&$data2do['crecimiento_armonico']==0)$rciu=true;
    echo $rciu?'Sí':'No';
?></div></div>
</div></div></div>
<?php endif; ?>
<?php endif; ?>

<div class="two-col"><div class="col">
<div class="section"><h2>Signos Vitales y Estática</h2>
<div class="row-item"><div class="label">Edad Gestacional:</div><div class="value"><?php echo vx($ev['edad_gestacional_semanas'],' sem');?></div></div>
<div class="row-item"><div class="label">Peso Materno:</div><div class="value"><?php echo vx($ev['peso_kg'],' kg');?></div></div>
<div class="row-item"><div class="label">TA Sistólica:</div><div class="value"><?php echo vx($ev['ta_sistolica'],' mmHg');?></div></div>
<div class="row-item"><div class="label">TA Diastólica:</div><div class="value"><?php echo vx($ev['ta_diastolica'],' mmHg');?></div></div>
<div class="row-item"><div class="label">FCF:</div><div class="value"><?php echo vx($ev['fcf_lpm'],' lpm');?></div></div>
<div class="row-item"><div class="label">Situación Fetal:</div><div class="value"><?php echo vx($ev['situacion_fetal']);?></div></div>
<div class="row-item"><div class="label">Presentación:</div><div class="value"><?php echo vx($ev['presentacion_fetal']);?></div></div>
<div class="row-item"><div class="label">Posición:</div><div class="value"><?php echo vx($ev['posicion_fetal']);?></div></div></div>

<div class="section"><h2>Antecedentes</h2>
<div class="row-item"><div class="label">Curva Tolerancia:</div><div class="value"><?php echo vx($a['curva_tolerancia_glucosa']);?></div></div>
<div class="row-item"><div class="label">Diabetes Gestacional:</div><div class="value"><?php echo si($a['diabetes_gestacional_actual']??false);?></div></div>
<div class="row-item"><div class="label">Movimientos Fetales:</div><div class="value"><?php echo vx($a['movimientos_fetales']);?></div></div>
<div class="row-item"><div class="label">Amenaza Parto Pret.:</div><div class="value"><?php echo si($a['signos_amenaza_parto_pretermino']??false);?></div></div>
<div class="row-item"><div class="label">Plan Nacimiento:</div><div class="value"><?php echo si($a['plan_nacimiento_definido']??false);?></div></div></div>

<div class="section"><h2>Doppler / Hemodinamia</h2>
<div class="row-item"><div class="label">AU PI:</div><div class="value"><?php echo vx($d['au_pi']);?></div></div>
<div class="row-item"><div class="label">Flujo Diastólico AU:</div><div class="value"><?php echo vx($d['au_flujo_diastolico']);?></div></div>
<div class="row-item"><div class="label">ACM PI:</div><div class="value"><?php echo vx($d['acm_pi']);?></div></div>
<div class="row-item"><div class="label">DV Onda A:</div><div class="value"><?php echo vx($d['dv_onda_a']);?></div></div>
<div class="row-item"><div class="label">UTA PI Promedio:</div><div class="value"><?php echo vx($d['uta_pi_promedio']);?></div></div>
<div class="row-item"><div class="label">Ratio CU/ICP:</div><div class="value"><?php echo vx($d['ratio_cu_icp']);?></div></div>
<div class="row-item"><div class="label">Alteración Doppler:</div><div class="value"><?php echo si($d['alteracion_doppler_detectada']??false);?></div></div></div>
</div><div class="col">

<div class="section"><h2>Crecimiento y RCIU</h2>
<div class="row-item"><div class="label">Peso Fetal:</div><div class="value"><?php echo vx($c['peso_fetal_estimado_gr'],' gr');?></div></div>
<div class="row-item"><div class="label">Percentil Ajustado:</div><div class="value"><?php echo vx($c['percentil_ajustado']);?></div></div>
<div class="row-item"><div class="label">Clasificación:</div><div class="value"><?php echo vx($c['clasificacion_crecimiento']);?></div></div>
<div class="row-item"><div class="label">RCIU Barcelona:</div><div class="value"><?php echo vx($c['estadio_rciu_barcelona']);?></div></div></div>

<div class="section"><h2>Anatomía y Líquido</h2>
<div class="row-item"><div class="label">Circular Cordón:</div><div class="value"><?php echo vx($an['circular_cordon_cuello']);?></div></div>
<div class="row-item"><div class="label">Líquido Amniótico:</div><div class="value"><?php echo vx($an['liquido_amniotico_mm'],' mm');?></div></div>
<div class="row-item"><div class="label">Método Medición:</div><div class="value"><?php echo vx($an['metodo_medicion_liquido']);?></div></div>
<div class="row-item"><div class="label">Diagnóstico Líquido:</div><div class="value"><?php echo vx($an['diagnostico_liquido']);?></div></div>
<div class="row-item"><div class="label">Estructuras Normales:</div><div class="value"><?php echo si($an['estructuras_normales']??true);?></div></div></div>

<div class="section"><h2>Evaluación Placentaria</h2>
<div class="row-item"><div class="label">Distancia OCI:</div><div class="value"><?php echo vx($pl['distancia_oci_mm'],' mm');?></div></div>
<div class="row-item"><div class="label">Grosor Placentario:</div><div class="value"><?php echo vx($pl['grosor_placentario_mm'],' mm');?></div></div>
<div class="row-item"><div class="label">Grado Madurez:</div><div class="value"><?php echo vx($pl['grado_madurez']);?></div></div>
<div class="row-item"><div class="label">Lagunas Vasculares:</div><div class="value"><?php echo vx($pl['lagunas_vasculares']);?></div></div>
<div class="row-item"><div class="label">Interfase Miometrial:</div><div class="value"><?php echo vx($pl['interfase_miometrial']);?></div></div>
<div class="row-item"><div class="label">Vasos Puente:</div><div class="value"><?php echo si($pl['vasos_puente']??false);?></div></div>
<div class="row-item"><div class="label">Acretismo FIGO PAS:</div><div class="value"><?php echo vx($pl['acretismo_figo_pas']);?></div></div></div>
</div></div>

<div class="section"><h2>Historial Clínico</h2>
<div class="two-col"><div class="col">
<div class="row-item"><div class="label">Hipertensión:</div><div class="value"><?php echo si($h['hipertension_cronica']??false);?></div></div>
<div class="row-item"><div class="label">Diabetes:</div><div class="value"><?php echo si($h['diabetes']??false);?></div></div>
<div class="row-item"><div class="label">Lupus/LES:</div><div class="value"><?php echo si($h['lupus_les']??false);?></div></div>
<div class="row-item"><div class="label">SAF:</div><div class="value"><?php echo si($h['sindrome_antifosfolipido_saf']??false);?></div></div></div><div class="col">
<div class="row-item"><div class="label">Preeclampsia/RCIU:</div><div class="value"><?php echo si($h['antecedente_preeclampsia_rciu']??false);?></div></div>
<div class="row-item"><div class="label">FIV:</div><div class="value"><?php echo si($h['fertilizacion_in_vitro']??false);?></div></div>
<div class="row-item"><div class="label">Parto Pretérmino:</div><div class="value"><?php echo si($h['antecedente_parto_pretermino']??false);?></div></div></div></div></div>

<div class="footer">Documento generado el <?php echo date('d/m/Y H:i');?> — PreNacer Sistema de Gestión Médico</div>
</div></body></html>
