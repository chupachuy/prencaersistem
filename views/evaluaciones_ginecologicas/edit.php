<?php
$title = "USG Ginecológico — " . htmlspecialchars($evaluacion['codigo_reporte']);
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

// Unified helper: searches evaluacion first, then all satellites for any field
$allData = array_merge(
    $evaluacion,
    $indicaciones ?: [],
    $antecedentes ?: [],
    $tecnica ?: [],
    $uteroCervix ?: [],
    $miomas ?: [],
    $adenomiosis ?: [],
    $endometrio ?: [],
    $ovarios ?: [],
    $anexos ?: [],
    $clasificacion ?: [],
    $impresion ?: [],
    $conclusion ?: []
);

$chk = fn($f) => !empty($allData[$f]) ? 'checked' : '';
$sel = fn($f, $v) => (isset($allData[$f]) && $allData[$f] == $v) ? 'selected' : '';
$val = fn($f) => htmlspecialchars($allData[$f] ?? '');
$num = fn($f) => htmlspecialchars($allData[$f] ?? '');
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/evaluaciones_ginecologicas'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0">Editar <?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></h1>
    </div>
</div>

<form action="<?php echo Url::to('/evaluaciones_ginecologicas/update'); ?>" method="POST" id="formEvaluacionGine">
    <input type="hidden" name="id" value="<?php echo $evaluacion['id']; ?>">
    <input type="hidden" name="codigo_reporte" value="<?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?>">

    <!-- Datos Generales -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-id-card me-2"></i>Datos Generales</h5></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Paciente</label>
                    <select name="paciente_id" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($pacientes as $p): ?>
                        <option value="<?php echo $p['id']; ?>" <?php echo $sel('paciente_id', $p['id']); ?>><?php echo htmlspecialchars($p['nombre'].' '.$p['apellido']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Médico que realiza</label>
                    <select name="medico_id" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($medicos as $m): ?>
                        <option value="<?php echo $m['id']; ?>" <?php echo $sel('medico_id', $m['id']); ?>><?php echo htmlspecialchars($m['nombre'].' '.$m['apellido']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Médico solicitante</label>
                    <select name="medico_solicitante_id" class="form-select">
                        <option value="">Ninguno</option>
                        <?php foreach ($medicos as $m): ?>
                        <option value="<?php echo $m['id']; ?>" <?php echo $sel('medico_solicitante_id', $m['id']); ?>><?php echo htmlspecialchars($m['nombre'].' '.$m['apellido']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha del estudio</label>
                    <input type="date" name="fecha_estudio" class="form-control" value="<?php echo $val('fecha_estudio'); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">FUM</label>
                    <input type="date" name="fum" class="form-control" value="<?php echo $val('fum'); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Día del ciclo</label>
                    <input type="number" name="dia_ciclo_menstrual" class="form-control" min="1" max="45" value="<?php echo $num('dia_ciclo_menstrual'); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="Pendiente" <?php echo $sel('estado','Pendiente'); ?>>Pendiente</option>
                        <option value="En proceso" <?php echo $sel('estado','En proceso'); ?>>En proceso</option>
                        <option value="Completado" <?php echo $sel('estado','Completado'); ?>>Completado</option>
                        <option value="Archivado" <?php echo $sel('estado','Archivado'); ?>>Archivado</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Indicación clínica</label>
                    <textarea name="indicacion_clinica" class="form-control" rows="2"><?php echo $val('indicacion_clinica'); ?></textarea>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2"><?php echo $val('observaciones'); ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicaciones -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-clipboard-list me-2"></i>Motivo del Estudio y Estatus Hormonal</h5></div>
        <div class="card-body">
            <h6 class="fw-bold">Motivo del estudio</h6>
            <div class="row g-2">
                <?php
                $motivos = [
                    'sangrado_uterino_anormal' => 'Sangrado uterino anormal', 'dolor_pelvico' => 'Dolor pélvico',
                    'miomatosis_uterina' => 'Miomatosis uterina', 'sospecha_polipo_endometrial' => 'Sospecha de pólipo endometrial',
                    'engrosamiento_endometrial' => 'Engrosamiento endometrial', 'control_diu' => 'Control de DIU',
                    'infertilidad_reproduccion' => 'Infertilidad / reproducción', 'quiste_ovarico_masa_anexial' => 'Quiste ovárico / masa anexial',
                    'sindrome_climaterico' => 'Síndrome climatérico / perimenopausia', 'sangrado_posmenopausico' => 'Sangrado posmenopáusico'
                ];
                foreach ($motivos as $k => $l): ?>
                <div class="col-md-3"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                <?php endforeach; ?>
                <div class="col-md-6"><input type="text" name="motivo_estudio_otro" class="form-control form-control-sm" placeholder="Otro motivo..." value="<?php echo $val('motivo_estudio_otro'); ?>"></div>
            </div>
            <h6 class="fw-bold mt-3">Estatus hormonal</h6>
            <div class="row g-2">
                <?php
                $hormonales = [
                    'premenopausica'=>'Premenopáusica','perimenopausica'=>'Perimenopáusica','posmenopausica'=>'Posmenopáusica',
                    'terapia_hormonal'=>'Uso de terapia hormonal','tamoxifeno'=>'Uso de tamoxifeno',
                    'anticonceptivos_hormonales'=>'Anticonceptivos hormonales','estatus_no_especificado'=>'No especificado'
                ];
                foreach ($hormonales as $k => $l): ?>
                <div class="col-md-3"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Antecedentes -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-history me-2"></i>Antecedentes</h5></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><label class="form-label">Gesta</label><input type="number" name="gesta" class="form-control" value="<?php echo $num('gesta'); ?>"></div>
                <div class="col-md-3"><label class="form-label">Para</label><input type="number" name="para" class="form-control" value="<?php echo $num('para'); ?>"></div>
                <div class="col-md-3"><label class="form-label">Cesáreas</label><input type="number" name="cesareas" class="form-control" value="<?php echo $num('cesareas'); ?>"></div>
                <div class="col-md-3"><label class="form-label">Abortos</label><input type="number" name="abortos" class="form-control" value="<?php echo $num('abortos'); ?>"></div>
            </div>
            <div class="mt-3 row g-2">
                <div class="col-md-12"><label class="form-label fw-bold">Paridad satisfecha</label>
                    <div class="form-check form-check-inline"><input type="radio" name="paridad_satisfecha" value="1" class="form-check-input" <?php echo (isset($allData['paridad_satisfecha']) && $allData['paridad_satisfecha'] === 1) ? 'checked' : ''; ?>><label class="form-check-label">Sí</label></div>
                    <div class="form-check form-check-inline"><input type="radio" name="paridad_satisfecha" value="0" class="form-check-input" <?php echo (isset($allData['paridad_satisfecha']) && $allData['paridad_satisfecha'] === 0) ? 'checked' : ''; ?>><label class="form-check-label">No</label></div>
                </div>
                <div class="col-md-3"><div class="form-check"><input type="checkbox" name="legrado_cirugia_uterina" class="form-check-input" id="legrado_cirugia_uterina" <?php echo $chk('legrado_cirugia_uterina'); ?>><label class="form-check-label" for="legrado_cirugia_uterina">Legrado / cirugía uterina</label></div></div>
                <div class="col-md-3"><div class="form-check"><input type="checkbox" name="miomectomia" class="form-check-input" id="miomectomia" <?php echo $chk('miomectomia'); ?>><label class="form-check-label" for="miomectomia">Miomectomía</label></div></div>
                <div class="col-md-3"><div class="form-check"><input type="checkbox" name="endometriosis_adenomiosis" class="form-check-input" id="endometriosis_adenomiosis" <?php echo $chk('endometriosis_adenomiosis'); ?>><label class="form-check-label" for="endometriosis_adenomiosis">Endometriosis / adenomiosis</label></div></div>
            </div>
            <div class="mt-3"><textarea name="antecedentes_otros" class="form-control" rows="2" placeholder="Otros antecedentes..."><?php echo $val('otros'); ?></textarea></div>
        </div>
    </div>

    <!-- Técnica -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-wave-square me-2"></i>Técnica</h5></div>
        <div class="card-body">
            <h6 class="fw-bold">Vía de exploración</h6>
            <div class="row g-2">
                <?php
                $vias = ['via_endovaginal'=>'Endovaginal','via_transabdominal'=>'Transabdominal','via_doppler_color'=>'Doppler color / Power Doppler','via_evaluacion_3d'=>'Evaluación 3D','via_sonohisterografia'=>'Sonohisterografía'];
                foreach ($vias as $k => $l): ?>
                <div class="col-md-3"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                <?php endforeach; ?>
            </div>
            <h6 class="fw-bold mt-3">Calidad del estudio</h6>
            <div class="row g-3">
                <div class="col-md-3"><select name="calidad" class="form-select"><option value="">—</option><option value="Adecuada" <?php echo $sel('calidad','Adecuada'); ?>>Adecuada</option><option value="Limitada" <?php echo $sel('calidad','Limitada'); ?>>Limitada</option></select></div>
                <?php
                $lims = ['limitada_dolor'=>'Dolor','limitada_distension_intestinal'=>'Distensión intestinal','limitada_habitus_corporal'=>'Habitus corporal','limitada_posicion_uterina'=>'Posición uterina'];
                foreach ($lims as $k => $l): ?>
                <div class="col-md-3"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                <?php endforeach; ?>
                <div class="col-md-6"><input type="text" name="calidad_otra" class="form-control form-control-sm" placeholder="Otra limitación..." value="<?php echo $val('calidad_otra'); ?>"></div>
            </div>
        </div>
    </div>

    <!-- Útero + Cérvix -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-uterus me-2"></i>Útero y Cérvix</h5></div>
        <div class="card-body">
            <h6 class="fw-bold">Situación uterina</h6>
            <select name="situacion" class="form-select mb-3"><option value="">—</option>
                <?php foreach (['Anteversoflexion','Retroversoflexion','Intermedia','Lateralizado'] as $o): ?>
                <option value="<?php echo $o; ?>" <?php echo $sel('situacion',$o); ?>><?php echo $o; ?></option>
                <?php endforeach; ?>
            </select>
            <h6 class="fw-bold">Morfología</h6>
            <div class="row g-2">
                <?php
                $morfs = ['morfologia_regular'=>'Regular','morfologia_bordes_irregulares'=>'Bordes irregulares','morfologia_globoso'=>'Globoso','morfologia_aumentado'=>'Aumentado','morfologia_disminuido'=>'Disminuido'];
                foreach ($morfs as $k => $l): ?>
                <div class="col-md-2"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                <?php endforeach; ?>
                <div class="col-md-4"><input type="text" name="morfologia_otro" class="form-control form-control-sm" placeholder="Otro..." value="<?php echo $val('morfologia_otro'); ?>"></div>
            </div>
            <h6 class="fw-bold mt-3">Dimensiones uterinas</h6>
            <div class="row g-3">
                <div class="col-md-3"><label class="form-label">Longitud (mm)</label><input type="number" step="0.01" name="dim_longitud_mm" class="form-control" value="<?php echo $num('dim_longitud_mm'); ?>"></div>
                <div class="col-md-3"><label class="form-label">Anteroposterior (mm)</label><input type="number" step="0.01" name="dim_anteroposterior_mm" class="form-control" value="<?php echo $num('dim_anteroposterior_mm'); ?>"></div>
                <div class="col-md-3"><label class="form-label">Transverso (mm)</label><input type="number" step="0.01" name="dim_transverso_mm" class="form-control" value="<?php echo $num('dim_transverso_mm'); ?>"></div>
                <div class="col-md-3"><label class="form-label">Volumen (cc)</label><input type="number" step="0.01" name="volumen_cc" class="form-control" value="<?php echo $num('volumen_cc'); ?>"></div>
            </div>
            <h6 class="fw-bold mt-3">Miometrio</h6>
            <div class="row g-2">
                <?php
                $mios = ['miometrio_homogeneo'=>'Homogéneo','miometrio_heterogeneo'=>'Heterogéneo','miometrio_imagenes_leiomiomas'=>'Leiomiomas','miometrio_sugestivo_adenomiosis'=>'Sugestivo adenomiosis','miometrio_calcificaciones'=>'Calcificaciones','miometrio_areas_quisticas'=>'Áreas quísticas','miometrio_sombra_acustica'=>'Sombra acústica'];
                foreach ($mios as $k => $l): ?>
                <div class="col-md-3"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                <?php endforeach; ?>
                <div class="col-md-4"><input type="text" name="miometrio_otro" class="form-control form-control-sm" placeholder="Otro..." value="<?php echo $val('miometrio_otro'); ?>"></div>
            </div>
            <hr>
            <h6 class="fw-bold">Cérvix</h6>
            <div class="row g-3">
                <div class="col-md-3"><label class="form-label">Longitud cervical (mm)</label><input type="number" step="0.01" name="cervix_longitud_mm" class="form-control" value="<?php echo $num('cervix_longitud_mm'); ?>"></div>
            </div>
            <div class="row g-2 mt-2">
                <?php
                $cerv = ['cervix_sin_alteraciones'=>'Sin alteraciones','cervix_quistes_naboth'=>'Quistes de Naboth','cervix_polipo_endocervical'=>'Pólipo endocervical','cervix_lesion_visible_usg'=>'Lesión visible por USG','cervix_liquido_canal'=>'Líquido en canal cervical'];
                foreach ($cerv as $k => $l): ?>
                <div class="col-md-3"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                <?php endforeach; ?>
                <div class="col-md-4"><input type="text" name="cervix_otro" class="form-control form-control-sm" placeholder="Otro..." value="<?php echo $val('cervix_otro'); ?>"></div>
            </div>
        </div>
    </div>

    <!-- Miomas -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-circle-notch me-2"></i>Miomas / Leiomiomas</h5></div>
        <div class="card-body">
            <div class="form-check mb-3"><input type="checkbox" name="miomas_identificados" class="form-check-input" id="miomas_identificados" <?php echo $chk('identificados'); ?>><label class="form-check-label" for="miomas_identificados">Se identifican imágenes compatibles con leiomiomas</label></div>
            <div class="row g-3">
                <div class="col-md-3"><label class="form-label">Número aproximado</label><input type="number" name="miomas_numero_aproximado" class="form-control" value="<?php echo $num('numero_aproximado'); ?>"></div>
                <div class="col-md-3"><label class="form-label">Mioma dominante (mm)</label><input type="number" step="0.01" name="mioma_dominante_mm" class="form-control" value="<?php echo $num('mioma_dominante_mm'); ?>"></div>
            </div>
            <h6 class="fw-bold mt-3">Predominio</h6>
            <div class="row g-2">
                <?php
                $pre = ['predominio_submucosos'=>'Submucosos','predominio_intramurales'=>'Intramurales','predominio_subserosos'=>'Subserosos','predominio_pediculados'=>'Pediculados','predominio_cervicales'=>'Cervicales','predominio_distribucion_difusa'=>'Múltiples difusos'];
                foreach ($pre as $k => $l): ?>
                <div class="col-md-2"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                <?php endforeach; ?>
            </div>
            <h6 class="fw-bold mt-3">Detalle por mioma</h6>
            <table class="table table-sm table-bordered" id="miomasDetalleTable">
                <thead><tr><th>#</th><th>Localización</th><th>X mm</th><th>Y mm</th><th>Z mm</th><th>Relación endometrio</th><th>FIGO</th><th>Doppler</th><th>Comentarios</th><th></th></tr></thead>
                <tbody id="miomasDetalleBody">
                    <?php if (!empty($miomasDetalle)): foreach ($miomasDetalle as $i => $md): ?>
                    <tr>
                        <td><?php echo $md['numero']; ?></td>
                        <td><select name="md_localizacion[]" class="form-select form-select-sm"><option value=""></option><?php foreach (['Fondo','Anterior','Posterior','Lateral','Cervical'] as $o): ?><option value="<?php echo $o; ?>" <?php echo ($md['localizacion'] ?? '') == $o ? 'selected' : ''; ?>><?php echo $o; ?></option><?php endforeach; ?></select></td>
                        <td><input type="number" step="0.01" name="md_medida_x[]" class="form-control form-control-sm" value="<?php echo htmlspecialchars($md['medida_x_mm'] ?? ''); ?>"></td>
                        <td><input type="number" step="0.01" name="md_medida_y[]" class="form-control form-control-sm" value="<?php echo htmlspecialchars($md['medida_y_mm'] ?? ''); ?>"></td>
                        <td><input type="number" step="0.01" name="md_medida_z[]" class="form-control form-control-sm" value="<?php echo htmlspecialchars($md['medida_z_mm'] ?? ''); ?>"></td>
                        <td><select name="md_relacion[]" class="form-select form-select-sm"><option value=""></option><?php foreach (['No contacta','Contacta','Desplaza','Distorsiona cavidad'] as $o): ?><option value="<?php echo $o; ?>" <?php echo ($md['relacion_endometrio'] ?? '') == $o ? 'selected' : ''; ?>><?php echo $o; ?></option><?php endforeach; ?></select></td>
                        <td><input type="text" name="md_figo[]" class="form-control form-control-sm" placeholder="FIGO 0-8" value="<?php echo htmlspecialchars($md['clasificacion_figo'] ?? ''); ?>"></td>
                        <td><select name="md_doppler[]" class="form-select form-select-sm"><option value=""></option><?php foreach (['Escaso','Moderado','Aumentado'] as $o): ?><option value="<?php echo $o; ?>" <?php echo ($md['doppler'] ?? '') == $o ? 'selected' : ''; ?>><?php echo $o; ?></option><?php endforeach; ?></select></td>
                        <td><input type="text" name="md_comentarios[]" class="form-control form-control-sm" value="<?php echo htmlspecialchars($md['comentarios'] ?? ''); ?>"></td>
                        <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()"><i class="fa-solid fa-times"></i></button></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
            <button type="button" class="btn btn-sm btn-apple-primary" id="addMiomaRow"><i class="fa-solid fa-plus"></i> Agregar mioma</button>
            <p class="text-muted mt-2 small"><strong>FIGO:</strong> 0=Intracavitario pediculado | 1=Submucoso <50% intramural | 2=Submucoso >=50% | 3=Intramural contacto endometrio | 4=Intramural puro | 5=Subseroso >=50% intramural | 6=Subseroso <50% | 7=Subseroso pediculado | 8=Especial</p>
        </div>
    </div>

    <!-- Adenomiosis -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-magnifying-glass me-2"></i>Adenomiosis</h5></div>
        <div class="card-body">
            <div class="mb-3"><label class="form-label">Hallazgos</label><select name="adeno_hallazgos" class="form-select"><option value="">—</option><?php foreach (['No se observan','Si se observan','Indeterminado'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel('hallazgos',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
            <h6 class="fw-bold">Datos sonográficos</h6>
            <div class="row g-2">
                <?php
                $ad = ['adeno_utero_globoso'=>'Útero globoso','adeno_asimetria_paredes'=>'Asimetría de paredes','adeno_miometrio_heterogeneo'=>'Miometrio heterogéneo','adeno_estriaciones_lineales'=>'Estriaciones lineales','adeno_quistes_miometriales'=>'Quistes miometriales','adeno_islas_hiperecogenicas'=>'Islas hiperecogénicas','adeno_sombra_abanico'=>'Sombra en abanico','adeno_zona_union_irregular'=>'Zona de unión irregular','adeno_vascularidad_translesional'=>'Vascularidad translesional'];
                foreach ($ad as $k => $l): ?>
                <div class="col-md-3"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                <?php endforeach; ?>
                <div class="col-md-6"><input type="text" name="adeno_datos_otro" class="form-control form-control-sm" placeholder="Otro..." value="<?php echo $val('datos_otro'); ?>"></div>
            </div>
            <h6 class="fw-bold mt-3">Distribución</h6>
            <select name="adeno_distribucion" class="form-select mb-2"><option value="">—</option><?php foreach (['Difusa','Focal'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel('distribucion',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select>
            <div class="row g-2">
                <?php foreach (['adeno_predominio_anterior'=>'Anterior','adeno_predominio_posterior'=>'Posterior','adeno_predominio_fundico'=>'Fúndico'] as $k=>$l): ?>
                <div class="col-md-2"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Endometrio -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-layer-group me-2"></i>Endometrio</h5></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><label class="form-label">Grosor (mm)</label><input type="number" step="0.01" name="endometrio_grosor_mm" class="form-control" value="<?php echo $num('grosor_mm'); ?>"></div>
                <div class="col-md-3"><label class="form-label">Patrón</label><select name="endometrio_patron" class="form-select"><option value="">—</option><?php foreach (['Lineal','Trilaminar','Hiperecogenico','Heterogeneo','Quistico','Irregular','NoValorable'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel('patron',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
                <div class="col-md-3"><label class="form-label">Correlación con ciclo</label><select name="endometrio_correlacion_ciclo" class="form-select"><option value="">—</option><?php foreach (['Acorde','Engrosado','Delgado','NoValorableSangrado','NoValorableMiomas'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel('correlacion_ciclo',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
            </div>
            <h6 class="fw-bold mt-3">Cavidad endometrial</h6>
            <div class="row g-2">
                <?php
                $cav = ['endometrio_cavidad_regular'=>'Regular','endometrio_cavidad_distorsionada'=>'Distorsionada','endometrio_cavidad_liquido'=>'Líquido intracavitario','endometrio_cavidad_polipo'=>'Imagen focal (pólipo)','endometrio_cavidad_mioma_submucoso'=>'Mioma submucoso','endometrio_cavidad_sinequias'=>'Sinequias','endometrio_cavidad_diu'=>'DIU intrauterino'];
                foreach ($cav as $k => $l): ?>
                <div class="col-md-3"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                <?php endforeach; ?>
                <div class="col-md-6"><input type="text" name="endometrio_cavidad_otro" class="form-control form-control-sm" placeholder="Otro..." value="<?php echo $val('cavidad_otro'); ?>"></div>
            </div>
            <h6 class="fw-bold mt-3">Doppler endometrial</h6>
            <select name="endometrio_doppler" class="form-select"><option value="">—</option><?php foreach (['SinVascularidad','VasoUnicoPolipo','VascularidadDifusa','VascularidadIrregular','NoEvaluado'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel('doppler',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select>
            <h6 class="fw-bold mt-3">DIU</h6>
            <div class="row g-3">
                <div class="col-md-4"><select name="diu_posicion" class="form-select"><option value="">—</option><?php foreach (['Normoinserto','Descendido','ParcialmenteExpulsado','BrazoIncluidoMiometrio','NoVisible'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel('diu_posicion',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
                <div class="col-md-3"><label class="form-label">Distancia al fondo (mm)</label><input type="number" step="0.01" name="diu_distancia_fondo_mm" class="form-control" value="<?php echo $num('diu_distancia_fondo_mm'); ?>"></div>
            </div>
        </div>
    </div>

    <!-- Ovarios -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-circle me-2"></i>Ovarios</h5></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 border-end">
                    <h6 class="fw-bold">Ovario Derecho</h6>
                    <div class="row g-2 mb-2">
                        <div class="col-3"><input type="number" step="0.01" name="der_dim_x_mm" class="form-control form-control-sm" placeholder="X mm" value="<?php echo $num('der_dim_x_mm'); ?>"></div>
                        <div class="col-3"><input type="number" step="0.01" name="der_dim_y_mm" class="form-control form-control-sm" placeholder="Y mm" value="<?php echo $num('der_dim_y_mm'); ?>"></div>
                        <div class="col-3"><input type="number" step="0.01" name="der_dim_z_mm" class="form-control form-control-sm" placeholder="Z mm" value="<?php echo $num('der_dim_z_mm'); ?>"></div>
                        <div class="col-3"><input type="number" step="0.01" name="der_volumen_cc" class="form-control form-control-sm" placeholder="Vol cc" value="<?php echo $num('der_volumen_cc'); ?>"></div>
                    </div>
                    <div class="row g-1">
                        <?php $derMorf = ['der_normal'=>'Normal','der_atrofico'=>'Atrófico','der_multifolicular'=>'Multifolicular','der_poliquistico'=>'Poliquístico','der_cuerpo_luteo'=>'Cuerpo lúteo','der_quiste_simple'=>'Quiste simple','der_quiste_hemorragico'=>'Quiste hemorrágico','der_endometrioma'=>'Endometrioma','der_lesion_solida'=>'Lesión sólida','der_lesion_compleja'=>'Lesión compleja','der_no_visible'=>'No visible'];
                        foreach ($derMorf as $k => $l): ?>
                        <div class="col-md-4"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label small" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                        <?php endforeach; ?>
                    </div>
                    <p class="mt-2 mb-1 fw-bold small">Folículo dominante / lesión</p>
                    <div class="row g-2">
                        <div class="col-4"><input type="number" step="0.01" name="der_foliculo_med_x_mm" class="form-control form-control-sm" placeholder="X mm" value="<?php echo $num('der_foliculo_med_x_mm'); ?>"></div>
                        <div class="col-4"><input type="number" step="0.01" name="der_foliculo_med_y_mm" class="form-control form-control-sm" placeholder="Y mm" value="<?php echo $num('der_foliculo_med_y_mm'); ?>"></div>
                        <div class="col-4"><input type="number" step="0.01" name="der_foliculo_med_z_mm" class="form-control form-control-sm" placeholder="Z mm" value="<?php echo $num('der_foliculo_med_z_mm'); ?>"></div>
                        <div class="col-6"><select name="der_foliculo_contenido" class="form-select form-select-sm"><option value="">Contenido</option><?php foreach (['Anecoico','Hemorragico','EcosFinos','Solido','Mixto'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel('der_foliculo_contenido',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
                        <div class="col-6"><select name="der_foliculo_pared" class="form-select form-select-sm"><option value="">Pared</option><?php foreach (['Fina','Gruesa','Irregular'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel('der_foliculo_pared',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
                        <div class="col-4"><div class="form-check"><input type="checkbox" name="der_foliculo_septos" class="form-check-input" id="der_foliculo_septos" <?php echo $chk('der_foliculo_septos'); ?>><label class="form-check-label small" for="der_foliculo_septos">Septos</label></div><input type="number" step="0.01" name="der_foliculo_septos_grosor" class="form-control form-control-sm mt-1" placeholder="Grosor mm" value="<?php echo $num('der_foliculo_septos_grosor'); ?>"></div>
                        <div class="col-4"><div class="form-check"><input type="checkbox" name="der_foliculo_papilares" class="form-check-input" id="der_foliculo_papilares" <?php echo $chk('der_foliculo_papilares'); ?>><label class="form-check-label small" for="der_foliculo_papilares">Papilares</label></div><input type="number" name="der_foliculo_papilares_num" class="form-control form-control-sm mt-1" placeholder="Número" value="<?php echo $num('der_foliculo_papilares_num'); ?>"></div>
                        <div class="col-4"><div class="form-check"><input type="checkbox" name="der_foliculo_sombra" class="form-check-input" id="der_foliculo_sombra" <?php echo $chk('der_foliculo_sombra'); ?>><label class="form-check-label small" for="der_foliculo_sombra">Sombra acústica</label></div></div>
                        <div class="col-6"><select name="der_foliculo_doppler" class="form-select form-select-sm"><option value="">Doppler</option><?php foreach (['SinFlujo','FlujoPeriferico','FlujoCentral','FlujoComponenteSolido'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel('der_foliculo_doppler',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold">Ovario Izquierdo</h6>
                    <div class="row g-2 mb-2">
                        <div class="col-3"><input type="number" step="0.01" name="izq_dim_x_mm" class="form-control form-control-sm" placeholder="X mm" value="<?php echo $num('izq_dim_x_mm'); ?>"></div>
                        <div class="col-3"><input type="number" step="0.01" name="izq_dim_y_mm" class="form-control form-control-sm" placeholder="Y mm" value="<?php echo $num('izq_dim_y_mm'); ?>"></div>
                        <div class="col-3"><input type="number" step="0.01" name="izq_dim_z_mm" class="form-control form-control-sm" placeholder="Z mm" value="<?php echo $num('izq_dim_z_mm'); ?>"></div>
                        <div class="col-3"><input type="number" step="0.01" name="izq_volumen_cc" class="form-control form-control-sm" placeholder="Vol cc" value="<?php echo $num('izq_volumen_cc'); ?>"></div>
                    </div>
                    <div class="row g-1">
                        <?php $izqMorf = ['izq_normal'=>'Normal','izq_atrofico'=>'Atrófico','izq_multifolicular'=>'Multifolicular','izq_poliquistico'=>'Poliquístico','izq_cuerpo_luteo'=>'Cuerpo lúteo','izq_quiste_simple'=>'Quiste simple','izq_quiste_hemorragico'=>'Quiste hemorrágico','izq_endometrioma'=>'Endometrioma','izq_lesion_solida'=>'Lesión sólida','izq_lesion_compleja'=>'Lesión compleja','izq_no_visible'=>'No visible'];
                        foreach ($izqMorf as $k => $l): ?>
                        <div class="col-md-4"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label small" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                        <?php endforeach; ?>
                    </div>
                    <p class="mt-2 mb-1 fw-bold small">Folículo dominante / lesión</p>
                    <div class="row g-2">
                        <div class="col-4"><input type="number" step="0.01" name="izq_foliculo_med_x_mm" class="form-control form-control-sm" placeholder="X mm" value="<?php echo $num('izq_foliculo_med_x_mm'); ?>"></div>
                        <div class="col-4"><input type="number" step="0.01" name="izq_foliculo_med_y_mm" class="form-control form-control-sm" placeholder="Y mm" value="<?php echo $num('izq_foliculo_med_y_mm'); ?>"></div>
                        <div class="col-4"><input type="number" step="0.01" name="izq_foliculo_med_z_mm" class="form-control form-control-sm" placeholder="Z mm" value="<?php echo $num('izq_foliculo_med_z_mm'); ?>"></div>
                        <div class="col-6"><select name="izq_foliculo_contenido" class="form-select form-select-sm"><option value="">Contenido</option><?php foreach (['Anecoico','Hemorragico','EcosFinos','Solido','Mixto'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel('izq_foliculo_contenido',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
                        <div class="col-6"><select name="izq_foliculo_pared" class="form-select form-select-sm"><option value="">Pared</option><?php foreach (['Fina','Gruesa','Irregular'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel('izq_foliculo_pared',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
                        <div class="col-4"><div class="form-check"><input type="checkbox" name="izq_foliculo_septos" class="form-check-input" id="izq_foliculo_septos" <?php echo $chk('izq_foliculo_septos'); ?>><label class="form-check-label small" for="izq_foliculo_septos">Septos</label></div><input type="number" step="0.01" name="izq_foliculo_septos_grosor" class="form-control form-control-sm mt-1" placeholder="Grosor mm" value="<?php echo $num('izq_foliculo_septos_grosor'); ?>"></div>
                        <div class="col-4"><div class="form-check"><input type="checkbox" name="izq_foliculo_papilares" class="form-check-input" id="izq_foliculo_papilares" <?php echo $chk('izq_foliculo_papilares'); ?>><label class="form-check-label small" for="izq_foliculo_papilares">Papilares</label></div><input type="number" name="izq_foliculo_papilares_num" class="form-control form-control-sm mt-1" placeholder="Número" value="<?php echo $num('izq_foliculo_papilares_num'); ?>"></div>
                        <div class="col-4"><div class="form-check"><input type="checkbox" name="izq_foliculo_sombra" class="form-check-input" id="izq_foliculo_sombra" <?php echo $chk('izq_foliculo_sombra'); ?>><label class="form-check-label small" for="izq_foliculo_sombra">Sombra acústica</label></div></div>
                        <div class="col-6"><select name="izq_foliculo_doppler" class="form-select form-select-sm"><option value="">Doppler</option><?php foreach (['SinFlujo','FlujoPeriferico','FlujoCentral','FlujoComponenteSolido'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel('izq_foliculo_doppler',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Anexos + Fondo de Saco -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-circle-half-stroke me-2"></i>Anexos y Fondo de Saco</h5></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6"><h6 class="fw-bold">Anexo derecho</h6>
                    <div class="row g-2">
                        <?php foreach (['der_sin_alteraciones'=>'Sin alteraciones','der_lesion_anexial'=>'Lesión anexial','der_hidrosalpinx'=>'Hidrosálpinx','der_paraovarico'=>'Paraovárico'] as $k=>$l): ?>
                        <div class="col-md-6"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                        <?php endforeach; ?>
                    </div><input type="text" name="der_otro" class="form-control form-control-sm mt-2" placeholder="Otro..." value="<?php echo $val('der_otro'); ?>">
                </div>
                <div class="col-md-6"><h6 class="fw-bold">Anexo izquierdo</h6>
                    <div class="row g-2">
                        <?php foreach (['izq_sin_alteraciones'=>'Sin alteraciones','izq_lesion_anexial'=>'Lesión anexial','izq_hidrosalpinx'=>'Hidrosálpinx','izq_paraovarico'=>'Paraovárico'] as $k=>$l): ?>
                        <div class="col-md-6"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                        <?php endforeach; ?>
                    </div><input type="text" name="izq_otro" class="form-control form-control-sm mt-2" placeholder="Otro..." value="<?php echo $val('izq_otro'); ?>">
                </div>
            </div>
            <h6 class="fw-bold mt-3">Fondo de saco posterior</h6>
            <div class="row g-2">
                <?php $fs = ['fondo_saco_libre'=>'Libre','fondo_saco_liquido_escaso'=>'Líquido escaso','fondo_saco_liquido_moderado'=>'Líquido moderado','fondo_saco_liquido_abundante'=>'Líquido abundante','fondo_saco_liquido_ecos'=>'Líquido con ecos','fondo_saco_nodulo_implante'=>'Nódulo/implante','fondo_saco_dolor_presion'=>'Dolor a la presión'];
                foreach ($fs as $k=>$l): ?>
                <div class="col-md-3"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                <?php endforeach; ?>
            </div>
            <h6 class="fw-bold mt-3">Sliding sign</h6>
            <select name="sliding_sign" class="form-select"><option value="">—</option><?php foreach (['Positivo','Negativo','No evaluado'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel('sliding_sign',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select>
        </div>
    </div>

    <!-- Clasificación -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-tags me-2"></i>Clasificación Orientativa</h5></div>
        <div class="card-body">
            <h6 class="fw-bold">PALM-COEIN (sangrado uterino anormal)</h6>
            <div class="row g-2">
                <?php $palm = ['palm_polipo'=>'P: Pólipo','palm_adenomiosis'=>'A: Adenomiosis','palm_leiomioma'=>'L: Leiomioma','palm_malignidad'=>'M: Malignidad/hiperplasia','palm_coagulopatia'=>'C: Coagulopatía','palm_ovulatoria'=>'O: Disfunción ovulatoria','palm_endometrial'=>'E: Endometrial funcional','palm_iatrogenica'=>'I: Iatrogénica','palm_no_clasificada'=>'N: No clasificada'];
                foreach ($palm as $k=>$l): ?>
                <div class="col-md-3"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                <?php endforeach; ?>
            </div>
            <h6 class="fw-bold mt-3">Clasificación anexial</h6>
            <div class="row g-2">
                <?php $anex = ['anexial_funcional'=>'Funcional probable','anexial_benigna'=>'Benigna probable','anexial_indeterminada'=>'Indeterminada','anexial_sospechosa'=>'Sospechosa','anexial_sugiere_o_rads'=>'Sugiere O-RADS/IOTA'];
                foreach ($anex as $k=>$l): ?>
                <div class="col-md-2"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Impresión Diagnóstica -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-file-lines me-2"></i>Impresión Diagnóstica</h5></div>
        <div class="card-body">
            <h6 class="fw-bold">1. Útero</h6>
            <div class="row g-3">
                <div class="col-md-3"><select name="imp_utero_tamano" class="form-select"><option value="">Tamaño</option><?php foreach (['Normal','Aumentado','Disminuido'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel('utero_tamano',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
                <div class="col-md-9"><textarea name="imp_utero_morfologia" class="form-control" rows="1" placeholder="Morfología..."><?php echo $val('utero_morfologia'); ?></textarea></div>
            </div>
            <h6 class="fw-bold mt-3">2. Miometrio</h6>
            <div class="row g-2">
                <?php foreach (['imp_miometrio_sin_alteraciones'=>'Sin alteraciones','imp_miometrio_miomatosis'=>'Miomatosis','imp_miometrio_adenomiosis'=>'Adenomiosis'] as $k=>$l): ?>
                <div class="col-md-3"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                <?php endforeach; ?>
                <div class="col-md-12 mt-2"><textarea name="imp_miometrio_otro" class="form-control" rows="1" placeholder="Otro..."><?php echo $val('miometrio_otro'); ?></textarea></div>
            </div>
            <h6 class="fw-bold mt-3">3. Endometrio</h6>
            <div class="row g-3">
                <div class="col-md-2"><input type="number" step="0.01" name="imp_endometrio_grosor_mm" class="form-control" placeholder="mm" value="<?php echo $num('endometrio_grosor_mm'); ?>"></div>
                <div class="col-md-4"><input type="text" name="imp_endometrio_patron" class="form-control" placeholder="Patrón..." value="<?php echo $val('endometrio_patron'); ?>"></div>
                <div class="col-md-6 mt-2"><?php foreach (['imp_endometrio_acorde'=>'Acorde al contexto','imp_endometrio_engrosado'=>'Engrosado','imp_endometrio_correlacion'=>'Requiere correlación'] as $k=>$l): ?><div class="form-check form-check-inline"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div><?php endforeach; ?></div>
            </div>
            <h6 class="fw-bold mt-3">4-6. Ovarios y Anexos</h6>
            <textarea name="imp_ovario_derecho" class="form-control mb-2" rows="1" placeholder="Ovario derecho..."><?php echo $val('ovario_derecho'); ?></textarea>
            <textarea name="imp_ovario_izquierdo" class="form-control mb-2" rows="1" placeholder="Ovario izquierdo..."><?php echo $val('ovario_izquierdo'); ?></textarea>
            <textarea name="imp_anexos_fondo_saco" class="form-control" rows="1" placeholder="Anexos / fondo de saco..."><?php echo $val('anexos_fondo_saco'); ?></textarea>
        </div>
    </div>

    <!-- Conclusión + Recomendaciones -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fa-solid fa-check-double me-2"></i>Conclusión y Recomendaciones</h5></div>
        <div class="card-body">
            <h6 class="fw-bold">Conclusión</h6>
            <div class="row g-2">
                <?php $concls = ['concl_normal'=>'Dentro de límites esperados','concl_miomatosis'=>'Miomatosis uterina','concl_engrosamiento'=>'Engrosamiento endometrial','concl_polipo'=>'Imagen focal (pólipo)','concl_adenomiosis'=>'Datos sugestivos de adenomiosis','concl_quiste_simple_der'=>'Quiste simple derecho','concl_quiste_simple_izq'=>'Quiste simple izquierdo','concl_quiste_hemorragico_der'=>'Quiste hemorrágico derecho','concl_quiste_hemorragico_izq'=>'Quiste hemorrágico izquierdo','concl_endometrioma_der'=>'Endometrioma derecho','concl_endometrioma_izq'=>'Endometrioma izquierdo','concl_masa_indeterminada'=>'Masa anexial indeterminada'];
                foreach ($concls as $k=>$l): ?>
                <div class="col-md-3"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                <?php endforeach; ?>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-2"><input type="number" step="0.01" name="concl_mioma_dominante_mm" class="form-control form-control-sm" placeholder="Mioma mm" value="<?php echo $num('conclusion_mioma_dominante_mm'); ?>"></div>
                <div class="col-md-2"><input type="text" name="concl_figo" class="form-control form-control-sm" placeholder="FIGO" value="<?php echo $val('conclusion_figo'); ?>"></div>
                <div class="col-md-2"><input type="number" step="0.01" name="concl_medida_endometrio_mm" class="form-control form-control-sm" placeholder="Endometrio mm" value="<?php echo $num('conclusion_medida_endometrio_mm'); ?>"></div>
                <div class="col-md-2"><input type="number" step="0.01" name="concl_quiste_medida_mm" class="form-control form-control-sm" placeholder="Quiste mm" value="<?php echo $num('conclusion_quiste_medida_mm'); ?>"></div>
                <div class="col-md-12"><textarea name="concl_otro" class="form-control form-control-sm" rows="1" placeholder="Otro..."><?php echo $val('conclusion_otro'); ?></textarea></div>
            </div>
            <h6 class="fw-bold mt-3">Recomendaciones / Conducta sugerida</h6>
            <div class="row g-2">
                <?php $recs = ['rec_correlacion_edad_fum'=>'Correlacionar edad/FUM/sangrado','rec_correlacion_hb_hormonal'=>'Correlacionar Hb/hormonal','rec_estudio_histologico'=>'Estudio histológico endometrial','rec_histeroscopia_endometrio'=>'Histeroscopia diagnóstica','rec_sonohisterografia_histeroscopia'=>'Sonohisterografía/histeroscopia','rec_valorar_manejo_miomatosis'=>'Valorar manejo miomatosis','rec_iorads_marcadores_oncologia'=>'IOTA/O-RADS, oncología','rec_control_ultrasonografico'=>'Control ultrasonográfico'];
                foreach ($recs as $k=>$l): ?>
                <div class="col-md-3"><div class="form-check"><input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" id="<?php echo $k; ?>" <?php echo $chk($k); ?>><label class="form-check-label" for="<?php echo $k; ?>"><?php echo $l; ?></label></div></div>
                <?php endforeach; ?>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-2"><input type="number" name="rec_control_tiempo" class="form-control form-control-sm" placeholder="Tiempo" value="<?php echo $num('rec_control_tiempo'); ?>"></div>
                <div class="col-md-2"><select name="rec_control_unidad" class="form-select form-select-sm"><option value="">Unidad</option><?php foreach (['Semanas','Meses'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel('rec_control_unidad',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
                <div class="col-md-12"><textarea name="rec_otro" class="form-control form-control-sm" rows="1" placeholder="Otra recomendación..."><?php echo $val('rec_otro'); ?></textarea></div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mb-5">
        <a href="<?php echo Url::to('/evaluaciones_ginecologicas'); ?>" class="btn btn-apple-secondary">Cancelar</a>
        <button type="submit" class="btn btn-apple btn-apple-primary"><i class="fa-solid fa-save"></i> Guardar Cambios</button>
    </div>
</form>

<script>
document.getElementById('addMiomaRow').addEventListener('click', function() {
    const rows = document.querySelectorAll('#miomasDetalleBody tr');
    const rowNum = rows.length + 1;
    const tbody = document.getElementById('miomasDetalleBody');
    const tr = document.createElement('tr');
    tr.innerHTML = '<td>' + rowNum + '</td>' +
        '<td><select name="md_localizacion[]" class="form-select form-select-sm"><option value=""></option><option value="Fondo">Fondo</option><option value="Anterior">Anterior</option><option value="Posterior">Posterior</option><option value="Lateral">Lateral</option><option value="Cervical">Cervical</option></select></td>' +
        '<td><input type="number" step="0.01" name="md_medida_x[]" class="form-control form-control-sm"></td>' +
        '<td><input type="number" step="0.01" name="md_medida_y[]" class="form-control form-control-sm"></td>' +
        '<td><input type="number" step="0.01" name="md_medida_z[]" class="form-control form-control-sm"></td>' +
        '<td><select name="md_relacion[]" class="form-select form-select-sm"><option value=""></option><option value="No contacta">No contacta</option><option value="Contacta">Contacta</option><option value="Desplaza">Desplaza</option><option value="Distorsiona cavidad">Distorsiona cavidad</option></select></td>' +
        '<td><input type="text" name="md_figo[]" class="form-control form-control-sm" placeholder="FIGO 0-8"></td>' +
        '<td><select name="md_doppler[]" class="form-select form-select-sm"><option value=""></option><option value="Escaso">Escaso</option><option value="Moderado">Moderado</option><option value="Aumentado">Aumentado</option></select></td>' +
        '<td><input type="text" name="md_comentarios[]" class="form-control form-control-sm"></td>' +
        '<td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest(\'tr\').remove()"><i class="fa-solid fa-times"></i></button></td>';
    tbody.appendChild(tr);
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
