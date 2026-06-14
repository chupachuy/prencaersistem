<?php
$title = "Editar Ultrasonido Temprano";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$chk = function($field) use ($evaluacion) { return !empty($evaluacion[$field]) ? 'checked' : ''; };
$sel = function($field, $value) use ($evaluacion) { return ($evaluacion[$field] ?? '') == $value ? 'selected' : ''; };
$selRadio = function($field, $value) use ($evaluacion) { return ($evaluacion[$field] ?? '') === $value || ($evaluacion[$field] ?? '') == $value ? 'checked' : ''; };
$val = function($field) use ($evaluacion) { return htmlspecialchars($evaluacion[$field] ?? ''); };

$sgCantidad = max(1, intval($evaluacion['sg_cantidad'] ?? 1));
$sgTipo = $evaluacion['sg_tipo'] ?? 'Unico';
if (empty($sacos)) {
    $sacos = [];
    for ($s = 1; $s <= $sgCantidad; $s++) {
        $sacos[] = [
            'id' => null, 'numero' => $s,
            'medida_mm' => $s == 1 ? ($evaluacion['sg_medida_mm'] ?? null) : null,
            'morfologia' => $s == 1 ? ($evaluacion['sg_morfologia'] ?? null) : null,
            'sv_presente' => null, 'sv_diametro_mm' => null, 'descripcion' => null
        ];
    }
}

$embrionesPorSaco = [];
foreach ($embriones as $emb) {
    $sid = $emb['saco_id'] ?? 0;
    if (!isset($embrionesPorSaco[$sid])) $embrionesPorSaco[$sid] = [];
    $embrionesPorSaco[$sid][] = $emb;
}
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/ultrasonido_temprano'); ?>" class="btn btn-light rounded-3">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="page-title mb-0">Editar Ultrasonido Temprano</h1>
    </div>
    <div class="page-header-actions">
        <span class="badge bg-primary fs-6"><?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></span>
    </div>
</div>

<form method="POST" action="<?php echo Url::to('/ultrasonido_temprano/update'); ?>" id="formUltrasonido">
    <input type="hidden" name="id" value="<?php echo $evaluacion['id']; ?>">
    <input type="hidden" name="codigo_reporte" value="<?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?>">

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-user me-2"></i>Datos Generales</h5></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Código de Reporte</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha de Estudio *</label>
                    <input type="date" name="fecha_estudio" class="form-control" value="<?php echo $evaluacion['fecha_estudio']; ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Edad</label>
                    <input type="number" name="edad" class="form-control" value="<?php echo $val('edad'); ?>" placeholder="años" min="10" max="99">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Paciente *</label>
                    <select name="paciente_id" class="form-select" required>
                        <option value="">Seleccionar paciente...</option>
                        <?php foreach ($pacientes as $p): ?>
                            <option value="<?php echo $p['id']; ?>" <?php echo $evaluacion['paciente_id'] == $p['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['nombre'] . ' ' . $p['apellido']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-user-doctor me-2"></i>Referencia Médica</h5></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Médico Solicitante</label>
                    <select name="medico_solicitante_id" class="form-select">
                        <option value="">Seleccionar...</option>
                        <?php foreach ($medicos as $m): ?>
                            <option value="<?php echo $m['id']; ?>" <?php echo ($evaluacion['medico_solicitante_id'] ?? '') == $m['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($m['nombre'] . ' ' . $m['apellido']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Médico que Realiza <span class="text-danger">*</span></label>
                    <select name="medico_id" class="form-select" required>
                        <option value="">Seleccionar...</option>
                        <?php foreach ($medicos as $m): ?>
                            <option value="<?php echo $m['id']; ?>" <?php echo $evaluacion['medico_id'] == $m['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($m['nombre'] . ' ' . $m['apellido']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Médico Referido</label>
                    <select name="medico_referido_id" class="form-select">
                        <option value="">Ninguno</option>
                        <?php foreach ($medicos as $m): ?>
                            <option value="<?php echo $m['id']; ?>" <?php echo ($evaluacion['medico_referido_id'] ?? '') == $m['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($m['nombre'] . ' ' . $m['apellido']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-user me-2"></i>Datos del Estudio</h5></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">FUM</label>
                    <input type="date" name="fum" id="fum" class="form-control" value="<?php echo $val('fum'); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">EG Semanas</label>
                    <input type="number" name="edad_gest_semanas" id="eg_semanas" class="form-control" value="<?php echo $val('edad_gest_semanas'); ?>" placeholder="sem." min="1" max="11">
                </div>
                <div class="col-md-2">
                    <label class="form-label">EG Días</label>
                    <input type="number" name="edad_gest_dias" id="eg_dias" class="form-control" value="<?php echo $val('edad_gest_dias'); ?>" placeholder="días" min="0" max="6">
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-clipboard-list me-2"></i>Indicación del Estudio</h5></div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="indic_confirmacion_embarazo" value="1" <?php echo $chk('indic_confirmacion_embarazo'); ?>><label class="form-check-label">Confirmación de embarazo</label></div></div>
                        <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="indic_sangrado" value="1" <?php echo $chk('indic_sangrado'); ?>><label class="form-check-label">Sangrado transvaginal</label></div></div>
                        <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="indic_dolor_pelvico" value="1" <?php echo $chk('indic_dolor_pelvico'); ?>><label class="form-check-label">Dolor pélvico</label></div></div>
                        <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="indic_viabilidad" value="1" <?php echo $chk('indic_viabilidad'); ?>><label class="form-check-label">Valoración de viabilidad</label></div></div>
                        <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="indic_perdidas_gestacionales" value="1" <?php echo $chk('indic_perdidas_gestacionales'); ?>><label class="form-check-label">Pérdidas gestacionales</label></div></div>
                        <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="indic_reproduccion_asistida" value="1" <?php echo $chk('indic_reproduccion_asistida'); ?>><label class="form-check-label">Reproducción asistida</label></div></div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Otro</label>
                        <input type="text" name="indic_otro" class="form-control" value="<?php echo $val('indic_otro'); ?>" placeholder="Especifique...">
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-route me-2"></i>Vía de Exploración</h5></div>
                <div class="card-body">
                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="via_transvaginal" value="1" <?php echo $chk('via_transvaginal'); ?>><label class="form-check-label">Transvaginal</label></div>
                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="via_transabdominal" value="1" <?php echo $chk('via_transabdominal'); ?>><label class="form-check-label">Transabdominal</label></div>
                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="via_ambas" value="1" <?php echo $chk('via_ambas'); ?>><label class="form-check-label">Ambas</label></div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-uterus me-2"></i>Útero</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Posición</label>
                        <div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="utero_posicion" value="Anteroversion" <?php echo $selRadio('utero_posicion', 'Anteroversion'); ?>><label class="form-check-label">Anteroversión</label></div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="utero_posicion" value="Retroversion" <?php echo $selRadio('utero_posicion', 'Retroversion'); ?>><label class="form-check-label">Retroversión</label></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Contornos</label>
                        <div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="utero_contornos" value="Regulares" <?php echo ($evaluacion['utero_contornos'] ?? 'Regulares') == 'Regulares' ? 'checked' : ''; ?>><label class="form-check-label">Regulares</label></div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="utero_contornos" value="Irregulares" <?php echo ($evaluacion['utero_contornos'] ?? '') == 'Irregulares' ? 'checked' : ''; ?>><label class="form-check-label">Irregulares</label></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="utero_ecogenicidad_conservada" value="1" <?php echo $chk('utero_ecogenicidad_conservada'); ?>><label class="form-check-label">Ecogenicidad conservada</label></div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4"><label class="form-label">Dim. X (mm)</label><input type="number" step="0.1" name="utero_dim_x" class="form-control" value="<?php echo $val('utero_dim_x'); ?>" placeholder="mm"></div>
                        <div class="col-md-4"><label class="form-label">Dim. Y (mm)</label><input type="number" step="0.1" name="utero_dim_y" class="form-control" value="<?php echo $val('utero_dim_y'); ?>" placeholder="mm"></div>
                        <div class="col-md-4"><label class="form-label">Dim. Z (mm)</label><input type="number" step="0.1" name="utero_dim_z" class="form-control" value="<?php echo $val('utero_dim_z'); ?>" placeholder="mm"></div>
                    </div>
                    <div>
                        <label class="form-label fw-bold">Endometrio</label>
                        <input type="text" name="endometrio" class="form-control" value="<?php echo $val('endometrio'); ?>" placeholder="Describa el endometrio...">
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-location-dot me-2"></i>Localización del Embarazo</h5></div>
                <div class="card-body">
                    <select name="localizacion" id="localizacion" class="form-select mb-2">
                        <option value="">Seleccionar...</option>
                        <option value="Fundica" <?php echo ($evaluacion['localizacion'] ?? '') == 'Fundica' ? 'selected' : ''; ?>>Fúndica</option>
                        <option value="Corporal" <?php echo ($evaluacion['localizacion'] ?? '') == 'Corporal' ? 'selected' : ''; ?>>Corporal</option>
                        <option value="Segmentaria" <?php echo ($evaluacion['localizacion'] ?? '') == 'Segmentaria' ? 'selected' : ''; ?>>Segmentaria</option>
                        <option value="Cicatriz de cesarea" <?php echo ($evaluacion['localizacion'] ?? '') == 'Cicatriz de cesarea' ? 'selected' : ''; ?>>Cicatriz de cesárea</option>
                        <option value="Otra" <?php echo ($evaluacion['localizacion'] ?? '') == 'Otra' ? 'selected' : ''; ?>>Otra</option>
                    </select>
                    <div id="localizacionOtra" style="<?php echo ($evaluacion['localizacion'] ?? '') == 'Otra' ? '' : 'display:none;'; ?>">
                        <label class="form-label">Especifique</label>
                        <input type="text" name="localizacion_otra" class="form-control" value="<?php echo $val('localizacion_otra'); ?>" placeholder="Otra localización...">
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-note-sticky me-2"></i>Decidua</h5></div>
                <div class="card-body">
                    <textarea name="decidua" class="form-control" rows="3" placeholder="Describa la decidua..."><?php echo $val('decidua'); ?></textarea>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-circle me-2"></i>Saco Gestacional</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipo</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sg_tipo" id="sg_unico" value="Unico" onchange="toggleSgCantidad()" <?php echo $sgTipo == 'Unico' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="sg_unico">Único</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sg_tipo" id="sg_multiple" value="Multiple" onchange="toggleSgCantidad()" <?php echo $sgTipo == 'Multiple' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="sg_multiple">Múltiple</label>
                            </div>
                        </div>
                    </div>
                    <div id="sg_cantidad_group" style="<?php echo $sgTipo == 'Multiple' ? '' : 'display:none;'; ?>" class="mb-3">
                        <label class="form-label">Número de sacos gestacionales</label>
                        <select name="sg_cantidad" id="sg_cantidad" class="form-select" onchange="toggleSacoCards()">
                            <option value="1" <?php echo $sgCantidad == 1 ? 'selected' : ''; ?>>1</option>
                            <option value="2" <?php echo $sgCantidad == 2 ? 'selected' : ''; ?>>2</option>
                            <option value="3" <?php echo $sgCantidad == 3 ? 'selected' : ''; ?>>3</option>
                            <option value="4" <?php echo $sgCantidad == 4 ? 'selected' : ''; ?>>4</option>
                        </select>
                    </div>

                    <div id="sacos_cards">
                        <?php for ($s = 1; $s <= 4; $s++):
                            $saco = $sacos[$s - 1] ?? ['medida_mm' => null, 'morfologia' => null, 'sv_presente' => null, 'sv_diametro_mm' => null, 'descripcion' => null, 'id' => null];
                            $embSaco = [];
                            if ($saco['id']) {
                                $embSaco = $embrionesPorSaco[$saco['id']] ?? [];
                            } elseif ($s == 1 && !empty($embriones)) {
                                $hasSacoId = false;
                                foreach ($embriones as $e) { if (!empty($e['saco_id'])) $hasSacoId = true; }
                                if (!$hasSacoId) $embSaco = $embriones;
                            }
                            $numEmbSaco = max(1, count($embSaco));
                        ?>
                        <div class="card mb-3 border" id="saco_card_<?php echo $s; ?>" <?php echo $s > $sgCantidad ? 'style="display:none;"' : ''; ?>>
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold">Saco Gestacional #<?php echo $s; ?></h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2 mb-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Medida (mm)</label>
                                        <input type="number" step="0.1" name="saco_<?php echo $s; ?>_medida_mm" class="form-control saco-medida" value="<?php echo htmlspecialchars($saco['medida_mm'] ?? ''); ?>" placeholder="mm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Morfología</label>
                                        <select name="saco_<?php echo $s; ?>_morfologia" class="form-select">
                                            <option value="">—</option>
                                            <option value="Regular" <?php echo ($saco['morfologia'] ?? '') == 'Regular' ? 'selected' : ''; ?>>Regular</option>
                                            <option value="Irregular" <?php echo ($saco['morfologia'] ?? '') == 'Irregular' ? 'selected' : ''; ?>>Irregular</option>
                                        </select>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <label class="form-label fw-bold">Saco Vitelino</label>
                                <div class="mb-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="saco_<?php echo $s; ?>_sv_presente" id="saco_<?php echo $s; ?>_sv_presente_si" value="1" <?php echo ($saco['sv_presente'] ?? '') === 1 || ($saco['sv_presente'] ?? '') === '1' ? 'checked' : ''; ?> onchange="toggleSacoVitelinoDetalle(<?php echo $s; ?>)">
                                        <label class="form-check-label" for="saco_<?php echo $s; ?>_sv_presente_si">Presente</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="saco_<?php echo $s; ?>_sv_presente" id="saco_<?php echo $s; ?>_sv_presente_no" value="0" <?php echo ($saco['sv_presente'] ?? '') === 0 || ($saco['sv_presente'] ?? '') === '0' ? 'checked' : ''; ?> onchange="toggleSacoVitelinoDetalle(<?php echo $s; ?>)">
                                        <label class="form-check-label" for="saco_<?php echo $s; ?>_sv_presente_no">Ausente</label>
                                    </div>
                                </div>
                                <div id="saco_<?php echo $s; ?>_sv_detalle" style="<?php echo ($saco['sv_presente'] ?? '') ? '' : 'display:none;'; ?>">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label">Diámetro SV (mm)</label>
                                            <input type="number" step="0.1" name="saco_<?php echo $s; ?>_sv_diametro_mm" class="form-control" value="<?php echo htmlspecialchars($saco['sv_diametro_mm'] ?? ''); ?>" placeholder="mm">
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <label class="form-label fw-bold">Embrión</label>
                                <div class="mb-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="saco_<?php echo $s; ?>_embrion_visible" id="saco_<?php echo $s; ?>_embrion_visible_si" value="1" <?php echo !empty($embSaco) ? 'checked' : ''; ?> onchange="toggleEmbrionDetalle(<?php echo $s; ?>)">
                                        <label class="form-check-label" for="saco_<?php echo $s; ?>_embrion_visible_si">Visible</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="saco_<?php echo $s; ?>_embrion_visible" id="saco_<?php echo $s; ?>_embrion_visible_no" value="0" <?php echo empty($embSaco) ? 'checked' : ''; ?> onchange="toggleEmbrionDetalle(<?php echo $s; ?>)">
                                        <label class="form-check-label" for="saco_<?php echo $s; ?>_embrion_visible_no">No visible</label>
                                    </div>
                                </div>
                                <div id="saco_<?php echo $s; ?>_embrion_detalle" style="<?php echo !empty($embSaco) ? '' : 'display:none;'; ?>">
                                    <div class="mb-2">
                                        <label class="form-label">Número de embriones en este saco</label>
                                        <select name="saco_<?php echo $s; ?>_num_embriones" class="form-select" onchange="toggleEmbrionSubCards(<?php echo $s; ?>)">
                                            <option value="1" <?php echo $numEmbSaco == 1 ? 'selected' : ''; ?>>1</option>
                                            <option value="2" <?php echo $numEmbSaco == 2 ? 'selected' : ''; ?>>2</option>
                                            <option value="3" <?php echo $numEmbSaco == 3 ? 'selected' : ''; ?>>3</option>
                                        </select>
                                    </div>
                                    <?php for ($e = 1; $e <= 3; $e++):
                                        $emb = $embSaco[$e - 1] ?? ['crl_mm' => '', 'fcf_visible' => 0, 'fcf_lpm' => '', 'localizacion' => ''];
                                    ?>
                                    <div class="border rounded p-2 mb-2 bg-light" id="saco_<?php echo $s; ?>_embrion_card_<?php echo $e; ?>" <?php echo $e > $numEmbSaco ? 'style="display:none;"' : ''; ?>>
                                        <small class="fw-bold text-muted d-block mb-2">Embrión #<?php echo $e; ?></small>
                                        <div class="row g-1">
                                            <div class="col-md-4">
                                                <label class="form-label small">CRL (mm)</label>
                                                <input type="number" step="0.1" name="saco_<?php echo $s; ?>_embrion_<?php echo $e; ?>_crl" class="form-control form-control-sm crl-input" value="<?php echo htmlspecialchars($emb['crl_mm'] ?? ''); ?>" placeholder="mm">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small">FCF visible</label>
                                                <div class="form-check mt-1">
                                                    <input class="form-check-input fcf-check" type="checkbox" name="saco_<?php echo $s; ?>_embrion_<?php echo $e; ?>_fcf_visible" value="1" <?php echo ($emb['fcf_visible'] ?? 0) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label small">Visible</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small">FCF (lpm)</label>
                                                <input type="number" name="saco_<?php echo $s; ?>_embrion_<?php echo $e; ?>_fcf_lpm" class="form-control form-control-sm fcf-input" value="<?php echo htmlspecialchars($emb['fcf_lpm'] ?? ''); ?>" placeholder="lpm">
                                            </div>
                                        </div>
                                        <div class="mt-1">
                                            <label class="form-label small">Localización</label>
                                            <input type="text" name="saco_<?php echo $s; ?>_embrion_<?php echo $e; ?>_localizacion" class="form-control form-control-sm" value="<?php echo htmlspecialchars($emb['localizacion'] ?? ''); ?>" placeholder="Localización...">
                                        </div>
                                    </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-heart-pulse me-2"></i>Viabilidad</h5></div>
                <div class="card-body">
                    <label class="form-label fw-bold">Determinación de viabilidad</label>
                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="viabilidad" id="viabilidad_viable" value="Viable" <?php echo $selRadio('viabilidad', 'Viable'); ?> onchange="sugerirDiagnostico()">
                            <label class="form-check-label" for="viabilidad_viable">Viable</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="viabilidad" id="viabilidad_noviable" value="No viable" <?php echo $selRadio('viabilidad', 'No viable'); ?> onchange="sugerirDiagnostico()">
                            <label class="form-check-label" for="viabilidad_noviable">No viable</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="viabilidad" id="viabilidad_incierto" value="Incierto" <?php echo $selRadio('viabilidad', 'Incierto'); ?> onchange="sugerirDiagnostico()">
                            <label class="form-check-label" for="viabilidad_incierto">Incierto</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-layer-group me-2"></i>Corion y Amnios</h5></div>
                <div class="card-body">
                    <div class="form-check"><input class="form-check-input" type="checkbox" name="corion_amnios_normal" value="1" <?php echo $chk('corion_amnios_normal'); ?>><label class="form-check-label">Corion y amnios identificables y de aspecto normal para la edad gestacional</label></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-venus-mars me-2"></i>Evaluación de Anexos</h5></div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-lg-6">
                    <h6 class="fw-bold mb-3 text-primary">Ovario Derecho</h6>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4"><label class="form-label">Dim. X (mm)</label><input type="number" step="0.1" name="ovario_der_dim_x" class="form-control" value="<?php echo $val('ovario_der_dim_x'); ?>" placeholder="mm"></div>
                        <div class="col-md-4"><label class="form-label">Dim. Y (mm)</label><input type="number" step="0.1" name="ovario_der_dim_y" class="form-control" value="<?php echo $val('ovario_der_dim_y'); ?>" placeholder="mm"></div>
                        <div class="col-md-4"><label class="form-label">Dim. Z (mm)</label><input type="number" step="0.1" name="ovario_der_dim_z" class="form-control" value="<?php echo $val('ovario_der_dim_z'); ?>" placeholder="mm"></div>
                    </div>
                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="ovario_der_normal" value="1" <?php echo $chk('ovario_der_normal'); ?>><label class="form-check-label">Normal</label></div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-6"><label class="form-label">Cuerpo lúteo (mm)</label><input type="number" step="0.1" name="ovario_der_cuerpo_luteo_mm" class="form-control" value="<?php echo $val('ovario_der_cuerpo_luteo_mm'); ?>" placeholder="mm"></div>
                        <div class="col-md-6"><label class="form-label">Quiste simple (mm)</label><input type="number" step="0.1" name="ovario_der_quiste_simple_mm" class="form-control" value="<?php echo $val('ovario_der_quiste_simple_mm'); ?>" placeholder="mm"></div>
                    </div>
                    <div><label class="form-label">Otra alteración</label><input type="text" name="ovario_der_otra_alteracion" class="form-control" value="<?php echo $val('ovario_der_otra_alteracion'); ?>" placeholder="Especifique..."></div>
                </div>
                <div class="col-lg-6">
                    <h6 class="fw-bold mb-3 text-primary">Ovario Izquierdo</h6>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4"><label class="form-label">Dim. X (mm)</label><input type="number" step="0.1" name="ovario_izq_dim_x" class="form-control" value="<?php echo $val('ovario_izq_dim_x'); ?>" placeholder="mm"></div>
                        <div class="col-md-4"><label class="form-label">Dim. Y (mm)</label><input type="number" step="0.1" name="ovario_izq_dim_y" class="form-control" value="<?php echo $val('ovario_izq_dim_y'); ?>" placeholder="mm"></div>
                        <div class="col-md-4"><label class="form-label">Dim. Z (mm)</label><input type="number" step="0.1" name="ovario_izq_dim_z" class="form-control" value="<?php echo $val('ovario_izq_dim_z'); ?>" placeholder="mm"></div>
                    </div>
                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="ovario_izq_normal" value="1" <?php echo $chk('ovario_izq_normal'); ?>><label class="form-check-label">Normal</label></div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-6"><label class="form-label">Cuerpo lúteo (mm)</label><input type="number" step="0.1" name="ovario_izq_cuerpo_luteo_mm" class="form-control" value="<?php echo $val('ovario_izq_cuerpo_luteo_mm'); ?>" placeholder="mm"></div>
                        <div class="col-md-6"><label class="form-label">Quiste simple (mm)</label><input type="number" step="0.1" name="ovario_izq_quiste_simple_mm" class="form-control" value="<?php echo $val('ovario_izq_quiste_simple_mm'); ?>" placeholder="mm"></div>
                    </div>
                    <div><label class="form-label">Otra alteración</label><input type="text" name="ovario_izq_otra_alteracion" class="form-control" value="<?php echo $val('ovario_izq_otra_alteracion'); ?>" placeholder="Especifique..."></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-magnifying-glass me-2"></i>Fondo de Saco de Douglas y Hallazgos Adicionales</h5></div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Fondo de Saco de Douglas</label>
                <select name="douglas" class="form-select">
                    <option value="">Seleccionar...</option>
                    <option value="Libre" <?php echo $sel('douglas', 'Libre'); ?>>Libre</option>
                    <option value="Escasa cantidad de liquido libre" <?php echo $sel('douglas', 'Escasa cantidad de liquido libre'); ?>>Escasa cantidad de líquido libre</option>
                    <option value="Moderada cantidad de liquido libre" <?php echo $sel('douglas', 'Moderada cantidad de liquido libre'); ?>>Moderada cantidad de líquido libre</option>
                    <option value="Abundante liquido libre" <?php echo $sel('douglas', 'Abundante liquido libre'); ?>>Abundante líquido libre</option>
                </select>
            </div>
            <hr>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="hematoma_subcorionico" id="hematoma_subcorionico" value="1" <?php echo $chk('hematoma_subcorionico'); ?> onchange="toggleHematoma()">
                <label class="form-check-label fw-bold">Hematoma subcoriónico</label>
            </div>
            <div id="hematoma_detalles" style="<?php echo $evaluacion['hematoma_subcorionico'] ? '' : 'display:none;'; ?>">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Localización</label><input type="text" name="hematoma_localizacion" class="form-control" value="<?php echo $val('hematoma_localizacion'); ?>" placeholder="Localización del hematoma..."></div>
                    <div class="col-md-2"><label class="form-label">Dim. X (mm)</label><input type="number" step="0.1" name="hematoma_dim_x" class="form-control" value="<?php echo $val('hematoma_dim_x'); ?>" placeholder="mm"></div>
                    <div class="col-md-2"><label class="form-label">Dim. Y (mm)</label><input type="number" step="0.1" name="hematoma_dim_y" class="form-control" value="<?php echo $val('hematoma_dim_y'); ?>" placeholder="mm"></div>
                    <div class="col-md-2"><label class="form-label">Dim. Z (mm)</label><input type="number" step="0.1" name="hematoma_dim_z" class="form-control" value="<?php echo $val('hematoma_dim_z'); ?>" placeholder="mm"></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4"><label class="form-label">Volumen estimado (ml)</label><input type="number" step="0.1" name="hematoma_volumen_ml" class="form-control" value="<?php echo $val('hematoma_volumen_ml'); ?>" placeholder="ml"></div>
                </div>
            </div>
            <hr>
            <div class="row g-2">
                <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="miomas_uterinos" value="1" <?php echo $chk('miomas_uterinos'); ?>><label class="form-check-label">Miomas uterinos</label></div></div>
                <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="adenomiosis" value="1" <?php echo $chk('adenomiosis'); ?>><label class="form-check-label">Adenomiosis</label></div></div>
                <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="malformacion_uterina" value="1" <?php echo $chk('malformacion_uterina'); ?>><label class="form-check-label">Malformación uterina</label></div></div>
            </div>
            <div class="mt-3">
                <label class="form-label">Otros hallazgos</label>
                <textarea name="hallazgos_otro" class="form-control" rows="2" placeholder="Describa otros hallazgos..."><?php echo $val('hallazgos_otro'); ?></textarea>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-file-lines me-2"></i>Impresión Diagnóstica</h5></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">CRL (mm)</label>
                    <input type="number" step="0.1" name="impresion_crl_mm" id="impresion_crl_mm" class="form-control" value="<?php echo $val('impresion_crl_mm'); ?>" placeholder="mm">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Semanas</label>
                    <input type="number" name="impresion_semanas" class="form-control" value="<?php echo $val('impresion_semanas'); ?>" placeholder="sem">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Días</label>
                    <input type="number" name="impresion_dias" class="form-control" value="<?php echo $val('impresion_dias'); ?>" placeholder="días">
                </div>
                <div class="col-md-3">
                    <label class="form-label">FCF (lpm)</label>
                    <input type="number" name="impresion_fcf_lpm" id="impresion_fcf_lpm" class="form-control" value="<?php echo $val('impresion_fcf_lpm'); ?>" placeholder="lpm">
                </div>
            </div>
            <div class="mt-3">
                <label class="form-label">Texto de impresión diagnóstica</label>
                <textarea name="impresion_texto" id="impresion_texto" class="form-control" rows="4" placeholder="Escriba la impresión diagnóstica..."><?php echo $val('impresion_texto'); ?></textarea>
                <small class="text-muted">El campo acepta sugerencia automática según los criterios de viabilidad. Puede modificarlo libremente.</small>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <button class="btn btn-link text-decoration-none p-0 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#criteriosCollapse" aria-expanded="false">
                    <i class="fa-solid fa-book-medical me-2"></i>Criterios de Pérdida Gestacional Precoz (referencia)
                    <i class="fa-solid fa-chevron-down ms-2 small"></i>
                </button>
            </h5>
        </div>
        <div id="criteriosCollapse" class="collapse">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h6 class="fw-bold text-warning mb-3"><i class="fa-solid fa-triangle-exclamation me-1"></i> Criterios Sugestivos (NO diagnósticos)</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered small">
                                <thead class="table-warning"><tr><th>#</th><th>Criterio</th></tr></thead>
                                <tbody>
                                    <tr><td>1</td><td>CRL &lt; 7mm sin actividad cardíaca</td></tr>
                                    <tr><td>2</td><td>Diámetro del saco gestacional entre 16 y 25 mm sin embrión</td></tr>
                                    <tr><td>3</td><td>Ausencia de embrión con actividad cardíaca entre 7 y 13 días después de una ecografía con SG sin vesícula vitelina</td></tr>
                                    <tr><td>4</td><td>Ausencia de embrión con actividad cardíaca entre 7 y 10 días después de una ecografía con SG y vesícula vitelina</td></tr>
                                    <tr><td>5</td><td>Ausencia de embrión ≥ 6 semanas después de la fecha de última regla</td></tr>
                                    <tr><td>6</td><td>Amnios vacío (amnios visualizado adyacente a vesícula vitelina, sin embrión visible)</td></tr>
                                    <tr><td>7</td><td>Vesícula vitelina elongada (&gt;7 mm)</td></tr>
                                    <tr><td>8</td><td>Saco gestacional pequeño en comparación con la medida del embrión (&lt;5 mm de diferencia entre diámetro del SG y el CRL)</td></tr>
                                    <tr><td>9</td><td>Frecuencia cardíaca inferior a 100 latidos por minuto</td></tr>
                                    <tr><td>10</td><td>Presencia de hematoma subcoriónico masivo</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h6 class="fw-bold text-danger mb-3"><i class="fa-solid fa-circle-exclamation me-1"></i> Criterios Diagnósticos (pérdida gestacional precoz)</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered small">
                                <thead class="table-danger"><tr><th>#</th><th>Criterio (mediante ecografía transvaginal)</th></tr></thead>
                                <tbody>
                                    <tr><td>1</td><td><strong>CRL ≥ 7 mm sin actividad cardíaca</strong></td></tr>
                                    <tr><td>2</td><td>Ausencia de embrión con actividad cardíaca ≥ 2 semanas después de una ecografía con SG sin vesícula vitelina</td></tr>
                                    <tr><td>3</td><td>Ausencia de embrión con actividad cardíaca &gt; 11 días después de una ecografía con SG y vesícula vitelina</td></tr>
                                    <tr><td>4</td><td><strong>Diámetro medio del saco gestacional ≥ 25 mm sin embrión</strong> ni vesícula vitelina en su interior</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-flag me-2"></i>Estado</h5></div>
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <select name="estado" class="form-select">
                        <option value="Pendiente" <?php echo $sel('estado', 'Pendiente'); ?>>Pendiente</option>
                        <option value="En proceso" <?php echo $sel('estado', 'En proceso'); ?>>En proceso</option>
                        <option value="Completado" <?php echo $sel('estado', 'Completado'); ?>>Completado</option>
                        <option value="Archivado" <?php echo $sel('estado', 'Archivado'); ?>>Archivado</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <a href="<?php echo Url::to('/ultrasonido_temprano'); ?>" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-apple btn-apple-primary"><i class="fa-solid fa-save me-1"></i> Actualizar</button>
                <?php if (Auth::check() && Auth::user()['rol_id'] != Auth::ROLE_MEDICO): ?>
                <button type="button" class="btn btn-outline-danger ms-auto" onclick="if(confirm('¿Eliminar este ultrasonido?')){document.getElementById('formDelete').submit();}"><i class="fa-solid fa-trash me-1"></i> Eliminar</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>

<?php if (Auth::check() && Auth::user()['rol_id'] != Auth::ROLE_MEDICO): ?>
<form id="formDelete" method="POST" action="<?php echo Url::to('/ultrasonido_temprano/delete'); ?>" style="display:none;">
    <input type="hidden" name="id" value="<?php echo $evaluacion['id']; ?>">
</form>
<?php endif; ?>

<script>
const today = '<?php echo date('Y-m-d'); ?>';

function toggleSgCantidad() {
    var multiple = document.getElementById('sg_multiple');
    var cntGroup = document.getElementById('sg_cantidad_group');
    if (multiple && multiple.checked) {
        cntGroup.style.display = 'block';
        if (document.getElementById('sg_cantidad').value === '1') document.getElementById('sg_cantidad').value = '2';
    } else {
        cntGroup.style.display = 'none';
        document.getElementById('sg_cantidad').value = '1';
    }
    toggleSacoCards();
}

function toggleSacoCards() {
    var multiple = document.getElementById('sg_multiple');
    var n = (multiple && multiple.checked) ? parseInt(document.getElementById('sg_cantidad').value) || 1 : 1;
    for (var s = 1; s <= 4; s++) {
        var card = document.getElementById('saco_card_' + s);
        if (card) card.style.display = s <= n ? 'block' : 'none';
    }
}

function toggleSacoVitelinoDetalle(s) {
    var si = document.getElementById('saco_' + s + '_sv_presente_si');
    var det = document.getElementById('saco_' + s + '_sv_detalle');
    if (det) det.style.display = (si && si.checked) ? 'block' : 'none';
}

function toggleEmbrionDetalle(s) {
    var si = document.getElementById('saco_' + s + '_embrion_visible_si');
    var det = document.getElementById('saco_' + s + '_embrion_detalle');
    if (det) det.style.display = (si && si.checked) ? 'block' : 'none';
    if (si && si.checked) toggleEmbrionSubCards(s);
}

function toggleEmbrionSubCards(s) {
    var sel = document.querySelector('select[name="saco_' + s + '_num_embriones"]');
    var n = sel ? parseInt(sel.value) || 1 : 1;
    for (var e = 1; e <= 3; e++) {
        var card = document.getElementById('saco_' + s + '_embrion_card_' + e);
        if (card) card.style.display = e <= n ? 'block' : 'none';
    }
}

function toggleHematoma() {
    var chk = document.getElementById('hematoma_subcorionico');
    var det = document.getElementById('hematoma_detalles');
    if (chk && chk.checked) { det.style.display = 'block'; } else { det.style.display = 'none'; }
}

function sugerirDiagnostico() {
    var viabilidad = document.querySelector('input[name="viabilidad"]:checked');
    var texto = document.getElementById('impresion_texto');
    if (!viabilidad || !texto) return;

    if (viabilidad.value === 'No viable') {
        var crlInputs = document.querySelectorAll('.crl-input');
        var fcfChecks = document.querySelectorAll('.fcf-check');
        var sacoMedidas = document.querySelectorAll('.saco-medida');

        var hasCrlGte7 = false;
        crlInputs.forEach(function(inp) { if (parseFloat(inp.value) >= 7) hasCrlGte7 = true; });
        var anyFcf = false;
        fcfChecks.forEach(function(chk) { if (chk.checked) anyFcf = true; });
        var maxSaco = 0;
        sacoMedidas.forEach(function(inp) { var v = parseFloat(inp.value); if (v > maxSaco) maxSaco = v; });

        var embrionVisibleAny = document.querySelector('input[id$="_embrion_visible_si"]:checked');
        var sugerencia = '';
        if (hasCrlGte7 && !anyFcf) {
            sugerencia = 'Embarazo NO viable. Criterio diagnóstico: CRL ≥ 7 mm sin actividad cardíaca en ecografía transvaginal, considerado prueba definitiva de pérdida gestacional precoz.';
        } else if (maxSaco >= 25 && !embrionVisibleAny) {
            sugerencia = 'Embarazo NO viable. Criterio diagnóstico: Diámetro medio del saco gestacional ≥ 25 mm sin embrión identificable en ecografía transvaginal, considerado prueba definitiva de pérdida gestacional precoz.';
        } else {
            sugerencia = 'Embarazo NO viable según criterios clínicos y ecográficos.';
        }
        texto.value = sugerencia;
    } else if (viabilidad.value === 'Viable') {
        texto.value = 'Embarazo intrauterino viable.';
    } else {
        texto.value = 'Viabilidad incierta. Se recomienda control ecográfico evolutivo.';
    }
}

document.getElementById('fum').addEventListener('change', calcularEG);
function calcularEG() {
    var fum = document.getElementById('fum').value;
    if (!fum) return;
    var fechaFum = new Date(fum);
    var fechaHoy = new Date(today);
    var diffMs = fechaHoy - fechaFum;
    var diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
    if (diffDays < 0) diffDays = 0;
    var semanas = Math.floor(diffDays / 7);
    var dias = diffDays % 7;
    document.getElementById('eg_semanas').value = semanas;
    document.getElementById('eg_dias').value = dias;
}

document.getElementById('localizacion').addEventListener('change', function() {
    document.getElementById('localizacionOtra').style.display = this.value === 'Otra' ? 'block' : 'none';
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
