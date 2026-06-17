<?php
$datos = [
    'evaluacion' => $evaluacion,
    'anatomia' => $anatomia,
    'marcadores' => $marcadores,
    'entorno' => $entorno,
    'diagnostica' => $diagnostica,
    'historial' => $historial
];
if (!function_exists('pVal')) {
    function pVal($val, $suffix = '') {
        if ($val === null || $val === '') return '—';
        return htmlspecialchars($val) . $suffix;
    }
}
if (!function_exists('pBool')) {
    function pBool($val, $normal = 'Normal', $alt = 'Alterado') {
        $valStr = $val ? $normal : $alt;
        $class = $val ? 'badge-success' : 'badge-danger';
        return '<span class="badge ' . $class . '">' . $valStr . '</span>';
    }
}
if (!function_exists('pFecha')) {
    function pFecha($val) {
        if (!$val) return '—';
        return date('d/m/Y', strtotime($val));
    }
}
if (!function_exists('pBadge')) {
    function pBadge($val) {
        if (!$val) return '—';
        return htmlspecialchars($val);
    }
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Evaluación 1er Trimestre — <?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></title>
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
        <h1>EVALUACIÓN 1ER TRIMESTRE</h1>
        <div class="subtitle">Código: <?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></div>
    </div>

    <!-- Fila 1: Datos Generales | Signos Vitales -->
    <table class="two-col-table">
        <tr>
            <td style="width:50%;">
                <h2>Datos Generales</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Fecha Evaluación:</td>
                        <td class="val"><?php echo pFecha($evaluacion['fecha_evaluacion']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Fecha Estudio:</td>
                        <td class="val"><?php echo pFecha($evaluacion['fecha_estudio']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Paciente:</td>
                        <td class="val"><strong><?php echo htmlspecialchars($evaluacion['paciente_nombre'] . ' ' . $evaluacion['paciente_apellido']); ?></strong></td>
                    </tr>
                    <tr>
                        <td class="lbl">Médico:</td>
                        <td class="val"><?php echo htmlspecialchars($evaluacion['medico_nombre'] . ' ' . $evaluacion['medico_apellido']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Estado:</td>
                        <td class="val"><?php echo pRiskBadge($evaluacion['estado']); ?></td>
                    </tr>
                </table>
            </td>
            <td style="width:50%;">
                <h2>Signos Vitales</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Peso:</td>
                        <td class="val"><?php echo pVal($evaluacion['peso_kg'], ' kg'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Talla:</td>
                        <td class="val"><?php echo pVal($evaluacion['talla_cm'], ' cm'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">TA Sistólica:</td>
                        <td class="val"><?php echo pVal($evaluacion['ta_sistolica'], ' mmHg'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">TA Diastólica:</td>
                        <td class="val"><?php echo pVal($evaluacion['ta_diastolica'], ' mmHg'); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-bottom:none;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Fila 2: Datos Obstétricos | Anatomía Fetal -->
    <table class="two-col-table">
        <tr>
            <td style="width:50%;">
                <h2>Datos Obstétricos</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">FUM:</td>
                        <td class="val"><?php echo pFecha($evaluacion['fum']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">FPP (USG):</td>
                        <td class="val"><?php echo pFecha($evaluacion['fpp_usg']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Edad Gestacional:</td>
                        <td class="val"><?php echo pVal($evaluacion['edad_gestacional_semanas'], ' sem'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">LCC:</td>
                        <td class="val"><?php echo pVal($evaluacion['lcc_mm'], ' mm'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">FCF:</td>
                        <td class="val"><?php echo pVal($evaluacion['fcf_lpm'], ' lpm'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Estado Feto:</td>
                        <td class="val"><?php echo htmlspecialchars($evaluacion['estado_feto'] ?? 'Vivo'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Múltiple:</td>
                        <td class="val"><?php echo $evaluacion['embarazo_multiple'] ? 'Sí' : 'No'; ?></td>
                    </tr>
                </table>
            </td>
            <td style="width:50%;">
                <h2>Anatomía Fetal</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Exploración:</td>
                        <td class="val"><?php echo htmlspecialchars($anatomia['estado_exploracion'] ?? '—'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">SNC Simetría:</td>
                        <td class="val"><?php echo pBool($anatomia['snc_simetria_plexos'] ?? true); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Macizo Facial:</td>
                        <td class="val"><?php echo pBool($anatomia['macizo_facial_integro'] ?? true); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Situs:</td>
                        <td class="val"><?php echo htmlspecialchars($anatomia['torax_situs'] ?? '—'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Eje Cardíaco:</td>
                        <td class="val"><?php echo pVal($anatomia['torax_eje_cardiaco_grados'], '°'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Cámara Gástrica:</td>
                        <td class="val"><?php echo pBool($anatomia['abdomen_camara_gastrica'] ?? true); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Extremidades:</td>
                        <td class="val"><?php echo pBool($anatomia['extremidades_completas'] ?? true); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <?php if (!empty($anatomia['observaciones_anomalias'])): ?>
    <div style="padding: 0 10px 15px 0; margin-top: -10px;">
        <span style="font-weight: bold; color: #1B4F5A; display: block; font-size: 11px;">Observaciones de Anatomía Fetal:</span>
        <div class="notes-box"><?php echo nl2br(htmlspecialchars($anatomia['observaciones_anomalias'])); ?></div>
    </div>
    <?php endif; ?>

    <!-- Fila 3: Historial Clínico | Marcadores FMF -->
    <table class="two-col-table">
        <tr>
            <td style="width:50%;">
                <h2>Historial Clínico</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Hipertensión Crónica:</td>
                        <td class="val"><?php echo ($historial['hipertension_cronica'] ?? 0) ? 'Sí' : 'No'; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Diabetes:</td>
                        <td class="val"><?php echo ($historial['diabetes'] ?? 0) ? 'Sí' : 'No'; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Lupus / LES:</td>
                        <td class="val"><?php echo ($historial['lupus_les'] ?? 0) ? 'Sí' : 'No'; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">SAF:</td>
                        <td class="val"><?php echo ($historial['sindrome_antifosfolipido_saf'] ?? 0) ? 'Sí' : 'No'; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Preeclampsia / RCIU:</td>
                        <td class="val"><?php echo ($historial['antecedente_preeclampsia_rciu'] ?? 0) ? 'Sí' : 'No'; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">FIV:</td>
                        <td class="val"><?php echo ($historial['fertilizacion_in_vitro'] ?? 0) ? 'Sí' : 'No'; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Parto Pretérmino:</td>
                        <td class="val"><?php echo ($historial['antecedente_parto_pretermino'] ?? 0) ? 'Sí' : 'No'; ?></td>
                    </tr>
                </table>
            </td>
            <td style="width:50%;">
                <h2>Marcadores FMF</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Translucencia Nucal:</td>
                        <td class="val"><?php echo pVal($marcadores['translucencia_nucal_mm'], ' mm'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Hueso Nasal:</td>
                        <td class="val"><?php echo ($marcadores['hueso_nasal_presente'] ?? true) ? 'Presente' : 'Ausente'; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Ductus Venoso:</td>
                        <td class="val"><?php echo pBadge($marcadores['ductus_venoso_onda_a']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Reg. Tricuspídea:</td>
                        <td class="val"><?php echo ($marcadores['regurgitacion_tricuspidea_ausente'] ?? true) ? 'Ausente' : 'Presente'; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Vejiga Fetal:</td>
                        <td class="val"><?php echo pVal($marcadores['vejiga_fetal_mm'], ' mm'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">UTA PI Promedio:</td>
                        <td class="val"><?php echo pVal($marcadores['uta_pi_promedio']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Muesca Bilateral:</td>
                        <td class="val"><?php echo ($marcadores['muesca_bilateral'] ?? 0) ? 'Presente' : 'Ausente'; ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">PAPP-A (MoM):</td>
                        <td class="val"><?php echo pVal($marcadores['papp_a_mom']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">PLGF (MoM):</td>
                        <td class="val"><?php echo pVal($marcadores['plgf_mom']); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="padding: 0 10px 15px 0;">
        <table class="section-table">
            <tr>
                <td class="lbl" style="width: 180px;">Tamizaje Genético:</td>
                <td class="val" style="text-align: left;">
                    <?php echo ($marcadores['tamizaje_genetico_tipo'] ?? 'No realizado') !== 'No realizado' ? htmlspecialchars($marcadores['tamizaje_genetico_tipo'] . ' — ' . ($marcadores['tamizaje_genetico_resultado'] ?? '—')) : 'No realizado'; ?>
                </td>
            </tr>
        </table>
    </div>

    <!-- Fila 4: Entorno Materno | Impresión Diagnóstica -->
    <table class="two-col-table">
        <tr>
            <td style="width:50%;">
                <h2>Entorno Materno</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Líquido Amniótico:</td>
                        <td class="val"><?php echo htmlspecialchars($entorno['liquido_amniotico'] ?? '—'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Posición Placenta:</td>
                        <td class="val"><?php echo pBadge($entorno['placenta_posicion']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Inserción Placenta:</td>
                        <td class="val"><?php echo pBadge($entorno['placenta_insercion']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Longitud Cervical:</td>
                        <td class="val"><?php echo pVal($entorno['longitud_cervical_mm'], ' mm'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Índice Consistencia:</td>
                        <td class="val"><?php echo pVal($entorno['indice_consistencia_cervical_pct'], '%'); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">ESHRE-ESGE:</td>
                        <td class="val"><?php echo pBadge($entorno['morfologia_uterina_eshre']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Miomas:</td>
                        <td class="val"><?php echo ($entorno['miomas_visibles'] ?? 0) ? 'Sí (' . htmlspecialchars($entorno['miomas_figo_tipo'] ?? '') . ')' : 'No'; ?></td>
                    </tr>
                </table>
            </td>
            <td style="width:50%;">
                <h2>Impresión Diagnóstica</h2>
                <table class="section-table">
                    <tr>
                        <td class="lbl">Riesgo Basal:</td>
                        <td class="val"><?php echo pRiskBadge($diagnostica['riesgo_basal_cromosomopatias']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Riesgo Ajustado:</td>
                        <td class="val"><?php echo pRiskBadge($diagnostica['riesgo_ajustado_cromosomopatias']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Probabilidad Cromo.:</td>
                        <td class="val"><?php echo pRiskBadge($diagnostica['probabilidad_cromosomopatias']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Preeclampsia Temprana:</td>
                        <td class="val"><?php echo pRiskBadge($diagnostica['riesgo_preeclampsia_temprana']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Enf. Placentaria Tardía:</td>
                        <td class="val"><?php echo pRiskBadge($diagnostica['riesgo_enfermedad_placentaria_tardia']); ?></td>
                    </tr>
                    <tr>
                        <td class="lbl">Parto Pretérmino:</td>
                        <td class="val"><?php echo pRiskBadge($diagnostica['riesgo_parto_pretermino']); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-bottom:none;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

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
                    <strong>Dr(a). <?php echo htmlspecialchars($evaluacion['medico_nombre'] . ' ' . $evaluacion['medico_apellido']); ?></strong><br>
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
    <a href="<?php echo Url::to('/evaluaciones_1er_trimestre/pdf?id=' . $evaluacion['id']); ?>"
       style="padding:10px 30px;font-size:14px;cursor:pointer;border:none;background:#1B4F5A;color:#fff;border-radius:8px;text-decoration:none;display:inline-block;font-weight:bold;">
        <i class="fa-solid fa-download"></i> Descargar PDF
    </a>
</div>

</body>
</html>
