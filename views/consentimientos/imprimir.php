<?php
$datosDinamicos = $consentimiento['datos_dinamicos'] ? json_decode($consentimiento['datos_dinamicos'], true) : [];
$firmas = $firmas ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consentimiento — <?php echo htmlspecialchars($consentimiento['nombre_documento']); ?></title>
    <style>
        @media print { .no-print { display: none !important; } }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #2A2A2A;
            line-height: 1.6;
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
            width: 150px;
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
        .content-box {
            background-color: #ffffff;
            border: 1px solid #E5EDED;
            padding: 15px;
            border-radius: 4px;
            font-size: 11px;
            line-height: 1.6;
            color: #2A2A2A;
            margin-bottom: 20px;
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
        .revocacion-banner {
            border: 2px solid #A82424;
            background-color: #FDF0F0;
            color: #A82424;
            font-weight: bold;
            text-align: center;
            padding: 12px;
            font-size: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<?php
$estadoClass = match($consentimiento['estado'] ?? '') {
    'Completado' => 'badge-success',
    'Parcialmente Firmado' => 'badge-warning',
    'Revocado' => 'badge-danger',
    default => 'badge-secondary'
};
?>

<?php if (($consentimiento['estado'] ?? '') === 'Revocado'): ?>
    <div class="revocacion-banner">
        ⚠️ Este documento ha sido REVOCADO por la paciente
    </div>
<?php endif; ?>

<div class="title-container">
    <h1><?php echo htmlspecialchars($consentimiento['nombre_documento']); ?></h1>
    <?php if (!empty($consentimiento['version'])): ?>
        <div class="subtitle">Versión: <?php echo htmlspecialchars($consentimiento['version']); ?></div>
    <?php endif; ?>
</div>

<table class="patient-card">
    <tr>
        <td class="lbl">Paciente:</td>
        <td class="val"><strong><?php echo htmlspecialchars($consentimiento['paciente_nombre'] . ' ' . $consentimiento['paciente_apellido']); ?></strong></td>
        <td class="lbl">Fecha Generación:</td>
        <td class="val"><?php echo date('d/m/Y H:i', strtotime($consentimiento['fecha_generacion'])); ?></td>
    </tr>
    <tr>
        <td class="lbl">Médico Responsable:</td>
        <td class="val">Dr(a). <?php echo htmlspecialchars($consentimiento['medico_nombre'] . ' ' . $consentimiento['medico_apellido']); ?></td>
        <td class="lbl">Estado:</td>
        <td class="val">
            <span class="badge <?php echo $estadoClass; ?>"><?php echo htmlspecialchars($consentimiento['estado']); ?></span>
        </td>
    </tr>
    <?php if ($consentimiento['medico_telefono'] || $datosDinamicos): ?>
    <tr>
        <td class="lbl">Teléfono Médico:</td>
        <td class="val"><?php echo htmlspecialchars($consentimiento['medico_telefono'] ?: '—'); ?></td>
        <td class="lbl">Redes Sociales:</td>
        <td class="val">
            <?php 
            $rs = [];
            if (!empty($datosDinamicos['facebook'])) $rs[] = 'FB: ' . $datosDinamicos['facebook'];
            if (!empty($datosDinamicos['instagram'])) $rs[] = 'IG: ' . $datosDinamicos['instagram'];
            echo !empty($rs) ? htmlspecialchars(implode(', ', $rs)) : '—';
            ?>
        </td>
    </tr>
    <?php endif; ?>
</table>

<?php if (!empty($consentimiento['contenido'])): ?>
<div class="section-title">Contenido del Consentimiento</div>
<div class="content-box">
    <?php echo $consentimiento['contenido']; ?>
</div>
<?php endif; ?>

<div class="section-title">Registro de Firmas y Validación Digital</div>
<?php if (empty($firmas)): ?>
    <p style="color:#86868b; font-style:italic; padding: 10px;">No se registran firmas digitales para este documento.</p>
<?php else: ?>
    <table style="width: 100%; margin-top: 10px; border-collapse: collapse;">
        <tr>
        <?php 
        $colCount = 0;
        foreach ($firmas as $firma): 
            if ($colCount > 0 && $colCount % 2 == 0) {
                echo '</tr><tr>';
            }
            $rutaFisica = __DIR__ . '/../../' . $firma['ruta_imagen_firma'];
            $imagenBase64 = '';
            if (file_exists($rutaFisica)) {
                $imagenBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($rutaFisica));
            }
        ?>
            <td style="width: 48%; padding: 10px; border: 1px solid #D1DDDC; vertical-align: top; background-color: #F4F7F6;">
                <div style="font-weight: bold; color: #1B4F5A; font-size: 11px; margin-bottom: 6px; border-bottom: 1px solid #81BABB; padding-bottom: 3px;">
                    <?php echo htmlspecialchars($firma['rol_firmante'] . ' - ' . $firma['nombre_firmante']); ?>
                </div>
                <div style="text-align: center; height: 90px; padding: 5px; background: #ffffff; border: 1px solid #E5EDED; margin-bottom: 5px;">
                    <?php if ($imagenBase64): ?>
                        <img src="<?php echo $imagenBase64; ?>" style="max-height: 80px; max-width: 100%; display: inline-block; vertical-align: middle;" alt="Firma">
                    <?php else: ?>
                        <div style="line-height: 80px; color: #86868b; font-style: italic; font-size: 10px;">[ Firma no disponible ]</div>
                    <?php endif; ?>
                </div>
                <div style="font-size: 9px; color: #86868b; line-height: 1.4;">
                    <strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($firma['fecha_firma'])); ?><br>
                    <strong>IP Origen:</strong> <?php echo htmlspecialchars($firma['ip_origen']); ?><br>
                    <strong>Tipo Acción:</strong> <?php echo htmlspecialchars($firma['tipo_accion'] ?? 'Aceptación'); ?>
                </div>
            </td>
            <?php if ($colCount % 2 == 0): ?>
                <td style="width: 4%;">&nbsp;</td>
            <?php endif; ?>
        <?php 
            $colCount++;
        endforeach; 
        if ($colCount % 2 != 0) {
            echo '<td style="width: 48%;">&nbsp;</td>';
        }
        ?>
        </tr>
    </table>
<?php endif; ?>

<div class="no-print" style="text-align:center;margin:30px 0;">
    <a href="<?php echo Url::to('/consentimientos/pdf?id=' . $consentimiento['id']); ?>"
       style="padding:10px 30px;font-size:14px;cursor:pointer;border:none;background:#1B4F5A;color:#fff;border-radius:8px;text-decoration:none;display:inline-block;font-weight:bold;">
        <i class="fa-solid fa-download"></i> Descargar PDF
    </a>
</div>
</body>
</html>
