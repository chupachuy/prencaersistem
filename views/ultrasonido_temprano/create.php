<?php
$title = "Nuevo Ultrasonido Temprano";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
$hoy = date('Y-m-d');
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/ultrasonido_temprano'); ?>" class="btn btn-light rounded-3">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="page-title mb-0">Nuevo Ultrasonido Obstétrico Temprano</h1>
    </div>
    <div class="page-header-actions">
        <span class="text-muted"><?php echo date('d \d\e ') . $meses[intval(date('m'))] . date(' \d\e Y'); ?></span>
    </div>
</div>

<form method="POST" action="<?php echo Url::to('/ultrasonido_temprano/store'); ?>" id="formUltrasonido">
    <input type="hidden" name="codigo_reporte" value="<?php echo htmlspecialchars($codigo_reporte); ?>">

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-user me-2"></i>Datos Generales</h5></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Código de Reporte</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($codigo_reporte); ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha de Estudio *</label>
                    <input type="date" name="fecha_estudio" class="form-control" value="<?php echo $hoy; ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Edad</label>
                    <input type="number" name="edad" class="form-control" placeholder="años" min="10" max="99">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Paciente *</label>
                    <select name="paciente_id" class="form-select" required>
                        <option value="">Seleccionar paciente...</option>
                        <?php foreach ($pacientes as $p): ?>
                            <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nombre'] . ' ' . $p['apellido']); ?></option>
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
                            <option value="<?php echo $m['id']; ?>" <?php echo Auth::id() == $m['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($m['nombre'] . ' ' . $m['apellido']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Médico que Realiza <span class="text-danger">*</span></label>
                    <select name="medico_id" class="form-select" required>
                        <option value="">Seleccionar...</option>
                        <?php foreach ($medicos as $m): ?>
                            <option value="<?php echo $m['id']; ?>" <?php echo Auth::user()['rol_id'] == Auth::ROLE_MEDICO && Auth::id() == $m['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($m['nombre'] . ' ' . $m['apellido']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Médico Referido</label>
                    <select name="medico_referido_id" class="form-select">
                        <option value="">Ninguno</option>
                        <?php foreach ($medicos as $m): ?>
                            <option value="<?php echo $m['id']; ?>"><?php echo htmlspecialchars($m['nombre'] . ' ' . $m['apellido']); ?></option>
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
                    <input type="date" name="fum" id="fum" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">EG Semanas</label>
                    <input type="number" name="edad_gest_semanas" id="eg_semanas" class="form-control" placeholder="sem." min="1" max="11">
                </div>
                <div class="col-md-2">
                    <label class="form-label">EG Días</label>
                    <input type="number" name="edad_gest_dias" id="eg_dias" class="form-control" placeholder="días" min="0" max="6">
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
                        <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="indic_confirmacion_embarazo" id="indic_confirmacion_embarazo" value="1"><label class="form-check-label" for="indic_confirmacion_embarazo">Confirmación de embarazo</label></div></div>
                        <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="indic_sangrado" id="indic_sangrado" value="1"><label class="form-check-label" for="indic_sangrado">Sangrado transvaginal</label></div></div>
                        <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="indic_dolor_pelvico" id="indic_dolor_pelvico" value="1"><label class="form-check-label" for="indic_dolor_pelvico">Dolor pélvico</label></div></div>
                        <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="indic_viabilidad" id="indic_viabilidad" value="1"><label class="form-check-label" for="indic_viabilidad">Valoración de viabilidad</label></div></div>
                        <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="indic_perdidas_gestacionales" id="indic_perdidas_gestacionales" value="1"><label class="form-check-label" for="indic_perdidas_gestacionales">Pérdidas gestacionales</label></div></div>
                        <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="indic_reproduccion_asistida" id="indic_reproduccion_asistida" value="1"><label class="form-check-label" for="indic_reproduccion_asistida">Reproducción asistida</label></div></div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Otro</label>
                        <input type="text" name="indic_otro" class="form-control" placeholder="Especifique...">
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-route me-2"></i>Vía de Exploración</h5></div>
                <div class="card-body">
                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="via_transvaginal" id="via_transvaginal" value="1"><label class="form-check-label" for="via_transvaginal">Transvaginal</label></div>
                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="via_transabdominal" id="via_transabdominal" value="1"><label class="form-check-label" for="via_transabdominal">Transabdominal</label></div>
                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="via_ambas" id="via_ambas" value="1"><label class="form-check-label" for="via_ambas">Ambas</label></div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-uterus me-2"></i>Útero</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Posición</label>
                        <div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="utero_posicion" id="utero_anteroversion" value="Anteroversion"><label class="form-check-label" for="utero_anteroversion">Anteroversión</label></div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="utero_posicion" id="utero_retroversion" value="Retroversion"><label class="form-check-label" for="utero_retroversion">Retroversión</label></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Contornos</label>
                        <div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="utero_contornos" id="utero_contornos_regulares" value="Regulares" checked><label class="form-check-label" for="utero_contornos_regulares">Regulares</label></div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="utero_contornos" id="utero_contornos_irregulares" value="Irregulares"><label class="form-check-label" for="utero_contornos_irregulares">Irregulares</label></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="utero_ecogenicidad_conservada" id="utero_ecogenicidad_conservada" value="1" checked><label class="form-check-label" for="utero_ecogenicidad_conservada">Ecogenicidad conservada</label></div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4"><label class="form-label">Dim. X (mm)</label><input type="number" step="0.1" name="utero_dim_x" class="form-control" placeholder="mm"></div>
                        <div class="col-md-4"><label class="form-label">Dim. Y (mm)</label><input type="number" step="0.1" name="utero_dim_y" class="form-control" placeholder="mm"></div>
                        <div class="col-md-4"><label class="form-label">Dim. Z (mm)</label><input type="number" step="0.1" name="utero_dim_z" class="form-control" placeholder="mm"></div>
                    </div>
                    <div>
                        <label class="form-label fw-bold">Endometrio</label>
                        <input type="text" name="endometrio" class="form-control" placeholder="Describa el endometrio...">
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-location-dot me-2"></i>Localización del Embarazo</h5></div>
                <div class="card-body">
                    <select name="localizacion" id="localizacion" class="form-select mb-2">
                        <option value="">Seleccionar...</option>
                        <option value="Fundica">Fúndica</option>
                        <option value="Corporal">Corporal</option>
                        <option value="Segmentaria">Segmentaria</option>
                        <option value="Cicatriz de cesarea">Cicatriz de cesárea</option>
                        <option value="Otra">Otra</option>
                    </select>
                    <div id="localizacionOtra" style="display:none;">
                        <label class="form-label">Especifique</label>
                        <input type="text" name="localizacion_otra" class="form-control" placeholder="Otra localización...">
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-note-sticky me-2"></i>Decidua</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Saco Gestacional — Tipo</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sg_tipo" id="sg_unico" value="Unico" onchange="toggleSgCantidad()" checked>
                                <label class="form-check-label" for="sg_unico">Único</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sg_tipo" id="sg_multiple" value="Multiple" onchange="toggleSgCantidad()">
                                <label class="form-check-label" for="sg_multiple">Múltiple</label>
                            </div>
                        </div>
                    </div>
                    <div id="sg_cantidad_group" style="display:none;" class="mb-3">
                        <label class="form-label">Número de sacos gestacionales</label>
                        <select name="sg_cantidad" id="sg_cantidad" class="form-select" onchange="toggleSacoCards()">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>

                    <div id="sacos_cards">
                        <?php for ($s = 1; $s <= 4; $s++): ?>
                        <div class="card mb-3 border <?php echo $s > 1 ? 'saco-card-hidden' : ''; ?>" id="saco_card_<?php echo $s; ?>" <?php echo $s > 1 ? 'style="display:none;"' : ''; ?>>
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold">Saco Gestacional #<?php echo $s; ?></h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2 mb-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Medida (mm)</label>
                                        <input type="number" step="0.1" name="saco_<?php echo $s; ?>_medida_mm" class="form-control saco-medida" placeholder="mm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Morfología</label>
                                        <select name="saco_<?php echo $s; ?>_morfologia" class="form-select">
                                            <option value="">—</option>
                                            <option value="Regular">Regular</option>
                                            <option value="Irregular">Irregular</option>
                                        </select>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <label class="form-label fw-bold">Saco Vitelino</label>
                                <div class="mb-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input sv-radio-presente" type="radio" name="saco_<?php echo $s; ?>_sv_presente" id="saco_<?php echo $s; ?>_sv_presente_si" value="1" onchange="toggleSacoVitelinoDetalle(<?php echo $s; ?>)">
                                        <label class="form-check-label" for="saco_<?php echo $s; ?>_sv_presente_si">Presente</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input sv-radio-ausente" type="radio" name="saco_<?php echo $s; ?>_sv_presente" id="saco_<?php echo $s; ?>_sv_presente_no" value="0" onchange="toggleSacoVitelinoDetalle(<?php echo $s; ?>)">
                                        <label class="form-check-label" for="saco_<?php echo $s; ?>_sv_presente_no">Ausente</label>
                                    </div>
                                </div>
                                <div id="saco_<?php echo $s; ?>_sv_detalle" style="display:none;">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label">Diámetro SV (mm)</label>
                                            <input type="number" step="0.1" name="saco_<?php echo $s; ?>_sv_diametro_mm" class="form-control" placeholder="mm">
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <label class="form-label fw-bold">Embrión</label>
                                <div class="mb-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input emb-radio-visible" type="radio" name="saco_<?php echo $s; ?>_embrion_visible" id="saco_<?php echo $s; ?>_embrion_visible_si" value="1" onchange="toggleEmbrionDetalle(<?php echo $s; ?>)">
                                        <label class="form-check-label" for="saco_<?php echo $s; ?>_embrion_visible_si">Visible</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input emb-radio-novisible" type="radio" name="saco_<?php echo $s; ?>_embrion_visible" id="saco_<?php echo $s; ?>_embrion_visible_no" value="0" onchange="toggleEmbrionDetalle(<?php echo $s; ?>)" checked>
                                        <label class="form-check-label" for="saco_<?php echo $s; ?>_embrion_visible_no">No visible</label>
                                    </div>
                                </div>
                                <div id="saco_<?php echo $s; ?>_embrion_detalle" style="display:none;">
                                    <div class="mb-2">
                                        <label class="form-label">Número de embriones en este saco</label>
                                        <select name="saco_<?php echo $s; ?>_num_embriones" class="form-select saco-num-embriones" onchange="toggleEmbrionSubCards(<?php echo $s; ?>)">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                    </div>
                                    <?php for ($e = 1; $e <= 3; $e++): ?>
                                    <div class="border rounded p-2 mb-2 bg-light" id="saco_<?php echo $s; ?>_embrion_card_<?php echo $e; ?>" <?php echo $e > 1 ? 'style="display:none;"' : ''; ?>>
                                        <small class="fw-bold text-muted d-block mb-2">Embrión #<?php echo $e; ?></small>
                                        <div class="row g-1">
                                            <div class="col-md-4">
                                                <label class="form-label small">CRL (mm)</label>
                                                <input type="number" step="0.1" name="saco_<?php echo $s; ?>_embrion_<?php echo $e; ?>_crl" class="form-control form-control-sm crl-input" placeholder="mm">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small">FCF visible</label>
                                                <div class="form-check mt-1">
                                                    <input class="form-check-input fcf-check" type="checkbox" name="saco_<?php echo $s; ?>_embrion_<?php echo $e; ?>_fcf_visible" value="1">
                                                    <label class="form-check-label small">Visible</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small">FCF (lpm)</label>
                                                <input type="number" name="saco_<?php echo $s; ?>_embrion_<?php echo $e; ?>_fcf_lpm" class="form-control form-control-sm fcf-input" placeholder="lpm">
                                            </div>
                                        </div>
                                        <div class="mt-1">
                                            <label class="form-label small">Localización</label>
                                            <input type="text" name="saco_<?php echo $s; ?>_embrion_<?php echo $e; ?>_localizacion" class="form-control form-control-sm" placeholder="Localización...">
                                        </div>
                                    </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                    <small class="text-muted mb-3 d-block">Saco #1 siempre visible. Al seleccionar "Múltiple" se despliegan sacos adicionales.</small>
                    <hr>
                    <label class="form-label fw-bold">Descripción de la Decidua</label>
                    <textarea name="decidua" class="form-control" rows="3" placeholder="Describa la decidua..."></textarea>
                </div>
            </div>
        </div>

        <div class="col-lg-6">

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-heart-pulse me-2"></i>Viabilidad</h5></div>
                <div class="card-body">
                    <label class="form-label fw-bold">Determinación de viabilidad</label>
                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="viabilidad" id="viabilidad_viable" value="Viable" onchange="sugerirDiagnostico()">
                            <label class="form-check-label" for="viabilidad_viable">Viable</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="viabilidad" id="viabilidad_noviable" value="No viable" onchange="sugerirDiagnostico()">
                            <label class="form-check-label" for="viabilidad_noviable">No viable</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="viabilidad" id="viabilidad_incierto" value="Incierto" onchange="sugerirDiagnostico()">
                            <label class="form-check-label" for="viabilidad_incierto">Incierto</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-layer-group me-2"></i>Corion y Amnios</h5></div>
                <div class="card-body">
                    <div class="form-check"><input class="form-check-input" type="checkbox" name="corion_amnios_normal" id="corion_amnios_normal" value="1" checked><label class="form-check-label" for="corion_amnios_normal">Corion y amnios identificables y de aspecto normal para la edad gestacional</label></div>
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
                        <div class="col-md-4"><label class="form-label">Dim. X (mm)</label><input type="number" step="0.1" name="ovario_der_dim_x" class="form-control" placeholder="mm"></div>
                        <div class="col-md-4"><label class="form-label">Dim. Y (mm)</label><input type="number" step="0.1" name="ovario_der_dim_y" class="form-control" placeholder="mm"></div>
                        <div class="col-md-4"><label class="form-label">Dim. Z (mm)</label><input type="number" step="0.1" name="ovario_der_dim_z" class="form-control" placeholder="mm"></div>
                    </div>
                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="ovario_der_normal" id="ovario_der_normal" value="1" checked><label class="form-check-label" for="ovario_der_normal">Normal</label></div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-6"><label class="form-label">Cuerpo lúteo (mm)</label><input type="number" step="0.1" name="ovario_der_cuerpo_luteo_mm" class="form-control" placeholder="mm"></div>
                        <div class="col-md-6"><label class="form-label">Quiste simple (mm)</label><input type="number" step="0.1" name="ovario_der_quiste_simple_mm" class="form-control" placeholder="mm"></div>
                    </div>
                    <div><label class="form-label">Otra alteración</label><input type="text" name="ovario_der_otra_alteracion" class="form-control" placeholder="Especifique..."></div>
                </div>
                <div class="col-lg-6">
                    <h6 class="fw-bold mb-3 text-primary">Ovario Izquierdo</h6>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4"><label class="form-label">Dim. X (mm)</label><input type="number" step="0.1" name="ovario_izq_dim_x" class="form-control" placeholder="mm"></div>
                        <div class="col-md-4"><label class="form-label">Dim. Y (mm)</label><input type="number" step="0.1" name="ovario_izq_dim_y" class="form-control" placeholder="mm"></div>
                        <div class="col-md-4"><label class="form-label">Dim. Z (mm)</label><input type="number" step="0.1" name="ovario_izq_dim_z" class="form-control" placeholder="mm"></div>
                    </div>
                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="ovario_izq_normal" id="ovario_izq_normal" value="1" checked><label class="form-check-label" for="ovario_izq_normal">Normal</label></div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-6"><label class="form-label">Cuerpo lúteo (mm)</label><input type="number" step="0.1" name="ovario_izq_cuerpo_luteo_mm" class="form-control" placeholder="mm"></div>
                        <div class="col-md-6"><label class="form-label">Quiste simple (mm)</label><input type="number" step="0.1" name="ovario_izq_quiste_simple_mm" class="form-control" placeholder="mm"></div>
                    </div>
                    <div><label class="form-label">Otra alteración</label><input type="text" name="ovario_izq_otra_alteracion" class="form-control" placeholder="Especifique..."></div>
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
                    <option value="Libre">Libre</option>
                    <option value="Escasa cantidad de liquido libre">Escasa cantidad de líquido libre</option>
                    <option value="Moderada cantidad de liquido libre">Moderada cantidad de líquido libre</option>
                    <option value="Abundante liquido libre">Abundante líquido libre</option>
                </select>
            </div>
            <hr>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="hematoma_subcorionico" id="hematoma_subcorionico" value="1" onchange="toggleHematoma()">
                <label class="form-check-label fw-bold" for="hematoma_subcorionico">Hematoma subcoriónico</label>
            </div>
            <div id="hematoma_detalles" style="display:none;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Localización</label>
                        <input type="text" name="hematoma_localizacion" class="form-control" placeholder="Localización del hematoma...">
                    </div>
                    <div class="col-md-2"><label class="form-label">Dim. X (mm)</label><input type="number" step="0.1" name="hematoma_dim_x" class="form-control" placeholder="mm"></div>
                    <div class="col-md-2"><label class="form-label">Dim. Y (mm)</label><input type="number" step="0.1" name="hematoma_dim_y" class="form-control" placeholder="mm"></div>
                    <div class="col-md-2"><label class="form-label">Dim. Z (mm)</label><input type="number" step="0.1" name="hematoma_dim_z" class="form-control" placeholder="mm"></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4"><label class="form-label">Volumen estimado (ml)</label><input type="number" step="0.1" name="hematoma_volumen_ml" class="form-control" placeholder="ml"></div>
                </div>
            </div>
            <hr>
            <div class="row g-2">
                <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="miomas_uterinos" id="miomas_uterinos" value="1"><label class="form-check-label" for="miomas_uterinos">Miomas uterinos</label></div></div>
                <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="adenomiosis" id="adenomiosis" value="1"><label class="form-check-label" for="adenomiosis">Adenomiosis</label></div></div>
                <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="malformacion_uterina" id="malformacion_uterina" value="1"><label class="form-check-label" for="malformacion_uterina">Malformación uterina</label></div></div>
            </div>
            <div class="mt-3">
                <label class="form-label">Otros hallazgos</label>
                <textarea name="hallazgos_otro" class="form-control" rows="2" placeholder="Describa otros hallazgos..."></textarea>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-file-lines me-2"></i>Impresión Diagnóstica</h5></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">CRL (mm)</label>
                    <input type="number" step="0.1" name="impresion_crl_mm" id="impresion_crl_mm" class="form-control" placeholder="mm">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Semanas</label>
                    <input type="number" name="impresion_semanas" class="form-control" placeholder="sem">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Días</label>
                    <input type="number" name="impresion_dias" class="form-control" placeholder="días">
                </div>
                <div class="col-md-3">
                    <label class="form-label">FCF (lpm)</label>
                    <input type="number" name="impresion_fcf_lpm" id="impresion_fcf_lpm" class="form-control" placeholder="lpm">
                </div>
            </div>
            <div class="mt-3">
                <label class="form-label">Texto de impresión diagnóstica</label>
                <textarea name="impresion_texto" id="impresion_texto" class="form-control" rows="4" placeholder="Escriba la impresión diagnóstica..."></textarea>
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
            <div class="row">
                <div class="col-md-4">
                    <select name="estado" class="form-select">
                        <option value="Pendiente">Pendiente</option>
                        <option value="En proceso">En proceso</option>
                        <option value="Completado">Completado</option>
                        <option value="Archivado">Archivado</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <a href="<?php echo Url::to('/ultrasonido_temprano'); ?>" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-apple btn-apple-primary"><i class="fa-solid fa-save me-1"></i> Guardar</button>
            </div>
        </div>
    </div>
</form>

<script>
const today = '<?php echo $hoy; ?>';

function toggleSgCantidad() {
    var multiple = document.getElementById('sg_multiple');
    var cntGroup = document.getElementById('sg_cantidad_group');
    if (multiple && multiple.checked) {
        cntGroup.style.display = 'block';
        document.getElementById('sg_cantidad').value = '2';
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
        crlInputs.forEach(function(inp) {
            if (parseFloat(inp.value) >= 7) hasCrlGte7 = true;
        });
        var anyFcf = false;
        fcfChecks.forEach(function(chk) {
            if (chk.checked) anyFcf = true;
        });
        var maxSaco = 0;
        sacoMedidas.forEach(function(inp) {
            var v = parseFloat(inp.value);
            if (v > maxSaco) maxSaco = v;
        });

        var embrionVisibleAny = document.querySelector('input[id$="_embrion_visible_si"]:checked');

        var sugerencia = '';
        if (hasCrlGte7 && !anyFcf) {
            sugerencia = 'Embarazo NO viable. Una sola ecografía transvaginal que identifica un embrión con CRL ≥ 7 mm sin actividad cardíaca se considera prueba definitiva de pérdida gestacional precoz.';
        } else if (maxSaco >= 25 && !embrionVisibleAny) {
            sugerencia = 'Embarazo NO viable. Una sola ecografía transvaginal que identifica un saco gestacional con diámetro medio ≥ 25 mm sin embrión se considera prueba definitiva de pérdida gestacional precoz.';
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
