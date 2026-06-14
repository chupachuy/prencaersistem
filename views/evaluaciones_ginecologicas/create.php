<?php
$title = "Nuevo USG Ginecológico";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/evaluaciones_ginecologicas'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0"><?php echo htmlspecialchars($title); ?></h1>
    </div>
</div>

<form action="<?php echo Url::to('/evaluaciones_ginecologicas/store'); ?>" method="POST" id="formEvaluacionGine">
    <input type="hidden" name="codigo_reporte" value="<?php echo htmlspecialchars($codigo_reporte); ?>">

    <!-- 1. Datos Generales -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-id-card me-2"></i> Datos Generales
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="paciente_id" class="form-label">Paciente *</label>
                    <select class="form-select" id="paciente_id" name="paciente_id" required>
                        <option value="">Seleccione un paciente</option>
                        <?php foreach ($pacientes as $p): ?>
                            <option value="<?php echo $p['id']; ?>" <?php echo (isset($paciente_id) && $paciente_id == $p['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($p['nombre'] . ' ' . $p['apellido']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="medico_id" class="form-label">Médico *</label>
                    <select class="form-select" id="medico_id" name="medico_id" required>
                        <option value="">Seleccione un médico</option>
                        <?php foreach ($medicos as $m): ?>
                            <option value="<?php echo $m['id']; ?>">
                                <?php echo htmlspecialchars($m['nombre'] . ' ' . $m['apellido'] . ($m['especialidad'] ? ' - ' . $m['especialidad'] : '')); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="medico_solicitante_id" class="form-label">Médico Solicitante</label>
                    <select class="form-select" id="medico_solicitante_id" name="medico_solicitante_id">
                        <option value="">Seleccione (opcional)</option>
                        <?php foreach ($medicos as $m): ?>
                            <option value="<?php echo $m['id']; ?>">
                                <?php echo htmlspecialchars($m['nombre'] . ' ' . $m['apellido']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="medico_referido_id" class="form-label">Médico Referido</label>
                    <select class="form-select" id="medico_referido_id" name="medico_referido_id">
                        <option value="">Ninguno</option>
                        <?php foreach ($medicos as $m): ?>
                            <option value="<?php echo $m['id']; ?>">
                                <?php echo htmlspecialchars($m['nombre'] . ' ' . $m['apellido']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="fecha_estudio" class="form-label">Fecha de Estudio *</label>
                    <input type="date" class="form-control" id="fecha_estudio" name="fecha_estudio" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fum" class="form-label">FUM</label>
                    <input type="date" class="form-control" id="fum" name="fum">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="dia_ciclo_menstrual" class="form-label">Día del Ciclo Menstrual</label>
                    <input type="number" class="form-control" id="dia_ciclo_menstrual" name="dia_ciclo_menstrual" min="1" max="45" placeholder="Ej: 14">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="indicacion_clinica" class="form-label">Indicación Clínica</label>
                    <textarea class="form-control" id="indicacion_clinica" name="indicacion_clinica" rows="2" placeholder="Describa la indicación clínica..."></textarea>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="Pendiente">Pendiente</option>
                        <option value="En proceso">En proceso</option>
                        <option value="Completado">Completado</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <textarea class="form-control" id="observaciones" name="observaciones" rows="2" placeholder="Observaciones..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Indicaciones -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-clipboard-list me-2"></i> Indicaciones
        </div>
        <div class="card-body">
            <h6 class="text-muted mb-3">Motivo del Estudio</h6>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="sangrado_uterino_anormal" name="sangrado_uterino_anormal">
                        <label class="form-check-label" for="sangrado_uterino_anormal">Sangrado Uterino Anormal</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="dolor_pelvico" name="dolor_pelvico">
                        <label class="form-check-label" for="dolor_pelvico">Dolor Pélvico</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="miomatosis_uterina" name="miomatosis_uterina">
                        <label class="form-check-label" for="miomatosis_uterina">Miomatosis Uterina</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="sospecha_polipo_endometrial" name="sospecha_polipo_endometrial">
                        <label class="form-check-label" for="sospecha_polipo_endometrial">Sospecha Pólipo Endometrial</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="engrosamiento_endometrial" name="engrosamiento_endometrial">
                        <label class="form-check-label" for="engrosamiento_endometrial">Engrosamiento Endometrial</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="control_diu" name="control_diu">
                        <label class="form-check-label" for="control_diu">Control DIU</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="infertilidad_reproduccion" name="infertilidad_reproduccion">
                        <label class="form-check-label" for="infertilidad_reproduccion">Infertilidad/Reproducción</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="quiste_ovarico_masa_anexial" name="quiste_ovarico_masa_anexial">
                        <label class="form-check-label" for="quiste_ovarico_masa_anexial">Quiste Ovárico/Masa Anexial</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="sindrome_climaterico" name="sindrome_climaterico">
                        <label class="form-check-label" for="sindrome_climaterico">Síndrome Climatérico</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="sangrado_posmenopausico" name="sangrado_posmenopausico">
                        <label class="form-check-label" for="sangrado_posmenopausico">Sangrado Posmenopáusico</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6 mb-3">
                    <label for="motivo_estudio_otro" class="form-label">Otro Motivo</label>
                    <input type="text" class="form-control" id="motivo_estudio_otro" name="motivo_estudio_otro" placeholder="Especifique otro motivo...">
                </div>
            </div>
            <h6 class="text-muted mb-3 mt-3">Estatus Hormonal</h6>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="premenopausica" name="premenopausica">
                        <label class="form-check-label" for="premenopausica">Premenopáusica</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="perimenopausica" name="perimenopausica">
                        <label class="form-check-label" for="perimenopausica">Perimenopáusica</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="posmenopausica" name="posmenopausica">
                        <label class="form-check-label" for="posmenopausica">Posmenopáusica</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terapia_hormonal" name="terapia_hormonal">
                        <label class="form-check-label" for="terapia_hormonal">Terapia Hormonal</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="tamoxifeno" name="tamoxifeno">
                        <label class="form-check-label" for="tamoxifeno">Tamoxifeno</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="anticonceptivos_hormonales" name="anticonceptivos_hormonales">
                        <label class="form-check-label" for="anticonceptivos_hormonales">Anticonceptivos Hormonales</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="estatus_no_especificado" name="estatus_no_especificado">
                        <label class="form-check-label" for="estatus_no_especificado">No Especificado</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Antecedentes -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-history me-2"></i> Antecedentes
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="gesta" class="form-label">Gesta</label>
                    <input type="number" class="form-control" id="gesta" name="gesta" min="0" placeholder="0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="para" class="form-label">Para</label>
                    <input type="number" class="form-control" id="para" name="para" min="0" placeholder="0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="cesareas" class="form-label">Cesáreas</label>
                    <input type="number" class="form-control" id="cesareas" name="cesareas" min="0" placeholder="0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="abortos" class="form-label">Abortos</label>
                    <input type="number" class="form-control" id="abortos" name="abortos" min="0" placeholder="0">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Paridad Satisfecha</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="paridad_satisfecha" id="paridad_si" value="1">
                            <label class="form-check-label" for="paridad_si">Sí</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="paridad_satisfecha" id="paridad_no" value="0" checked>
                            <label class="form-check-label" for="paridad_no">No</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="legrado_cirugia_uterina" name="legrado_cirugia_uterina">
                        <label class="form-check-label" for="legrado_cirugia_uterina">Legrado / Cirugía Uterina</label>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="miomectomia" name="miomectomia">
                        <label class="form-check-label" for="miomectomia">Miomectomía</label>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="endometriosis_adenomiosis" name="endometriosis_adenomiosis">
                        <label class="form-check-label" for="endometriosis_adenomiosis">Endometriosis / Adenomiosis</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12 mb-3">
                    <label for="antecedentes_otros" class="form-label">Otros Antecedentes</label>
                    <textarea class="form-control" id="antecedentes_otros" name="antecedentes_otros" rows="2" placeholder="Otros antecedentes relevantes..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Técnica -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-gear me-2"></i> Técnica
        </div>
        <div class="card-body">
            <h6 class="text-muted mb-3">Vía de Estudio</h6>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="via_endovaginal" name="via_endovaginal" checked>
                        <label class="form-check-label" for="via_endovaginal">Endovaginal</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="via_transabdominal" name="via_transabdominal">
                        <label class="form-check-label" for="via_transabdominal">Transabdominal</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="via_doppler_color" name="via_doppler_color">
                        <label class="form-check-label" for="via_doppler_color">Doppler Color</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="via_evaluacion_3d" name="via_evaluacion_3d">
                        <label class="form-check-label" for="via_evaluacion_3d">Evaluación 3D</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="via_sonohisterografia" name="via_sonohisterografia">
                        <label class="form-check-label" for="via_sonohisterografia">Sonohisterografía</label>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4 mb-3">
                    <label for="calidad" class="form-label">Calidad del Estudio</label>
                    <select class="form-select" id="calidad" name="calidad">
                        <option value="Adecuada">Adecuada</option>
                        <option value="Limitada">Limitada</option>
                    </select>
                </div>
            </div>
            <h6 class="text-muted mb-3">Limitaciones (si aplica)</h6>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="limitada_dolor" name="limitada_dolor">
                        <label class="form-check-label" for="limitada_dolor">Dolor</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="limitada_distension_intestinal" name="limitada_distension_intestinal">
                        <label class="form-check-label" for="limitada_distension_intestinal">Distensión Intestinal</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="limitada_habitus_corporal" name="limitada_habitus_corporal">
                        <label class="form-check-label" for="limitada_habitus_corporal">Hábitus Corporal</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="limitada_posicion_uterina" name="limitada_posicion_uterina">
                        <label class="form-check-label" for="limitada_posicion_uterina">Posición Uterina</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6 mb-3">
                    <label for="calidad_otra" class="form-label">Otra Limitación</label>
                    <input type="text" class="form-control" id="calidad_otra" name="calidad_otra" placeholder="Especifique...">
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Útero + Cérvix -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-uterus me-2"></i> Útero y Cérvix
        </div>
        <div class="card-body">
            <h6 class="text-muted mb-3">Situación Uterina</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="situacion" class="form-label">Situación</label>
                    <select class="form-select" id="situacion" name="situacion">
                        <option value="">Seleccione...</option>
                        <option value="Anteversoflexion">Anteversoflexión</option>
                        <option value="Retroversoflexion">Retroversoflexión</option>
                        <option value="Intermedia">Intermedia</option>
                        <option value="Lateralizado">Lateralizado</option>
                    </select>
                </div>
            </div>
            <h6 class="text-muted mb-3">Morfología</h6>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="morfologia_regular" name="morfologia_regular" checked>
                        <label class="form-check-label" for="morfologia_regular">Regular</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="morfologia_bordes_irregulares" name="morfologia_bordes_irregulares">
                        <label class="form-check-label" for="morfologia_bordes_irregulares">Bordes Irregulares</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="morfologia_globoso" name="morfologia_globoso">
                        <label class="form-check-label" for="morfologia_globoso">Globoso</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="morfologia_aumentado" name="morfologia_aumentado">
                        <label class="form-check-label" for="morfologia_aumentado">Aumentado de Tamaño</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="morfologia_disminuido" name="morfologia_disminuido">
                        <label class="form-check-label" for="morfologia_disminuido">Disminuido de Tamaño</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6 mb-3">
                    <label for="morfologia_otro" class="form-label">Otro Hallazgo Morfológico</label>
                    <input type="text" class="form-control" id="morfologia_otro" name="morfologia_otro" placeholder="Especifique...">
                </div>
            </div>
            <h6 class="text-muted mb-3 mt-3">Dimensiones Uterinas</h6>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="dim_longitud_mm" class="form-label">Longitud (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="dim_longitud_mm" name="dim_longitud_mm" placeholder="Ej: 75.0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="dim_anteroposterior_mm" class="form-label">Anteroposterior (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="dim_anteroposterior_mm" name="dim_anteroposterior_mm" placeholder="Ej: 40.0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="dim_transverso_mm" class="form-label">Transverso (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="dim_transverso_mm" name="dim_transverso_mm" placeholder="Ej: 50.0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="volumen_cc" class="form-label">Volumen (cc)</label>
                    <input type="number" step="0.01" class="form-control" id="volumen_cc" name="volumen_cc" placeholder="Ej: 78.5">
                </div>
            </div>
            <h6 class="text-muted mb-3 mt-3">Miometrio</h6>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="miometrio_homogeneo" name="miometrio_homogeneo" checked>
                        <label class="form-check-label" for="miometrio_homogeneo">Homogéneo</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="miometrio_heterogeneo" name="miometrio_heterogeneo">
                        <label class="form-check-label" for="miometrio_heterogeneo">Heterogéneo</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="miometrio_imagenes_leiomiomas" name="miometrio_imagenes_leiomiomas">
                        <label class="form-check-label" for="miometrio_imagenes_leiomiomas">Imágenes de Leiomiomas</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="miometrio_sugestivo_adenomiosis" name="miometrio_sugestivo_adenomiosis">
                        <label class="form-check-label" for="miometrio_sugestivo_adenomiosis">Sugestivo de Adenomiosis</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="miometrio_calcificaciones" name="miometrio_calcificaciones">
                        <label class="form-check-label" for="miometrio_calcificaciones">Calcificaciones</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="miometrio_areas_quisticas" name="miometrio_areas_quisticas">
                        <label class="form-check-label" for="miometrio_areas_quisticas">Áreas Quísticas</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="miometrio_sombra_acustica" name="miometrio_sombra_acustica">
                        <label class="form-check-label" for="miometrio_sombra_acustica">Sombra Acústica</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6 mb-3">
                    <label for="miometrio_otro" class="form-label">Otro Hallazgo Miometrial</label>
                    <input type="text" class="form-control" id="miometrio_otro" name="miometrio_otro" placeholder="Especifique...">
                </div>
            </div>
            <h6 class="text-muted mb-3 mt-3">Cérvix</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="cervix_longitud_mm" class="form-label">Longitud Cervical (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="cervix_longitud_mm" name="cervix_longitud_mm" placeholder="Ej: 32.0">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="cervix_sin_alteraciones" name="cervix_sin_alteraciones" checked>
                        <label class="form-check-label" for="cervix_sin_alteraciones">Sin Alteraciones</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="cervix_quistes_naboth" name="cervix_quistes_naboth">
                        <label class="form-check-label" for="cervix_quistes_naboth">Quistes de Naboth</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="cervix_polipo_endocervical" name="cervix_polipo_endocervical">
                        <label class="form-check-label" for="cervix_polipo_endocervical">Pólipo Endocervical</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="cervix_lesion_visible_usg" name="cervix_lesion_visible_usg">
                        <label class="form-check-label" for="cervix_lesion_visible_usg">Lesión Visible en USG</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="cervix_liquido_canal" name="cervix_liquido_canal">
                        <label class="form-check-label" for="cervix_liquido_canal">Líquido en Canal Endocervical</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6 mb-3">
                    <label for="cervix_otro" class="form-label">Otro Hallazgo Cervical</label>
                    <input type="text" class="form-control" id="cervix_otro" name="cervix_otro" placeholder="Especifique...">
                </div>
            </div>
        </div>
    </div>

    <!-- 6. Miomas -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-circle-nodes me-2"></i> Miomas
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="miomas_identificados" name="miomas_identificados">
                        <label class="form-check-label" for="miomas_identificados">Miomas Identificados</label>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4 mb-3">
                    <label for="miomas_numero_aproximado" class="form-label">Número Aproximado</label>
                    <input type="number" class="form-control" id="miomas_numero_aproximado" name="miomas_numero_aproximado" min="0" placeholder="Ej: 3">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="mioma_dominante_mm" class="form-label">Mioma Dominante (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="mioma_dominante_mm" name="mioma_dominante_mm" placeholder="Ej: 45.0">
                </div>
            </div>
            <h6 class="text-muted mb-3">Predominio</h6>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="predominio_submucosos" name="predominio_submucosos">
                        <label class="form-check-label" for="predominio_submucosos">Submucosos</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="predominio_intramurales" name="predominio_intramurales">
                        <label class="form-check-label" for="predominio_intramurales">Intramurales</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="predominio_subserosos" name="predominio_subserosos">
                        <label class="form-check-label" for="predominio_subserosos">Subserosos</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="predominio_pediculados" name="predominio_pediculados">
                        <label class="form-check-label" for="predominio_pediculados">Pediculados</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="predominio_cervicales" name="predominio_cervicales">
                        <label class="form-check-label" for="predominio_cervicales">Cervicales</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="predominio_distribucion_difusa" name="predominio_distribucion_difusa">
                        <label class="form-check-label" for="predominio_distribucion_difusa">Distribución Difusa</label>
                    </div>
                </div>
            </div>
            <h6 class="text-muted mb-3 mt-4">Detalle de Miomas</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="miomasDetalleTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;">#</th>
                            <th>Localización</th>
                            <th style="width:90px;">X (mm)</th>
                            <th style="width:90px;">Y (mm)</th>
                            <th style="width:90px;">Z (mm)</th>
                            <th>Relación con Endometrio</th>
                            <th style="width:80px;">FIGO</th>
                            <th>Doppler</th>
                            <th>Comentarios</th>
                            <th style="width:40px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="miomasDetalleBody">
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-outline-primary" id="addMiomaRow">
                <i class="fa-solid fa-plus"></i> Agregar Mioma
            </button>
        </div>
    </div>

    <!-- 7. Adenomiosis -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-magnifying-glass me-2"></i> Adenomiosis
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="adeno_hallazgos" class="form-label">Hallazgos</label>
                    <select class="form-select" id="adeno_hallazgos" name="adeno_hallazgos">
                        <option value="No se observan">No se observan</option>
                        <option value="Si se observan">Sí se observan</option>
                        <option value="Indeterminado">Indeterminado</option>
                    </select>
                </div>
            </div>
            <h6 class="text-muted mb-3">Características</h6>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="adeno_utero_globoso" name="adeno_utero_globoso">
                        <label class="form-check-label" for="adeno_utero_globoso">Útero Globoso</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="adeno_asimetria_paredes" name="adeno_asimetria_paredes">
                        <label class="form-check-label" for="adeno_asimetria_paredes">Asimetría de Paredes</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="adeno_miometrio_heterogeneo" name="adeno_miometrio_heterogeneo">
                        <label class="form-check-label" for="adeno_miometrio_heterogeneo">Miometrio Heterogéneo</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="adeno_estriaciones_lineales" name="adeno_estriaciones_lineales">
                        <label class="form-check-label" for="adeno_estriaciones_lineales">Estriaciones Lineales</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="adeno_quistes_miometriales" name="adeno_quistes_miometriales">
                        <label class="form-check-label" for="adeno_quistes_miometriales">Quistes Miometriales</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="adeno_islas_hiperecogenicas" name="adeno_islas_hiperecogenicas">
                        <label class="form-check-label" for="adeno_islas_hiperecogenicas">Islas Hiperecogénicas</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="adeno_sombra_abanico" name="adeno_sombra_abanico">
                        <label class="form-check-label" for="adeno_sombra_abanico">Sombra en Abanico</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="adeno_zona_union_irregular" name="adeno_zona_union_irregular">
                        <label class="form-check-label" for="adeno_zona_union_irregular">Zona de Unión Irregular</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="adeno_vascularidad_translesional" name="adeno_vascularidad_translesional">
                        <label class="form-check-label" for="adeno_vascularidad_translesional">Vascularidad Translesional</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6 mb-3">
                    <label for="adeno_datos_otro" class="form-label">Otros Datos</label>
                    <input type="text" class="form-control" id="adeno_datos_otro" name="adeno_datos_otro" placeholder="Especifique...">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4 mb-3">
                    <label for="adeno_distribucion" class="form-label">Distribución</label>
                    <select class="form-select" id="adeno_distribucion" name="adeno_distribucion">
                        <option value="">Seleccione...</option>
                        <option value="Difusa">Difusa</option>
                        <option value="Focal">Focal</option>
                    </select>
                </div>
            </div>
            <h6 class="text-muted mb-3">Predominio</h6>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="adeno_predominio_anterior" name="adeno_predominio_anterior">
                        <label class="form-check-label" for="adeno_predominio_anterior">Anterior</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="adeno_predominio_posterior" name="adeno_predominio_posterior">
                        <label class="form-check-label" for="adeno_predominio_posterior">Posterior</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="adeno_predominio_fundico" name="adeno_predominio_fundico">
                        <label class="form-check-label" for="adeno_predominio_fundico">Fúndico</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 8. Endometrio -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-layer-group me-2"></i> Endometrio
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="endometrio_grosor_mm" class="form-label">Grosor Endometrial (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="endometrio_grosor_mm" name="endometrio_grosor_mm" placeholder="Ej: 8.5">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="endometrio_patron" class="form-label">Patrón</label>
                    <select class="form-select" id="endometrio_patron" name="endometrio_patron">
                        <option value="">Seleccione...</option>
                        <option value="Lineal">Lineal</option>
                        <option value="Trilaminar">Trilaminar</option>
                        <option value="Hiperecogenico">Hiperecogénico</option>
                        <option value="Heterogeneo">Heterogéneo</option>
                        <option value="Quistico">Quístico</option>
                        <option value="Irregular">Irregular</option>
                        <option value="NoValorable">No Valorable</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="endometrio_correlacion_ciclo" class="form-label">Correlación con Ciclo</label>
                    <select class="form-select" id="endometrio_correlacion_ciclo" name="endometrio_correlacion_ciclo">
                        <option value="">Seleccione...</option>
                        <option value="Acorde">Acorde</option>
                        <option value="Engrosado">Engrosado</option>
                        <option value="Delgado">Delgado</option>
                        <option value="NoValorableSangrado">No Valorable (Sangrado)</option>
                        <option value="NoValorableMiomas">No Valorable (Miomas)</option>
                    </select>
                </div>
            </div>
            <h6 class="text-muted mb-3">Cavidad Endometrial</h6>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="endometrio_cavidad_regular" name="endometrio_cavidad_regular" checked>
                        <label class="form-check-label" for="endometrio_cavidad_regular">Regular</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="endometrio_cavidad_distorsionada" name="endometrio_cavidad_distorsionada">
                        <label class="form-check-label" for="endometrio_cavidad_distorsionada">Distorsionada</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="endometrio_cavidad_liquido" name="endometrio_cavidad_liquido">
                        <label class="form-check-label" for="endometrio_cavidad_liquido">Líquido en Cavidad</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="endometrio_cavidad_polipo" name="endometrio_cavidad_polipo">
                        <label class="form-check-label" for="endometrio_cavidad_polipo">Pólipo</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="endometrio_cavidad_mioma_submucoso" name="endometrio_cavidad_mioma_submucoso">
                        <label class="form-check-label" for="endometrio_cavidad_mioma_submucoso">Mioma Submucoso</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="endometrio_cavidad_sinequias" name="endometrio_cavidad_sinequias">
                        <label class="form-check-label" for="endometrio_cavidad_sinequias">Sinequias</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="endometrio_cavidad_diu" name="endometrio_cavidad_diu">
                        <label class="form-check-label" for="endometrio_cavidad_diu">DIU</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6 mb-3">
                    <label for="endometrio_cavidad_otro" class="form-label">Otro Hallazgo de Cavidad</label>
                    <input type="text" class="form-control" id="endometrio_cavidad_otro" name="endometrio_cavidad_otro" placeholder="Especifique...">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4 mb-3">
                    <label for="endometrio_doppler" class="form-label">Doppler Endometrial</label>
                    <select class="form-select" id="endometrio_doppler" name="endometrio_doppler">
                        <option value="NoEvaluado">No Evaluado</option>
                        <option value="SinVascularidad">Sin Vascularidad</option>
                        <option value="VasoUnicoPolipo">Vaso Único (Pólipo)</option>
                        <option value="VascularidadDifusa">Vascularidad Difusa</option>
                        <option value="VascularidadIrregular">Vascularidad Irregular</option>
                    </select>
                </div>
            </div>
            <h6 class="text-muted mb-3 mt-3">DIU (si aplica)</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="diu_posicion" class="form-label">Posición del DIU</label>
                    <select class="form-select" id="diu_posicion" name="diu_posicion">
                        <option value="">No aplica</option>
                        <option value="Normoinserto">Normoinserto</option>
                        <option value="Descendido">Descendido</option>
                        <option value="ParcialmenteExpulsado">Parcialmente Expulsado</option>
                        <option value="BrazoIncluidoMiometrio">Brazo Incluido en Miometrio</option>
                        <option value="NoVisible">No Visible</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="diu_distancia_fondo_mm" class="form-label">Distancia al Fondo (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="diu_distancia_fondo_mm" name="diu_distancia_fondo_mm" placeholder="Ej: 15.0">
                </div>
            </div>
        </div>
    </div>

    <!-- 9. Ovarios -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-egg me-2"></i> Ovarios
        </div>
        <div class="card-body">
            <h6 class="text-muted mb-3">Ovario Derecho</h6>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="der_dim_x_mm" class="form-label">Dimensión X (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="der_dim_x_mm" name="der_dim_x_mm" placeholder="Ej: 30.0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="der_dim_y_mm" class="form-label">Dimensión Y (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="der_dim_y_mm" name="der_dim_y_mm" placeholder="Ej: 20.0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="der_dim_z_mm" class="form-label">Dimensión Z (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="der_dim_z_mm" name="der_dim_z_mm" placeholder="Ej: 25.0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="der_volumen_cc" class="form-label">Volumen (cc)</label>
                    <input type="number" step="0.01" class="form-control" id="der_volumen_cc" name="der_volumen_cc" placeholder="Ej: 7.8">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_normal" name="der_normal" checked>
                        <label class="form-check-label" for="der_normal">Normal</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_atrofico" name="der_atrofico">
                        <label class="form-check-label" for="der_atrofico">Atrófico</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_multifolicular" name="der_multifolicular">
                        <label class="form-check-label" for="der_multifolicular">Multifolicular</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_poliquistico" name="der_poliquistico">
                        <label class="form-check-label" for="der_poliquistico">Poliquístico</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_cuerpo_luteo" name="der_cuerpo_luteo">
                        <label class="form-check-label" for="der_cuerpo_luteo">Cuerpo Lúteo</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_quiste_simple" name="der_quiste_simple">
                        <label class="form-check-label" for="der_quiste_simple">Quiste Simple</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_quiste_hemorragico" name="der_quiste_hemorragico">
                        <label class="form-check-label" for="der_quiste_hemorragico">Quiste Hemorrágico</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_endometrioma" name="der_endometrioma">
                        <label class="form-check-label" for="der_endometrioma">Endometrioma</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_lesion_solida" name="der_lesion_solida">
                        <label class="form-check-label" for="der_lesion_solida">Lesión Sólida</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_lesion_compleja" name="der_lesion_compleja">
                        <label class="form-check-label" for="der_lesion_compleja">Lesión Compleja</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_no_visible" name="der_no_visible">
                        <label class="form-check-label" for="der_no_visible">No Visible</label>
                    </div>
                </div>
            </div>
            <h6 class="text-muted mb-3 mt-3">Folículo/Lesión Dominante Derecho</h6>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="der_foliculo_med_x_mm" class="form-label">Medida X (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="der_foliculo_med_x_mm" name="der_foliculo_med_x_mm">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="der_foliculo_med_y_mm" class="form-label">Medida Y (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="der_foliculo_med_y_mm" name="der_foliculo_med_y_mm">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="der_foliculo_med_z_mm" class="form-label">Medida Z (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="der_foliculo_med_z_mm" name="der_foliculo_med_z_mm">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="der_foliculo_contenido" class="form-label">Contenido</label>
                    <select class="form-select" id="der_foliculo_contenido" name="der_foliculo_contenido">
                        <option value="">Seleccione...</option>
                        <option value="Anecoico">Anecoico</option>
                        <option value="Hemorragico">Hemorrágico</option>
                        <option value="EcosFinos">Ecos Finos</option>
                        <option value="Solido">Sólido</option>
                        <option value="Mixto">Mixto</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="der_foliculo_pared" class="form-label">Pared</label>
                    <select class="form-select" id="der_foliculo_pared" name="der_foliculo_pared">
                        <option value="">Seleccione...</option>
                        <option value="Fina">Fina</option>
                        <option value="Gruesa">Gruesa</option>
                        <option value="Irregular">Irregular</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="der_foliculo_doppler" class="form-label">Doppler</label>
                    <select class="form-select" id="der_foliculo_doppler" name="der_foliculo_doppler">
                        <option value="">No evaluado</option>
                        <option value="SinFlujo">Sin Flujo</option>
                        <option value="FlujoPeriferico">Flujo Periférico</option>
                        <option value="FlujoCentral">Flujo Central</option>
                        <option value="FlujoComponenteSolido">Flujo en Componente Sólido</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_foliculo_septos" name="der_foliculo_septos">
                        <label class="form-check-label" for="der_foliculo_septos">Septos</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="der_foliculo_septos_grosor" class="form-label">Grosor de Septos (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="der_foliculo_septos_grosor" name="der_foliculo_septos_grosor" placeholder="mm">
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_foliculo_papilares" name="der_foliculo_papilares">
                        <label class="form-check-label" for="der_foliculo_papilares">Proyecciones Papilares</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="der_foliculo_papilares_num" class="form-label">Número</label>
                    <input type="number" class="form-control" id="der_foliculo_papilares_num" name="der_foliculo_papilares_num" min="0" placeholder="0">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_foliculo_sombra" name="der_foliculo_sombra">
                        <label class="form-check-label" for="der_foliculo_sombra">Sombra Acústica</label>
                    </div>
                </div>
            </div>

            <hr class="mt-4 mb-4">

            <h6 class="text-muted mb-3">Ovario Izquierdo</h6>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="izq_dim_x_mm" class="form-label">Dimensión X (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="izq_dim_x_mm" name="izq_dim_x_mm" placeholder="Ej: 30.0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="izq_dim_y_mm" class="form-label">Dimensión Y (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="izq_dim_y_mm" name="izq_dim_y_mm" placeholder="Ej: 20.0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="izq_dim_z_mm" class="form-label">Dimensión Z (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="izq_dim_z_mm" name="izq_dim_z_mm" placeholder="Ej: 25.0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="izq_volumen_cc" class="form-label">Volumen (cc)</label>
                    <input type="number" step="0.01" class="form-control" id="izq_volumen_cc" name="izq_volumen_cc" placeholder="Ej: 7.8">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_normal" name="izq_normal" checked>
                        <label class="form-check-label" for="izq_normal">Normal</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_atrofico" name="izq_atrofico">
                        <label class="form-check-label" for="izq_atrofico">Atrófico</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_multifolicular" name="izq_multifolicular">
                        <label class="form-check-label" for="izq_multifolicular">Multifolicular</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_poliquistico" name="izq_poliquistico">
                        <label class="form-check-label" for="izq_poliquistico">Poliquístico</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_cuerpo_luteo" name="izq_cuerpo_luteo">
                        <label class="form-check-label" for="izq_cuerpo_luteo">Cuerpo Lúteo</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_quiste_simple" name="izq_quiste_simple">
                        <label class="form-check-label" for="izq_quiste_simple">Quiste Simple</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_quiste_hemorragico" name="izq_quiste_hemorragico">
                        <label class="form-check-label" for="izq_quiste_hemorragico">Quiste Hemorrágico</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_endometrioma" name="izq_endometrioma">
                        <label class="form-check-label" for="izq_endometrioma">Endometrioma</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_lesion_solida" name="izq_lesion_solida">
                        <label class="form-check-label" for="izq_lesion_solida">Lesión Sólida</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_lesion_compleja" name="izq_lesion_compleja">
                        <label class="form-check-label" for="izq_lesion_compleja">Lesión Compleja</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_no_visible" name="izq_no_visible">
                        <label class="form-check-label" for="izq_no_visible">No Visible</label>
                    </div>
                </div>
            </div>
            <h6 class="text-muted mb-3 mt-3">Folículo/Lesión Dominante Izquierdo</h6>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="izq_foliculo_med_x_mm" class="form-label">Medida X (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="izq_foliculo_med_x_mm" name="izq_foliculo_med_x_mm">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="izq_foliculo_med_y_mm" class="form-label">Medida Y (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="izq_foliculo_med_y_mm" name="izq_foliculo_med_y_mm">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="izq_foliculo_med_z_mm" class="form-label">Medida Z (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="izq_foliculo_med_z_mm" name="izq_foliculo_med_z_mm">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="izq_foliculo_contenido" class="form-label">Contenido</label>
                    <select class="form-select" id="izq_foliculo_contenido" name="izq_foliculo_contenido">
                        <option value="">Seleccione...</option>
                        <option value="Anecoico">Anecoico</option>
                        <option value="Hemorragico">Hemorrágico</option>
                        <option value="EcosFinos">Ecos Finos</option>
                        <option value="Solido">Sólido</option>
                        <option value="Mixto">Mixto</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="izq_foliculo_pared" class="form-label">Pared</label>
                    <select class="form-select" id="izq_foliculo_pared" name="izq_foliculo_pared">
                        <option value="">Seleccione...</option>
                        <option value="Fina">Fina</option>
                        <option value="Gruesa">Gruesa</option>
                        <option value="Irregular">Irregular</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="izq_foliculo_doppler" class="form-label">Doppler</label>
                    <select class="form-select" id="izq_foliculo_doppler" name="izq_foliculo_doppler">
                        <option value="">No evaluado</option>
                        <option value="SinFlujo">Sin Flujo</option>
                        <option value="FlujoPeriferico">Flujo Periférico</option>
                        <option value="FlujoCentral">Flujo Central</option>
                        <option value="FlujoComponenteSolido">Flujo en Componente Sólido</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_foliculo_septos" name="izq_foliculo_septos">
                        <label class="form-check-label" for="izq_foliculo_septos">Septos</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="izq_foliculo_septos_grosor" class="form-label">Grosor de Septos (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="izq_foliculo_septos_grosor" name="izq_foliculo_septos_grosor" placeholder="mm">
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_foliculo_papilares" name="izq_foliculo_papilares">
                        <label class="form-check-label" for="izq_foliculo_papilares">Proyecciones Papilares</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="izq_foliculo_papilares_num" class="form-label">Número</label>
                    <input type="number" class="form-control" id="izq_foliculo_papilares_num" name="izq_foliculo_papilares_num" min="0" placeholder="0">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_foliculo_sombra" name="izq_foliculo_sombra">
                        <label class="form-check-label" for="izq_foliculo_sombra">Sombra Acústica</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 10. Anexos + Fondo de Saco -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-circle-exclamation me-2"></i> Anexos y Fondo de Saco
        </div>
        <div class="card-body">
            <h6 class="text-muted mb-3">Anexo Derecho</h6>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_sin_alteraciones" name="der_sin_alteraciones" checked>
                        <label class="form-check-label" for="der_sin_alteraciones">Sin Alteraciones</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_lesion_anexial" name="der_lesion_anexial">
                        <label class="form-check-label" for="der_lesion_anexial">Lesión Anexial</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_hidrosalpinx" name="der_hidrosalpinx">
                        <label class="form-check-label" for="der_hidrosalpinx">Hidrosálpinx</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="der_paraovarico" name="der_paraovarico">
                        <label class="form-check-label" for="der_paraovarico">Paraovárico</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6 mb-3">
                    <label for="der_otro" class="form-label">Otro Hallazgo Anexo Derecho</label>
                    <input type="text" class="form-control" id="der_otro" name="der_otro" placeholder="Especifique...">
                </div>
            </div>

            <h6 class="text-muted mb-3 mt-3">Anexo Izquierdo</h6>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_sin_alteraciones" name="izq_sin_alteraciones" checked>
                        <label class="form-check-label" for="izq_sin_alteraciones">Sin Alteraciones</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_lesion_anexial" name="izq_lesion_anexial">
                        <label class="form-check-label" for="izq_lesion_anexial">Lesión Anexial</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_hidrosalpinx" name="izq_hidrosalpinx">
                        <label class="form-check-label" for="izq_hidrosalpinx">Hidrosálpinx</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="izq_paraovarico" name="izq_paraovarico">
                        <label class="form-check-label" for="izq_paraovarico">Paraovárico</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6 mb-3">
                    <label for="izq_otro" class="form-label">Otro Hallazgo Anexo Izquierdo</label>
                    <input type="text" class="form-control" id="izq_otro" name="izq_otro" placeholder="Especifique...">
                </div>
            </div>

            <h6 class="text-muted mb-3 mt-4">Fondo de Saco de Douglas</h6>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="fondo_saco_libre" name="fondo_saco_libre" checked>
                        <label class="form-check-label" for="fondo_saco_libre">Libre</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="fondo_saco_liquido_escaso" name="fondo_saco_liquido_escaso">
                        <label class="form-check-label" for="fondo_saco_liquido_escaso">Líquido Escaso</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="fondo_saco_liquido_moderado" name="fondo_saco_liquido_moderado">
                        <label class="form-check-label" for="fondo_saco_liquido_moderado">Líquido Moderado</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="fondo_saco_liquido_abundante" name="fondo_saco_liquido_abundante">
                        <label class="form-check-label" for="fondo_saco_liquido_abundante">Líquido Abundante</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="fondo_saco_liquido_ecos" name="fondo_saco_liquido_ecos">
                        <label class="form-check-label" for="fondo_saco_liquido_ecos">Líquido con Ecos</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="fondo_saco_nodulo_implante" name="fondo_saco_nodulo_implante">
                        <label class="form-check-label" for="fondo_saco_nodulo_implante">Nódulo/Implante</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="fondo_saco_dolor_presion" name="fondo_saco_dolor_presion">
                        <label class="form-check-label" for="fondo_saco_dolor_presion">Dolor a la Presión</label>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4 mb-3">
                    <label for="sliding_sign" class="form-label">Sliding Sign</label>
                    <select class="form-select" id="sliding_sign" name="sliding_sign">
                        <option value="No evaluado">No evaluado</option>
                        <option value="Positivo">Positivo</option>
                        <option value="Negativo">Negativo</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- 11. Clasificación Orientativa -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-tags me-2"></i> Clasificación Orientativa
        </div>
        <div class="card-body">
            <h6 class="text-muted mb-3">PALM-COEIN (Anomalías Uterinas)</h6>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="palm_polipo" name="palm_polipo">
                        <label class="form-check-label" for="palm_polipo">Pólipo</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="palm_adenomiosis" name="palm_adenomiosis">
                        <label class="form-check-label" for="palm_adenomiosis">Adenomiosis</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="palm_leiomioma" name="palm_leiomioma">
                        <label class="form-check-label" for="palm_leiomioma">Leiomioma</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="palm_malignidad" name="palm_malignidad">
                        <label class="form-check-label" for="palm_malignidad">Malignidad</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="palm_coagulopatia" name="palm_coagulopatia">
                        <label class="form-check-label" for="palm_coagulopatia">Coagulopatía</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="palm_ovulatoria" name="palm_ovulatoria">
                        <label class="form-check-label" for="palm_ovulatoria">Ovulatoria</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="palm_endometrial" name="palm_endometrial">
                        <label class="form-check-label" for="palm_endometrial">Endometrial</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="palm_iatrogenica" name="palm_iatrogenica">
                        <label class="form-check-label" for="palm_iatrogenica">Iatrogénica</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="palm_no_clasificada" name="palm_no_clasificada">
                        <label class="form-check-label" for="palm_no_clasificada">No Clasificada</label>
                    </div>
                </div>
            </div>
            <h6 class="text-muted mb-3 mt-4">Clasificación Anexial (O-RADS/IOTA)</h6>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="anexial_funcional" name="anexial_funcional">
                        <label class="form-check-label" for="anexial_funcional">Funcional</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="anexial_benigna" name="anexial_benigna">
                        <label class="form-check-label" for="anexial_benigna">Benigna</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="anexial_indeterminada" name="anexial_indeterminada">
                        <label class="form-check-label" for="anexial_indeterminada">Indeterminada</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="anexial_sospechosa" name="anexial_sospechosa">
                        <label class="form-check-label" for="anexial_sospechosa">Sospechosa</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="anexial_sugiere_o_rads" name="anexial_sugiere_o_rads">
                        <label class="form-check-label" for="anexial_sugiere_o_rads">Sugiere O-RADS</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 12. Impresión Diagnóstica -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-stethoscope me-2"></i> Impresión Diagnóstica
        </div>
        <div class="card-body">
            <h6 class="text-muted mb-3">Útero</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="imp_utero_tamano" class="form-label">Tamaño Uterino</label>
                    <select class="form-select" id="imp_utero_tamano" name="imp_utero_tamano">
                        <option value="">Seleccione...</option>
                        <option value="Normal">Normal</option>
                        <option value="Aumentado">Aumentado</option>
                        <option value="Disminuido">Disminuido</option>
                    </select>
                </div>
                <div class="col-md-8 mb-3">
                    <label for="imp_utero_morfologia" class="form-label">Morfología Uterina</label>
                    <textarea class="form-control" id="imp_utero_morfologia" name="imp_utero_morfologia" rows="2" placeholder="Describa hallazgos morfológicos..."></textarea>
                </div>
            </div>
            <h6 class="text-muted mb-3">Miometrio</h6>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="imp_miometrio_sin_alteraciones" name="imp_miometrio_sin_alteraciones" checked>
                        <label class="form-check-label" for="imp_miometrio_sin_alteraciones">Sin Alteraciones</label>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="imp_miometrio_miomatosis" name="imp_miometrio_miomatosis">
                        <label class="form-check-label" for="imp_miometrio_miomatosis">Miomatosis</label>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="imp_miometrio_adenomiosis" name="imp_miometrio_adenomiosis">
                        <label class="form-check-label" for="imp_miometrio_adenomiosis">Adenomiosis</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12 mb-3">
                    <label for="imp_miometrio_otro" class="form-label">Otro Hallazgo Miometrial</label>
                    <textarea class="form-control" id="imp_miometrio_otro" name="imp_miometrio_otro" rows="2" placeholder="Describa otros hallazgos..."></textarea>
                </div>
            </div>

            <h6 class="text-muted mb-3 mt-3">Endometrio</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="imp_endometrio_grosor_mm" class="form-label">Grosor (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="imp_endometrio_grosor_mm" name="imp_endometrio_grosor_mm" placeholder="Ej: 8.5">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="imp_endometrio_patron" class="form-label">Patrón</label>
                    <input type="text" class="form-control" id="imp_endometrio_patron" name="imp_endometrio_patron" placeholder="Ej: Trilaminar">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="imp_endometrio_acorde" name="imp_endometrio_acorde" checked>
                        <label class="form-check-label" for="imp_endometrio_acorde">Acorde al Ciclo</label>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="imp_endometrio_engrosado" name="imp_endometrio_engrosado">
                        <label class="form-check-label" for="imp_endometrio_engrosado">Engrosado</label>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="imp_endometrio_correlacion" name="imp_endometrio_correlacion">
                        <label class="form-check-label" for="imp_endometrio_correlacion">Requiere Correlación</label>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12 mb-3">
                    <label for="imp_ovario_derecho" class="form-label">Impresión Ovario Derecho</label>
                    <textarea class="form-control" id="imp_ovario_derecho" name="imp_ovario_derecho" rows="2" placeholder="Describa impresión diagnóstica..."></textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="imp_ovario_izquierdo" class="form-label">Impresión Ovario Izquierdo</label>
                    <textarea class="form-control" id="imp_ovario_izquierdo" name="imp_ovario_izquierdo" rows="2" placeholder="Describa impresión diagnóstica..."></textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="imp_anexos_fondo_saco" class="form-label">Impresión Anexos y Fondo de Saco</label>
                    <textarea class="form-control" id="imp_anexos_fondo_saco" name="imp_anexos_fondo_saco" rows="2" placeholder="Describa impresión diagnóstica..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- 13. Conclusión + Recomendaciones -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-clipboard-check me-2"></i> Conclusión y Recomendaciones
        </div>
        <div class="card-body">
            <h6 class="text-muted mb-3">Hallazgos Conclusivos</h6>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="concl_normal" name="concl_normal" checked>
                        <label class="form-check-label" for="concl_normal">Estudio Normal</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="concl_miomatosis" name="concl_miomatosis">
                        <label class="form-check-label" for="concl_miomatosis">Miomatosis</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="concl_engrosamiento" name="concl_engrosamiento">
                        <label class="form-check-label" for="concl_engrosamiento">Engrosamiento Endometrial</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="concl_polipo" name="concl_polipo">
                        <label class="form-check-label" for="concl_polipo">Pólipo Endometrial</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="concl_adenomiosis" name="concl_adenomiosis">
                        <label class="form-check-label" for="concl_adenomiosis">Adenomiosis</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="concl_quiste_simple_der" name="concl_quiste_simple_der">
                        <label class="form-check-label" for="concl_quiste_simple_der">Quiste Simple Derecho</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="concl_quiste_simple_izq" name="concl_quiste_simple_izq">
                        <label class="form-check-label" for="concl_quiste_simple_izq">Quiste Simple Izquierdo</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="concl_quiste_hemorragico_der" name="concl_quiste_hemorragico_der">
                        <label class="form-check-label" for="concl_quiste_hemorragico_der">Quiste Hemorrágico Derecho</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="concl_quiste_hemorragico_izq" name="concl_quiste_hemorragico_izq">
                        <label class="form-check-label" for="concl_quiste_hemorragico_izq">Quiste Hemorrágico Izquierdo</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="concl_endometrioma_der" name="concl_endometrioma_der">
                        <label class="form-check-label" for="concl_endometrioma_der">Endometrioma Derecho</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="concl_endometrioma_izq" name="concl_endometrioma_izq">
                        <label class="form-check-label" for="concl_endometrioma_izq">Endometrioma Izquierdo</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="concl_masa_indeterminada" name="concl_masa_indeterminada">
                        <label class="form-check-label" for="concl_masa_indeterminada">Masa Indeterminada</label>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-3 mb-3">
                    <label for="concl_mioma_dominante_mm" class="form-label">Mioma Dominante (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="concl_mioma_dominante_mm" name="concl_mioma_dominante_mm" placeholder="Ej: 45.0">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="concl_figo" class="form-label">FIGO</label>
                    <input type="text" class="form-control" id="concl_figo" name="concl_figo" placeholder="Ej: Tipo 3">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="concl_medida_endometrio_mm" class="form-label">Medida Endometrio (mm)</label>
                    <input type="number" step="0.01" class="form-control" id="concl_medida_endometrio_mm" name="concl_medida_endometrio_mm" placeholder="Ej: 8.5">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="concl_quiste_medida_mm" class="form-label">Medida de Quiste (mm)</label>
                    <input type="text" class="form-control" id="concl_quiste_medida_mm" name="concl_quiste_medida_mm" placeholder="Ej: 35 x 28">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="concl_otro" class="form-label">Otra Conclusión</label>
                    <textarea class="form-control" id="concl_otro" name="concl_otro" rows="2" placeholder="Describa otros hallazgos..."></textarea>
                </div>
            </div>

            <h6 class="text-muted mb-3 mt-4">Recomendaciones</h6>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rec_correlacion_edad_fum" name="rec_correlacion_edad_fum">
                        <label class="form-check-label" for="rec_correlacion_edad_fum">Correlación con Edad y FUM</label>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rec_correlacion_hb_hormonal" name="rec_correlacion_hb_hormonal">
                        <label class="form-check-label" for="rec_correlacion_hb_hormonal">Correlación HB/Hormonal</label>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rec_estudio_histologico" name="rec_estudio_histologico">
                        <label class="form-check-label" for="rec_estudio_histologico">Estudio Histológico</label>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rec_histeroscopia_endometrio" name="rec_histeroscopia_endometrio">
                        <label class="form-check-label" for="rec_histeroscopia_endometrio">Histeroscopia + Biopsia de Endometrio</label>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rec_sonohisterografia_histeroscopia" name="rec_sonohisterografia_histeroscopia">
                        <label class="form-check-label" for="rec_sonohisterografia_histeroscopia">Sonohisterografía / Histeroscopia</label>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rec_valorar_manejo_miomatosis" name="rec_valorar_manejo_miomatosis">
                        <label class="form-check-label" for="rec_valorar_manejo_miomatosis">Valorar Manejo de Miomatosis</label>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rec_iorads_marcadores_oncologia" name="rec_iorads_marcadores_oncologia">
                        <label class="form-check-label" for="rec_iorads_marcadores_oncologia">IOTA/O-RADS + Marcadores + Oncología</label>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rec_control_ultrasonografico" name="rec_control_ultrasonografico">
                        <label class="form-check-label" for="rec_control_ultrasonografico">Control Ultrasonográfico</label>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-3 mb-3">
                    <label for="rec_control_tiempo" class="form-label">Tiempo de Control</label>
                    <input type="number" class="form-control" id="rec_control_tiempo" name="rec_control_tiempo" min="1" placeholder="Ej: 6">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="rec_control_unidad" class="form-label">Unidad</label>
                    <select class="form-select" id="rec_control_unidad" name="rec_control_unidad">
                        <option value="">Seleccione...</option>
                        <option value="Semanas">Semanas</option>
                        <option value="Meses">Meses</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="rec_otro" class="form-label">Otras Recomendaciones</label>
                    <textarea class="form-control" id="rec_otro" name="rec_otro" rows="2" placeholder="Otras recomendaciones..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Estado y Submit -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-8 text-end ms-auto">
                    <a href="<?php echo Url::to('/evaluaciones_ginecologicas'); ?>" class="btn btn-apple btn-apple-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-apple btn-apple-primary btn-lg">
                        <i class="fa-solid fa-save"></i> Guardar USG Ginecológico
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
let miomaRowCount = 0;
document.getElementById('addMiomaRow').addEventListener('click', function() {
    miomaRowCount++;
    const tbody = document.getElementById('miomasDetalleBody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td class="text-center">${miomaRowCount}</td>
        <td><input type="text" class="form-control form-control-sm" name="md_localizacion[]" placeholder="Ej: Fúndico anterior"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="md_medida_x[]" placeholder="mm"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="md_medida_y[]" placeholder="mm"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="md_medida_z[]" placeholder="mm"></td>
        <td><input type="text" class="form-control form-control-sm" name="md_relacion[]" placeholder="Ej: Contacta con cavidad"></td>
        <td><input type="text" class="form-control form-control-sm" name="md_figo[]" placeholder="Ej: Tipo 3"></td>
        <td><input type="text" class="form-control form-control-sm" name="md_doppler[]" placeholder="Ej: Vascularización periférica"></td>
        <td><input type="text" class="form-control form-control-sm" name="md_comentarios[]" placeholder="Comentarios"></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()"><i class="fa-solid fa-trash"></i></button></td>
    `;
    tbody.appendChild(tr);
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
