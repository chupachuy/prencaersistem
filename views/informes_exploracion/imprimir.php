<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Exploración - <?php echo htmlspecialchars($informe['codigo_informe']); ?></title>
    <style>
        @media print { .no-print { display: none !important; } }
        body { font-family: Arial, sans-serif; padding: 0 20px 40px 20px; color: #333; }
        .doc-header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0071e3; padding-bottom: 10px; }
        .doc-header h1 { font-size: 18px; font-weight: 700; color: #0071e3; margin: 0; }
        .doc-header .code { font-size: 12px; color: #86868b; }
        .section { margin-bottom: 16px; }
        .section-title { font-size: 13px; font-weight: 700; border-bottom: 1px solid #ddd; padding-bottom: 4px; margin-bottom: 8px; color: #0071e3; }
        .row { display: flex; flex-wrap: wrap; margin-bottom: 4px; }
        .col { padding: 4px; }
        .label { font-weight: 600; color: #555; }
        .value { color: #1d1d1f; }
    </style>
</head>
<body>

<div class="doc-header">
    <h1>INFORME DE EXPLORACIÓN ESTRUCTURAL</h1>
    <div class="code">Código: <?php echo htmlspecialchars($informe['codigo_informe']); ?></div>
</div>

<div class="section">
    <div class="section-title">Datos del Informe</div>
    <div class="row">
        <div class="col" style="width:33%;"><span class="label">Fecha:</span> <?php echo date('d/m/Y', strtotime($informe['fecha_informe'])); ?></div>
        <div class="col" style="width:33%;"><span class="label">Trimestre:</span> Trimestre <?php echo htmlspecialchars($informe['trimestre']); ?></div>
        <div class="col" style="width:33%;"><span class="label">Estado:</span> <?php echo htmlspecialchars($informe['estado']); ?></div>
    </div>
    <?php if (!empty($informe['estudio_solicitado'])): ?>
    <div class="row">
        <div class="col" style="width:100%;"><span class="label">Estudio Solicitado:</span> <?php echo htmlspecialchars($informe['estudio_solicitado']); ?></div>
    </div>
    <?php endif; ?>
</div>

<?php if (!empty($informe['fecha_publicacion_parto_usg']) || !empty($informe['fecha_probable_parto_usg']) || !empty($informe['resumen_ultrasonido'])): ?>
<div class="section">
    <div class="section-title">Datos del Ultrasonido</div>
    <?php if (!empty($informe['fecha_publicacion_parto_usg'])): ?>
    <div class="row"><div class="col" style="width:100%;"><span class="label">Fecha Publicación Parto (USG):</span> <?php echo date('d/m/Y', strtotime($informe['fecha_publicacion_parto_usg'])); ?></div></div>
    <?php endif; ?>
    <?php if (!empty($informe['fecha_probable_parto_usg'])): ?>
    <div class="row"><div class="col" style="width:100%;"><span class="label">Fecha Probable de Parto (USG):</span> <?php echo date('d/m/Y', strtotime($informe['fecha_probable_parto_usg'])); ?></div></div>
    <?php endif; ?>
    <?php if (!empty($informe['resumen_ultrasonido'])): ?>
    <div class="row"><div class="col" style="width:100%;"><span class="label">Resumen del Ultrasonido:</span><br><?php echo nl2br(htmlspecialchars($informe['resumen_ultrasonido'])); ?></div></div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if (!empty($informe['observaciones'])): ?>
<div class="section">
    <div class="section-title">Observaciones</div>
    <p><?php echo nl2br(htmlspecialchars($informe['observaciones'])); ?></p>
</div>
<?php endif; ?>

<div class="no-print" style="text-align:center;margin:20px 0;"><a href="<?php echo Url::to('/informes_exploracion/pdf?id=' . $informe['id']); ?>"
       style="padding:10px 30px;font-size:16px;cursor:pointer;border:none;background:#1B4F5A;color:#fff;border-radius:8px;text-decoration:none;display:inline-block;">
        <i class="fa-solid fa-download"></i> Descargar PDF
    </a>
</div>
</body>
</html>
