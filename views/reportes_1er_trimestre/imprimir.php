<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte 1er Trimestre - <?php echo htmlspecialchars($reporte['codigo_reporte']); ?></title>
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
        .structural-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .structural-table td {
            padding: 6px 10px;
            font-size: 11px;
            border-bottom: 1px solid #E5EDED;
            vertical-align: top;
        }
        .structural-table tr:nth-child(even) td {
            background-color: #F9FBFA;
        }
        .structural-table .lbl {
            font-weight: bold;
            color: #1B4F5A;
            width: 180px;
        }
        .structural-table .val {
            color: #333333;
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

<?php
$estadoClass = match($reporte['estado']) {
    'Completado' => 'badge-success',
    'En proceso' => 'badge-warning',
    'Archivado' => 'badge-secondary',
    default => 'badge-info'
};
?>

<div class="title-container">
    <h1>REPORTE 1ER TRIMESTRE</h1>
    <div class="subtitle">Código: <?php echo htmlspecialchars($reporte['codigo_reporte']); ?></div>
</div>

<table class="patient-card">
    <tr>
        <td class="lbl">Paciente:</td>
        <td class="val"><strong><?php echo htmlspecialchars($reporte['paciente_nombre'] . ' ' . $reporte['paciente_apellido']); ?></strong></td>
        <td class="lbl">Fecha de Reporte:</td>
        <td class="val"><?php echo date('d/m/Y', strtotime($reporte['fecha_reporte'])); ?></td>
    </tr>
    <tr>
        <td class="lbl">Médico Tratante:</td>
        <td class="val"><?php echo htmlspecialchars($reporte['medico_nombre'] . ' ' . $reporte['medico_apellido']); ?></td>
        <td class="lbl">Lugar:</td>
        <td class="val"><?php echo htmlspecialchars($reporte['lugar'] ?? '-'); ?></td>
    </tr>
    <tr>
        <td class="lbl">Médico Referido:</td>
        <td class="val"><?php echo !empty($reporte['medico_referido_nombre']) ? htmlspecialchars($reporte['medico_referido_nombre'] . ' ' . $reporte['medico_referido_apellido']) : '—'; ?></td>
        <td class="lbl">Estado:</td>
        <td class="val">
            <span class="badge <?php echo $estadoClass; ?>"><?php echo htmlspecialchars($reporte['estado']); ?></span>
        </td>
    </tr>
</table>

<table class="grid-table">
    <tr>
        <!-- Columna Izquierda: Signos Vitales -->
        <td style="width: 48%;">
            <div class="section-title">Signos Vitales</div>
            <table class="data-table">
                <tr>
                    <td class="lbl">Peso:</td>
                    <td class="val"><?php echo $reporte['peso'] ? htmlspecialchars($reporte['peso']) . ' kg' : '—'; ?></td>
                </tr>
                <tr>
                    <td class="lbl">Talla:</td>
                    <td class="val"><?php echo $reporte['talla'] ? htmlspecialchars($reporte['talla']) . ' cm' : '—'; ?></td>
                </tr>
                <tr>
                    <td class="lbl">Presión Arterial:</td>
                    <td class="val">
                        <?php
                        if ($reporte['presion_sistolica'] && $reporte['presion_diastolica']) {
                            echo htmlspecialchars($reporte['presion_sistolica']) . '/' . htmlspecialchars($reporte['presion_diastolica']) . ' mmHg';
                        } else {
                            echo '—';
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </td>
        <!-- Espaciador -->
        <td style="width: 4%;">&nbsp;</td>
        <!-- Columna Derecha: Historia Obstétrica -->
        <td style="width: 48%;">
            <div class="section-title">Historia Obstétrica</div>
            <table class="data-table">
                <tr>
                    <td class="lbl">Gesta <small style="font-weight:normal;color:#666;">(Embarazos):</small></td>
                    <td class="val"><?php echo $reporte['gesta'] ?? '—'; ?></td>
                </tr>
                <tr>
                    <td class="lbl">Para <small style="font-weight:normal;color:#666;">(Partos):</small></td>
                    <td class="val"><?php echo $reporte['para'] ?? '—'; ?></td>
                </tr>
                <tr>
                    <td class="lbl">Abortos:</td>
                    <td class="val"><?php echo $reporte['abortos'] ?? '—'; ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table class="grid-table">
    <tr>
        <!-- Columna Izquierda: Fechas Obstétricas -->
        <td style="width: 48%;">
            <div class="section-title">Fechas Obstétricas</div>
            <table class="data-table">
                <tr>
                    <td class="lbl">Fecha Última Regla:</td>
                    <td class="val"><?php echo $reporte['fecha_ultima_regla'] ? date('d/m/Y', strtotime($reporte['fecha_ultima_regla'])) : '—'; ?></td>
                </tr>
                <tr>
                    <td class="lbl">EG (FUR):</td>
                    <td class="val"><?php echo $reporte['edad_gestacional_fum'] ? htmlspecialchars($reporte['edad_gestacional_fum']) . ' sem' : '—'; ?></td>
                </tr>
                <tr>
                    <td class="lbl">FPP (FUR):</td>
                    <td class="val"><?php echo $reporte['fecha_probable_parto_fum'] ? date('d/m/Y', strtotime($reporte['fecha_probable_parto_fum'])) : '—'; ?></td>
                </tr>
            </table>
        </td>
        <!-- Espaciador -->
        <td style="width: 4%;">&nbsp;</td>
        <!-- Columna Derecha: Datos Ecográficos -->
        <td style="width: 48%;">
            <div class="section-title">Datos Ecográficos</div>
            <table class="data-table">
                <tr>
                    <td class="lbl">LCC (Longitud C-C):</td>
                    <td class="val"><?php echo $reporte['longitud_craneo_cauda'] ? htmlspecialchars($reporte['longitud_craneo_cauda']) . ' mm' : '—'; ?></td>
                </tr>
                <tr>
                    <td class="lbl">EG (USG):</td>
                    <td class="val"><?php echo $reporte['edad_gestacional_usg'] ? htmlspecialchars($reporte['edad_gestacional_usg']) . ' sem' : '—'; ?></td>
                </tr>
                <tr>
                    <td class="lbl">FPP (USG):</td>
                    <td class="val"><?php echo $reporte['fecha_probable_parto_usg'] ? date('d/m/Y', strtotime($reporte['fecha_probable_parto_usg'])) : '—'; ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<?php if (!empty($reporte['equipo_usg']) || !empty($reporte['transductor_tipo']) || !empty($reporte['equipo_estudio'])): ?>
<div class="section-title">Detalles del Equipo y Estudio</div>
<table class="data-table">
    <?php if (!empty($reporte['equipo_usg'])): ?>
    <tr>
        <td class="lbl" style="width: 180px;">Equipo Ultrasonografía:</td>
        <td class="val"><?php echo htmlspecialchars($reporte['equipo_usg']); ?></td>
    </tr>
    <?php endif; ?>
    <?php if (!empty($reporte['transductor_tipo'])): ?>
    <tr>
        <td class="lbl" style="width: 180px;">Transductor:</td>
        <td class="val"><?php echo htmlspecialchars($reporte['transductor_tipo']); ?></td>
    </tr>
    <?php endif; ?>
    <?php if (!empty($reporte['equipo_estudio'])): ?>
    <tr>
        <td class="lbl" style="width: 180px; vertical-align: top;">Observaciones del Equipo:</td>
        <td class="val" style="vertical-align: top;"><?php echo nl2br(htmlspecialchars($reporte['equipo_estudio'])); ?></td>
    </tr>
    <?php endif; ?>
</table>
<?php endif; ?>

<div class="section-title">Exploración Estructural Fetal</div>
<table class="structural-table">
    <?php 
    $estructuras = [
        'craneo' => 'Cráneo',
        'sistema_nervioso_central' => 'Sistema Nervioso Central',
        'cuello' => 'Cuello',
        'cara' => 'Cara',
        'columna' => 'Columna',
        'torax' => 'Tórax',
        'corazon' => 'Corazón',
        'abdomen' => 'Abdomen',
        'extremidades' => 'Extremidades',
        'liquido_amniotico' => 'Líquido Amniótico',
        'decidua' => 'Decidua',
        'cervix' => 'Cérvix'
    ];
    $hasContent = false;
    foreach ($estructuras as $key => $label): 
        if (!empty($reporte[$key])): 
            $hasContent = true;
    ?>
        <tr>
            <td class="lbl"><?php echo $label; ?>:</td>
            <td class="val"><?php echo nl2br(htmlspecialchars($reporte[$key])); ?></td>
        </tr>
    <?php 
        endif;
    endforeach; 
    
    if (!$hasContent):
    ?>
        <tr>
            <td colspan="2" class="val" style="text-align: center; font-style: italic;">No se registraron hallazgos estructurales específicos.</td>
        </tr>
    <?php endif; ?>
</table>

<table class="signature-table">
    <tr>
        <td style="width: 50%; text-align: center;">
            <div class="signature-line">
                <strong>Dr(a). <?php echo htmlspecialchars($reporte['medico_nombre'] . ' ' . $reporte['medico_apellido']); ?></strong><br>
                Firma del Médico
            </div>
        </td>
        <td style="width: 50%; text-align: center; font-size: 10px; color: #777; padding-top: 15px;">
            Fecha de impresión: <?php echo date('d/m/Y H:i'); ?>
        </td>
    </tr>
</table>

<div class="no-print" style="text-align:center;margin:30px 0;">
    <a href="<?php echo Url::to('/reportes_1er_trimestre/pdf?id=' . $reporte['id']); ?>"
       style="padding:10px 30px;font-size:14px;cursor:pointer;border:none;background:#1B4F5A;color:#fff;border-radius:8px;text-decoration:none;display:inline-block;font-weight:bold;">
        <i class="fa-solid fa-download"></i> Descargar PDF
    </a>
</div>

</body>
</html>
