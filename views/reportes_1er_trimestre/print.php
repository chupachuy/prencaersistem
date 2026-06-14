<?php
$title = "Imprimir Reporte 1er Trimestre";
require_once __DIR__ . '/../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif; padding: 150px 20px 40px 20px;
        }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0 0; color: #666; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 16px; font-weight: bold; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; }
        .row { display: flex; flex-wrap: wrap; margin-bottom: 8px; }
        .col { padding: 5px; }
        .col-4 { width: 33.33%; }
        .col-6 { width: 50%; }
        .col-12 { width: 100%; }
        .label { font-weight: bold; color: #333; }
        .value { color: #666; }
        .badge { padding: 3px 8px; border-radius: 3px; font-size: 12px; }
        .badge-success { background: #28a745; color: white; }
        .badge-warning { background: #ffc107; color: #333; }
        .badge-secondary { background: #6c757d; color: white; }
        .badge-info { background: #17a2b8; color: white; }
        .text-center { text-align: center; }
        .btn-print { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE 1ER TRIMESTRE</h1>
        <p>Código: <?php echo htmlspecialchars($reporte['codigo_reporte']); ?></p>
    </div>

    <div class="section">
        <div class="section-title">Datos del Reporte</div>
        <div class="row">
            <div class="col col-4">
                <span class="label">Fecha:</span> <?php echo date('d/m/Y', strtotime($reporte['fecha_reporte'])); ?>
            </div>
            <div class="col col-4">
                <span class="label">Lugar:</span> <?php echo htmlspecialchars($reporte['lugar'] ?? '-'); ?>
            </div>
            <div class="col col-4">
                <span class="label">Estado:</span> 
                <?php 
                $estadoClass = match($reporte['estado']) {
                    'Completado' => 'badge-success',
                    'En proceso' => 'badge-warning',
                    'Archivado' => 'badge-secondary',
                    default => 'badge-info'
                };
                ?>
                <span class="badge <?php echo $estadoClass; ?>"><?php echo htmlspecialchars($reporte['estado']); ?></span>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Paciente</div>
        <div class="row">
            <div class="col col-6">
                <span class="label">Nombre:</span> <?php echo htmlspecialchars($reporte['paciente_nombre'] . ' ' . $reporte['paciente_apellido']); ?>
            </div>
            <div class="col col-6">
                <span class="label">Médico:</span> <?php echo htmlspecialchars($reporte['medico_nombre'] . ' ' . $reporte['medico_apellido']); ?>
            </div>
        </div>
        <?php if (!empty($reporte['medico_referido_nombre'])): ?>
        <div class="row">
            <div class="col col-12">
                <span class="label">Médico Referido:</span> <?php echo htmlspecialchars($reporte['medico_referido_nombre'] . ' ' . $reporte['medico_referido_apellido']); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="section">
        <div class="section-title">Signos Vitales</div>
        <div class="row">
            <div class="col col-4">
                <span class="label">Peso:</span> <?php echo $reporte['peso'] ? htmlspecialchars($reporte['peso']) . ' kg' : '-'; ?>
            </div>
            <div class="col col-4">
                <span class="label">Talla:</span> <?php echo $reporte['talla'] ? htmlspecialchars($reporte['talla']) . ' cm' : '-'; ?>
            </div>
            <div class="col col-4">
                <span class="label">Presión:</span> 
                <?php 
                if ($reporte['presion_sistolica'] && $reporte['presion_diastolica']) {
                    echo htmlspecialchars($reporte['presion_sistolica']) . '/' . htmlspecialchars($reporte['presion_diastolica']) . ' mmHg';
                } else {
                    echo '-';
                }
                ?>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Historia Obstétrica</div>
        <div class="row">
            <div class="col col-4">
                <span class="label">Gesta <small style="color: #666;">(N. de embarazos):</small></span> <?php echo $reporte['gesta'] ?? '-'; ?>
            </div>
            <div class="col col-4">
                <span class="label">Para <small style="color: #666;">(partos vaginales):</small></span> <?php echo $reporte['para'] ?? '-'; ?>
            </div>
            <div class="col col-4">
                <span class="label">Abortos:</span> <?php echo $reporte['abortos'] ?? '-'; ?>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Fechas Obstétricas</div>
        <div class="row">
            <div class="col col-4">
                <span class="label">Fecha Última Regla:</span> <?php echo $reporte['fecha_ultima_regla'] ? date('d/m/Y', strtotime($reporte['fecha_ultima_regla'])) : '-'; ?>
            </div>
            <div class="col col-4">
                <span class="label">Edad Gestacional (FUR):</span> <?php echo $reporte['edad_gestacional_fum'] ? htmlspecialchars($reporte['edad_gestacional_fum']) . ' sem' : '-'; ?>
            </div>
            <div class="col col-4">
                <span class="label">FPP (FUR):</span> <?php echo $reporte['fecha_probable_parto_fum'] ? date('d/m/Y', strtotime($reporte['fecha_probable_parto_fum'])) : '-'; ?>
            </div>
        </div>
        <?php if (!empty($reporte['equipo_estudio'])): ?>
        <div class="row">
            <div class="col col-12">
                <span class="label">Datos del Equipo:</span> <?php echo nl2br(htmlspecialchars($reporte['equipo_estudio'])); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="section">
        <div class="section-title">Datos Ecográficos</div>
        <div class="row">
            <div class="col col-4">
                <span class="label">LCC:</span> <?php echo $reporte['longitud_craneo_cauda'] ? htmlspecialchars($reporte['longitud_craneo_cauda']) . ' mm' : '-'; ?>
            </div>
            <div class="col col-4">
                <span class="label">EG (USG):</span> <?php echo $reporte['edad_gestacional_usg'] ? htmlspecialchars($reporte['edad_gestacional_usg']) . ' sem' : '-'; ?>
            </div>
            <div class="col col-4">
                <span class="label">Fecha Probable de Parto calculada por Ultrasonido:</span> <?php echo $reporte['fecha_probable_parto_usg'] ? date('d/m/Y', strtotime($reporte['fecha_probable_parto_usg'])) : '-'; ?>
            </div>
        </div>
        <div class="row">
            <div class="col col-6">
                <span class="label">Equipo de Ultrasonografía:</span> <?php echo $reporte['equipo_usg'] ? htmlspecialchars($reporte['equipo_usg']) : '-'; ?>
            </div>
            <div class="col col-6">
                <span class="label">Transductor:</span> <?php echo $reporte['transductor_tipo'] ? htmlspecialchars($reporte['transductor_tipo']) : '-'; ?>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Exploración Estructural Fetal</div>
        <?php if (!empty($reporte['craneo'])): ?>
        <div class="row"><div class="col col-12"><span class="label">Cráneo:</span> <?php echo nl2br(htmlspecialchars($reporte['craneo'])); ?></div></div>
        <?php endif; ?>
        <?php if (!empty($reporte['sistema_nervioso_central'])): ?>
        <div class="row"><div class="col col-12"><span class="label">Sistema Nervioso Central:</span> <?php echo nl2br(htmlspecialchars($reporte['sistema_nervioso_central'])); ?></div></div>
        <?php endif; ?>
        <?php if (!empty($reporte['cuello'])): ?>
        <div class="row"><div class="col col-12"><span class="label">Cuello:</span> <?php echo nl2br(htmlspecialchars($reporte['cuello'])); ?></div></div>
        <?php endif; ?>
        <?php if (!empty($reporte['cara'])): ?>
        <div class="row"><div class="col col-12"><span class="label">Cara:</span> <?php echo nl2br(htmlspecialchars($reporte['cara'])); ?></div></div>
        <?php endif; ?>
        <?php if (!empty($reporte['columna'])): ?>
        <div class="row"><div class="col col-12"><span class="label">Columna:</span> <?php echo nl2br(htmlspecialchars($reporte['columna'])); ?></div></div>
        <?php endif; ?>
        <?php if (!empty($reporte['torax'])): ?>
        <div class="row"><div class="col col-12"><span class="label">Tórax:</span> <?php echo nl2br(htmlspecialchars($reporte['torax'])); ?></div></div>
        <?php endif; ?>
        <?php if (!empty($reporte['corazon'])): ?>
        <div class="row"><div class="col col-12"><span class="label">Corazón:</span> <?php echo nl2br(htmlspecialchars($reporte['corazon'])); ?></div></div>
        <?php endif; ?>
        <?php if (!empty($reporte['abdomen'])): ?>
        <div class="row"><div class="col col-12"><span class="label">Abdomen:</span> <?php echo nl2br(htmlspecialchars($reporte['abdomen'])); ?></div></div>
        <?php endif; ?>
        <?php if (!empty($reporte['extremidades'])): ?>
        <div class="row"><div class="col col-12"><span class="label">Extremidades:</span> <?php echo nl2br(htmlspecialchars($reporte['extremidades'])); ?></div></div>
        <?php endif; ?>
        <?php if (!empty($reporte['liquido_amniotico'])): ?>
        <div class="row"><div class="col col-12"><span class="label">Líquido Amniótico:</span> <?php echo nl2br(htmlspecialchars($reporte['liquido_amniotico'])); ?></div></div>
        <?php endif; ?>
        <?php if (!empty($reporte['decidua'])): ?>
        <div class="row"><div class="col col-12"><span class="label">Decidua:</span> <?php echo nl2br(htmlspecialchars($reporte['decidua'])); ?></div></div>
        <?php endif; ?>
        <?php if (!empty($reporte['cervix'])): ?>
        <div class="row"><div class="col col-12"><span class="label">Cérvix:</span> <?php echo nl2br(htmlspecialchars($reporte['cervix'])); ?></div></div>
        <?php endif; ?>
    </div>

    <div class="section" style="margin-top: 30px;">
        <div class="row">
            <div class="col col-6">
                <p><strong>Médico:</strong> <?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?></p>
            </div>
            <div class="col col-6 text-center">
                <p><strong>Fecha de impresión:</strong> <?php echo date('d/m/Y H:i'); ?></p>
            </div>
        </div>
    </div>
</body>
</html>
