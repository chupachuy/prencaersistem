<?php
$title = "Editar Ultrasonido Temprano";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$chk = function($field) use ($evaluacion) { return $evaluacion[$field] ? 'checked' : ''; };
$sel = function($field, $value) use ($evaluacion) { return ($evaluacion[$field] == $value) ? 'selected' : ''; };
$selRadio = function($field, $value) use ($evaluacion) { return ($evaluacion[$field] === $value || $evaluacion[$field] == $value) ? 'checked' : ''; };
$val = function($field) use ($evaluacion) { return htmlspecialchars($evaluacion[$field] ?? ''); };
$embVisible = !empty($embriones);
$numEmbriones = count($embriones);
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
                <div class="col-md-6">
                    <label class="form-label">Paciente *</label>
                    <select name="paciente_id" class="form-select" required>
                        <option value="">Seleccionar paciente...</option>
                        <?php foreach ($pacientes as $p): ?>
                            <option value="<?php echo $p['id']; ?>" <?php echo $evaluacion['paciente_id'] == $p['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['nombre'] . ' ' . $p['apellido']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Médico *</label>
                    <select name="medico_id" class="form-select" required>
                        <option value="">Seleccionar médico...</option>
                        <?php foreach ($medicos as $m): ?>
                            <option value="<?php echo $m['id']; ?>" <?php echo $evaluacion['medico_id'] == $m['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($m['nombre'] . ' ' . $m['apellido']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
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
                        <label class="form-label">Posición</label>
                        <div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="utero_posicion" value="Anteroversion" <?php echo $selRadio('utero_posicion', 'Anteroversion'); ?>><label class="form-check-label">Anteroversión</label></div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="utero_posicion" value="Retroversion" <?php echo $selRadio('utero_posicion', 'Retroversion'); ?>><label class="form-check-label">Retroversión</label></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="utero_contornos_regulares" value="1" <?php echo $chk('utero_contornos_regulares'); ?>><label class="form-check-label">Contornos regulares</label></div>
                        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="utero_ecogenicidad_conservada" value="1" <?php echo $chk('utero_ecogenicidad_conservada'); ?>><label class="form-check-label">Ecogenicidad conservada</label></div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4"><label class="form-label">Dim. X (mm)</label><input type="number" step="0.1" name="utero_dim_x" class="form-control" value="<?php echo $val('utero_dim_x'); ?>" placeholder="mm"></div>
                        <div class="col-md-4"><label class="form-label">Dim. Y (mm)</label><input type="number" step="0.1" name="utero_dim_y" class="form-control" value="<?php echo $val('utero_dim_y'); ?>" placeholder="mm"></div>
                        <div class="col-md-4"><label class="form-label">Dim. Z (mm)</label><input type="number" step="0.1" name="utero_dim_z" class="form-control" value="<?php echo $val('utero_dim_z'); ?>" placeholder="mm"></div>
                    </div>
                    <div>
                        <label class="form-label">Endometrio</label>
                        <input type="text" name="endometrio" class="form-control" value="<?php echo $val('endometrio'); ?>" placeholder="Describa el endometrio...">
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-location-dot me-2"></i>Localización del Embarazo</h5></div>
                <div class="card-body">
                    <select name="localizacion" id="localizacion" class="form-select mb-2">
                        <option value="">Seleccionar...</option>
                        <option value="Fundica" <?php echo ($evaluacion['localizacion'] == 'Fundica') ? 'selected' : ''; ?>>Fúndica</option>
                        <option value="Corporal" <?php echo ($evaluacion['localizacion'] == 'Corporal') ? 'selected' : ''; ?>>Corporal</option>
                        <option value="Segmentaria" <?php echo ($evaluacion['localizacion'] == 'Segmentaria') ? 'selected' : ''; ?>>Segmentaria</option>
                        <option value="Cicatriz de cesarea" <?php echo ($evaluacion['localizacion'] == 'Cicatriz de cesarea') ? 'selected' : ''; ?>>Cicatriz de cesárea</option>
                        <option value="Otra" <?php echo ($evaluacion['localizacion'] == 'Otra') ? 'selected' : ''; ?>>Otra</option>
                    </select>
                    <div id="localizacionOtra" style="<?php echo $evaluacion['localizacion'] == 'Otra' ? 'display:block;' : 'display:none;'; ?>">
                        <label class="form-label">Especifique</label>
                        <input type="text" name="localizacion_otra" class="form-control" value="<?php echo $val('localizacion_otra'); ?>" placeholder="Otra localización...">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-circle me-2"></i>Saco Gestacional</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="sg_tipo" value="Unico" <?php echo $selRadio('sg_tipo', 'Unico'); ?>><label class="form-check-label">Único</label></div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="sg_tipo" value="Multiple" <?php echo $selRadio('sg_tipo', 'Multiple'); ?>><label class="form-check-label">Múltiple</label></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Morfología</label>
                        <div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="sg_morfologia" value="Regular" <?php echo $selRadio('sg_morfologia', 'Regular'); ?>><label class="form-check-label">Regular</label></div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="sg_morfologia" value="Irregular" <?php echo $selRadio('sg_morfologia', 'Irregular'); ?>><label class="form-check-label">Irregular</label></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Medida (mm)</label>
                            <input type="number" step="0.1" name="sg_medida_mm" class="form-control" value="<?php echo $val('sg_medida_mm'); ?>" placeholder="mm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-circle-dot me-2"></i>Saco Vitelino</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="sv_presente" id="sv_presente_si" value="1" <?php echo $evaluacion['sv_presente'] === 1 || $evaluacion['sv_presente'] === '1' ? 'checked' : ''; ?> onchange="toggleSacoVitelino()"><label class="form-check-label">Presente</label></div>
                        <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="sv_presente" id="sv_presente_no" value="0" <?php echo $evaluacion['sv_presente'] === 0 || $evaluacion['sv_presente'] === '0' ? 'checked' : ''; ?> onchange="toggleSacoVitelino()"><label class="form-check-label">Ausente</label></div>
                    </div>
                    <div id="sv_detalles" style="<?php echo $evaluacion['sv_presente'] ? '' : 'display:none;'; ?>">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label">Cantidad</label>
                                <select name="sv_cantidad" class="form-select">
                                    <option value="">—</option>
                                    <?php for ($s = 1; $s <= 3; $s++): ?>
                                        <option value="<?php echo $s; ?>" <?php echo $evaluacion['sv_cantidad'] == $s ? 'selected' : ''; ?>><?php echo $s; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Diámetro (mm)</label>
                                <input type="number" step="0.1" name="sv_diametro_mm" class="form-control" value="<?php echo $val('sv_diametro_mm'); ?>" placeholder="mm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-heart-pulse me-2"></i>Embrión</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="embrion_visible" id="embrion_visible_si" value="1" <?php echo $embVisible ? 'checked' : ''; ?> onchange="toggleEmbrión()"><label class="form-check-label">Visible</label></div>
                        <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="embrion_visible" id="embrion_visible_no" value="0" <?php echo !$embVisible ? 'checked' : ''; ?> onchange="toggleEmbrión()"><label class="form-check-label">No visible</label></div>
                    </div>
                    <div id="embrion_detalles" style="<?php echo $embVisible ? '' : 'display:none;'; ?>">
                        <div class="mb-3">
                            <label class="form-label">Número de embriones</label>
                            <select name="num_embriones" id="numEmbriones" class="form-select" onchange="toggleEmbriónCards()">
                                <option value="1" <?php echo $numEmbriones == 1 ? 'selected' : ''; ?>>1</option>
                                <option value="2" <?php echo $numEmbriones == 2 ? 'selected' : ''; ?>>2</option>
                                <option value="3" <?php echo $numEmbriones == 3 ? 'selected' : ''; ?>>3</option>
                            </select>
                        </div>
                        <div id="embriones_cards">
                            <?php for ($e = 1; $e <= 3; $e++): 
                                $emb = isset($embriones[$e - 1]) ? $embriones[$e - 1] : ['crl_mm' => '', 'fcf_visible' => 0, 'fcf_lpm' => '', 'localizacion' => ''];
                                ?>
                            <div class="card mb-3 border" id="embrion_card_<?php echo $e; ?>" <?php echo $e > $numEmbriones || $e > 1 && $numEmbriones == 1 ? 'style="display:none;"' : ''; ?>>
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">Embrión #<?php echo $e; ?></h6>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label">CRL (mm)</label>
                                            <input type="number" step="0.1" name="embrion_<?php echo $e; ?>_crl" class="form-control" value="<?php echo htmlspecialchars($emb['crl_mm'] ?? ''); ?>" placeholder="mm">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">FCF visible</label>
                                            <div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="embrion_<?php echo $e; ?>_fcf_visible" value="1" <?php echo ($emb['fcf_visible'] ?? 0) ? 'checked' : ''; ?>><label class="form-check-label">Visible</label></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">FCF (lpm)</label>
                                            <input type="number" name="embrion_<?php echo $e; ?>_fcf_lpm" class="form-control" value="<?php echo htmlspecialchars($emb['fcf_lpm'] ?? ''); ?>" placeholder="lpm">
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label">Localización</label>
                                        <input type="text" name="embrion_<?php echo $e; ?>_localizacion" class="form-control" value="<?php echo htmlspecialchars($emb['localizacion'] ?? ''); ?>" placeholder="Localización del embrión...">
                                    </div>
                                </div>
                            </div>
                            <?php endfor; ?>
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
                    <div class="col-md-6">
                        <label class="form-label">Localización</label>
                        <input type="text" name="hematoma_localizacion" class="form-control" value="<?php echo $val('hematoma_localizacion'); ?>" placeholder="Localización del hematoma...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Dim. X (mm)</label>
                        <input type="number" step="0.1" name="hematoma_dim_x" class="form-control" value="<?php echo $val('hematoma_dim_x'); ?>" placeholder="mm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Dim. Y (mm)</label>
                        <input type="number" step="0.1" name="hematoma_dim_y" class="form-control" value="<?php echo $val('hematoma_dim_y'); ?>" placeholder="mm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Dim. Z (mm)</label>
                        <input type="number" step="0.1" name="hematoma_dim_z" class="form-control" value="<?php echo $val('hematoma_dim_z'); ?>" placeholder="mm">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label class="form-label">Volumen estimado (ml)</label>
                        <input type="number" step="0.1" name="hematoma_volumen_ml" class="form-control" value="<?php echo $val('hematoma_volumen_ml'); ?>" placeholder="ml">
                    </div>
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
                    <input type="number" step="0.1" name="impresion_crl_mm" class="form-control" value="<?php echo $val('impresion_crl_mm'); ?>" placeholder="mm">
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
                    <input type="number" name="impresion_fcf_lpm" class="form-control" value="<?php echo $val('impresion_fcf_lpm'); ?>" placeholder="lpm">
                </div>
            </div>
            <div class="mt-3">
                <label class="form-label">Texto de impresión diagnóstica</label>
                <textarea name="impresion_texto" class="form-control" rows="4" placeholder="Escriba la impresión diagnóstica..."><?php echo $val('impresion_texto'); ?></textarea>
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

function toggleSacoVitelino() {
    var svSi = document.getElementById('sv_presente_si');
    var detalles = document.getElementById('sv_detalles');
    if (svSi && svSi.checked) {
        detalles.style.display = 'block';
    } else {
        detalles.style.display = 'none';
    }
}
function toggleEmbrión() {
    var vis = document.getElementById('embrion_visible_si');
    var det = document.getElementById('embrion_detalles');
    if (vis && vis.checked) {
        det.style.display = 'block';
    } else {
        det.style.display = 'none';
    }
}
function toggleEmbriónCards() {
    var n = parseInt(document.getElementById('numEmbriones').value) || 1;
    for (var i = 1; i <= 3; i++) {
        var card = document.getElementById('embrion_card_' + i);
        if (card) card.style.display = i <= n ? 'block' : 'none';
    }
}
function toggleHematoma() {
    var chk = document.getElementById('hematoma_subcorionico');
    var det = document.getElementById('hematoma_detalles');
    if (chk && chk.checked) {
        det.style.display = 'block';
    } else {
        det.style.display = 'none';
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
