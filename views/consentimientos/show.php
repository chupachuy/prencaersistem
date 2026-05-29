<?php
$title = "Ver Consentimiento";
$datosDinamicos = $consentimiento['datos_dinamicos'] ? json_decode($consentimiento['datos_dinamicos'], true) : [];
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/consentimientos'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0"><?php echo htmlspecialchars($consentimiento['nombre_documento']); ?></h1>
    </div>
    <div class="page-header-actions">
        <?php
        $estadoClass = match($consentimiento['estado']) {
            'Completado' => 'success',
            'Parcialmente Firmado' => 'warning',
            'Revocado' => 'danger',
            default => 'info'
        };
        ?>
        <span class="badge bg-<?php echo $estadoClass; ?> me-2"><?php echo htmlspecialchars($consentimiento['estado']); ?></span>
        <a href="<?php echo Url::to('/consentimientos/print?id=' . $consentimiento['id']); ?>" class="btn btn-apple btn-apple-secondary" target="_blank">
            <i class="fa-solid fa-print"></i> PDF
        </a>
        <?php if ($consentimiento['estado'] !== 'Completado' && $consentimiento['estado'] !== 'Revocado'): ?>
        <a href="<?php echo Url::to('/consentimientos/firmar?id=' . $consentimiento['id']); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-pen-to-square"></i> Firmar
        </a>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-id-card me-2"></i> Datos del Consentimiento
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Documento:</div>
                    <div class="col-md-8"><?php echo htmlspecialchars($consentimiento['nombre_documento']); ?>
                        <?php if ($consentimiento['version']): ?>
                            <small class="text-muted">(<?php echo htmlspecialchars($consentimiento['version']); ?>)</small>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Fecha:</div>
                    <div class="col-md-8"><?php echo date('d/m/Y H:i', strtotime($consentimiento['fecha_generacion'])); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Estado:</div>
                    <div class="col-md-8">
                        <span class="badge bg-<?php echo $estadoClass; ?>"><?php echo htmlspecialchars($consentimiento['estado']); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-user me-2"></i> Paciente y Médico
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Paciente:</div>
                    <div class="col-md-8"><?php echo htmlspecialchars($consentimiento['paciente_nombre'] . ' ' . $consentimiento['paciente_apellido']); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Médico:</div>
                    <div class="col-md-8"><?php echo htmlspecialchars($consentimiento['medico_nombre'] . ' ' . $consentimiento['medico_apellido']); ?></div>
                </div>
                <?php if ($consentimiento['medico_telefono']): ?>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Teléfono:</div>
                    <div class="col-md-8"><?php echo htmlspecialchars($consentimiento['medico_telefono']); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($consentimiento['contenido'])): ?>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-file-lines me-2"></i> Contenido del Consentimiento
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto; font-size: 14px; line-height: 1.7;">
                <?php echo $consentimiento['contenido']; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($datosDinamicos): ?>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-hashtag me-2"></i> Redes Sociales
            </div>
            <div class="card-body">
                <?php if (!empty($datosDinamicos['facebook'])): ?>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold"><i class="fa-brands fa-facebook me-1"></i> Facebook:</div>
                    <div class="col-md-8"><?php echo htmlspecialchars($datosDinamicos['facebook']); ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($datosDinamicos['instagram'])): ?>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold"><i class="fa-brands fa-instagram me-1"></i> Instagram:</div>
                    <div class="col-md-8"><?php echo htmlspecialchars($datosDinamicos['instagram']); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-pen-to-square me-2"></i> Registro de Firmas
            </div>
            <div class="card-body">
                <?php if (!empty($firmas)): ?>
                    <?php foreach ($firmas as $firma): ?>
                        <div class="card mb-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong><?php echo htmlspecialchars($firma['rol_firmante']); ?></strong>
                                        <span class="badge bg-<?php echo $firma['tipo_accion'] === 'Denegacion' ? 'danger' : 'success'; ?> ms-2">
                                            <?php echo htmlspecialchars($firma['tipo_accion']); ?>
                                        </span>
                                        <br>
                                        <small class="text-muted"><?php echo htmlspecialchars($firma['nombre_firmante']); ?></small>
                                        <br>
                                        <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($firma['fecha_firma'])); ?></small>
                                        <br>
                                        <small class="text-muted">IP: <?php echo htmlspecialchars($firma['ip_origen']); ?></small>
                                        <?php if (!empty($firma['geo_pais'])): ?>
                                        <br>
                                        <small class="text-muted"><i class="fa-solid fa-location-dot"></i> 
                                            <?php echo htmlspecialchars($firma['geo_ciudad'] . ', ' . $firma['geo_region'] . ', ' . $firma['geo_pais']); ?>
                                            <?php if (!empty($firma['geo_proveedor'])): ?> — <?php echo htmlspecialchars($firma['geo_proveedor']); ?><?php endif; ?>
                                        </small>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <img src="<?php echo Url::to($firma['ruta_imagen_firma']); ?>" 
                                             alt="Firma" style="max-width:180px; max-height:70px; border:1px solid #ddd;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center">No hay firmas registradas aún.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
