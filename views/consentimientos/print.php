<?php
$datosDinamicos = $consentimiento['datos_dinamicos'] ? json_decode($consentimiento['datos_dinamicos'], true) : [];
$fechaHoy = date('d/m/Y H:i');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consentimiento — <?php echo htmlspecialchars($consentimiento['nombre_documento']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; padding: 30px; color: #333; }
        .document { max-width: 800px; margin: 0 auto; }
        .doc-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .doc-header h1 { font-size: 20px; margin: 0; }
        .doc-header .version { font-size: 12px; color: #666; }
        .section { margin-bottom: 25px; }
        .section h2 { font-size: 14px; background: #f5f5f5; padding: 8px 12px; margin: 0 0 12px 0; border-left: 4px solid #333; }
        .row-item { display: flex; margin-bottom: 6px; font-size: 13px; }
        .label { width: 150px; font-weight: bold; flex-shrink: 0; }
        .value { flex: 1; }
        .signatures { margin-top: 30px; }
        .signature-block { display: inline-block; width: 45%; margin: 0 2% 20px 0; vertical-align: top; }
        .signature-block img { max-width: 100%; max-height: 80px; border: 1px solid #ddd; display: block; }
        .signature-name { font-size: 12px; margin-top: 4px; }
        .signature-date { font-size: 10px; color: #888; }
        .footer { margin-top: 40px; font-size: 10px; color: #999; text-align: center; border-top: 1px solid #eee; padding-top: 10px; }
        .denegacion { color: #d00; font-weight: bold; }
        @media print {
            body { padding: 15px; }
        }
    </style>
</head>
<body>

<div class="document">
    <div class="doc-header">
        <h1><?php echo htmlspecialchars($consentimiento['nombre_documento']); ?></h1>
        <?php if ($consentimiento['version']): ?>
            <div class="version">Versión: <?php echo htmlspecialchars($consentimiento['version']); ?></div>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>Datos del Consentimiento</h2>
        <div class="row-item">
            <div class="label">Fecha de generación:</div>
            <div class="value"><?php echo date('d/m/Y H:i', strtotime($consentimiento['fecha_generacion'])); ?></div>
        </div>
        <div class="row-item">
            <div class="label">Estado:</div>
            <div class="value"><?php echo htmlspecialchars($consentimiento['estado']); ?></div>
        </div>
    </div>

    <?php if (!empty($consentimiento['contenido'])): ?>
    <div class="section">
        <h2>Contenido del Consentimiento</h2>
        <div style="font-size: 12px; line-height: 1.8;">
            <?php echo $consentimiento['contenido']; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="section">
        <h2>Paciente</h2>
        <div class="row-item">
            <div class="label">Nombre completo:</div>
            <div class="value"><?php echo htmlspecialchars($consentimiento['paciente_nombre'] . ' ' . $consentimiento['paciente_apellido']); ?></div>
        </div>
    </div>

    <div class="section">
        <h2>Médico Responsable</h2>
        <div class="row-item">
            <div class="label">Nombre:</div>
            <div class="value"><?php echo htmlspecialchars($consentimiento['medico_nombre'] . ' ' . $consentimiento['medico_apellido']); ?></div>
        </div>
        <?php if ($consentimiento['medico_telefono']): ?>
        <div class="row-item">
            <div class="label">Teléfono:</div>
            <div class="value"><?php echo htmlspecialchars($consentimiento['medico_telefono']); ?></div>
        </div>
        <?php endif; ?>
    </div>

    <?php if ($datosDinamicos): ?>
    <div class="section">
        <h2>Datos de Redes Sociales</h2>
        <?php if (!empty($datosDinamicos['facebook'])): ?>
        <div class="row-item">
            <div class="label">Facebook:</div>
            <div class="value"><?php echo htmlspecialchars($datosDinamicos['facebook']); ?></div>
        </div>
        <?php endif; ?>
        <?php if (!empty($datosDinamicos['instagram'])): ?>
        <div class="row-item">
            <div class="label">Instagram:</div>
            <div class="value"><?php echo htmlspecialchars($datosDinamicos['instagram']); ?></div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="signatures">
        <h2 style="font-size:14px; border-bottom:1px solid #ccc; padding-bottom:8px;">Firmas</h2>
        <?php if (empty($firmas)): ?>
            <p style="color:#999; font-style:italic;">Sin firmas registradas.</p>
        <?php else: ?>
            <?php foreach ($firmas as $firma): ?>
                <div class="signature-block">
                    <?php 
                    $rutaFisica = __DIR__ . '/../../' . $firma['ruta_imagen_firma'];
                    if (file_exists($rutaFisica)): 
                        $imagenData = base64_encode(file_get_contents($rutaFisica));
                    ?>
                        <img src="data:image/png;base64,<?php echo $imagenData; ?>" alt="Firma">
                    <?php else: ?>
                        <div style="width:180px; height:60px; border:1px dashed #ccc; display:flex; align-items:center; justify-content:center; color:#999; font-size:11px;">
                            [ Firma no disponible ]
                        </div>
                    <?php endif; ?>
                    <div class="signature-name">
                        <strong><?php echo htmlspecialchars($firma['rol_firmante']); ?>:</strong>
                        <?php echo htmlspecialchars($firma['nombre_firmante']); ?>
                        <?php if ($firma['tipo_accion'] === 'Denegacion'): ?>
                            <span class="denegacion">[DENEGACIÓN]</span>
                        <?php endif; ?>
                    </div>
                    <div class="signature-date"><?php echo date('d/m/Y H:i', strtotime($firma['fecha_firma'])); ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="footer">
        Documento generado el <?php echo $fechaHoy; ?> — PreNacer Sistema de Gestión Médico
        <?php if ($consentimiento['estado'] === 'Revocado'): ?>
            <br><span class="denegacion">ESTE DOCUMENTO HA SIDO REVOCADO POR EL PACIENTE</span>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
