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

$embrionesPorSaco = [];
foreach ($embriones as $emb) {
    $sid = $emb['saco_id'] ?? 0;
    if (!isset($embrionesPorSaco[$sid])) $embrionesPorSaco[$sid] = [];
    $embrionesPorSaco[$sid][] = $emb;
}
$sacosMostrar = !empty($sacos) ? $sacos : [];
if (empty($sacosMostrar) && !empty($embriones)) {
    $sacosMostrar[] = ['id' => null, 'numero' => 1, 'medida_mm' => $evaluacion['sg_medida_mm'] ?? null, 'morfologia' => $evaluacion['sg_morfologia'] ?? null, 'sv_presente' => $evaluacion['sv_presente'] ?? null, 'sv_diametro_mm' => $evaluacion['sv_diametro_mm'] ?? null, 'descripcion' => null];
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
            echo match(($evaluacion['estado'] ?? 'Pendiente')) {
                'Completado' => 'bg-success',
                'En proceso' => 'bg-warning',
                'Archivado' => 'bg-secondary',
                default => 'bg-info'
            };
        ?>"><?php echo htmlspecialchars($evaluacion['estado'] ?? 'Pendiente'); ?></span>
        <?php if (($evaluacion['estado'] ?? '') === 'Completado'): ?>
            <form method="POST" action="<?php echo Url::to('/ultrasonido_temprano/enviar?id=' . $evaluacion['id']); ?>" style="display:inline;">
                <select name="destinatario" class="form-select form-select-sm" style="width:auto;display:inline;vertical-align:middle;">
                    <option value="">-- Destinatario --</option>
                    <?php if (!empty($evaluacion['paciente_email'])): ?><option value="paciente"><?php echo htmlspecialchars($evaluacion['paciente_nombre'] . ' ' . $evaluacion['paciente_apellido']); ?> (Paciente)</option><?php endif; ?>
                    <?php if (!empty($evaluacion['medico_email'])): ?><option value="medico"><?php echo htmlspecialchars($evaluacion['medico_nombre'] . ' ' . $evaluacion['medico_apellido']); ?> (Médico)</option><?php endif; ?>
                    <?php if (!empty($evaluacion['medico_solicitante_email'])): ?><option value="solicitante"><?php echo htmlspecialchars($evaluacion['medico_solicitante_nombre'] . ' ' . $evaluacion['medico_solicitante_apellido']); ?> (Solicitante)</option><?php endif; ?>
                    <?php if (!empty($evaluacion['medico_referido_email'])): ?><option value="referido"><?php echo htmlspecialchars($evaluacion['medico_referido_nombre'] . ' ' . $evaluacion['medico_referido_apellido']); ?> (Referido)</option><?php endif; ?>
                    <option value="todos">-- Todos --</option>
                </select>
                <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8'); ?>">
                <button type="button" class="btn btn-apple btn-apple-primary" onclick="var f=this.form,d=f.destinatario;if(!d.value){alert('Seleccione un destinatario');return;}if(confirm('¿Enviar a '+d.options[d.selectedIndex].text+'?'))f.submit();">
                    <i class="fa-solid fa-paper-plane"></i> Enviar
                </button>
            </form>
        <?php endif; ?>
        <a href="<?php echo Url::to('/ultrasonido_temprano/edit?id=' . $evaluacion['id']); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-edit"></i> Editar
        </a>
        <a href="<?php echo Url::to('/ultrasonido_temprano/pdf?id=' . $evaluacion['id']); ?>" class="btn btn-apple btn-apple-secondary" target="_blank">
            <i class="fa-solid fa-download"></i> Imprimir
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
                    <?php if (!empty($evaluacion['medico_solicitante_nombre'])): ?>
                    <dt class="col-sm-4">Solicitante</dt><dd class="col-sm-8"><?php echo htmlspecialchars($evaluacion['medico_solicitante_nombre'] . ' ' . $evaluacion['medico_solicitante_apellido']); ?></dd>
                    <?php endif; ?>
                    <?php if (!empty($evaluacion['medico_referido_nombre'])): ?>
                    <dt class="col-sm-4">Referido</dt><dd class="col-sm-8"><?php echo htmlspecialchars($evaluacion['medico_referido_nombre'] . ' ' . $evaluacion['medico_referido_apellido']); ?></dd>
                    <?php endif; ?>
                    <dt class="col-sm-4">Fecha</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['fecha_estudio'] ? date('d/m/Y', strtotime($evaluacion['fecha_estudio'])) : null); ?></dd>
                    <dt class="col-sm-4">FUM</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['fum'] ? date('d/m/Y', strtotime($evaluacion['fum'])) : null); ?></dd>
                    <dt class="col-sm-4">EG por FUM</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['edad_gest_semanas'], 's ') . showVal($evaluacion['edad_gest_dias'], 'd'); ?></dd>
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
                    if (!empty($evaluacion['indic_confirmacion_embarazo'])) $indicaciones[] = 'Confirmación de embarazo';
                    if (!empty($evaluacion['indic_sangrado'])) $indicaciones[] = 'Sangrado transvaginal';
                    if (!empty($evaluacion['indic_dolor_pelvico'])) $indicaciones[] = 'Dolor pélvico';
                    if (!empty($evaluacion['indic_viabilidad'])) $indicaciones[] = 'Valoración de viabilidad';
                    if (!empty($evaluacion['indic_perdidas_gestacionales'])) $indicaciones[] = 'Antecedente de pérdidas';
                    if (!empty($evaluacion['indic_reproduccion_asistida'])) $indicaciones[] = 'Reproducción asistida';
                    if (!empty($evaluacion['indic_otro'])) $indicaciones[] = htmlspecialchars($evaluacion['indic_otro']);
                    echo !empty($indicaciones) ? implode(', ', $indicaciones) : '—';
                    ?>
                </div>
                <h6>Vía de exploración</h6>
                <div>
                    <?php
                    $vias = [];
                    if (!empty($evaluacion['via_transvaginal'])) $vias[] = 'Transvaginal';
                    if (!empty($evaluacion['via_transabdominal'])) $vias[] = 'Transabdominal';
                    if (!empty($evaluacion['via_ambas'])) $vias[] = 'Ambas';
                    echo !empty($vias) ? implode(', ', $vias) : '—';
                    ?>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-uterus me-2"></i>Útero</h5></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Posición</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['utero_posicion'] ?? null); ?></dd>
                    <dt class="col-sm-4">Contornos</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['utero_contornos'] ?? 'Regulares'); ?></dd>
                    <dt class="col-sm-4">Ecogenicidad conservada</dt><dd class="col-sm-8"><?php echo badgeSiNo($evaluacion['utero_ecogenicidad_conservada'] ?? null); ?></dd>
                    <dt class="col-sm-4">Dimensiones</dt>
                    <dd class="col-sm-8"><?php echo showVal($evaluacion['utero_dim_x'] ?? null, ' x '); ?><?php echo showVal($evaluacion['utero_dim_y'] ?? null, ' x '); ?><?php echo showVal($evaluacion['utero_dim_z'] ?? null, ' mm'); ?></dd>
                    <dt class="col-sm-4">Endometrio</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['endometrio'] ?? null); ?></dd>
                </dl>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-note-sticky me-2"></i>Decidua</h5></div>
            <div class="card-body">
                <p class="mb-0"><?php echo !empty($evaluacion['decidua']) ? nl2br(htmlspecialchars($evaluacion['decidua'])) : '—'; ?></p>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-baby me-2"></i>Saco Gestacional y Vitelino</h5></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Localización</dt>
                    <dd class="col-sm-8">
                        <?php echo showVal($evaluacion['localizacion'] ?? null); ?>
                        <?php if (($evaluacion['localizacion'] ?? '') === 'Otra' && !empty($evaluacion['localizacion_otra'])): ?> (<?php echo htmlspecialchars($evaluacion['localizacion_otra']); ?>)<?php endif; ?>
                    </dd>
                    <dt class="col-sm-4">Saco gestacional</dt>
                    <dd class="col-sm-8">
                        <?php echo showVal($evaluacion['sg_tipo'] ?? null, ' / '); ?>
                        <?php echo showVal($evaluacion['sg_morfologia'] ?? null, ' / '); ?>
                        <?php echo showVal($evaluacion['sg_medida_mm'] ?? null, ' mm'); ?>
                        <?php if (!empty($evaluacion['sg_cantidad']) && $evaluacion['sg_cantidad'] > 1): ?>
                            (<?php echo $evaluacion['sg_cantidad']; ?> sacos)
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-4">Saco vitelino</dt>
                    <dd class="col-sm-8">
                        <?php if (($evaluacion['sv_presente'] ?? null) !== null): ?>
                            <?php echo $evaluacion['sv_presente'] ? 'Presente' : 'Ausente'; ?>
                            <?php if ($evaluacion['sv_presente']): ?> (<?php echo showVal($evaluacion['sv_cantidad'] ?? null); ?>, <?php echo showVal($evaluacion['sv_diametro_mm'] ?? null, ' mm'); ?>)<?php endif; ?>
                        <?php else: ?>—<?php endif; ?>
                    </dd>
                    <dt class="col-sm-4">Viabilidad</dt>
                    <dd class="col-sm-8">
                        <?php
                        $viab = $evaluacion['viabilidad'] ?? null;
                        if ($viab === 'Viable') echo '<span class="badge bg-success">Viable</span>';
                        elseif ($viab === 'No viable') echo '<span class="badge bg-danger">No viable</span>';
                        elseif ($viab === 'Incierto') echo '<span class="badge bg-warning text-dark">Incierto</span>';
                        else echo '—';
                        ?>
                    </dd>
                    <dt class="col-sm-4">Corion y Amnios</dt><dd class="col-sm-8"><?php echo badgeSiNo($evaluacion['corion_amnios_normal'] ?? null); ?></dd>
                </dl>
            </div>
        </div>

        <?php if (!empty($sacosMostrar)): ?>
        <div class="card mt-4">
            <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-circle me-2"></i>Detalle por Saco Gestacional</h5></div>
            <div class="card-body">
                <?php foreach ($sacosMostrar as $idx => $saco):
                    $sacoId = $saco['id'] ?? null;
                    $sacoEmbs = $sacoId ? ($embrionesPorSaco[$sacoId] ?? []) : ($idx == 0 ? ($embrionesPorSaco[0] ?? $embriones) : []);
                ?>
                <div class="mb-3 <?php echo $idx < count($sacosMostrar) - 1 ? 'border-bottom pb-3' : ''; ?>">
                    <h6 class="fw-bold mb-2">Saco Gestacional #<?php echo htmlspecialchars($saco['numero'] ?? ($idx + 1)); ?></h6>
                    <dl class="row mb-2 small">
                        <dt class="col-sm-4">Medida</dt><dd class="col-sm-8"><?php echo showVal($saco['medida_mm'] ?? null, ' mm'); ?></dd>
                        <dt class="col-sm-4">Morfología</dt><dd class="col-sm-8"><?php echo showVal($saco['morfologia'] ?? null); ?></dd>
                        <dt class="col-sm-4">Saco vitelino</dt>
                        <dd class="col-sm-8">
                            <?php if (($saco['sv_presente'] ?? null) !== null): ?>
                                <?php echo $saco['sv_presente'] ? 'Presente' : 'Ausente'; ?>
                                <?php if ($saco['sv_presente']): ?> (<?php echo showVal($saco['sv_diametro_mm'] ?? null, ' mm'); ?>)<?php endif; ?>
                            <?php else: ?>—<?php endif; ?>
                        </dd>
                    </dl>
                    <?php if (!empty($sacoEmbs)): ?>
                    <?php foreach ($sacoEmbs as $emb): ?>
                    <div class="bg-light rounded p-2 mb-1">
                        <small class="fw-bold text-muted">Embrión #<?php echo htmlspecialchars($emb['numero'] ?? ''); ?></small>
                        <dl class="row mb-0 small">
                            <dt class="col-sm-4">CRL</dt><dd class="col-sm-8"><?php echo showVal($emb['crl_mm'] ?? null, ' mm'); ?></dd>
                            <dt class="col-sm-4">FCF visible</dt><dd class="col-sm-8"><?php echo badgeSiNo($emb['fcf_visible'] ?? null); ?></dd>
                            <dt class="col-sm-4">FCF</dt><dd class="col-sm-8"><?php echo showVal($emb['fcf_lpm'] ?? null, ' lpm'); ?></dd>
                            <dt class="col-sm-4">Localización</dt><dd class="col-sm-8"><?php echo showVal($emb['localizacion'] ?? null); ?></dd>
                        </dl>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
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
                    <dt class="col-sm-4">Dimensiones</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['ovario_der_dim_x'] ?? null, ' x '); ?><?php echo showVal($evaluacion['ovario_der_dim_y'] ?? null, ' x '); ?><?php echo showVal($evaluacion['ovario_der_dim_z'] ?? null, ' mm'); ?></dd>
                    <dt class="col-sm-4">Estado</dt>
                    <dd class="col-sm-8">
                        <?php if (!empty($evaluacion['ovario_der_normal'])): ?>Normal<?php endif; ?>
                        <?php if (!empty($evaluacion['ovario_der_cuerpo_luteo_mm'])): ?> Cuerpo lúteo <?php echo $evaluacion['ovario_der_cuerpo_luteo_mm']; ?> mm<?php endif; ?>
                        <?php if (!empty($evaluacion['ovario_der_quiste_simple_mm'])): ?> Quiste simple <?php echo $evaluacion['ovario_der_quiste_simple_mm']; ?> mm<?php endif; ?>
                        <?php if (!empty($evaluacion['ovario_der_otra_alteracion'])): ?> <?php echo htmlspecialchars($evaluacion['ovario_der_otra_alteracion']); ?><?php endif; ?>
                        <?php if (empty($evaluacion['ovario_der_normal']) && empty($evaluacion['ovario_der_cuerpo_luteo_mm']) && empty($evaluacion['ovario_der_quiste_simple_mm']) && empty($evaluacion['ovario_der_otra_alteracion'])): ?>—<?php endif; ?>
                    </dd>
                </dl>
                <h6 class="text-muted">Ovario Izquierdo</h6>
                <dl class="row mb-0">
                    <dt class="col-sm-4">Dimensiones</dt><dd class="col-sm-8"><?php echo showVal($evaluacion['ovario_izq_dim_x'] ?? null, ' x '); ?><?php echo showVal($evaluacion['ovario_izq_dim_y'] ?? null, ' x '); ?><?php echo showVal($evaluacion['ovario_izq_dim_z'] ?? null, ' mm'); ?></dd>
                    <dt class="col-sm-4">Estado</dt>
                    <dd class="col-sm-8">
                        <?php if (!empty($evaluacion['ovario_izq_normal'])): ?>Normal<?php endif; ?>
                        <?php if (!empty($evaluacion['ovario_izq_cuerpo_luteo_mm'])): ?> Cuerpo lúteo <?php echo $evaluacion['ovario_izq_cuerpo_luteo_mm']; ?> mm<?php endif; ?>
                        <?php if (!empty($evaluacion['ovario_izq_quiste_simple_mm'])): ?> Quiste simple <?php echo $evaluacion['ovario_izq_quiste_simple_mm']; ?> mm<?php endif; ?>
                        <?php if (!empty($evaluacion['ovario_izq_otra_alteracion'])): ?> <?php echo htmlspecialchars($evaluacion['ovario_izq_otra_alteracion']); ?><?php endif; ?>
                        <?php if (empty($evaluacion['ovario_izq_normal']) && empty($evaluacion['ovario_izq_cuerpo_luteo_mm']) && empty($evaluacion['ovario_izq_quiste_simple_mm']) && empty($evaluacion['ovario_izq_otra_alteracion'])): ?>—<?php endif; ?>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-magnifying-glass me-2"></i>Hallazgos</h5></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-2">Fondo de saco Douglas</dt><dd class="col-sm-10"><?php echo showVal($evaluacion['douglas'] ?? null); ?></dd>
                </dl>
                <?php if (!empty($evaluacion['hematoma_subcorionico'])): ?>
                <hr>
                <h6 class="fw-bold">Hematoma subcoriónico</h6>
                <dl class="row mb-0">
                    <dt class="col-sm-2">Localización</dt><dd class="col-sm-10"><?php echo showVal($evaluacion['hematoma_localizacion'] ?? null); ?></dd>
                    <dt class="col-sm-2">Dimensiones</dt><dd class="col-sm-10"><?php echo showVal($evaluacion['hematoma_dim_x'] ?? null, ' x '); ?><?php echo showVal($evaluacion['hematoma_dim_y'] ?? null, ' x '); ?><?php echo showVal($evaluacion['hematoma_dim_z'] ?? null, ' mm'); ?></dd>
                    <dt class="col-sm-2">Volumen</dt><dd class="col-sm-10"><?php echo showVal($evaluacion['hematoma_volumen_ml'] ?? null, ' ml'); ?></dd>
                </dl>
                <?php endif; ?>
                <div class="mt-2">
                    <?php if (!empty($evaluacion['miomas_uterinos'])): ?><span class="badge bg-warning me-1">Miomas uterinos</span><?php endif; ?>
                    <?php if (!empty($evaluacion['adenomiosis'])): ?><span class="badge bg-warning me-1">Adenomiosis</span><?php endif; ?>
                    <?php if (!empty($evaluacion['malformacion_uterina'])): ?><span class="badge bg-warning me-1">Malformación uterina</span><?php endif; ?>
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
            <dt class="col-sm-2">CRL</dt><dd class="col-sm-4"><?php echo showVal($evaluacion['impresion_crl_mm'] ?? null, ' mm'); ?></dd>
            <dt class="col-sm-2">EG correspondiente</dt><dd class="col-sm-4"><?php echo showVal($evaluacion['impresion_semanas'] ?? null, 's ') . showVal($evaluacion['impresion_dias'] ?? null, 'd'); ?></dd>
            <dt class="col-sm-2">FCF</dt><dd class="col-sm-4"><?php echo showVal($evaluacion['impresion_fcf_lpm'] ?? null, ' lpm'); ?></dd>
        </dl>
        <?php if (!empty($evaluacion['impresion_texto'])): ?>
            <hr>
            <p class="mb-0"><?php echo nl2br(htmlspecialchars($evaluacion['impresion_texto'])); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
