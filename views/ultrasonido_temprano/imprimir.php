<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ultrasonido Temprano - <?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></title>
    <style>
        @media print { .no-print { display: none !important; } }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #2A2A2A;
            line-height: 1.5;
            font-size: 11px;
            padding: 0 10px;
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
        .patient-card {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #F4F7F6;
            border-left: 4px solid #1B4F5A;
        }
        .patient-card td {
            padding: 8px 12px;
            font-size: 11px;
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
        .section-title {
            background-color: #1B4F5A;
            color: #FFFFFF;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 10px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 2px solid #81BABB;
        }
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .grid-table td {
            vertical-align: top;
            padding: 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 5px 8px;
            font-size: 11px;
            border-bottom: 1px solid #E5EDED;
            vertical-align: middle;
        }
        .data-table tr:last-child td {
            border-bottom: none;
        }
        .data-table .lbl {
            font-weight: bold;
            color: #1B4F5A;
            width: 150px;
        }
        .data-table .val {
            color: #2A2A2A;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
        }
        .badge-success {
            background-color: #EAF8F0;
            color: #226E43;
        }
        .badge-danger {
            background-color: #FDF0F0;
            color: #A82424;
        }
        .badge-warning {
            background-color: #FCF7E6;
            color: #8F6E0A;
        }
        .badge-secondary {
            background-color: #F0F4F8;
            color: #475D74;
        }
        .badge-info {
            background-color: #EAF2F8;
            color: #2A6F97;
        }
        .sub-header {
            font-weight: bold;
            color: #1B4F5A;
            margin-top: 8px;
            margin-bottom: 4px;
            font-size: 11px;
            border-bottom: 1px solid #81BABB;
            padding-bottom: 2px;
        }
    </style>
</head>
<body>

<?php
$embriones = $embriones ?? [];

if (!function_exists('pVal')) {
    function pVal($val, $suffix = '') {
        if ($val === null || $val === '') return '—';
        return htmlspecialchars($val) . $suffix;
    }
}
if (!function_exists('pBool')) {
    function pBool($val, $labelTrue = 'Sí', $labelFalse = 'No') {
        if ($val === null) return '—';
        return $val ? $labelTrue : $labelFalse;
    }
}
if (!function_exists('pFecha')) {
    function pFecha($val) {
        if (!$val) return '—';
        return date('d/m/Y', strtotime($val));
    }
}

$embrionesPorSaco = [];
foreach ($embriones as $emb) {
    $sid = $emb['saco_id'] ?? 0;
    if (!isset($embrionesPorSaco[$sid])) $embrionesPorSaco[$sid] = [];
    $embrionesPorSaco[$sid][] = $emb;
}
$sacosMostrar = !empty($sacos) ? $sacos : [];
if (empty($sacosMostrar) && !empty($embriones)) {
    $sacosMostrar[] = [
        'id' => null,
        'numero' => 1,
        'medida_mm' => $evaluacion['sg_medida_mm'] ?? null,
        'morfologia' => $evaluacion['sg_morfologia'] ?? null,
        'sv_presente' => $evaluacion['sv_presente'] ?? null,
        'sv_diametro_mm' => $evaluacion['sv_diametro_mm'] ?? null,
        'descripcion' => null
    ];
}
?>

<div class="title-container">
    <h1>Ultrasonido Obstétrico Temprano</h1>
    <div class="subtitle">Código: <?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></div>
</div>

<table class="patient-card">
    <tr>
        <td class="lbl">Paciente:</td>
        <td class="val"><strong><?php echo htmlspecialchars($evaluacion['paciente_nombre'] . ' ' . $evaluacion['paciente_apellido']); ?></strong></td>
        <td class="lbl">Fecha:</td>
        <td class="val"><?php echo pFecha($evaluacion['fecha_estudio']); ?></td>
    </tr>
    <tr>
        <td class="lbl">Médico:</td>
        <td class="val"><?php echo htmlspecialchars($evaluacion['medico_nombre'] . ' ' . $evaluacion['medico_apellido']); ?></td>
        <td class="lbl">FUM:</td>
        <td class="val"><?php echo pFecha($evaluacion['fum']); ?></td>
    </tr>
    <tr>
        <td class="lbl">Edad:</td>
        <td class="val"><?php echo pVal($evaluacion['edad'], ' años'); ?></td>
        <td class="lbl">EG por FUM:</td>
        <td class="val"><?php echo pVal($evaluacion['edad_gest_semanas'], 's ') . pVal($evaluacion['edad_gest_dias'], 'd'); ?></td>
    </tr>
</table>

<div class="section-title">Indicación y Vía de Exploración</div>
<table class="data-table" style="margin-bottom: 15px;">
    <tr>
        <td class="lbl" style="width: 20%;">Indicación:</td>
        <td class="val">
            <?php
            $ind = [];
            if ($evaluacion['indic_confirmacion_embarazo']) $ind[] = 'Confirmación';
            if ($evaluacion['indic_sangrado']) $ind[] = 'Sangrado';
            if ($evaluacion['indic_dolor_pelvico']) $ind[] = 'Dolor pélvico';
            if ($evaluacion['indic_viabilidad']) $ind[] = 'Viabilidad';
            if ($evaluacion['indic_perdidas_gestacionales']) $ind[] = 'Pérdidas';
            if ($evaluacion['indic_reproduccion_asistida']) $ind[] = 'Rep. asistida';
            if (!empty($evaluacion['indic_otro'])) $ind[] = $evaluacion['indic_otro'];
            echo !empty($ind) ? implode(', ', $ind) : '—';
            ?>
        </td>
    </tr>
    <tr>
        <td class="lbl" style="width: 20%;">Vía:</td>
        <td class="val">
            <?php
            $v = [];
            if ($evaluacion['via_transvaginal']) $v[] = 'Transvaginal';
            if ($evaluacion['via_transabdominal']) $v[] = 'Transabdominal';
            if ($evaluacion['via_ambas']) $v[] = 'Ambas';
            echo !empty($v) ? implode(', ', $v) : '—';
            ?>
        </td>
    </tr>
</table>

<table class="grid-table">
    <tr>
        <!-- Columna Izquierda -->
        <td style="width: 48%;">
            <div class="section-title">Útero y Decidua</div>
            <table class="data-table">
                <tr>
                    <td class="lbl">Posición:</td>
                    <td class="val"><?php echo pVal($evaluacion['utero_posicion'] ?? null); ?></td>
                </tr>
                <tr>
                    <td class="lbl">Contornos:</td>
                    <td class="val"><?php echo pVal($evaluacion['utero_contornos'] ?? 'Regulares'); ?></td>
                </tr>
                <tr>
                    <td class="lbl">Ecogenicidad:</td>
                    <td class="val"><?php echo pBool($evaluacion['utero_ecogenicidad_conservada'] ?? null, 'Conservada', 'Alterada'); ?></td>
                </tr>
                <tr>
                    <td class="lbl">Dimensiones:</td>
                    <td class="val">
                        <?php 
                        if (!empty($evaluacion['utero_dim_x']) || !empty($evaluacion['utero_dim_y']) || !empty($evaluacion['utero_dim_z'])) {
                            echo pVal($evaluacion['utero_dim_x'] ?? null, ' x ') . pVal($evaluacion['utero_dim_y'] ?? null, ' x ') . pVal($evaluacion['utero_dim_z'] ?? null, ' mm');
                        } else {
                            echo '—';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="lbl">Endometrio:</td>
                    <td class="val"><?php echo pVal($evaluacion['endometrio'] ?? null); ?></td>
                </tr>
                <tr>
                    <td class="lbl">Decidua:</td>
                    <td class="val"><?php echo !empty($evaluacion['decidua']) ? nl2br(htmlspecialchars($evaluacion['decidua'])) : '—'; ?></td>
                </tr>
            </table>

            <div class="section-title">Saco y Viabilidad</div>
            <table class="data-table">
                <tr>
                    <td class="lbl">Localización:</td>
                    <td class="val">
                        <?php 
                        echo pVal($evaluacion['localizacion'] ?? null); 
                        if (($evaluacion['localizacion'] ?? '') === 'Otra' && !empty($evaluacion['localizacion_otra'])) {
                            echo ' (' . htmlspecialchars($evaluacion['localizacion_otra']) . ')';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="lbl">Saco gestacional:</td>
                    <td class="val">
                        <?php echo pVal($evaluacion['sg_tipo'] ?? null); ?> / <?php echo pVal($evaluacion['sg_morfologia'] ?? null); ?> / <?php echo pVal($evaluacion['sg_medida_mm'] ?? null, ' mm'); ?>
                        <?php if (!empty($evaluacion['sg_cantidad']) && $evaluacion['sg_cantidad'] > 1): ?>
                            (<?php echo $evaluacion['sg_cantidad']; ?> sacos)
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="lbl">Saco vitelino:</td>
                    <td class="val">
                        <?php 
                        echo ($evaluacion['sv_presente'] ?? null) !== null ? pBool($evaluacion['sv_presente'], 'Presente', 'Ausente') : '—'; 
                        if (!empty($evaluacion['sv_presente'])) {
                            echo ' (' . pVal($evaluacion['sv_cantidad'] ?? null) . ', ' . pVal($evaluacion['sv_diametro_mm'] ?? null, ' mm') . ')';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="lbl">Viabilidad:</td>
                    <td class="val">
                        <?php 
                        $viabilidad = $evaluacion['viabilidad'] ?? '';
                        if ($viabilidad === 'Viable') {
                            echo '<span class="badge badge-success">Viable</span>';
                        } elseif ($viabilidad === 'No viable') {
                            echo '<span class="badge badge-danger">No viable</span>';
                        } else {
                            echo pVal($viabilidad);
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="lbl">Corion/Amnios:</td>
                    <td class="val"><?php echo pBool($evaluacion['corion_amnios_normal'] ?? null, 'Normal', 'Alterado'); ?></td>
                </tr>
            </table>
        </td>

        <!-- Espaciador -->
        <td style="width: 4%;">&nbsp;</td>

        <!-- Columna Derecha -->
        <td style="width: 48%;">
            <div class="section-title">Anexos y Ovarios</div>
            <div class="sub-header">Ovario Derecho</div>
            <table class="data-table">
                <tr>
                    <td class="lbl">Dimensiones:</td>
                    <td class="val">
                        <?php 
                        if (!empty($evaluacion['ovario_der_dim_x']) || !empty($evaluacion['ovario_der_dim_y']) || !empty($evaluacion['ovario_der_dim_z'])) {
                            echo pVal($evaluacion['ovario_der_dim_x'] ?? null, ' x ') . pVal($evaluacion['ovario_der_dim_y'] ?? null, ' x ') . pVal($evaluacion['ovario_der_dim_z'] ?? null, ' mm');
                        } else {
                            echo '—';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="lbl">Estructura:</td>
                    <td class="val"><?php echo pBool($evaluacion['ovario_der_normal'] ?? null, 'Normal', 'Alterado'); ?></td>
                </tr>
                <?php if (!empty($evaluacion['ovario_der_cuerpo_luteo_mm'])): ?>
                    <tr><td class="lbl">Cuerpo lúteo:</td><td class="val"><?php echo $evaluacion['ovario_der_cuerpo_luteo_mm']; ?> mm</td></tr>
                <?php endif; ?>
                <?php if (!empty($evaluacion['ovario_der_quiste_simple_mm'])): ?>
                    <tr><td class="lbl">Quiste simple:</td><td class="val"><?php echo $evaluacion['ovario_der_quiste_simple_mm']; ?> mm</td></tr>
                <?php endif; ?>
                <?php if (!empty($evaluacion['ovario_der_otra_alteracion'])): ?>
                    <tr><td class="lbl">Otra alteración:</td><td class="val"><?php echo htmlspecialchars($evaluacion['ovario_der_otra_alteracion']); ?></td></tr>
                <?php endif; ?>
            </table>

            <div class="sub-header">Ovario Izquierdo</div>
            <table class="data-table">
                <tr>
                    <td class="lbl">Dimensiones:</td>
                    <td class="val">
                        <?php 
                        if (!empty($evaluacion['ovario_izq_dim_x']) || !empty($evaluacion['ovario_izq_dim_y']) || !empty($evaluacion['ovario_izq_dim_z'])) {
                            echo pVal($evaluacion['ovario_izq_dim_x'] ?? null, ' x ') . pVal($evaluacion['ovario_izq_dim_y'] ?? null, ' x ') . pVal($evaluacion['ovario_izq_dim_z'] ?? null, ' mm');
                        } else {
                            echo '—';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="lbl">Estructura:</td>
                    <td class="val"><?php echo pBool($evaluacion['ovario_izq_normal'] ?? null, 'Normal', 'Alterado'); ?></td>
                </tr>
                <?php if (!empty($evaluacion['ovario_izq_cuerpo_luteo_mm'])): ?>
                    <tr><td class="lbl">Cuerpo lúteo:</td><td class="val"><?php echo $evaluacion['ovario_izq_cuerpo_luteo_mm']; ?> mm</td></tr>
                <?php endif; ?>
                <?php if (!empty($evaluacion['ovario_izq_quiste_simple_mm'])): ?>
                    <tr><td class="lbl">Quiste simple:</td><td class="val"><?php echo $evaluacion['ovario_izq_quiste_simple_mm']; ?> mm</td></tr>
                <?php endif; ?>
                <?php if (!empty($evaluacion['ovario_izq_otra_alteracion'])): ?>
                    <tr><td class="lbl">Otra alteración:</td><td class="val"><?php echo htmlspecialchars($evaluacion['ovario_izq_otra_alteracion']); ?></td></tr>
                <?php endif; ?>
            </table>

            <div class="section-title">Hallazgos Adicionales</div>
            <table class="data-table">
                <tr>
                    <td class="lbl">Douglas:</td>
                    <td class="val"><?php echo pVal($evaluacion['douglas'] ?? null); ?></td>
                </tr>
                <tr>
                    <td class="lbl">Hematoma subcoriónico:</td>
                    <td class="val">
                        <?php 
                        $hasHematoma = $evaluacion['hematoma_subcorionico'] ?? null;
                        if ($hasHematoma) {
                            echo '<span class="badge badge-danger">Presente</span>';
                        } else {
                            echo '<span class="badge badge-success">Ausente</span>';
                        }
                        ?>
                    </td>
                </tr>
                <?php if (!empty($evaluacion['hematoma_subcorionico'])): ?>
                <tr>
                    <td class="lbl">Loc/Dim/Vol:</td>
                    <td class="val">
                        <?php echo pVal($evaluacion['hematoma_localizacion'] ?? null); ?> / 
                        <?php echo pVal($evaluacion['hematoma_dim_x'] ?? null) . 'x' . pVal($evaluacion['hematoma_dim_y'] ?? null) . 'x' . pVal($evaluacion['hematoma_dim_z'] ?? null); ?> mm / 
                        <?php echo pVal($evaluacion['hematoma_volumen_ml'] ?? null, ' ml'); ?>
                    </td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($evaluacion['miomas_uterinos']) || !empty($evaluacion['adenomiosis']) || !empty($evaluacion['malformacion_uterina']) || !empty($evaluacion['hallazgos_otro'])): ?>
                <tr>
                    <td class="lbl">Otros Hallazgos:</td>
                    <td class="val">
                        <?php if (!empty($evaluacion['miomas_uterinos'])): ?>Miomas uterinos<br><?php endif; ?>
                        <?php if (!empty($evaluacion['adenomiosis'])): ?>Adenomiosis<br><?php endif; ?>
                        <?php if (!empty($evaluacion['malformacion_uterina'])): ?>Malformación uterina<br><?php endif; ?>
                        <?php echo nl2br(htmlspecialchars($evaluacion['hallazgos_otro'] ?? '')); ?>
                    </td>
                </tr>
                <?php endif; ?>
            </table>
        </td>
    </tr>
</table>

<?php if (!empty($sacosMostrar)): ?>
<div class="section-title">Detalle por Saco Gestacional</div>
<table class="structural-table" style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
    <?php foreach ($sacosMostrar as $idx => $saco):
        $sacoId = $saco['id'] ?? null;
        $sacoEmbs = $sacoId ? ($embrionesPorSaco[$sacoId] ?? []) : ($idx == 0 ? ($embrionesPorSaco[0] ?? $embriones) : []);
    ?>
    <tr style="background-color: #F4F7F6; border-bottom: 1px solid #D1DDDC;">
        <td style="padding: 6px 10px; font-weight: bold; color: #1B4F5A;" colspan="2">Saco #<?php echo htmlspecialchars($saco['numero'] ?? ($idx + 1)); ?></td>
    </tr>
    <tr style="border-bottom: 1px solid #E5EDED;">
        <td style="padding: 5px 8px; width: 30%; font-weight: bold; color: #1B4F5A;">Medida / Morfología:</td>
        <td style="padding: 5px 8px;"><?php echo pVal($saco['medida_mm'] ?? null, ' mm'); ?> / <?php echo pVal($saco['morfologia'] ?? null); ?></td>
    </tr>
    <tr style="border-bottom: 1px solid #E5EDED;">
        <td style="padding: 5px 8px; font-weight: bold; color: #1B4F5A;">Saco vitelino:</td>
        <td style="padding: 5px 8px;">
            <?php 
            echo ($saco['sv_presente'] ?? null) !== null ? pBool($saco['sv_presente'], 'Presente', 'Ausente') : '—'; 
            if (!empty($saco['sv_presente'])) {
                echo ' (' . pVal($saco['sv_diametro_mm'] ?? null, ' mm') . ')';
            }
            ?>
        </td>
    </tr>
    <?php if (!empty($sacoEmbs)): ?>
        <?php foreach ($sacoEmbs as $emb): ?>
        <tr style="border-bottom: 1px solid #E5EDED;">
            <td style="padding: 5px 8px; font-weight: bold; color: #1B4F5A; padding-left: 20px;"><em>Embrión #<?php echo htmlspecialchars($emb['numero'] ?? ''); ?></em></td>
            <td style="padding: 5px 8px;">
                <strong>CRL:</strong> <?php echo pVal($emb['crl_mm'] ?? null, ' mm'); ?> | 
                <strong>FCF:</strong> <?php echo pBool($emb['fcf_visible'] ?? null, 'Visible', 'No visible'); ?>
                <?php if (!empty($emb['fcf_lpm'])): ?> (<?php echo $emb['fcf_lpm']; ?> lpm)<?php endif; ?> | 
                <strong>Localización:</strong> <?php echo pVal($emb['localizacion'] ?? null); ?>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php endforeach; ?>
</table>
<?php endif; ?>

<div class="section-title">Impresión Diagnóstica</div>
<table class="data-table" style="margin-bottom: 10px;">
    <tr>
        <td class="lbl">CRL Promedio:</td>
        <td class="val"><?php echo pVal($evaluacion['impresion_crl_mm'] ?? null, ' mm'); ?></td>
        <td class="lbl">EG Promedio:</td>
        <td class="val"><?php echo pVal($evaluacion['impresion_semanas'] ?? null, 's ') . pVal($evaluacion['impresion_dias'] ?? null, 'd'); ?></td>
        <td class="lbl">FCF Promedio:</td>
        <td class="val"><?php echo pVal($evaluacion['impresion_fcf_lpm'] ?? null, ' lpm'); ?></td>
    </tr>
</table>
<?php if (!empty($evaluacion['impresion_texto'])): ?>
    <div style="background-color: #F9FBFA; padding: 10px; border: 1px solid #E5EDED; border-radius: 4px; line-height: 1.5; margin-top: 5px;">
        <?php echo nl2br(htmlspecialchars($evaluacion['impresion_texto'])); ?>
    </div>
<?php endif; ?>

<table style="width: 100%; margin-top: 40px; border-collapse: collapse;">
    <tr>
        <td style="width: 50%; text-align: center; vertical-align: top;">
            <div style="border-top: 1px solid #1B4F5A; width: 220px; margin: 0 auto; padding-top: 5px; font-size: 10px;">
                <strong>Dr(a). <?php echo htmlspecialchars($evaluacion['medico_nombre'] . ' ' . $evaluacion['medico_apellido']); ?></strong><br>
                Firma del Médico
            </div>
        </td>
        <td style="width: 50%; text-align: center; font-size: 10px; color: #777; padding-top: 15px; vertical-align: top;">
            Fecha de impresión: <?php echo date('d/m/Y H:i'); ?>
        </td>
    </tr>
</table>

<div class="no-print" style="text-align:center;margin:20px 0;">
    <a href="<?php echo Url::to('/ultrasonido_temprano/pdf?id=' . $evaluacion['id']); ?>"
       style="padding:10px 30px;font-size:14px;cursor:pointer;border:none;background:#1B4F5A;color:#fff;border-radius:8px;text-decoration:none;display:inline-block;font-weight:bold;">
        <i class="fa-solid fa-download"></i> Descargar PDF
    </a>
</div>
</body>
</html>
