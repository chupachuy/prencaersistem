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
                <div class="col-md-6">
                    <label class="form-label">Paciente *</label>
                    <select name="paciente_id" class="form-select" required>
                        <option value="">Seleccionar paciente...</option>
                        <?php foreach ($pacientes as $p): ?>
                            <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nombre'] . ' ' . $p['apellido']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Médico *</label>
                    <select name="medico_id" class="form-select" required>
                        <option value="">Seleccionar médico...</option>
                        <?php foreach ($medicos as $m): ?>
                            <option value="<?php echo $m['id']; ?>" <?php echo Auth::user()['rol_id'] == Auth::ROLE_MEDICO && Auth::id() == $m['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($m['nombre'] . ' ' . $m['apellido']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
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
                        <label class="form-label">Posición</label>
                        <div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="utero_posicion" id="utero_anteroversion" value="Anteroversion"><label class="form-check-label" for="utero_anteroversion">Anteroversión</label></div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="utero_posicion" id="utero_retroversion" value="Retroversion"><label class="form-check-label" for="utero_retroversion">Retroversión</label></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="utero_contornos_regulares" id="utero_contornos_regulares" value="1" checked><label class="form-check-label" for="utero_contornos_regulares">Contornos regulares</label></div>
                        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="utero_ecogenicidad_conservada" id="utero_ecogenicidad_conservada" value="1" checked><label class="form-check-label" for="utero_ecogenicidad_conservada">Ecogenicidad conservada</label></div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4"><label class="form-label">Dim. X (mm)</label><input type="number" step="0.1" name="utero_dim_x" class="form-control" placeholder="mm"></div>
                        <div class="col-md-4"><label class="form-label">Dim. Y (mm)</label><input type="number" step="0.1" name="utero_dim_y" class="form-control" placeholder="mm"></div>
                        <div class="col-md-4"><label class="form-label">Dim. Z (mm)</label><input type="number" step="0.1" name="utero_dim_z" class="form-control" placeholder="mm"></div>
                    </div>
                    <div>
                        <label class="form-label">Endometrio</label>
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
        </div>

        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-circle me-2"></i>Saco Gestacional</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="sg_tipo" id="sg_unico" value="Unico"><label class="form-check-label" for="sg_unico">Único</label></div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="sg_tipo" id="sg_multiple" value="Multiple"><label class="form-check-label" for="sg_multiple">Múltiple</label></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Morfología</label>
                        <div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="sg_morfologia" id="sg_regular" value="Regular"><label class="form-check-label" for="sg_regular">Regular</label></div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="sg_morfologia" id="sg_irregular" value="Irregular"><label class="form-check-label" for="sg_irregular">Irregular</label></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Medida (mm)</label>
                            <input type="number" step="0.1" name="sg_medida_mm" class="form-control" placeholder="mm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-circle-dot me-2"></i>Saco Vitelino</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="sv_presente" id="sv_presente_si" value="1" onchange="toggleSacoVitelino()"><label class="form-check-label" for="sv_presente_si">Presente</label></div>
                        <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="sv_presente" id="sv_presente_no" value="0" onchange="toggleSacoVitelino()"><label class="form-check-label" for="sv_presente_no">Ausente</label></div>
                    </div>
                    <div id="sv_detalles" style="display:none;">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label">Cantidad</label>
                                <select name="sv_cantidad" class="form-select">
                                    <option value="">—</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Diámetro (mm)</label>
                                <input type="number" step="0.1" name="sv_diametro_mm" class="form-control" placeholder="mm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-heart-pulse me-2"></i>Embrión</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="embrion_visible" id="embrion_visible_si" value="1" onchange="toggleEmbrión()"><label class="form-check-label" for="embrion_visible_si">Visible</label></div>
                        <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="embrion_visible" id="embrion_visible_no" value="0" onchange="toggleEmbrión()" checked><label class="form-check-label" for="embrion_visible_no">No visible</label></div>
                    </div>
                    <div id="embrion_detalles" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label">Número de embriones</label>
                            <select name="num_embriones" id="numEmbriones" class="form-select" onchange="toggleEmbriónCards()">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                        <div id="embriones_cards">
                            <?php for ($e = 1; $e <= 3; $e++): ?>
                            <div class="card mb-3 border" id="embrion_card_<?php echo $e; ?>" <?php echo $e > 1 ? 'style="display:none;"' : ''; ?>>
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">Embrión #<?php echo $e; ?></h6>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label">CRL (mm)</label>
                                            <input type="number" step="0.1" name="embrion_<?php echo $e; ?>_crl" class="form-control" placeholder="mm">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">FCF visible</label>
                                            <div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="embrion_<?php echo $e; ?>_fcf_visible" value="1"><label class="form-check-label">Visible</label></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">FCF (lpm)</label>
                                            <input type="number" name="embrion_<?php echo $e; ?>_fcf_lpm" class="form-control" placeholder="lpm">
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label">Localización</label>
                                        <input type="text" name="embrion_<?php echo $e; ?>_localizacion" class="form-control" placeholder="Localización del embrión...">
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
                    <div class="col-md-2">
                        <label class="form-label">Dim. X (mm)</label>
                        <input type="number" step="0.1" name="hematoma_dim_x" class="form-control" placeholder="mm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Dim. Y (mm)</label>
                        <input type="number" step="0.1" name="hematoma_dim_y" class="form-control" placeholder="mm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Dim. Z (mm)</label>
                        <input type="number" step="0.1" name="hematoma_dim_z" class="form-control" placeholder="mm">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label class="form-label">Volumen estimado (ml)</label>
                        <input type="number" step="0.1" name="hematoma_volumen_ml" class="form-control" placeholder="ml">
                    </div>
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
                    <input type="number" step="0.1" name="impresion_crl_mm" class="form-control" placeholder="mm">
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
                    <input type="number" name="impresion_fcf_lpm" class="form-control" placeholder="lpm">
                </div>
            </div>
            <div class="mt-3">
                <label class="form-label">Texto de impresión diagnóstica</label>
                <textarea name="impresion_texto" class="form-control" rows="4" placeholder="Escriba la impresión diagnóstica..."></textarea>
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
