<?php
$datos = [
    'evaluacion' => $evaluacion,
    'anatomia' => $anatomia,
    'marcadores' => $marcadores,
    'entorno' => $entorno,
    'diagnostica' => $diagnostica,
    'historial' => $historial
];
$fechaImpresion = date('d/m/Y H:i');

function pVal($val, $suffix = '') {
    if ($val === null || $val === '') return '—';
    return htmlspecialchars($val) . $suffix;
}
function pBool($val, $normal = 'Normal', $alt = 'Alterado') {
    return $val ? $normal : $alt;
}
function pFecha($val) {
    if (!$val) return '—';
    return date('d/m/Y', strtotime($val));
}
function pBadge($val) {
    if (!$val) return '—';
    return htmlspecialchars($val);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Evaluación 1er Trimestre — <?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif; padding: 150px 30px 40px 30px; color: #333;
        }
        .document { max-width: 800px; margin: 0 auto; }
        .doc-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .doc-header h1 { font-size: 20px; margin: 0; }
        .doc-header .code { font-size: 13px; color: #666; margin-top: 5px; }
        .section { margin-bottom: 25px; }
        .section h2 { font-size: 13px; background: #f5f5f5; padding: 6px 10px; margin: 0 0 10px 0; border-left: 4px solid #333; }
        .row-item { display: flex; margin-bottom: 4px; font-size: 12px; }
        .label { width: 160px; font-weight: bold; flex-shrink: 0; }
        .value { flex: 1; }
        .two-col { display: flex; gap: 30px; }
        .col { flex: 1; }
        .badge { font-size: 10px; padding: 2px 6px; border-radius: 3px; background: #eee; }
        .footer { margin-top: 40px; font-size: 10px; color: #999; text-align: center; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
<div class="document">
    <div class="doc-header">
        <h1>EVALUACIÓN 1ER TRIMESTRE</h1>
        <div class="code"><?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></div>
    </div>

    <div class="section">
        <h2>Datos Generales</h2>
        <div class="row-item"><div class="label">Fecha Evaluación:</div><div class="value"><?php echo pFecha($evaluacion['fecha_evaluacion']); ?></div></div>
        <div class="row-item"><div class="label">Fecha Estudio:</div><div class="value"><?php echo pFecha($evaluacion['fecha_estudio']); ?></div></div>
        <div class="row-item"><div class="label">Paciente:</div><div class="value"><?php echo htmlspecialchars($evaluacion['paciente_nombre'] . ' ' . $evaluacion['paciente_apellido']); ?></div></div>
        <div class="row-item"><div class="label">Médico:</div><div class="value"><?php echo htmlspecialchars($evaluacion['medico_nombre'] . ' ' . $evaluacion['medico_apellido']); ?></div></div>
        <div class="row-item"><div class="label">Estado:</div><div class="value"><?php echo htmlspecialchars($evaluacion['estado']); ?></div></div>
    </div>

    <div class="two-col">
        <div class="col">
            <div class="section">
                <h2>Signos Vitales</h2>
                <div class="row-item"><div class="label">Peso:</div><div class="value"><?php echo pVal($evaluacion['peso_kg'], ' kg'); ?></div></div>
                <div class="row-item"><div class="label">Talla:</div><div class="value"><?php echo pVal($evaluacion['talla_cm'], ' cm'); ?></div></div>
                <div class="row-item"><div class="label">TA Sistólica:</div><div class="value"><?php echo pVal($evaluacion['ta_sistolica'], ' mmHg'); ?></div></div>
                <div class="row-item"><div class="label">TA Diastólica:</div><div class="value"><?php echo pVal($evaluacion['ta_diastolica'], ' mmHg'); ?></div></div>
            </div>
            <div class="section">
                <h2>Historial Clínico</h2>
                <div class="row-item"><div class="label">Hipertensión:</div><div class="value"><?php echo ($historial['hipertension_cronica'] ?? 0) ? 'Sí' : 'No'; ?></div></div>
                <div class="row-item"><div class="label">Diabetes:</div><div class="value"><?php echo ($historial['diabetes'] ?? 0) ? 'Sí' : 'No'; ?></div></div>
                <div class="row-item"><div class="label">Lupus/LES:</div><div class="value"><?php echo ($historial['lupus_les'] ?? 0) ? 'Sí' : 'No'; ?></div></div>
                <div class="row-item"><div class="label">SAF:</div><div class="value"><?php echo ($historial['sindrome_antifosfolipido_saf'] ?? 0) ? 'Sí' : 'No'; ?></div></div>
                <div class="row-item"><div class="label">Preeclampsia/RCIU:</div><div class="value"><?php echo ($historial['antecedente_preeclampsia_rciu'] ?? 0) ? 'Sí' : 'No'; ?></div></div>
                <div class="row-item"><div class="label">FIV:</div><div class="value"><?php echo ($historial['fertilizacion_in_vitro'] ?? 0) ? 'Sí' : 'No'; ?></div></div>
                <div class="row-item"><div class="label">Parto Pretérmino:</div><div class="value"><?php echo ($historial['antecedente_parto_pretermino'] ?? 0) ? 'Sí' : 'No'; ?></div></div>
            </div>
            <div class="section">
                <h2>Marcadores FMF</h2>
                <div class="row-item"><div class="label">Translucencia Nucal:</div><div class="value"><?php echo pVal($marcadores['translucencia_nucal_mm'], ' mm'); ?></div></div>
                <div class="row-item"><div class="label">Hueso Nasal:</div><div class="value"><?php echo ($marcadores['hueso_nasal_presente'] ?? true) ? 'Presente' : 'Ausente'; ?></div></div>
                <div class="row-item"><div class="label">Ductus Venoso:</div><div class="value"><?php echo pBadge($marcadores['ductus_venoso_onda_a']); ?></div></div>
                <div class="row-item"><div class="label">Reg. Tricuspídea:</div><div class="value"><?php echo ($marcadores['regurgitacion_tricuspidea_ausente'] ?? true) ? 'Ausente' : 'Presente'; ?></div></div>
                <div class="row-item"><div class="label">Vejiga Fetal:</div><div class="value"><?php echo pVal($marcadores['vejiga_fetal_mm'], ' mm'); ?></div></div>
                <div class="row-item"><div class="label">UTA PI:</div><div class="value"><?php echo pVal($marcadores['uta_pi_promedio']); ?></div></div>
                <div class="row-item"><div class="label">Muesca Bilateral:</div><div class="value"><?php echo ($marcadores['muesca_bilateral'] ?? 0) ? 'Presente' : 'Ausente'; ?></div></div>
                <div class="row-item"><div class="label">PAPP-A (MoM):</div><div class="value"><?php echo pVal($marcadores['papp_a_mom']); ?></div></div>
                <div class="row-item"><div class="label">PLGF (MoM):</div><div class="value"><?php echo pVal($marcadores['plgf_mom']); ?></div></div>
                <div class="row-item"><div class="label">Tamizaje Genético:</div><div class="value"><?php echo ($marcadores['tamizaje_genetico_tipo'] ?? 'No realizado') !== 'No realizado' ? htmlspecialchars($marcadores['tamizaje_genetico_tipo'] . ' — ' . ($marcadores['tamizaje_genetico_resultado'] ?? '—')) : 'No realizado'; ?></div></div>
            </div>
        </div>
        <div class="col">
            <div class="section">
                <h2>Datos Obstétricos</h2>
                <div class="row-item"><div class="label">FUM:</div><div class="value"><?php echo pFecha($evaluacion['fum']); ?></div></div>
                <div class="row-item"><div class="label">FPP (USG):</div><div class="value"><?php echo pFecha($evaluacion['fpp_usg']); ?></div></div>
                <div class="row-item"><div class="label">Edad Gestacional:</div><div class="value"><?php echo pVal($evaluacion['edad_gestacional_semanas'], ' sem'); ?></div></div>
                <div class="row-item"><div class="label">LCC:</div><div class="value"><?php echo pVal($evaluacion['lcc_mm'], ' mm'); ?></div></div>
                <div class="row-item"><div class="label">FCF:</div><div class="value"><?php echo pVal($evaluacion['fcf_lpm'], ' lpm'); ?></div></div>
                <div class="row-item"><div class="label">Estado Feto:</div><div class="value"><?php echo htmlspecialchars($evaluacion['estado_feto'] ?? 'Vivo'); ?></div></div>
                <div class="row-item"><div class="label">Múltiple:</div><div class="value"><?php echo $evaluacion['embarazo_multiple'] ? 'Sí' : 'No'; ?></div></div>
            </div>
            <div class="section">
                <h2>Anatomía Fetal</h2>
                <div class="row-item"><div class="label">Exploración:</div><div class="value"><?php echo htmlspecialchars($anatomia['estado_exploracion'] ?? '—'); ?></div></div>
                <div class="row-item"><div class="label">SNC Simetría:</div><div class="value"><?php echo pBool($anatomia['snc_simetria_plexos'] ?? true); ?></div></div>
                <div class="row-item"><div class="label">Macizo Facial:</div><div class="value"><?php echo pBool($anatomia['macizo_facial_integro'] ?? true); ?></div></div>
                <div class="row-item"><div class="label">Situs:</div><div class="value"><?php echo htmlspecialchars($anatomia['torax_situs'] ?? '—'); ?></div></div>
                <div class="row-item"><div class="label">Eje Cardíaco:</div><div class="value"><?php echo pVal($anatomia['torax_eje_cardiaco_grados'], '°'); ?></div></div>
                <div class="row-item"><div class="label">Cámara Gástrica:</div><div class="value"><?php echo pBool($anatomia['abdomen_camara_gastrica'] ?? true); ?></div></div>
                <div class="row-item"><div class="label">Extremidades:</div><div class="value"><?php echo pBool($anatomia['extremidades_completas'] ?? true); ?></div></div>
                <?php if (!empty($anatomia['observaciones_anomalias'])): ?>
                <div class="row-item"><div class="label">Observaciones:</div><div class="value"><?php echo nl2br(htmlspecialchars($anatomia['observaciones_anomalias'])); ?></div></div>
                <?php endif; ?>
            </div>
            <div class="section">
                <h2>Entorno Materno</h2>
                <div class="row-item"><div class="label">Líquido Amniótico:</div><div class="value"><?php echo htmlspecialchars($entorno['liquido_amniotico'] ?? '—'); ?></div></div>
                <div class="row-item"><div class="label">Posición Placenta:</div><div class="value"><?php echo pBadge($entorno['placenta_posicion']); ?></div></div>
                <div class="row-item"><div class="label">Inserción:</div><div class="value"><?php echo pBadge($entorno['placenta_insercion']); ?></div></div>
                <div class="row-item"><div class="label">Longitud Cervical:</div><div class="value"><?php echo pVal($entorno['longitud_cervical_mm'], ' mm'); ?></div></div>
                <div class="row-item"><div class="label">Índice Consistencia:</div><div class="value"><?php echo pVal($entorno['indice_consistencia_cervical_pct'], '%'); ?></div></div>
                <div class="row-item"><div class="label">ESHRE-ESGE:</div><div class="value"><?php echo pBadge($entorno['morfologia_uterina_eshre']); ?></div></div>
                <div class="row-item"><div class="label">Miomas:</div><div class="value"><?php echo ($entorno['miomas_visibles'] ?? 0) ? 'Sí (' . htmlspecialchars($entorno['miomas_figo_tipo'] ?? '') . ')' : 'No'; ?></div></div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Impresión Diagnóstica</h2>
        <div class="row-item"><div class="label">Riesgo Basal:</div><div class="value"><?php echo pBadge($diagnostica['riesgo_basal_cromosomopatias']); ?></div></div>
        <div class="row-item"><div class="label">Riesgo Ajustado:</div><div class="value"><?php echo pBadge($diagnostica['riesgo_ajustado_cromosomopatias']); ?></div></div>
        <div class="row-item"><div class="label">Prob. Cromosomopatías:</div><div class="value"><?php echo pBadge($diagnostica['probabilidad_cromosomopatias']); ?></div></div>
        <div class="row-item"><div class="label">Preeclampsia Temprana:</div><div class="value"><?php echo pBadge($diagnostica['riesgo_preeclampsia_temprana']); ?></div></div>
        <div class="row-item"><div class="label">Enf. Placentaria Tardía:</div><div class="value"><?php echo pBadge($diagnostica['riesgo_enfermedad_placentaria_tardia']); ?></div></div>
        <div class="row-item"><div class="label">Parto Pretérmino:</div><div class="value"><?php echo pBadge($diagnostica['riesgo_parto_pretermino']); ?></div></div>
    </div>

    <?php if (!empty($imagenes)): ?>
    <div class="section">
        <h2>IMÁGENES DEL ESTUDIO</h2>
        <table style="width:100%;border-collapse:collapse;">
        <?php $c = 0; foreach ($imagenes as $img): ?>
            <?php if ($c % 3 == 0): ?><tr><?php endif; ?>
            <td style="text-align:center;padding:8px;vertical-align:top;">
                <img src="<?php echo ltrim($img['ruta_imagen'], '/'); ?>" style="width:<?=$img['width']?>px;height:<?=$img['height']?>px;border:1px solid #ddd;padding:2px;">
            </td>
            <?php if ($c % 3 == 2): ?></tr><?php endif; ?>
        <?php $c++; endforeach; ?>
        <?php if ($c % 3 != 0): ?></tr><?php endif; ?>
        </table>
    </div>
    <?php endif; ?>

    <div class="footer">
        Documento generado el <?php echo $fechaImpresion; ?> — PreNacer Sistema de Gestión Médico
    </div>
</div>
</body>
</html>
