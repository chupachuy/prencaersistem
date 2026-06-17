<?php
$ev=$evaluacion;$b=$biometria;$a=$anatomia;$m=$marcadores;$en=$entorno;$d=$diagnostica;$h=$historial;
if (!function_exists('v')) {
    function v($x,$s=''){return ($x===null||$x==='')?'—':htmlspecialchars($x).$s;}
}
if (!function_exists('fd')) {
    function fd($x){return $x?date('d/m/Y',strtotime($x)):'—';}
}
if (!function_exists('si')) {
    function si($x){return $x?'Sí':'No';}
}

if (!function_exists('pRiskBadge')) {
    function pRiskBadge($val) {
        if ($val === null || $val === '') return '—';
        $valStr = strtolower(trim($val));
        $class = 'badge-secondary';
        if (strpos($valStr, 'bajo') !== false || strpos($valStr, 'normal') !== false || strpos($valStr, 'negativo') !== false) {
            $class = 'badge-success';
        } elseif (strpos($valStr, 'alto') !== false || strpos($valStr, 'alterado') !== false || strpos($valStr, 'positivo') !== false) {
            $class = 'badge-danger';
        } elseif (strpos($valStr, 'medio') !== false || strpos($valStr, 'moderado') !== false || strpos($valStr, 'intermedio') !== false) {
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
    <title>Evaluación 2do Trimestre — <?php echo htmlspecialchars($ev['codigo_reporte']); ?></title>
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
            margin: 0 0 10px 0;
            border-bottom: 2px solid #81BABB;
            font-weight: bold;
            text-transform: uppercase;
        }
        .two-col-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .two-col-table td {
            vertical-align: top;
            padding: 0 10px;
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
            padding: 5px 8px;
            font-size: 11px;
            border-bottom: 1px solid #E5EDED;
            vertical-align: middle;
        }
        .section-table tr:last-child td {
            border-bottom: none;
        }
        .section-table .lbl {
            font-weight: bold;
            color: #1B4F5A;
            width: 160px;
            text-align: left;
        }
        .section-table .val {
            color: #2A2A2A;
            text-align: right;
        }
        .comparison-note {
            font-size: 9px;
            color: #475D74;
            background-color: #F0F4F8;
            padding: 1px 4px;
            border-radius: 3px;
            display: inline-block;
            margin-left: 4px;
            font-weight: normal;
        }
        .notes-box {
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
            padding: 2px 6px;
            font-size: 10px;
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
            margin-top: 40px;
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
        <h1>EVALUACIÓN 2DO TRIMESTRE</h1>
        <div class="subtitle">Código: <?php echo htmlspecialchars($ev['codigo_reporte']); ?></div>
    </div>

    <!-- Fila 1: Datos Generales | Datos Clínicos -->
    <table class="two-col-table">
        <tr>
            <td style="width:50%;">
                <h2>Datos Generales</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Fecha Evaluación:</td>
                        <td class="val"><?php echo fd($ev['fecha_evaluacion']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Fecha Estudio:</td>
                        <td class="val"><?php echo fd($ev['fecha_estudio']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Paciente:</td>
                        <td class="val"><strong><?php echo htmlspecialchars($ev['paciente_nombre'].' '.$ev['paciente_apellido']); ?></strong></td>
                    </tr>
                    <tr>
                        <td class="lbl">Médico:</td>
                        <td class="val"><?php echo htmlspecialchars($ev['medico_nombre'].' '.$ev['medico_apellido']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Estado:</td>
                        <td class="val"><?php echo pRiskBadge($ev['estado']); ?></td>
                    </tr>
                </table>
            </td>
            <td style="width:50%;">
                <h2>Datos Clínicos</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Peso:</td>
                        <td class="val">
                            <?php echo v($ev['peso_kg'],' kg'); ?>
                            <?php if(!empty($ev['peso_kg']) && !empty($data1er['peso_kg'])): 
                                $dp = round($ev['peso_kg'] - $data1er['peso_kg'], 2); ?>
                                <span class="comparison-note">(<?php echo $dp >= 0 ? '+' : ''; ?><?php echo $dp; ?> kg vs 1T)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="lbl">Talla:</td>
                        <td class="val">
                            <?php echo v($ev['talla_cm'],' cm'); ?>
                            <?php if(!empty($ev['talla_cm']) && !empty($data1er['talla_cm'])): ?>
                                <span class="comparison-note">(1T: <?php echo $data1er['talla_cm']; ?> cm)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="lbl">PAM:</td>
                        <td class="val">
                            <?php echo v($ev['pam_mmhg'],' mmHg'); ?>
                            <?php if(!empty($ev['pam_mmhg']) && !empty($data1er['ta_sistolica']) && !empty($data1er['ta_diastolica'])): 
                                $p1 = round(($data1er['ta_sistolica'] + 2 * $data1er['ta_diastolica']) / 3, 2); 
                                $dpam = round($ev['pam_mmhg'] - $p1, 2); ?>
                                <span class="comparison-note">(<?php echo $dpam >= 0 ? '+' : ''; ?><?php echo $dpam; ?> vs 1T)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="lbl">UTA PI Promedio:</td>
                        <td class="val">
                            <?php echo v($ev['uta_pi_promedio']); ?>
                            <?php if(!empty($ev['uta_pi_promedio']) && !empty($data1er['uta_pi_promedio'])): 
                                $du = round($ev['uta_pi_promedio'] - $data1er['uta_pi_promedio'], 2); ?>
                                <span class="comparison-note">(<?php echo $du >= 0 ? '+' : ''; ?><?php echo $du; ?> vs 1T)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="lbl">Edad Gestacional:</td>
                        <td class="val"><?php echo v($ev['edad_gestacional_semanas'],' sem'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">FPP Actual:</td>
                        <td class="val"><?php echo fd($ev['fpp_actual']); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Fila 2: Biometría y Crecimiento | Anatomía Fetal -->
    <table class="two-col-table">
        <tr>
            <td style="width:50%;">
                <h2>Biometría y Crecimiento</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Estado Feto:</td>
                        <td class="val"><?php echo htmlspecialchars($b['estado_feto']??'Vivo'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">FCF:</td>
                        <td class="val"><?php echo v($b['fcf_lpm'],' lpm'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Peso Fetal Estimado:</td>
                        <td class="val"><?php echo v($b['peso_fetal_estimado_gr'],' gr'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Percentil Hadlock:</td>
                        <td class="val"><?php echo v($b['percentil_hadlock']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Crec. Armónico:</td>
                        <td class="val"><?php echo si($b['crecimiento_armonico']??true); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Índice Cefálico:</td>
                        <td class="val"><?php echo v($b['indice_cefalico_ci']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">FL/AC:</td>
                        <td class="val"><?php echo v($b['fl_ac_pct'],'%'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">HC/AC:</td>
                        <td class="val"><?php echo v($b['hc_ac_campbell']); ?></td>
                    </tr>
                </table>
            </td>
            <td style="width:50%;">
                <h2>Anatomía Fetal</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Cráneo/SNC:</td>
                        <td class="val"><?php echo pBool($a['craneo_snc_normal']??true); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Cara/Cuello:</td>
                        <td class="val"><?php echo pBool($a['cara_cuello_normal']??true); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Corazón:</td>
                        <td class="val"><?php echo pBool($a['corazon_normal']??true); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Tórax/Diafragma:</td>
                        <td class="val"><?php echo pBool($a['torax_diafragma_normal']??true); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Abdomen:</td>
                        <td class="val"><?php echo pBool($a['abdomen_normal']??true); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Genitourinario:</td>
                        <td class="val"><?php echo pBool($a['genitourinario_normal']??true); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Columna:</td>
                        <td class="val"><?php echo pBool($a['columna_normal']??true); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Extremidades:</td>
                        <td class="val"><?php echo pBool($a['extremidades_normal']??true); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <?php if(!empty($a['detalles_anomalias'])): ?>
    <div style="padding: 0 10px 15px 0; margin-top: -10px;">
        <span style="font-weight: bold; color: #1B4F5A; display: block; font-size: 11px;">Detalles de Anomalías Anatómicas:</span>
        <div class="notes-box"><?php echo nl2br(htmlspecialchars($a['detalles_anomalias'])); ?></div>
    </div>
    <?php endif; ?>

    <!-- Fila 3: Marcadores Ecográficos | Entorno Placentario -->
    <table class="two-col-table">
        <tr>
            <td style="width:50%;">
                <h2>Marcadores Ecográficos</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Ventriculomegalia:</td>
                        <td class="val"><?php echo pBool(!($m['ventriculomegalia_leve']??false), 'Ausente', 'Presente (Leve)'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Quistes Plexos:</td>
                        <td class="val"><?php echo pBool(!($m['quistes_plexos_coroideos']??false), 'Ausente', 'Presente'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Pliegue Nucal Aum.:</td>
                        <td class="val"><?php echo pBool(!($m['pliegue_nucal_aumentado']??false), 'Ausente', 'Presente'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Hueso Nasal:</td>
                        <td class="val"><?php echo pBool(!($m['hueso_nasal_ausente']??false), 'Presente', 'Ausente'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Foco Ecogénico Card.:</td>
                        <td class="val"><?php echo pBool(!($m['foco_ecogenico_cardiaco']??false), 'Ausente', 'Presente'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Intestino Hiperec.:</td>
                        <td class="val"><?php echo pBool(!($m['intestino_hiperecogenico']??false), 'Ausente', 'Presente'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Fémur Corto:</td>
                        <td class="val"><?php echo pBool(!($m['femur_corto']??false), 'Ausente', 'Presente'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Arteria Umb. Única:</td>
                        <td class="val"><?php echo pBool(!($m['arteria_umbilical_unica']??false), 'Ausente', 'Presente'); ?></td>
                    </tr>
                </table>
            </td>
            <td style="width:50%;">
                <h2>Entorno Placentario</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Posición Placenta:</td>
                        <td class="val"><?php echo v($en['placenta_posicion']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Dist. Borde OCI:</td>
                        <td class="val"><?php echo v($en['distancia_borde_oci_mm'],' mm'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Acretismo FIGO:</td>
                        <td class="val"><?php echo pRiskBadge($en['acretismo_figo_grado']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Bolsillo Máx. Líquido:</td>
                        <td class="val"><?php echo v($en['bolsillo_max_liquido_mm'],' mm'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Longitud Cervical:</td>
                        <td class="val"><?php echo v($en['longitud_cervical_mm'],' mm'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Índice Consistencia:</td>
                        <td class="val"><?php echo v($en['indice_consistencia_cervical']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Funneling:</td>
                        <td class="val"><?php echo ($en['funneling_presente']??false)?'Presente ('.v($en['funneling_mm'],' mm').')':'Ausente'; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Sludge:</td>
                        <td class="val"><?php echo v($en['sludge_intraamniotico']); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Fila 4: Impresión Diagnóstica | Historial Clínico -->
    <table class="two-col-table">
        <tr>
            <td style="width:50%;">
                <h2>Impresión Diagnóstica</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Cromosomopatías:</td>
                        <td class="val"><?php echo pRiskBadge($d['riesgo_cromosomopatias']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Parto Pretérmino:</td>
                        <td class="val"><?php echo pRiskBadge($d['riesgo_parto_pretermino']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Preeclampsia:</td>
                        <td class="val"><?php echo pRiskBadge($d['riesgo_preeclampsia']); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-bottom:none;">&nbsp;</td>
                    </tr>
                </table>
            </td>
            <td style="width:50%;">
                <h2>Historial Clínico</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Hipertensión:</td>
                        <td class="val"><?php echo ($h['hipertension_cronica']??false)?'Sí':'No'; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Diabetes:</td>
                        <td class="val"><?php echo ($h['diabetes']??false)?'Sí':'No'; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Lupus/LES:</td>
                        <td class="val"><?php echo ($h['lupus_les']??false)?'Sí':'No'; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">SAF:</td>
                        <td class="val"><?php echo ($h['sindrome_antifosfolipido_saf']??false)?'Sí':'No'; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Preeclampsia/RCIU:</td>
                        <td class="val"><?php echo ($h['antecedente_preeclampsia_rciu']??false)?'Sí':'No'; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">FIV:</td>
                        <td class="val"><?php echo ($h['fertilizacion_in_vitro']??false)?'Sí':'No'; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Parto Pretérmino:</td>
                        <td class="val"><?php echo ($h['antecedente_parto_pretermino']??false)?'Sí':'No'; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <?php if(!empty($d['observaciones_medicas'])): ?>
    <div style="padding: 0 10px 15px 0; margin-top: -10px;">
        <span style="font-weight: bold; color: #1B4F5A; display: block; font-size: 11px;">Observaciones Médicas:</span>
        <div class="notes-box"><?php echo nl2br(htmlspecialchars($d['observaciones_medicas'])); ?></div>
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

<div class="no-print" style="text-align:center;margin:20px 0;">
    <a href="<?php echo Url::to('/evaluaciones_2do_trimestre/pdf?id=' . $ev['id']); ?>"
       style="padding:10px 30px;font-size:14px;cursor:pointer;border:none;background:#1B4F5A;color:#fff;border-radius:8px;text-decoration:none;display:inline-block;font-weight:bold;">
        <i class="fa-solid fa-download"></i> Descargar PDF
    </a>
</div>

</body>
</html>
