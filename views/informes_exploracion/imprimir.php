<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Exploración - <?php echo htmlspecialchars($informe['codigo_informe']); ?></title>
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
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .data-table td {
            padding: 6px 10px;
            font-size: 11px;
            border-bottom: 1px solid #E5EDED;
            vertical-align: top;
        }
        .data-table tr:last-child td {
            border-bottom: none;
        }
        .data-table .lbl {
            font-weight: bold;
            color: #1B4F5A;
            width: 200px;
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
        .block-text {
            background-color: #F9FBFA;
            padding: 12px;
            border: 1px solid #E5EDED;
            border-radius: 4px;
            line-height: 1.5;
            margin-top: 5px;
        }
    </style>
</head>
<body>

<?php
$estadoClass = match($informe['estado'] ?? '') {
    'Completado' => 'badge-success',
    'En proceso' => 'badge-warning',
    'Archivado' => 'badge-secondary',
    default => 'badge-info'
};

$pacienteNombre = isset($paciente) ? htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellido']) : '—';
$pacienteEdad = isset($paciente['fecha_nacimiento']) ? (date('Y') - date('Y', strtotime($paciente['fecha_nacimiento']))) . ' años' : '—';
$medicoNombre = isset($medico) ? htmlspecialchars($medico['nombre'] . ' ' . $medico['apellido']) : '—';
$medicoRefNombre = !empty($medicoReferido) ? htmlspecialchars($medicoReferido['nombre'] . ' ' . $medicoReferido['apellido']) : '—';
?>

<div class="title-container">
    <h1>Informe de Exploración Estructural</h1>
    <div class="subtitle">Código: <?php echo htmlspecialchars($informe['codigo_informe']); ?></div>
</div>

<table class="patient-card">
    <tr>
        <td class="lbl">Paciente:</td>
        <td class="val"><strong><?php echo $pacienteNombre; ?></strong></td>
        <td class="lbl">Fecha de Informe:</td>
        <td class="val"><?php echo date('d/m/Y', strtotime($informe['fecha_informe'])); ?></td>
    </tr>
    <tr>
        <td class="lbl">Médico que realiza:</td>
        <td class="val"><?php echo $medicoNombre; ?></td>
        <td class="lbl">Edad de Paciente:</td>
        <td class="val"><?php echo $pacienteEdad; ?></td>
    </tr>
    <tr>
        <td class="lbl">Médico Referidor:</td>
        <td class="val"><?php echo $medicoRefNombre; ?></td>
        <td class="lbl">Trimestre / Estado:</td>
        <td class="val">
            Trimestre <?php echo htmlspecialchars($informe['trimestre']); ?> / 
            <span class="badge <?php echo $estadoClass; ?>"><?php echo htmlspecialchars($informe['estado']); ?></span>
        </td>
    </tr>
</table>

<div class="section-title">Datos Clínicos y del Estudio</div>
<table class="data-table">
    <?php if (!empty($informe['estudio_solicitado'])): ?>
    <tr>
        <td class="lbl">Estudio Solicitado:</td>
        <td class="val"><?php echo htmlspecialchars($informe['estudio_solicitado']); ?></td>
    </tr>
    <?php endif; ?>
    <?php if (!empty($informe['fecha_publicacion_parto_usg'])): ?>
    <tr>
        <td class="lbl">Fecha Publicación Parto (USG):</td>
        <td class="val"><?php echo date('d/m/Y', strtotime($informe['fecha_publicacion_parto_usg'])); ?></td>
    </tr>
    <?php endif; ?>
    <?php if (!empty($informe['fecha_probable_parto_usg'])): ?>
    <tr>
        <td class="lbl">Fecha Probable de Parto (USG):</td>
        <td class="val"><?php echo date('d/m/Y', strtotime($informe['fecha_probable_parto_usg'])); ?></td>
    </tr>
    <?php endif; ?>
</table>

<?php if (!empty($informe['resumen_ultrasonido'])): ?>
<div class="section-title">Resumen del Ultrasonido</div>
<div class="block-text">
    <?php echo nl2br(htmlspecialchars($informe['resumen_ultrasonido'])); ?>
</div>
<?php endif; ?>

<?php if (!empty($informe['observaciones'])): ?>
<div class="section-title">Observaciones Adicionales</div>
<div class="block-text">
    <?php echo nl2br(htmlspecialchars($informe['observaciones'])); ?>
</div>
<?php endif; ?>

<table style="width: 100%; margin-top: 50px; border-collapse: collapse;">
    <tr>
        <td style="width: 50%; text-align: center; vertical-align: top;">
            <div style="border-top: 1px solid #1B4F5A; width: 220px; margin: 0 auto; padding-top: 5px; font-size: 10px;">
                <strong>Dr(a). <?php echo $medicoNombre; ?></strong><br>
                Firma del Médico
            </div>
        </td>
        <td style="width: 50%; text-align: center; font-size: 10px; color: #777; padding-top: 15px; vertical-align: top;">
            Fecha de impresión: <?php echo date('d/m/Y H:i'); ?>
        </td>
    </tr>
</table>

<div class="no-print" style="text-align:center;margin:20px 0;">
    <a href="<?php echo Url::to('/informes_exploracion/pdf?id=' . $informe['id']); ?>"
       style="padding:10px 30px;font-size:14px;cursor:pointer;border:none;background:#1B4F5A;color:#fff;border-radius:8px;text-decoration:none;display:inline-block;font-weight:bold;">
        <i class="fa-solid fa-download"></i> Descargar PDF
    </a>
</div>
</body>
</html>
