<?php
$ev=$evaluacion;$a=$antecedentes;$c=$crecimiento;$d=$doppler;$an=$anatomia;$pl=$placentaria;$h=$historial;
function vx($x,$s=''){return($x===null||$x==='')?'—':htmlspecialchars($x).$s;}
function fd($x){return$x?date('d/m/Y',strtotime($x)):'—';}
function si($x,$t='Sí'){return$x?$t:'No';}
function nb($x){return nl2br(htmlspecialchars($x??''));}
$meses=['','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Evaluación 3er Trimestre — <?php echo htmlspecialchars($ev['codigo_reporte']);?></title>
<style>
@media print { .no-print { display: none !important; } }
body{
font-family:Helvetica,Arial,sans-serif;padding:0 25px 40px 25px;color:#222;font-size:11px;line-height:1.5;}
.document{max-width:780px;margin:0 auto;}
.doc-header{text-align:center;margin-bottom:18px;border-bottom:2px solid #333;padding-bottom:12px;}
.doc-header h1{font-size:16px;margin:0 0 4px 0;text-transform:uppercase;letter-spacing:1px;}
.doc-header .sub{font-size:11px;color:#555;}
.patient-bar{border:1px solid #ccc;padding:10px 14px;margin-bottom:18px;font-size:11px;}
.patient-bar table{width:100%;border-collapse:collapse;}
.patient-bar td{padding:2px 4px;vertical-align:top;}
.patient-bar .lbl{font-weight:bold;white-space:nowrap;width:140px;}
h2{font-size:12px;background:#555;color:#fff;padding:4px 10px;margin:16px 0 8px 0;text-transform:uppercase;letter-spacing:0.5px;}
h3{font-size:11px;background:#eee;padding:4px 10px;margin:10px 0 6px 0;color:#333;}
.placenta-table{width:100%;border-collapse:collapse;margin:8px 0;font-size:11px;}
.placenta-table th{background:#555;color:#fff;text-align:left;padding:5px 8px;font-size:10px;}
.placenta-table td{padding:4px 8px;border-bottom:1px solid #ddd;}
.placenta-table tr:nth-child(even) td{background:#fafafa;}
.two-col{display:flex;gap:24px;}.col{flex:1;}
.row-item{display:flex;margin-bottom:3px;}.label{width:165px;font-weight:bold;flex-shrink:0;}.value{flex:1;}
.obs-box{border:1px dashed #ccc;padding:10px;margin:8px 0;min-height:40px;font-style:italic;color:#555;}
.signature-line{border-top:1px solid #333;margin-top:40px;text-align:center;font-size:11px;padding-top:4px;width:250px;}
</style></head><body>

<div class="document" style="padding-top:10mm;">

<div class="doc-header">
    <h1>Evaluación 3er Trimestre</h1>
    <div class="sub"><?php echo htmlspecialchars($ev['codigo_reporte']);?> — <?php echo date('j').' de '.$meses[date('n')].' del '.date('Y');?></div>
</div>

<!-- Datos del paciente y estudio -->
<div class="patient-bar"><table>
    <tr><td class="lbl">Paciente:</td><td><strong><?php echo htmlspecialchars($ev['paciente_nombre'].' '.$ev['paciente_apellido']);?></strong></td>
        <td class="lbl">Peso:</td><td><?php echo vx($ev['peso_kg'],' kg');?></td></tr>
    <tr><td class="lbl">Médico:</td><td><?php echo htmlspecialchars($ev['medico_nombre'].' '.$ev['medico_apellido']);?></td>
        <td class="lbl">Talla:</td><td><?php echo vx($ev['talla_cm'],' cm');?></td></tr>
    <tr><td class="lbl">Fecha Evaluación:</td><td><?php echo fd($ev['fecha_evaluacion']);?></td>
        <td class="lbl">TA:</td><td><?php echo vx($ev['ta_sistolica']);?> / <?php echo vx($ev['ta_diastolica'],' mmHg');?></td></tr>
    <tr><td class="lbl">Fecha Estudio:</td><td><?php echo fd($ev['fecha_estudio']);?></td>
        <td class="lbl">Estudio Solicitado:</td><td><?php echo vx($ev['estudio_solicitado']);?></td></tr>
    <tr><td class="lbl">Edad Gestacional:</td><td><?php echo vx($ev['edad_gestacional_semanas'],' semanas');?></td>
        <td class="lbl">FPP (FUM):</td><td><?php echo $ev['fpp_fum']?date('d \d\e F \d\e Y',strtotime($ev['fpp_fum'])):'—';?></td></tr>
    <tr><td class="lbl">FPP (USG):</td><td><?php echo $ev['fpp_usg']?date('d \d\e F \d\e Y',strtotime($ev['fpp_usg'])):'—';?></td>
        <td class="lbl">Equipo:</td><td><?php echo vx($ev['equipo_ultrasonido']);?></td></tr>
</table></div>

<!-- Descripción del estudio -->
<?php if(!empty($ev['equipo_ultrasonido']) || !empty($ev['estudio_solicitado'])): ?>
<p style="font-size:10px;color:#555;margin-bottom:18px;text-align:justify;">
Se realizó estudio ultrasonográfico de alta definición<?php echo !empty($ev['equipo_ultrasonido']) ? ', utilizando un equipo '.htmlspecialchars($ev['equipo_ultrasonido']).' con transductor convexo transabdominal, volumétrico de banda ancha' : ''; ?>, encontrando:
</p>
<?php endif; ?>

<h2>Estática Fetal</h2>
<div class="two-col"><div class="col">
<div class="row-item"><div class="label">Condición Fetal:</div><div class="value">Feto único <?php echo strtolower(vx($ev['feto_unico_vivo']));?></div></div>
<div class="row-item"><div class="label">FCF:</div><div class="value"><?php echo vx($ev['fcf_lpm'],' latidos/minuto');?></div></div>
<div class="row-item"><div class="label">Situación:</div><div class="value"><?php echo vx($ev['situacion_fetal']);?></div></div>
</div><div class="col">
<div class="row-item"><div class="label">Presentación:</div><div class="value"><?php echo vx($ev['presentacion_fetal']);?></div></div>
<div class="row-item"><div class="label">Posición:</div><div class="value"><?php echo vx($ev['posicion_fetal']);?></div></div>
</div></div>

<!-- Checklist 1T y 2T -->
<?php if (!empty($data1er) || !empty($data2do)): ?>
<h2>Checklist Prenacer — Antecedentes</h2>
<?php if (!empty($data1er)): ?>
<div class="two-col"><div class="col">
<div class="row-item"><div class="label">Riesgo Preeclampsia (FMF):</div><div class="value"><?php echo vx($data1er['riesgo_preeclampsia_temprana']??null);?></div></div>
<div class="row-item"><div class="label">Doppler UT PI:</div><div class="value"><?php echo vx($data1er['uta_pi_promedio']??null);?> <?php echo !empty($data1er['muesca_bilateral'])?'(Muesca bilateral)':'';?></div></div>
<div class="row-item"><div class="label">PAPP-A MoM:</div><div class="value"><?php echo vx($data1er['papp_a_mom']??null);?></div></div>
<div class="row-item"><div class="label">PLGF MoM:</div><div class="value"><?php echo vx($data1er['plgf_mom']??null);?></div></div>
</div><div class="col">
<div class="row-item"><div class="label">Tamizaje Genético:</div><div class="value"><?php echo !empty($data1er['tamizaje_genetico_tipo'])?vx($data1er['tamizaje_genetico_tipo']).' — '.vx($data1er['tamizaje_genetico_resultado']??null):'—';?></div></div>
<div class="row-item"><div class="label">Longitud Cervical 1T:</div><div class="value"><?php echo !empty($data1er['longitud_cervical_mm'])?$data1er['longitud_cervical_mm'].' mm':'—';?></div></div>
<div class="row-item"><div class="label">Miomas 1T:</div><div class="value"><?php echo !empty($data1er['miomas_visibles'])?'Sí (FIGO: '.($data1er['miomas_figo_tipo']??'—').')':'No';?></div></div>
</div></div>
<?php endif; ?>
<?php if (!empty($data2do)): ?><div style="margin-top:10px;"><strong>2do Trimestre:</strong> Morfología: <?php
    $morfNormal=true;$cmf=['craneo_snc_normal','cara_cuello_normal','corazon_normal','torax_diafragma_normal','abdomen_normal','genitourinario_normal','columna_normal','extremidades_normal'];
    foreach($cmf as $cm) if(isset($data2do[$cm])&&$data2do[$cm]==0){$morfNormal=false;break;}
    echo $morfNormal?'Normal':'<strong>Alterada</strong>';
?> | Doppler UT PI: <?php echo vx($data2do['uta_pi_promedio']??null);?> | Placenta: <?php echo vx($data2do['placenta_posicion']??null);?> | Cervical: <?php echo !empty($data2do['longitud_cervical_mm'])?$data2do['longitud_cervical_mm'].' mm':'—';?> | Funneling: <?php echo !empty($data2do['funneling_presente'])?'Sí':'No';?></div>
<?php endif; ?>
<?php endif; ?>

<h2>Evaluación Placentaria (AJOG 2025 / FIGO 2023)</h2>
<table class="placenta-table">
<tr><th>Parámetro</th><th>Valor normal / referencia</th><th>Hallazgo</th></tr>
<tr><td>Localización</td><td>Anterior / Posterior / Fúndica / Lateral</td><td><?php echo vx($pl['localizacion_placentaria']);?></td></tr>
<tr><td>Relación con OCI</td><td>≥20 mm del OCI = normal</td><td><?php echo vx($pl['distancia_oci_mm'],' mm');?></td></tr>
<tr><td>Grado de madurez placentaria</td><td>Grado 0–1 normal hasta 34 sem</td><td><?php echo vx($pl['grado_madurez']);?></td></tr>
<tr><td>Grosor placentario</td><td>25–50 mm (según EG)</td><td><?php echo vx($pl['grosor_placentario_mm'],' mm');?></td></tr>
<tr><td>Ecogenicidad</td><td>Homogénea</td><td><?php echo vx($pl['ecogenicidad']);?></td></tr>
<tr><td>Lagunas vasculares</td><td>Ausentes / mínimas (Grado 0–1 FIGO)</td><td><?php echo vx($pl['lagunas_vasculares']);?></td></tr>
<tr><td>Interfase miometrio-placentaria</td><td>Íntegra</td><td><?php echo vx($pl['interfase_miometrial']);?></td></tr>
<tr><td>Zona retroplacentaria</td><td>Presente, hipoecoica</td><td><?php echo vx($pl['zona_retroplacentaria']);?></td></tr>
<tr><td>Vasos puente miometriales</td><td>Ausentes</td><td><?php echo si($pl['vasos_puente']??false,'Presentes');?></td></tr>
<tr><td>Protrusión placentaria</td><td>No</td><td><?php echo si($pl['protrusion_placentaria']??false);?></td></tr>
<tr><td>Vascularización anómala (Color Doppler)</td><td>Flujo periférico fino / sin turbulencia</td><td><?php echo vx($pl['vascularizacion_anomala_doppler']);?></td></tr>
<tr><td>Inserción del cordón</td><td>Central / Paracentral / Marginal / Velamentosa</td><td><?php echo vx($pl['insercion_cordon']);?></td></tr>
<tr><td>Número de vasos umbilicales</td><td>3 vasos</td><td><?php echo vx($pl['numero_vasos_umbilicales']);?></td></tr>
<tr><td>Calcificaciones</td><td>Leves en 3er trimestre</td><td><?php echo vx($pl['calcificaciones']);?></td></tr>
<tr><td>Índice de perfusión placentaria (Doppler 3D)</td><td>VI 20–40%, FI 30–50%, VFI 5–15%</td><td><?php echo vx($pl['perfusion_vi'],'%').' / '.vx($pl['perfusion_fi'],'%').' / '.vx($pl['perfusion_vfi'],'%');?></td></tr>
<tr><td>Acretismo FIGO (PAS)</td><td>Grado 0 — Normal</td><td><?php echo vx($pl['acretismo_figo_pas']);?></td></tr>
</table>

<h2>Crecimiento y RCIU</h2>
<div class="two-col"><div class="col">
<div class="row-item"><div class="label">Peso Fetal Estimado:</div><div class="value"><?php echo vx($c['peso_fetal_estimado_gr'],' gr');?></div></div>
<div class="row-item"><div class="label">Percentil Ajustado:</div><div class="value"><?php echo vx($c['percentil_ajustado']);?></div></div>
</div><div class="col">
<div class="row-item"><div class="label">Clasificación:</div><div class="value"><?php echo vx($c['clasificacion_crecimiento']);?></div></div>
<div class="row-item"><div class="label">RCIU Barcelona:</div><div class="value"><?php echo vx($c['estadio_rciu_barcelona']);?></div></div>
</div></div>

<h2>Doppler / Hemodinamia</h2>
<div class="two-col"><div class="col">
<div class="row-item"><div class="label">Arteria Umbilical (PI):</div><div class="value"><?php echo vx($d['au_pi']);?></div></div>
<div class="row-item"><div class="label">Flujo Diastólico AU:</div><div class="value"><?php echo vx($d['au_flujo_diastolico']);?></div></div>
<div class="row-item"><div class="label">Arteria Cerebral Media (PI):</div><div class="value"><?php echo vx($d['acm_pi']);?></div></div>
<div class="row-item"><div class="label">Ductus Venoso (Onda A):</div><div class="value"><?php echo vx($d['dv_onda_a']);?></div></div>
</div><div class="col">
<div class="row-item"><div class="label">Arterias Uterinas (PI prom):</div><div class="value"><?php echo vx($d['uta_pi_promedio']);?></div></div>
<div class="row-item"><div class="label">Ratio CU/ICP:</div><div class="value"><?php echo vx($d['ratio_cu_icp']);?></div></div>
<div class="row-item"><div class="label">Vena Umbilical:</div><div class="value"><?php echo vx($d['vena_umbilical']);?></div></div>
<div class="row-item"><div class="label">Alteración Doppler:</div><div class="value"><?php echo si($d['alteracion_doppler_detectada']??false);?></div></div>
</div></div>

<h2>Anatomía y Líquido Amniótico</h2>
<div class="two-col"><div class="col">
<div class="row-item"><div class="label">Circular de Cordón:</div><div class="value"><?php echo vx($an['circular_cordon_cuello']);?></div></div>
<div class="row-item"><div class="label">Líquido Amniótico:</div><div class="value"><?php echo vx($an['liquido_amniotico_mm'],' mm');?></div></div>
<div class="row-item"><div class="label">Método Medición:</div><div class="value"><?php echo vx($an['metodo_medicion_liquido']);?></div></div>
</div><div class="col">
<div class="row-item"><div class="label">Diagnóstico Líquido:</div><div class="value"><?php echo vx($an['diagnostico_liquido']);?></div></div>
<div class="row-item"><div class="label">Estructuras Normales:</div><div class="value"><?php echo si($an['estructuras_normales']??true);?></div></div>
</div></div>

<h2>Antecedentes del 3er Trimestre</h2>
<div class="two-col"><div class="col">
<div class="row-item"><div class="label">Curva Tolerancia Glucosa:</div><div class="value"><?php echo vx($a['curva_tolerancia_glucosa']);?></div></div>
<div class="row-item"><div class="label">Diabetes Gestacional:</div><div class="value"><?php echo si($a['diabetes_gestacional_actual']??false);?></div></div>
<div class="row-item"><div class="label">Movimientos Fetales:</div><div class="value"><?php echo vx($a['movimientos_fetales']);?></div></div>
</div><div class="col">
<div class="row-item"><div class="label">Amenaza Parto Pretérmino:</div><div class="value"><?php echo si($a['signos_amenaza_parto_pretermino']??false);?></div></div>
<div class="row-item"><div class="label">Plan de Nacimiento:</div><div class="value"><?php echo si($a['plan_nacimiento_definido']??false);?></div></div>
</div></div>

<?php if(!empty($pl['miomas_visibles']) || !empty($pl['morfologia_uterina_eshre'])): ?>
<h3>Miomas y Morfología Uterina</h3>
<div class="row-item"><div class="label">Morfología ESHRE-ESGE:</div><div class="value"><?php echo vx($pl['morfologia_uterina_eshre']);?></div></div>
<div class="row-item"><div class="label">Miomas Visibles:</div><div class="value"><?php echo si($pl['miomas_visibles']??false);?> <?php echo !empty($pl['miomas_figo_tipo'])?'| FIGO: '.vx($pl['miomas_figo_tipo']):'';?> <?php echo !empty($pl['miomas_dimensiones_mm'])?'| '.vx($pl['miomas_dimensiones_mm'],' mm'):'';?></div></div>
<div class="row-item"><div class="label">Obstruyen Canal:</div><div class="value"><?php echo si($pl['miomas_obstruyen_canal']??false);?></div></div>
<?php endif; ?>

<?php if(!empty($ev['observaciones'])): ?>
<h3>Observaciones</h3>
<div class="obs-box"><?php echo nb($ev['observaciones']);?></div>
<?php endif; ?>

<?php if (!empty($imagenes)): ?>
<h2>IMÁGENES DEL ESTUDIO</h2>
<table style="width:100%;border-collapse:collapse;">
<?php $c = 0; foreach ($imagenes as $img): ?>
    <?php if ($c % 3 == 0): ?><tr><?php endif; ?>
    <td style="text-align:center;padding:8px;vertical-align:top;">
        <img src="<?php echo Url::to($img['ruta_imagen']); ?>" style="max-width:180px;max-height:180px;border:1px solid #ddd;padding:2px;">
    </td>
    <?php if ($c % 3 == 2): ?></tr><?php endif; ?>
<?php $c++; endforeach; ?>
<?php if ($c % 3 != 0): ?></tr><?php endif; ?>
</table>
<?php endif; ?>

<div class="signature-line">Firma del Médico: <?php echo htmlspecialchars($ev['medico_nombre'].' '.$ev['medico_apellido']);?></div>

</div>

<div class="no-print" style="text-align:center;margin:20px 0;"><a href="<?php echo Url::to('/evaluaciones_3er_trimestre/pdf?id=' . $ev['id']); ?>"
       style="padding:10px 30px;font-size:16px;cursor:pointer;border:none;background:#1B4F5A;color:#fff;border-radius:8px;text-decoration:none;display:inline-block;">
        <i class="fa-solid fa-download"></i> Descargar PDF
    </a>
</div>
</body></html>
