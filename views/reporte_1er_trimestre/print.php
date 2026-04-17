<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte 1er Trimestre - <?php echo $consulta['paciente_nombre'] ?? ''; ?></title>
    <?php
    $meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    $fecha_creacion = date('j') . ' de ' . $meses[date('n')] . ' del ' . date('Y');
    ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12pt; }
            .card { border: 1px solid #ddd !important; }
            .card-header { background: #f8f9fa !important; -webkit-print-color-adjust: exact; }
        }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card-header { background: #e9ecef; font-weight: 600; }
        .header-title { font-size: 18pt; font-weight: bold; color: #2c3e50; }
        .section-title { font-size: 12pt; font-weight: bold; color: #495057; border-bottom: 2px solid #dee2e6; padding-bottom: 5px; margin-bottom: 15px; }
        .label-field { font-weight: 600; color: #6c757d; font-size: 10pt; }
        .value-field { font-size: 11pt; color: #212529; }
        .checkbox-label { font-size: 11pt; }
        .checked { color: #198754; }
        .unchecked { color: #dc3545; }
        .footer-report { margin-top: 30px; padding-top: 15px; border-top: 1px solid #dee2e6; font-size: 10pt; color: #6c757d; }
    </style>
</head>
<body>
    <div class="no-print mb-3 py-3 bg-light border-bottom">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="<?php echo Url::to('/consultas'); ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fa-solid fa-print"></i> Imprimir / Guardar PDF
                </button>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1 class="header-title">REPORTE 1ER TRIMESTRE</h1>
                <p class="text-muted mb-0"><?php echo $fecha_creacion; ?></p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <p><span class="label-field">Paciente:</span> <span class="value-field fw-bold"><?php echo htmlspecialchars($consulta['paciente_nombre'] ?? ''); ?></span></p>
            </div>
            <div class="col-md-6">
                <p><span class="label-field">Fecha de Consulta:</span> <span class="value-field"><?php echo $consulta['fecha_consulta'] ? date('d/m/Y', strtotime($consulta['fecha_consulta'])) : ''; ?></span></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa-solid fa-calendar me-2"></i> Fechas Obstétricas
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-6"><span class="label-field">Fecha Última Regla (FUR):</span></div>
                            <div class="col-6 value-field"><?php echo $reporte['fecha_ultima_regla'] ? date('d/m/Y', strtotime($reporte['fecha_ultima_regla'])) : '-'; ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6"><span class="label-field">FPP calculada por FUR:</span></div>
                            <div class="col-6 value-field"><?php echo $reporte['fpp_fum'] ? date('d/m/Y', strtotime($reporte['fpp_fum'])) : '-'; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-6"><span class="label-field">FPP calculada por USG:</span></div>
                            <div class="col-6 value-field"><?php echo $reporte['fpp_usg'] ? date('d/m/Y', strtotime($reporte['fpp_usg'])) : '-'; ?></div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa-solid fa-ruler me-2"></i> Medidas Ecográficas
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-6"><span class="label-field">Longitud Craneo-Cauda:</span></div>
                            <div class="col-6 value-field"><?php echo $reporte['longitud_craneo_cauda_mm'] ?? '-'; ?> mm</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6"><span class="label-field">Translucencia Nucal:</span></div>
                            <div class="col-6 value-field"><?php echo $reporte['translucencia_nucal_mm'] ?? '-'; ?> mm</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6"><span class="label-field">Frecuencia Cardíaca Fetal:</span></div>
                            <div class="col-6 value-field"><?php echo $reporte['frecuencia_cardiaca_fetal'] ?? '-'; ?> lpm</div>
                        </div>
                        <div class="row">
                            <div class="col-6"><span class="label-field">Longitud Cervical:</span></div>
                            <div class="col-6 value-field"><?php echo $reporte['longitud_cervical_mm'] ?? '-'; ?> mm</div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> Evaluaciones de Riesgo
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-6"><span class="label-field">Riesgo Cromosomopatías:</span></div>
                            <div class="col-6 value-field"><?php echo $reporte['riesgo_cromosomopatias'] ?? '-'; ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6"><span class="label-field">Riesgo Placentaria Temprana:</span></div>
                            <div class="col-6 value-field"><?php echo $reporte['riesgo_placentaria_temprana'] ?? '-'; ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6"><span class="label-field">Riesgo Placentaria Tardía:</span></div>
                            <div class="col-6 value-field"><?php echo $reporte['riesgo_placentaria_tardia'] ?? '-'; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-6"><span class="label-field">Riesgo Parto Pretermino:</span></div>
                            <div class="col-6 value-field"><?php echo $reporte['riesgo_parto_pretermino'] ?? '-'; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa-solid fa-child me-2"></i> Exploración Anatómica
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-8"><span class="checkbox-label">Hueso Nasal Presente</span></div>
                            <div class="col-4">
                                <?php if ($reporte['hueso_nasal_presente']): ?>
                                    <span class="checked"><i class="fa-solid fa-check-circle"></i> Normal</span>
                                <?php else: ?>
                                    <span class="unchecked"><i class="fa-solid fa-times-circle"></i> Anormal</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-8"><span class="checkbox-label">Anatomía Craneo/SNC</span></div>
                            <div class="col-4">
                                <?php if ($reporte['anatomia_craneo_snc_normal']): ?>
                                    <span class="checked"><i class="fa-solid fa-check-circle"></i> Normal</span>
                                <?php else: ?>
                                    <span class="unchecked"><i class="fa-solid fa-times-circle"></i> Anormal</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-8"><span class="checkbox-label">Anatomía Corazón</span></div>
                            <div class="col-4">
                                <?php if ($reporte['anatomia_corazon_normal']): ?>
                                    <span class="checked"><i class="fa-solid fa-check-circle"></i> Normal</span>
                                <?php else: ?>
                                    <span class="unchecked"><i class="fa-solid fa-times-circle"></i> Anormal</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-8"><span class="checkbox-label">Anatomía Abdomen</span></div>
                            <div class="col-4">
                                <?php if ($reporte['anatomia_abdomen_normal']): ?>
                                    <span class="checked"><i class="fa-solid fa-check-circle"></i> Normal</span>
                                <?php else: ?>
                                    <span class="unchecked"><i class="fa-solid fa-times-circle"></i> Anormal</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8"><span class="checkbox-label">Anatomía Extremidades</span></div>
                            <div class="col-4">
                                <?php if ($reporte['anatomia_extremidades_normal']): ?>
                                    <span class="checked"><i class="fa-solid fa-check-circle"></i> Normal</span>
                                <?php else: ?>
                                    <span class="unchecked"><i class="fa-solid fa-times-circle"></i> Anormal</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa-solid fa-place-of-worship me-2"></i> Placenta y Líquido Amniótico
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-6"><span class="label-field">Localización Placenta:</span></div>
                            <div class="col-6 value-field"><?php echo $reporte['localizacion_placenta'] ?? '-'; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-6"><span class="label-field">Líquido Amniótico:</span></div>
                            <div class="col-6">
                                <?php if ($reporte['liquido_amniotico_normal']): ?>
                                    <span class="checked"><i class="fa-solid fa-check-circle"></i> Normal</span>
                                <?php else: ?>
                                    <span class="unchecked"><i class="fa-solid fa-times-circle"></i> Anormal</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($reporte['observaciones_comentarios'])): ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa-solid fa-comment-medical me-2"></i> Observaciones
                    </div>
                    <div class="card-body">
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($reporte['observaciones_comentarios'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer-report">
            <div class="row">
                <div class="col-6">
                    <p class="mb-1">Doctor: <?php echo htmlspecialchars($user['nombre'] ?? ''); ?></p>
                </div>
                <div class="col-6 text-end">
                    <p class="mb-1">Firma: ___________________________</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
