<?php
$ev=$evaluacion;$a=$antecedentes;$c=$crecimiento;$d=$doppler;$an=$anatomia;$pl=$placentaria;$h=$historial;
if (!function_exists('vx')) {
    function vx($x,$s=''){return($x===null||$x==='')?'—':htmlspecialchars($x).$s;}
}
if (!function_exists('fd')) {
    function fd($x){return$x?date('d/m/Y',strtotime($x)):'—';}
}
if (!function_exists('si')) {
    function si($x,$t='Sí'){return$x?$t:'No';}
}
if (!function_exists('nb')) {
    function nb($x){return nl2br(htmlspecialchars($x??''));}
}
$meses=['','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];

if (!function_exists('pRiskBadge')) {
    function pRiskBadge($val) {
        if ($val === null || $val === '') return '—';
        $valStr = strtolower(trim($val));
        $class = 'badge-secondary';
        if (strpos($valStr, 'bajo') !== false || strpos($valStr, 'normal') !== false || strpos($valStr, 'negativo') !== false) {
            $class = 'badge-success';
        } elseif (strpos($valStr, 'alto') !== false || strpos($valStr, 'alterado') !== false || strpos($valStr, 'positivo') !== false) {
            $class = 'badge-danger';
        } elseif (strpos($valStr, 'medio') !== false || strpos($valStr, 'moderado') !== false || strpos($valStr, 'intermedio') !== false || strpos($valStr, 'rciu') !== false) {
            $class = 'badge-warning';
        }
        return '<span class="badge ' . $class . '">' . htmlspecialchars($val) . '</span>';
    }
}

if (!function_exists('pBool')) {
    function pBool($val, $normal = 'Normal', $alt = 'Alterado') {
        $valStr = $val ? $normal : $alt;
        $class = $val ? 'badge-success' : 'badge-danger';
        return '<span class="badge ' . $class . '">' . $valStr . '</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Evaluación 3er Trimestre — <?php echo htmlspecialchars($ev['codigo_reporte']);?></title>
    <style>
        @media print { .no-print { display: none !important; } }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #2A2A2A;
            line-height: 1.5;
            font-size: 11px;
            padding: 0 10px;
        }
        .document {
            max-width: 800px;
            margin: 0 auto;
        }
        .title-container {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1B4F5A;
            padding-bottom: 10px;
        }
        .title-container h1 {
            font-size: 18px;
            font-weight: bold;
            color: #1B4F5A;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .title-container .subtitle {
            font-size: 12px;
            color: #81BABB;
            margin-top: 5px;
            font-weight: bold;
        }
        h2 {
            font-size: 11px;
            background-color: #1B4F5A;
            color: #FFFFFF;
            padding: 6px 10px;
            margin: 15px 0 8px 0;
            border-bottom: 2px solid #81BABB;
            font-weight: bold;
            text-transform: uppercase;
        }
        h3 {
            font-size: 10px;
            background-color: #F4F7F6;
            color: #1B4F5A;
            padding: 4px 8px;
            margin: 8px 0 5px 0;
            border-left: 3px solid #81BABB;
            font-weight: bold;
        }
        .patient-card {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #F4F7F6;
            border-left: 4px solid #1B4F5A;
        }
        .patient-card td {
            padding: 6px 10px;
            font-size: 10.5px;
            vertical-align: top;
        }
        .patient-card .lbl {
            font-weight: bold;
            color: #1B4F5A;
            width: 120px;
        }
        .patient-card .val {
            color: #2A2A2A;
        }
        .two-col-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .two-col-table td {
            vertical-align: top;
            padding: 0 8px;
        }
        .two-col-table td:first-child {
            padding-left: 0;
        }
        .two-col-table td:last-child {
            padding-right: 0;
        }
        .section-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .section-table td {
            padding: 4px 6px;
            font-size: 10.5px;
            border-bottom: 1px solid #E5EDED;
            vertical-align: middle;
        }
        .section-table tr:last-child td {
            border-bottom: none;
        }
        .section-table .lbl {
            font-weight: bold;
            color: #1B4F5A;
            width: 155px;
            text-align: left;
        }
        .section-table .val {
            color: #2A2A2A;
            text-align: right;
        }
        .placenta-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .placenta-table th {
            background-color: #1B4F5A;
            color: #FFFFFF;
            text-align: left;
            padding: 5px 8px;
            font-size: 10px;
            font-weight: bold;
            border-bottom: 2px solid #81BABB;
        }
        .placenta-table td {
            padding: 5px 8px;
            font-size: 10px;
            border-bottom: 1px solid #E5EDED;
        }
        .placenta-table tr:nth-child(even) td {
            background-color: #F9FBFA;
        }
        .obs-box {
            background-color: #F4F7F6;
            border: 1px solid #D1DDDC;
            padding: 8px 12px;
            font-size: 10px;
            color: #333333;
            margin-top: 5px;
            text-align: left;
        }
        .badge {
            display: inline-block;
            padding: 1px 5px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 3px;
        }
        .badge-success {
            background-color: #EAF8F0;
            color: #226E43;
        }
        .badge-warning {
            background-color: #FCF7E6;
            color: #8F6E0A;
        }
        .badge-danger {
            background-color: #FDF0F0;
            color: #A82424;
        }
        .badge-secondary {
            background-color: #F0F4F8;
            color: #475D74;
        }
        .image-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .image-table td {
            text-align: center;
            padding: 10px;
            vertical-align: top;
            width: 33.33%;
        }
        .image-frame {
            border: 1px solid #D1DDDC;
            background-color: #F4F7F6;
            padding: 6px;
        }
        .image-frame img {
            max-width: 180px;
            max-height: 180px;
            display: block;
            margin: 0 auto 5px auto;
        }
        .image-caption {
            font-size: 10px;
            color: #555;
            font-style: italic;
        }
        .signature-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .signature-table td {
            padding: 10px;
            vertical-align: top;
        }
        .signature-line {
            border-top: 1px solid #1B4F5A;
            width: 220px;
            margin: 0 auto;
            padding-top: 5px;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="document" style="padding-top:5mm;">

    <div class="title-container">
        <h1>EVALUACIÓN 3ER TRIMESTRE</h1>
        <div class="subtitle"><?php echo htmlspecialchars($ev['codigo_reporte']);?> — <?php echo date('j').' de '.$meses[date('n')].' del '.date('Y');?></div>
    </div>

    <!-- Datos del paciente y estudio -->
    <table class="patient-card">
        <tr>
            <td class="lbl">Paciente:</td>
            <td class="val"><strong><?php echo htmlspecialchars($ev['paciente_nombre'].' '.$ev['paciente_apellido']);?></strong></td>
            <td class="lbl">Peso:</td>
            <td class="val"><?php echo vx($ev['peso_kg'], ' kg');?></td>
        </tr>
        <tr>
            <td class="lbl">Médico Tratante:</td>
            <td class="val"><?php echo htmlspecialchars($ev['medico_nombre'].' '.$ev['medico_apellido']);?></td>
            <td class="lbl">Talla:</td>
            <td class="val"><?php echo vx($ev['talla_cm'], ' cm');?></td>
        </tr>
        <tr>
            <td class="lbl">Fecha Evaluación:</td>
            <td class="val"><?php echo fd($ev['fecha_evaluacion']);?></td>
            <td class="lbl">Presión Arterial:</td>
            <td class="val"><?php echo vx($ev['ta_sistolica']);?> / <?php echo vx($ev['ta_diastolica'], ' mmHg');?></td>
        </tr>
        <tr>
            <td class="lbl">Fecha Estudio:</td>
            <td class="val"><?php echo fd($ev['fecha_estudio']);?></td>
            <td class="lbl">Estudio Solicitado:</td>
            <td class="val"><?php echo vx($ev['estudio_solicitado']);?></td>
        </tr>
        <tr>
            <td class="lbl">Edad Gestacional:</td>
            <td class="val"><?php echo vx($ev['edad_gestacional_semanas'], ' sem');?></td>
            <td class="lbl">FPP (FUM):</td>
            <td class="val"><?php echo $ev['fpp_fum'] ? date('d/m/Y', strtotime($ev['fpp_fum'])) : '—';?></td>
        </tr>
        <tr>
            <td class="lbl">FPP (USG):</td>
            <td class="val"><?php echo $ev['fpp_usg'] ? date('d/m/Y', strtotime($ev['fpp_usg'])) : '—';?></td>
            <td class="lbl">Equipo:</td>
            <td class="val"><?php echo vx($ev['equipo_ultrasonido']);?></td>
        </tr>
    </table>

    <?php if(!empty($ev['equipo_ultrasonido']) || !empty($ev['estudio_solicitado'])): ?>
    <p style="font-size:10px; color:#555; margin-bottom:12px; text-align:justify; font-style:italic;">
        Se realizó estudio ultrasonográfico de alta definición<?php echo !empty($ev['equipo_ultrasonido']) ? ', utilizando un equipo '.htmlspecialchars($ev['equipo_ultrasonido']).' con transductor convexo transabdominal, volumétrico de banda ancha' : ''; ?>, reportando los siguientes hallazgos:
    </p>
    <?php endif; ?>

    <!-- Fila 1: Estática Fetal | Crecimiento y RCIU -->
    <table class="two-col-table">
        <tr>
            <td style="width: 50%;">
                <h2>Estática Fetal</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Condición Fetal:</td>
                        <td class="val">Feto único <?php echo strtolower(vx($ev['feto_unico_vivo']));?></td>
                    </tr>
                    <tr>
                        <td class="lbl">FCF:</td>
                        <td class="val"><?php echo vx($ev['fcf_lpm'],' lpm');?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Situación:</td>
                        <td class="val"><?php echo vx($ev['situacion_fetal']);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Presentación:</td>
                        <td class="val"><?php echo vx($ev['presentacion_fetal']);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Posición:</td>
                        <td class="val"><?php echo vx($ev['posicion_fetal']);?></td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%;">
                <h2>Crecimiento y RCIU</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Peso Fetal Estimado:</td>
                        <td class="val"><?php echo vx($c['peso_fetal_estimado_gr'],' gr');?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Percentil Ajustado:</td>
                        <td class="val"><?php echo vx($c['percentil_ajustado']);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Clasificación:</td>
                        <td class="val"><?php echo pRiskBadge($c['clasificacion_crecimiento']);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">RCIU Barcelona:</td>
                        <td class="val"><?php echo pRiskBadge($c['estadio_rciu_barcelona']);?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-bottom:none;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Checklist 1T y 2T (si existen antecedentes) -->
    <?php if (!empty($data1er) || !empty($data2do)): ?>
    <h2>Checklist Prenacer — Antecedentes Históricos</h2>
    <table class="section-table" style="margin-bottom:15px;">
        <?php if (!empty($data1er)): ?>
        <tr>
            <td style="width: 100%; padding: 6px;" colspan="2">
                <strong>Primer Trimestre (1T):</strong>
                Riesgo Preeclampsia (FMF): <?php echo pRiskBadge($data1er['riesgo_preeclampsia_temprana']??null);?> | 
                Doppler UT PI: <?php echo vx($data1er['uta_pi_promedio']??null);?> <?php echo !empty($data1er['muesca_bilateral'])?'(Muesca bilateral)':'';?> | 
                PAPP-A MoM: <?php echo vx($data1er['papp_a_mom']??null);?> | 
                PLGF MoM: <?php echo vx($data1er['plgf_mom']??null);?> | 
                Cervical: <?php echo !empty($data1er['longitud_cervical_mm'])?$data1er['longitud_cervical_mm'].' mm':'—';?>
            </td>
        </tr>
        <?php endif; ?>
        <?php if (!empty($data2do)): ?>
        <tr>
            <td style="width: 100%; padding: 6px;" colspan="2">
                <strong>Segundo Trimestre (2T):</strong>
                Morfología: <?php
                    $morfNormal=true;$cmf=['craneo_snc_normal','cara_cuello_normal','corazon_normal','torax_diafragma_normal','abdomen_normal','genitourinario_normal','columna_normal','extremidades_normal'];
                    foreach($cmf as $cm) if(isset($data2do[$cm])&&$data2do[$cm]==0){$morfNormal=false;break;}
                    echo $morfNormal ? '<span class="badge badge-success">Normal</span>' : '<span class="badge badge-danger">Alterada</span>';
                ?> | 
                Doppler UT PI: <?php echo vx($data2do['uta_pi_promedio']??null);?> | 
                Placenta: <?php echo vx($data2do['placenta_posicion']??null);?> | 
                Cervical: <?php echo !empty($data2do['longitud_cervical_mm'])?$data2do['longitud_cervical_mm'].' mm':'—';?> | 
                Funneling: <?php echo !empty($data2do['funneling_presente'])?'Sí':'No';?>
            </td>
        </tr>
        <?php endif; ?>
    </table>
    <?php endif; ?>

    <!-- Evaluación Placentaria -->
    <div style="page-break-inside: avoid;">
        <h2>Evaluación Placentaria (AJOG 2025 / FIGO 2023)</h2>
        <table class="placenta-table">
            <thead>
                <tr>
                    <th style="width: 35%;">Parámetro</th>
                    <th style="width: 35%;">Valor normal / referencia</th>
                    <th style="width: 30%;">Hallazgo</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>Localización</td><td>Anterior / Posterior / Fúndica / Lateral</td><td><?php echo vx($pl['localizacion_placentaria']);?></td></tr>
                <tr><td>Relación con OCI</td><td>≥20 mm del OCI = normal</td><td><?php echo vx($pl['distancia_oci_mm'],' mm');?></td></tr>
                <tr><td>Grado de madurez placentaria</td><td>Grado 0–1 normal hasta 34 sem</td><td><?php echo vx($pl['grado_madurez']);?></td></tr>
                <tr><td>Grosor placentario</td><td>25–50 mm (según EG)</td><td><?php echo vx($pl['grosor_placentario_mm'],' mm');?></td></tr>
                <tr><td>Ecogenicidad</td><td>Homogénea</td><td><?php echo vx($pl['ecogenicidad']);?></td></tr>
                <tr><td>Lagunas vasculares</td><td>Ausentes / mínimas (Grado 0–1 FIGO)</td><td><?php echo vx($pl['lagunas_vasculares']);?></td></tr>
                <tr><td>Interfase miometrio-placentaria</td><td>Íntegra</td><td><?php echo vx($pl['interfase_miometrial']);?></td></tr>
                <tr><td>Zona retroplacentaria</td><td>Presente, hipoecoica</td><td><?php echo vx($pl['zona_retroplacentaria']);?></td></tr>
                <tr><td>Vasos puente miometriales</td><td>Ausentes</td><td><?php echo si($pl['vasos_puente']??false, 'Presentes');?></td></tr>
                <tr><td>Protrusión placentaria</td><td>No</td><td><?php echo si($pl['protrusion_placentaria']??false);?></td></tr>
                <tr><td>Vascularización anómala (Doppler)</td><td>Flujo periférico fino / sin turbulencia</td><td><?php echo vx($pl['vascularizacion_anomala_doppler']);?></td></tr>
                <tr><td>Inserción del cordón</td><td>Central / Paracentral / Marginal / Velamentosa</td><td><?php echo vx($pl['insercion_cordon']);?></td></tr>
                <tr><td>Número de vasos umbilicales</td><td>3 vasos</td><td><?php echo vx($pl['numero_vasos_umbilicales']);?></td></tr>
                <tr><td>Calcificaciones</td><td>Leves en 3er trimestre</td><td><?php echo vx($pl['calcificaciones']);?></td></tr>
                <tr><td>Doppler 3D (Perfusión)</td><td>VI 20–40%, FI 30–50%, VFI 5–15%</td><td><?php echo vx($pl['perfusion_vi'],'%').' / '.vx($pl['perfusion_fi'],'%').' / '.vx($pl['perfusion_vfi'],'%');?></td></tr>
                <tr><td>Acretismo FIGO (PAS)</td><td>Grado 0 — Normal</td><td><?php echo pRiskBadge($pl['acretismo_figo_pas']);?></td></tr>
            </tbody>
        </table>
    </div>

    <!-- Fila 2: Doppler / Hemodinamia | Anatomía y Líquido Amniótico -->
    <table class="two-col-table" style="page-break-inside: avoid;">
        <tr>
            <td style="width: 50%;">
                <h2>Doppler / Hemodinamia</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Arteria Umbilical (PI):</td>
                        <td class="val"><?php echo vx($d['au_pi']);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Flujo Diastólico AU:</td>
                        <td class="val"><?php echo vx($d['au_flujo_diastolico']);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Art. Cerebral Media (PI):</td>
                        <td class="val"><?php echo vx($d['acm_pi']);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Ductus Venoso (Onda A):</td>
                        <td class="val"><?php echo vx($d['dv_onda_a']);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Art. Uterinas (PI prom):</td>
                        <td class="val"><?php echo vx($d['uta_pi_promedio']);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Ratio CU / ICP:</td>
                        <td class="val"><?php echo vx($d['ratio_cu_icp']);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Vena Umbilical:</td>
                        <td class="val"><?php echo vx($d['vena_umbilical']);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Alteración Doppler:</td>
                        <td class="val"><?php echo pBool($d['alteracion_doppler_detectada']??false, 'Detectada', 'No detectada'); ?></td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%;">
                <h2>Anatomía y Líquido Amniótico</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Circular de Cordón:</td>
                        <td class="val"><?php echo vx($an['circular_cordon_cuello']);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Líquido Amniótico:</td>
                        <td class="val"><?php echo vx($an['liquido_amniotico_mm'],' mm');?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Método Medición:</td>
                        <td class="val"><?php echo vx($an['metodo_medicion_liquido']);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Diagnóstico Líquido:</td>
                        <td class="val"><?php echo vx($an['diagnostico_liquido']);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Estructuras Fetales:</td>
                        <td class="val"><?php echo pBool($an['estructuras_normales']??true);?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Fila 3: Antecedentes 3T | Miomas y Morfología Uterina -->
    <table class="two-col-table" style="page-break-inside: avoid;">
        <tr>
            <td style="width: 50%;">
                <h2>Antecedentes del 3er Trimestre</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Curva Tolerancia Glucosa:</td>
                        <td class="val"><?php echo vx($a['curva_tolerancia_glucosa']);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Diabetes Gestacional:</td>
                        <td class="val"><?php echo si($a['diabetes_gestacional_actual']??false);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Movimientos Fetales:</td>
                        <td class="val"><?php echo vx($a['movimientos_fetales']);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Amenaza Parto Pretérmino:</td>
                        <td class="val"><?php echo si($a['signos_amenaza_parto_pretermino']??false);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Plan de Nacimiento:</td>
                        <td class="val"><?php echo si($a['plan_nacimiento_definido']??false);?></td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%;">
                <h2>Miomas y Morfología Uterina</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Morfología ESHRE-ESGE:</td>
                        <td class="val"><?php echo vx($pl['morfologia_uterina_eshre']);?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Miomas Visibles:</td>
                        <td class="val">
                            <?php echo si($pl['miomas_visibles']??false);?> 
                            <?php echo !empty($pl['miomas_figo_tipo'])?'| FIGO: '.vx($pl['miomas_figo_tipo']):'';?>
                        </td>
                    </tr>
                    <tr>
                        <td class="lbl">Dimensiones Miomas:</td>
                        <td class="val"><?php echo vx($pl['miomas_dimensiones_mm'],' mm');?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Obstruyen Canal:</td>
                        <td class="val"><?php echo si($pl['miomas_obstruyen_canal']??false);?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-bottom:none;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <?php if(!empty($ev['observaciones'])): ?>
    <div style="page-break-inside: avoid; margin-bottom: 15px;">
        <span style="font-weight: bold; color: #1B4F5A; display: block; font-size: 11px;">Observaciones del Estudio:</span>
        <div class="obs-box"><?php echo nb($ev['observaciones']);?></div>
    </div>
    <?php endif; ?>

    <?php if (!empty($imagenes)): ?>
    <div style="page-break-inside: avoid;">
        <h2>IMÁGENES DEL ESTUDIO</h2>
        <table class="image-table">
        <?php $c = 0; foreach ($imagenes as $img): ?>
            <?php if ($c % 3 == 0): ?><tr><?php endif; ?>
            <td>
                <div class="image-frame">
                    <img src="<?php echo Url::to($img['ruta_imagen']); ?>">
                    <?php if (!empty($img['descripcion_imagen'])): ?>
                        <div class="image-caption"><?php echo htmlspecialchars($img['descripcion_imagen']); ?></div>
                    <?php endif; ?>
                </div>
            </td>
            <?php if ($c % 3 == 2): ?></tr><?php endif; ?>
        <?php $c++; endforeach; ?>
        <?php if ($c % 3 != 0): ?></tr><?php endif; ?>
        </table>
    </div>
    <?php endif; ?>

    <table class="signature-table" style="page-break-inside: avoid;">
        <tr>
            <td style="width: 50%; text-align: center;">
                <div class="signature-line">
                    <strong>Dr(a). <?php echo htmlspecialchars($ev['medico_nombre'].' '.$ev['medico_apellido']); ?></strong><br>
                    Firma del Médico
                </div>
            </td>
            <td style="width: 50%; text-align: center; font-size: 10px; color: #777; padding-top: 15px;">
                Fecha de impresión: <?php echo date('d/m/Y H:i'); ?>
            </td>
        </tr>
    </table>

</div>

<div class="no-print" style="text-align:center;margin:30px 0;">
    <a href="<?php echo Url::to('/evaluaciones_3er_trimestre/pdf?id=' . $ev['id']); ?>"
       style="padding:10px 30px;font-size:14px;cursor:pointer;border:none;background:#1B4F5A;color:#fff;border-radius:8px;text-decoration:none;display:inline-block;font-weight:bold;">
        <i class="fa-solid fa-download"></i> Descargar PDF
    </a>
</div>

</body></html>
