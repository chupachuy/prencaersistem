<?php
$title = "Ver Ultrasonido Temprano";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

function badgeSiNo($val) {
    if ($val === null) return '<span class="badge bg-secondary">—</span>';
    return $val ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-danger">No</span>';
}
function showVal($val, $suffix = '') {
    if ($val === null || $val === '') return '—';
    return htmlspecialchars($val) . $suffix;
}
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/ultrasonido_temprano'); ?>" class="btn btn-light rounded-3">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="page-title mb-0"><?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></h1>
    </div>
    <div class="page-header-actions">
        <span class="badge fs-6 <?php
            echo match($evaluacion['estado']) {
                'Completado' => 'bg-success',
                'En proceso' => 'bg-warning',
                'Archivado' => 'bg-secondary',
                default => 'bg-info'
            };
        ?>"><?php echo htmlspecialchars($evaluacion['estado']); ?></span>
        <a href="<?php echo Url::to('/ultrasonido_temprano/edit?id=' . $evaluacion['id']); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-edit"></i> Editar
        </a>
        <a href="<?php echo Url::to('/ultrasonido_temprano/print?id=' . $evaluacion['id']); ?>" class="btn btn-apple btn-apple-secondary" target="_blank">
            <i class="fa-solid fa-print"></i> Imprimir
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-user me-2"></i>Datos Generales</h5></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Paciente</dt><dd class="col-sm-8"><?php echo htmlspecialchars($evaluacion['paciente_nombre'] . ' ' . $evaluacion['paciente_apellido']); ?></dd>
                    <dt class="col-sm-4">Médico</dt><dd class="col-sm-8"><?php echo htmlspecialchars($evaluacion['medico_nombre'] . ' ' . $evaluacion['medico_apellido']); ?></dd>
                    <dt class="col-sm-4">Fecha</dt><dd class="col-sm-8"><?php echo showVal(date('d/m/Y', strtotime($evaluacion['fecha_estudio']))); ?></dd>
                    <dt class="col-sm-4">FUM</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['fum'] ? date('d/m/Y', strtotime($evaluacion['fum'])) : null); ?></dd>
                    <dt class="col-sm-4">EG por FUM</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['edad_gest_semanas'], 's') . ' ' . showVal($evaluacion['edad_gest_dias'], 'd'); ?></dd>
                </dl>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-stethoscope me-2"></i>Indicación y Vía</h5></div>
            <div class="card-body">
                <h6>Indicación del estudio</h6>
                <div class="mb-3">
                    <?php
                    $indicaciones = [];
                    if ($evaluacion['indic_confirmacion_embarazo']) $indicaciones[] = 'Confirmación de embarazo';
                    if ($evaluacion['indic_sangrado']) $indicaciones[] = 'Sangrado transvaginal';
                    if ($evaluacion['indic_dolor_pelvico']) $indicaciones[] = 'Dolor pélvico';
                    if ($evaluacion['indic_viabilidad']) $indicaciones[] = 'Valoración de viabilidad';
                    if ($evaluacion['indic_perdidas_gestacionales']) $indicaciones[] = 'Antecedente de pérdidas';
                    if ($evaluacion['indic_reproduccion_asistida']) $indicaciones[] = 'Reproducción asistida';
                    if (!empty($evaluacion['indic_otro'])) $indicaciones[] = htmlspecialchars($evaluacion['indic_otro']);
                    echo !empty($indicaciones) ? implode(', ', $indicaciones) : '—';
                    ?>
                </div>
                <h6>Vía de exploración</h6>
                <div>
                    <?php
                    $vias = [];
                    if ($evaluacion['via_transvaginal']) $vias[] = 'Transvaginal';
                    if ($evaluacion['via_transabdominal']) $vias[] = 'Transabdominal';
                    if ($evaluacion['via_ambas']) $vias[] = 'Ambas';
                    echo !empty($vias) ? implode(', ', $vias) : '—';
                    ?>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-uterus me-2"></i>Útero</h5></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Posición</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['utero_posicion']); ?></dd>
                    <dt class="col-sm-4">Contornos regulares</dt><dd class="col-sm-8"><?php echo badgeSiNo($evaluacion['utero_contornos_regulares']); ?></dd>
                    <dt class="col-sm-4">Ecogenicidad conservada</dt><dd class="col-sm-8"><?php echo badgeSiNo($evaluacion['utero_ecogenicidad_conservada']); ?></dd>
                    <dt class="col-sm-4">Dimensiones</dt>
                    <dd class="col-sm-8"><?php echo showVal($evaluacion['utero_dim_x'], ' x '); ?><?php echo showVal($evaluacion['utero_dim_y'], ' x '); ?><?php echo showVal($evaluacion['utero_dim_z'], ' mm'); ?></dd>
                    <dt class="col-sm-4">Endometrio</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['endometrio']); ?></dd>
                </dl>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-baby me-2"></i>Saco Gestacional y Vitelino</h5></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Localización</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['localizacion']); ?><?php if ($evaluacion['localizacion'] === 'Otra' && $evaluacion['localizacion_otra']): ?> (<?php echo htmlspecialchars($evaluacion['localizacion_otra']); ?>)<?php endif; ?></dd>
                    <dt class="col-sm-4">Saco gestacional</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['sg_tipo']); ?> / <?php echo showVal($evaluacion['sg_morfologia']); ?> / <?php echo showVal($evaluacion['sg_medida_mm'], ' mm'); ?></dd>
                    <dt class="col-sm-4">Saco vitelino</dt><dd class="col-sm-8"><?php echo $evaluacion['sv_presente'] !== null ? ($evaluacion['sv_presente'] ? 'Presente' : 'Ausente') : '—'; ?><?php if ($evaluacion['sv_presente']): ?> (<?php echo showVal($evaluacion['sv_cantidad']); ?>, <?php echo showVal($evaluacion['sv_diametro_mm'], ' mm'); ?>)<?php endif; ?></dd>
                    <dt class="col-sm-4">Corion y Amnios</dt><dd class="col-sm-8"><?php echo badgeSiNo($evaluacion['corion_amnios_normal']); ?></dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <?php if (!empty($embriones)): ?>
        <div class="card">
            <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-heart-pulse me-2"></i>Embriones</h5></div>
            <div class="card-body">
                <?php foreach ($embriones as $i => $emb): ?>
                    <div class="mb-3 <?php echo $i < count($embriones) - 1 ? 'border-bottom pb-3' : ''; ?>">
                        <h6 class="fw-bold mb-2">Embrión #<?php echo $emb['numero']; ?></h6>
                        <dl class="row mb-0 small">
                            <dt class="col-sm-4">CRL</dt><dd class="col-sm-8"><?php echo showVal($emb['crl_mm'], ' mm'); ?></dd>
                            <dt class="col-sm-4">FCF visible</dt><dd class="col-sm-8"><?php echo badgeSiNo($emb['fcf_visible']); ?></dd>
                            <dt class="col-sm-4">FCF</dt><dd class="col-sm-8"><?php echo showVal($emb['fcf_lpm'], ' lpm'); ?></dd>
                            <dt class="col-sm-4">Localización</dt><dd class="col-sm-8"><?php echo showVal($emb['localizacion']); ?></dd>
                        </dl>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="card mt-4">
            <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-venus-mars me-2"></i>Anexos</h5></div>
            <div class="card-body">
                <h6 class="text-muted">Ovario Derecho</h6>
                <dl class="row mb-3">
                    <dt class="col-sm-4">Dimensiones</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['ovario_der_dim_x'], ' x '); ?><?php echo showVal($evaluacion['ovario_der_dim_y'], ' x '); ?><?php echo showVal($evaluacion['ovario_der_dim_z'], ' mm'); ?></dd>
                    <dt class="col-sm-4">Estado</dt>
                    <dd class="col-sm-8">
                        <?php if ($evaluacion['ovario_der_normal']): ?>Normal<?php endif; ?>
                        <?php if ($evaluacion['ovario_der_cuerpo_luteo_mm']): ?>Cuerpo lúteo <?php echo $evaluacion['ovario_der_cuerpo_luteo_mm']; ?> mm<?php endif; ?>
                        <?php if ($evaluacion['ovario_der_quiste_simple_mm']): ?>Quiste simple <?php echo $evaluacion['ovario_der_quiste_simple_mm']; ?> mm<?php endif; ?>
                        <?php if (!empty($evaluacion['ovario_der_otra_alteracion'])): ?><?php echo htmlspecialchars($evaluacion['ovario_der_otra_alteracion']); ?><?php endif; ?>
                        <?php if (!$evaluacion['ovario_der_normal'] && !$evaluacion['ovario_der_cuerpo_luteo_mm'] && !$evaluacion['ovario_der_quiste_simple_mm'] && empty($evaluacion['ovario_der_otra_alteracion'])): ?>—<?php endif; ?>
                    </dd>
                </dl>
                <h6 class="text-muted">Ovario Izquierdo</h6>
                <dl class="row mb-0">
                    <dt class="col-sm-4">Dimensiones</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['ovario_izq_dim_x'], ' x '); ?><?php echo showVal($evaluacion['ovario_izq_dim_y'], ' x '); ?><?php echo showVal($evaluacion['ovario_izq_dim_z'], ' mm'); ?></dd>
                    <dt class="col-sm-4">Estado</dt>
                    <dd class="col-sm-8">
                        <?php if ($evaluacion['ovario_izq_normal']): ?>Normal<?php endif; ?>
                        <?php if ($evaluacion['ovario_izq_cuerpo_luteo_mm']): ?>Cuerpo lúteo <?php echo $evaluacion['ovario_izq_cuerpo_luteo_mm']; ?> mm<?php endif; ?>
                        <?php if ($evaluacion['ovario_izq_quiste_simple_mm']): ?>Quiste simple <?php echo $evaluacion['ovario_izq_quiste_simple_mm']; ?> mm<?php endif; ?>
                        <?php if (!empty($evaluacion['ovario_izq_otra_alteracion'])): ?><?php echo htmlspecialchars($evaluacion['ovario_izq_otra_alteracion']); ?><?php endif; ?>
                        <?php if (!$evaluacion['ovario_izq_normal'] && !$evaluacion['ovario_izq_cuerpo_luteo_mm'] && !$evaluacion['ovario_izq_quiste_simple_mm'] && empty($evaluacion['ovario_izq_otra_alteracion'])): ?>—<?php endif; ?>
                    </dd>
                </dl>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-magnifying-glass me-2"></i>Hallazgos</h5></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Fondo de saco Douglas</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['douglas']); ?></dd>
                </dl>
                <?php if ($evaluacion['hematoma_subcorionico']): ?>
                <hr>
                <h6 class="fw-bold">Hematoma subcoriónico</h6>
                <dl class="row mb-0">
                    <dt class="col-sm-4">Localización</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['hematoma_localizacion']); ?></dd>
                    <dt class="col-sm-4">Dimensiones</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['hematoma_dim_x'], ' x '); ?><?php echo showVal($evaluacion['hematoma_dim_y'], ' x '); ?><?php echo showVal($evaluacion['hematoma_dim_z'], ' mm'); ?></dd>
                    <dt class="col-sm-4">Volumen</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['hematoma_volumen_ml'], ' ml'); ?></dd>
                </dl>
                <?php endif; ?>
                <div class="mt-2">
                    <?php if ($evaluacion['miomas_uterinos']): ?><span class="badge bg-warning me-1">Miomas uterinos</span><?php endif; ?>
                    <?php if ($evaluacion['adenomiosis']): ?><span class="badge bg-warning me-1">Adenomiosis</span><?php endif; ?>
                    <?php if ($evaluacion['malformacion_uterina']): ?><span class="badge bg-warning me-1">Malformación uterina</span><?php endif; ?>
                    <?php if (!empty($evaluacion['hallazgos_otro'])): ?><p class="mt-2 mb-0"><strong>Otros:</strong> <?php echo nl2br(htmlspecialchars($evaluacion['hallazgos_otro'])); ?></p><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-file-lines me-2"></i>Impresión Diagnóstica</h5></div>
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-2">CRL</dt><dd class="col-sm-4"><?php echo showVal($evaluacion['impresion_crl_mm'], ' mm'); ?></dd>
            <dt class="col-sm-2">EG correspondiente</dt><dd class="col-sm-4"><?php echo showVal($evaluacion['impresion_semanas'], 's ') . showVal($evaluacion['impresion_dias'], 'd'); ?></dd>
            <dt class="col-sm-2">FCF</dt><dd class="col-sm-4"><?php echo showVal($evaluacion['impresion_fcf_lpm'], ' lpm'); ?></dd>
        </dl>
        <?php if (!empty($evaluacion['impresion_texto'])): ?>
            <hr>
            <p class="mb-0"><?php echo nl2br(htmlspecialchars($evaluacion['impresion_texto'])); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
