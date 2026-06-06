<?php
require_once __DIR__ . '/../layouts/header.php';

function pVal($val, $suffix = '') {
    if ($val === null || $val === '') return '—';
    return htmlspecialchars($val) . $suffix;
}
function pBool($val, $labelTrue = 'Sí', $labelFalse = 'No') {
    if ($val === null) return '—';
    return $val ? $labelTrue : $labelFalse;
}
function pFecha($val) {
    if (!$val) return '—';
    return date('d/m/Y', strtotime($val));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ultrasonido Temprano - <?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; font-size: 11px; color: #1d1d1f; padding: 20px; }
        .document { max-width: 800px; margin: 0 auto; }
        .doc-header { text-align: center; margin-bottom: 16px; border-bottom: 2px solid #0071e3; padding-bottom: 10px; }
        .doc-header h1 { font-size: 16px; font-weight: 700; color: #0071e3; margin: 0; }
        .doc-header .code { font-size: 12px; color: #86868b; }
        .section { margin-bottom: 12px; }
        .section-title { font-size: 12px; font-weight: 700; color: #0071e3; border-bottom: 1px solid #d2d2d7; padding-bottom: 4px; margin-bottom: 6px; }
        .two-col { display: flex; gap: 16px; }
        .two-col .col { flex: 1; }
        table.info { width: 100%; border-collapse: collapse; }
        table.info td { padding: 2px 6px; vertical-align: top; }
        table.info td.label { font-weight: 600; color: #86868b; width: 35%; white-space: nowrap; }
        table.info td.value { color: #1d1d1f; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 6px; font-size: 10px; font-weight: 600; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #ffe6e6; color: #b02a2a; }
        .footer { margin-top: 20px; padding-top: 8px; border-top: 1px solid #d2d2d7; font-size: 9px; color: #86868b; text-align: center; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right; margin-bottom: 12px;">
        <button onclick="window.print()" class="btn btn-sm btn-primary">Imprimir</button>
        <button onclick="window.close()" class="btn btn-sm btn-secondary">Cerrar</button>
    </div>

    <div class="document">
        <div class="doc-header">
            <h1>Ultrasonido Obstétrico Temprano (&lt;11 semanas)</h1>
            <div class="code"><?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></div>
        </div>

        <div class="section">
            <div class="section-title">Datos Generales</div>
            <table class="info">
                <tr><td class="label">Paciente:</td><td class="value"><?php echo htmlspecialchars($evaluacion['paciente_nombre'] . ' ' . $evaluacion['paciente_apellido']); ?></td><td class="label">Fecha:</td><td class="value"><?php echo pFecha($evaluacion['fecha_estudio']); ?></td></tr>
                <tr><td class="label">Médico:</td><td class="value"><?php echo htmlspecialchars($evaluacion['medico_nombre'] . ' ' . $evaluacion['medico_apellido']); ?></td><td class="label">FUM:</td><td class="value"><?php echo pFecha($evaluacion['fum']); ?></td></tr>
                <tr><td class="label">Edad:</td><td class="value"><?php echo pVal($evaluacion['edad'], ' años'); ?></td><td class="label">EG por FUM:</td><td class="value"><?php echo pVal($evaluacion['edad_gest_semanas'], 's ') . pVal($evaluacion['edad_gest_dias'], 'd'); ?></td></tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Indicación y Vía de Exploración</div>
            <table class="info">
                <tr><td class="label">Indicación:</td><td class="value">
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
                </td></tr>
                <tr><td class="label">Vía:</td><td class="value">
                    <?php
                    $v = [];
                    if ($evaluacion['via_transvaginal']) $v[] = 'Transvaginal';
                    if ($evaluacion['via_transabdominal']) $v[] = 'Transabdominal';
                    if ($evaluacion['via_ambas']) $v[] = 'Ambas';
                    echo !empty($v) ? implode(', ', $v) : '—';
                    ?>
                </td></tr>
            </table>
        </div>

        <div class="two-col">
            <div class="col">
                <div class="section">
                    <div class="section-title">Útero</div>
                    <table class="info">
                        <tr><td class="label">Posición:</td><td class="value"><?php echo pVal($evaluacion['utero_posicion']); ?></td></tr>
                        <tr><td class="label">Contornos:</td><td class="value"><?php echo pBool($evaluacion['utero_contornos_regulares'], 'Regulares'); ?></td></tr>
                        <tr><td class="label">Ecogenicidad:</td><td class="value"><?php echo pBool($evaluacion['utero_ecogenicidad_conservada'], 'Conservada'); ?></td></tr>
                        <tr><td class="label">Dimensiones:</td><td class="value"><?php echo pVal($evaluacion['utero_dim_x'], ' x '); ?><?php echo pVal($evaluacion['utero_dim_y'], ' x '); ?><?php echo pVal($evaluacion['utero_dim_z'], ' mm'); ?></td></tr>
                        <tr><td class="label">Endometrio:</td><td class="value"><?php echo pVal($evaluacion['endometrio']); ?></td></tr>
                    </table>
                </div>

                <div class="section">
                    <div class="section-title">Saco Gestacional / Vitelino</div>
                    <table class="info">
                        <tr><td class="label">Localización:</td><td class="value"><?php echo pVal($evaluacion['localizacion']); ?><?php if ($evaluacion['localizacion'] === 'Otra' && $evaluacion['localizacion_otra']): ?> (<?php echo htmlspecialchars($evaluacion['localizacion_otra']); ?>)<?php endif; ?></td></tr>
                        <tr><td class="label">Saco gestacional:</td><td class="value"><?php echo pVal($evaluacion['sg_tipo']); ?> / <?php echo pVal($evaluacion['sg_morfologia']); ?> / <?php echo pVal($evaluacion['sg_medida_mm'], ' mm'); ?></td></tr>
                        <tr><td class="label">Saco vitelino:</td><td class="value"><?php echo $evaluacion['sv_presente'] !== null ? pBool($evaluacion['sv_presente'], 'Presente', 'Ausente') : '—'; ?><?php if ($evaluacion['sv_presente']): ?> (<?php echo pVal($evaluacion['sv_cantidad']); ?>, <?php echo pVal($evaluacion['sv_diametro_mm'], ' mm'); ?>)<?php endif; ?></td></tr>
                        <tr><td class="label">Corion/Amnios:</td><td class="value"><?php echo pBool($evaluacion['corion_amnios_normal'], 'Normal'); ?></td></tr>
                    </table>
                </div>

                <?php if (!empty($embriones)): ?>
                <div class="section">
                    <div class="section-title">Embriones</div>
                    <?php foreach ($embriones as $emb): ?>
                        <table class="info" style="margin-bottom:4px;">
                            <tr><td class="label" colspan="2"><strong>#<?php echo $emb['numero']; ?></strong></td></tr>
                            <tr><td class="label">CRL:</td><td class="value"><?php echo pVal($emb['crl_mm'], ' mm'); ?></td></tr>
                            <tr><td class="label">FCF:</td><td class="value"><?php echo pBool($emb['fcf_visible'], 'Visible', 'No visible'); ?><?php if ($emb['fcf_lpm']): ?> (<?php echo $emb['fcf_lpm']; ?> lpm)<?php endif; ?></td></tr>
                            <tr><td class="label">Localización:</td><td class="value"><?php echo pVal($emb['localizacion']); ?></td></tr>
                        </table>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="col">
                <div class="section">
                    <div class="section-title">Anexos</div>
                    <div style="margin-bottom:6px;"><strong>Ovario Derecho</strong></div>
                    <table class="info">
                        <tr><td class="label">Dimensiones:</td><td class="value"><?php echo pVal($evaluacion['ovario_der_dim_x'], ' x '); ?><?php echo pVal($evaluacion['ovario_der_dim_y'], ' x '); ?><?php echo pVal($evaluacion['ovario_der_dim_z'], ' mm'); ?></td></tr>
                        <tr><td class="label">Normal:</td><td class="value"><?php echo pBool($evaluacion['ovario_der_normal']); ?></td></tr>
                        <?php if ($evaluacion['ovario_der_cuerpo_luteo_mm']): ?><tr><td class="label">Cuerpo lúteo:</td><td class="value"><?php echo $evaluacion['ovario_der_cuerpo_luteo_mm']; ?> mm</td></tr><?php endif; ?>
                        <?php if ($evaluacion['ovario_der_quiste_simple_mm']): ?><tr><td class="label">Quiste simple:</td><td class="value"><?php echo $evaluacion['ovario_der_quiste_simple_mm']; ?> mm</td></tr><?php endif; ?>
                        <?php if (!empty($evaluacion['ovario_der_otra_alteracion'])): ?><tr><td class="label">Otra:</td><td class="value"><?php echo htmlspecialchars($evaluacion['ovario_der_otra_alteracion']); ?></td></tr><?php endif; ?>
                    </table>
                    <div style="margin-bottom:6px; margin-top:8px;"><strong>Ovario Izquierdo</strong></div>
                    <table class="info">
                        <tr><td class="label">Dimensiones:</td><td class="value"><?php echo pVal($evaluacion['ovario_izq_dim_x'], ' x '); ?><?php echo pVal($evaluacion['ovario_izq_dim_y'], ' x '); ?><?php echo pVal($evaluacion['ovario_izq_dim_z'], ' mm'); ?></td></tr>
                        <tr><td class="label">Normal:</td><td class="value"><?php echo pBool($evaluacion['ovario_izq_normal']); ?></td></tr>
                        <?php if ($evaluacion['ovario_izq_cuerpo_luteo_mm']): ?><tr><td class="label">Cuerpo lúteo:</td><td class="value"><?php echo $evaluacion['ovario_izq_cuerpo_luteo_mm']; ?> mm</td></tr><?php endif; ?>
                        <?php if ($evaluacion['ovario_izq_quiste_simple_mm']): ?><tr><td class="label">Quiste simple:</td><td class="value"><?php echo $evaluacion['ovario_izq_quiste_simple_mm']; ?> mm</td></tr><?php endif; ?>
                        <?php if (!empty($evaluacion['ovario_izq_otra_alteracion'])): ?><tr><td class="label">Otra:</td><td class="value"><?php echo htmlspecialchars($evaluacion['ovario_izq_otra_alteracion']); ?></td></tr><?php endif; ?>
                    </table>
                </div>

                <div class="section">
                    <div class="section-title">Hallazgos Adicionales</div>
                    <table class="info">
                        <tr><td class="label">Douglas:</td><td class="value"><?php echo pVal($evaluacion['douglas']); ?></td></tr>
                        <tr><td class="label">Hematoma subcoriónico:</td><td class="value"><?php echo pBool($evaluacion['hematoma_subcorionico']); ?></td></tr>
                        <?php if ($evaluacion['hematoma_subcorionico']): ?>
                        <tr><td class="label">Loc/Dim/Vol:</td><td class="value"><?php echo pVal($evaluacion['hematoma_localizacion']); ?> / <?php echo pVal($evaluacion['hematoma_dim_x']); ?>x<?php echo pVal($evaluacion['hematoma_dim_y']); ?>x<?php echo pVal($evaluacion['hematoma_dim_z']); ?> mm / <?php echo pVal($evaluacion['hematoma_volumen_ml'], ' ml'); ?></td></tr>
                        <?php endif; ?>
                        <?php if ($evaluacion['miomas_uterinos'] || $evaluacion['adenomiosis'] || $evaluacion['malformacion_uterina'] || !empty($evaluacion['hallazgos_otro'])): ?>
                        <tr><td class="label">Otros:</td><td class="value">
                            <?php if ($evaluacion['miomas_uterinos']): ?>Miomas uterinos<br><?php endif; ?>
                            <?php if ($evaluacion['adenomiosis']): ?>Adenomiosis<br><?php endif; ?>
                            <?php if ($evaluacion['malformacion_uterina']): ?>Malformación uterina<br><?php endif; ?>
                            <?php echo nl2br(htmlspecialchars($evaluacion['hallazgos_otro'] ?? '')); ?>
                        </td></tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Impresión Diagnóstica</div>
            <table class="info">
                <tr><td class="label">CRL:</td><td class="value"><?php echo pVal($evaluacion['impresion_crl_mm'], ' mm'); ?></td><td class="label">EG:</td><td class="value"><?php echo pVal($evaluacion['impresion_semanas'], 's ') . pVal($evaluacion['impresion_dias'], 'd'); ?></td><td class="label">FCF:</td><td class="value"><?php echo pVal($evaluacion['impresion_fcf_lpm'], ' lpm'); ?></td></tr>
            </table>
            <?php if (!empty($evaluacion['impresion_texto'])): ?>
                <p style="margin-top:4px; line-height:1.4;"><?php echo nl2br(htmlspecialchars($evaluacion['impresion_texto'])); ?></p>
            <?php endif; ?>
        </div>

        <div class="footer">
            Impreso el <?php echo date('d/m/Y H:i'); ?> — <?php echo htmlspecialchars($evaluacion['medico_nombre'] . ' ' . $evaluacion['medico_apellido']); ?>
        </div>
    </div>
</body>
</html>
