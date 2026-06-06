<?php
$title = "Ver USG Ginecológico";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$labels = function($data, $map) {
    $selected = [];
    foreach ($map as $field => $label) {
        if (!empty($data[$field])) $selected[] = $label;
    }
    return !empty($selected) ? implode(', ', $selected) : '—';
};
$siNo = fn($v) => $v ? 'Sí' : 'No';
$siNoNA = fn($v) => $v === null ? '—' : ($v ? 'Sí' : 'No');
$txt = fn($v) => htmlspecialchars($v ?: '—');
$num = fn($v) => $v !== null && $v !== '' ? htmlspecialchars($v) : '—';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/evaluaciones_ginecologicas'); ?>" class="btn btn-light rounded-3">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="page-title mb-0">USG Ginecológico — <?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></h1>
    </div>
    <div class="page-header-actions">
        <a href="<?php echo Url::to('/evaluaciones_ginecologicas/edit?id=' . $evaluacion['id']); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-edit"></i> Editar
        </a>
        <a href="<?php echo Url::to('/evaluaciones_ginecologicas/print?id=' . $evaluacion['id']); ?>" class="btn btn-light" target="_blank">
            <i class="fa-solid fa-print"></i> Imprimir
        </a>
    </div>
</div>

<!-- DATOS GENERALES -->
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Datos Generales</h5></div>
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Paciente</dt>
            <dd class="col-sm-3"><?php echo htmlspecialchars($evaluacion['paciente_nombre'] . ' ' . $evaluacion['paciente_apellido']); ?></dd>
            <dt class="col-sm-3">Edad</dt>
            <dd class="col-sm-3"><?php echo $evaluacion['fecha_nacimiento'] ? (date('Y') - date('Y', strtotime($evaluacion['fecha_nacimiento']))) . ' años' : '—'; ?></dd>
            <dt class="col-sm-3">Fecha del estudio</dt>
            <dd class="col-sm-3"><?php echo date('d/m/Y', strtotime($evaluacion['fecha_estudio'])); ?></dd>
            <dt class="col-sm-3">Médico solicitante</dt>
            <dd class="col-sm-3"><?php echo $txt($evaluacion['solicitante_nombre'] ? $evaluacion['solicitante_nombre'] . ' ' . $evaluacion['solicitante_apellido'] : null); ?></dd>
            <dt class="col-sm-3">Médico que realiza</dt>
            <dd class="col-sm-3"><?php echo htmlspecialchars($evaluacion['medico_nombre'] . ' ' . $evaluacion['medico_apellido']); ?></dd>
            <dt class="col-sm-3">Indicación clínica</dt>
            <dd class="col-sm-9"><?php echo $txt($evaluacion['indicacion_clinica']); ?></dd>
            <dt class="col-sm-3">FUM</dt>
            <dd class="col-sm-3"><?php echo $evaluacion['fum'] ? date('d/m/Y', strtotime($evaluacion['fum'])) : '—'; ?></dd>
            <dt class="col-sm-3">Día del ciclo</dt>
            <dd class="col-sm-3"><?php echo $num($evaluacion['dia_ciclo_menstrual']); ?></dd>
        </dl>
    </div>
</div>

<!-- INDICACIONES -->
<?php if ($indicaciones): ?>
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Indicaciones</h5></div>
    <div class="card-body">
        <h6 class="fw-bold">Motivo del estudio</h6>
        <p><?php echo $labels($indicaciones, [
            'sangrado_uterino_anormal' => 'Sangrado uterino anormal',
            'dolor_pelvico' => 'Dolor pélvico',
            'miomatosis_uterina' => 'Miomatosis uterina',
            'sospecha_polipo_endometrial' => 'Sospecha de pólipo endometrial',
            'engrosamiento_endometrial' => 'Engrosamiento endometrial',
            'control_diu' => 'Control de DIU',
            'infertilidad_reproduccion' => 'Infertilidad / reproducción',
            'quiste_ovarico_masa_anexial' => 'Quiste ovárico / masa anexial',
            'sindrome_climaterico' => 'Síndrome climatérico / perimenopausia',
            'sangrado_posmenopausico' => 'Sangrado posmenopáusico',
        ]); ?></p>
        <?php if (!empty($indicaciones['motivo_estudio_otro'])): ?>
        <p><strong>Otro:</strong> <?php echo htmlspecialchars($indicaciones['motivo_estudio_otro']); ?></p>
        <?php endif; ?>

        <h6 class="fw-bold mt-3">Estatus hormonal</h6>
        <p><?php echo $labels($indicaciones, [
            'premenopausica' => 'Premenopáusica',
            'perimenopausica' => 'Perimenopáusica',
            'posmenopausica' => 'Posmenopáusica',
            'terapia_hormonal' => 'Uso de terapia hormonal',
            'tamoxifeno' => 'Uso de tamoxifeno',
            'anticonceptivos_hormonales' => 'Anticonceptivos hormonales',
            'estatus_no_especificado' => 'No especificado',
        ]); ?></p>
    </div>
</div>
<?php endif; ?>

<!-- ANTECEDENTES -->
<?php if ($antecedentes): ?>
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Antecedentes</h5></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3"><strong>Gesta:</strong> <?php echo $num($antecedentes['gesta']); ?></div>
            <div class="col-md-3"><strong>Para:</strong> <?php echo $num($antecedentes['para']); ?></div>
            <div class="col-md-3"><strong>Cesáreas:</strong> <?php echo $num($antecedentes['cesareas']); ?></div>
            <div class="col-md-3"><strong>Abortos:</strong> <?php echo $num($antecedentes['abortos']); ?></div>
        </div>
        <div class="mt-2">
            <strong>Paridad satisfecha:</strong> <?php echo $siNoNA($antecedentes['paridad_satisfecha']); ?><br>
            <strong>Legrado/cirugía uterina:</strong> <?php echo $siNo($antecedentes['legrado_cirugia_uterina']); ?><br>
            <strong>Miomectomía:</strong> <?php echo $siNo($antecedentes['miomectomia']); ?><br>
            <strong>Endometriosis/adenomiosis:</strong> <?php echo $siNo($antecedentes['endometriosis_adenomiosis']); ?>
        </div>
        <?php if (!empty($antecedentes['otros'])): ?>
        <p class="mt-2"><strong>Otros:</strong> <?php echo nl2br(htmlspecialchars($antecedentes['otros'])); ?></p>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- TÉCNICA -->
<?php if ($tecnica): ?>
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Técnica</h5></div>
    <div class="card-body">
        <p><?php echo $labels($tecnica, [
            'via_endovaginal' => 'Endovaginal',
            'via_transabdominal' => 'Transabdominal complementario',
            'via_doppler_color' => 'Doppler color / power Doppler',
            'via_evaluacion_3d' => 'Evaluación 3D',
            'via_sonohisterografia' => 'Sonohisterografía',
        ]); ?></p>
        <p><strong>Calidad:</strong> <?php echo $txt($tecnica['calidad']); ?></p>
        <p><?php echo $labels($tecnica, [
            'limitada_dolor' => 'Limitada por dolor',
            'limitada_distension_intestinal' => 'Limitada por distensión intestinal',
            'limitada_habitus_corporal' => 'Limitada por habitus corporal',
            'limitada_posicion_uterina' => 'Limitada por posición uterina',
        ]); ?></p>
        <?php if (!empty($tecnica['calidad_otra'])): ?>
        <p><strong>Otra:</strong> <?php echo htmlspecialchars($tecnica['calidad_otra']); ?></p>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- ÚTERO + CÉRVIX -->
<?php if ($uteroCervix): ?>
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Útero y Cérvix</h5></div>
    <div class="card-body">
        <p><strong>Situación:</strong> <?php echo $txt($uteroCervix['situacion']); ?></p>
        <p><strong>Morfología:</strong> <?php echo $labels($uteroCervix, [
            'morfologia_regular' => 'Regular', 'morfologia_bordes_irregulares' => 'Bordes irregulares',
            'morfologia_globoso' => 'Globoso', 'morfologia_aumentado' => 'Aumentado', 'morfologia_disminuido' => 'Disminuido',
        ]); ?> <?php if (!empty($uteroCervix['morfologia_otro'])) echo ' — ' . htmlspecialchars($uteroCervix['morfologia_otro']); ?></p>
        <div class="row">
            <div class="col-md-3"><strong>Longitud:</strong> <?php echo $num($uteroCervix['dim_longitud_mm']); ?> mm</div>
            <div class="col-md-3"><strong>AP:</strong> <?php echo $num($uteroCervix['dim_anteroposterior_mm']); ?> mm</div>
            <div class="col-md-3"><strong>Transverso:</strong> <?php echo $num($uteroCervix['dim_transverso_mm']); ?> mm</div>
            <div class="col-md-3"><strong>Volumen:</strong> <?php echo $num($uteroCervix['volumen_cc']); ?> cc</div>
        </div>
        <p class="mt-2"><strong>Miometrio:</strong> <?php echo $labels($uteroCervix, [
            'miometrio_homogeneo' => 'Homogéneo', 'miometrio_heterogeneo' => 'Heterogéneo',
            'miometrio_imagenes_leiomiomas' => 'Imágenes compatibles con leiomiomas',
            'miometrio_sugestivo_adenomiosis' => 'Datos sugestivos de adenomiosis',
            'miometrio_calcificaciones' => 'Calcificaciones',
            'miometrio_areas_quisticas' => 'Áreas quísticas miometriales',
            'miometrio_sombra_acustica' => 'Sombra acústica',
        ]); ?> <?php if (!empty($uteroCervix['miometrio_otro'])) echo ' — ' . htmlspecialchars($uteroCervix['miometrio_otro']); ?></p>
        <hr>
        <p><strong>Longitud cervical:</strong> <?php echo $num($uteroCervix['cervix_longitud_mm']); ?> mm</p>
        <p><strong>Hallazgos cervicales:</strong> <?php echo $labels($uteroCervix, [
            'cervix_sin_alteraciones' => 'Sin alteraciones', 'cervix_quistes_naboth' => 'Quistes de Naboth',
            'cervix_polipo_endocervical' => 'Pólipo endocervical', 'cervix_lesion_visible_usg' => 'Lesión visible por USG',
            'cervix_liquido_canal' => 'Líquido en canal cervical',
        ]); ?> <?php if (!empty($uteroCervix['cervix_otro'])) echo ' — ' . htmlspecialchars($uteroCervix['cervix_otro']); ?></p>
    </div>
</div>
<?php endif; ?>

<!-- MIOMAS -->
<?php if ($miomas): ?>
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Miomas / Leiomiomas</h5></div>
    <div class="card-body">
        <p><strong>Identificados:</strong> <?php echo $siNo($miomas['identificados']); ?></p>
        <?php if ($miomas['identificados']): ?>
        <p><strong>Número aproximado:</strong> <?php echo $num($miomas['numero_aproximado']); ?></p>
        <p><strong>Mioma dominante:</strong> <?php echo $num($miomas['mioma_dominante_mm']); ?> mm</p>
        <p><strong>Predominio:</strong> <?php echo $labels($miomas, [
            'predominio_submucosos' => 'Submucosos', 'predominio_intramurales' => 'Intramurales',
            'predominio_subserosos' => 'Subserosos', 'predominio_pediculados' => 'Pediculados',
            'predominio_cervicales' => 'Cervicales', 'predominio_distribucion_difusa' => 'Múltiples, distribución difusa',
        ]); ?></p>
        <?php if (!empty($miomasDetalle)): ?>
        <table class="table table-sm table-bordered mt-3">
            <thead><tr><th>#</th><th>Localización</th><th>Medidas (mm)</th><th>Relación endometrio</th><th>FIGO</th><th>Doppler</th><th>Comentarios</th></tr></thead>
            <tbody>
            <?php foreach ($miomasDetalle as $md): ?>
            <tr>
                <td><?php echo $md['numero']; ?></td>
                <td><?php echo $txt($md['localizacion']); ?></td>
                <td><?php echo $num($md['medida_x_mm']) . ' × ' . $num($md['medida_y_mm']) . ' × ' . $num($md['medida_z_mm']); ?></td>
                <td><?php echo $txt($md['relacion_endometrio']); ?></td>
                <td><?php echo $txt($md['clasificacion_figo']); ?></td>
                <td><?php echo $txt($md['doppler']); ?></td>
                <td><?php echo $txt($md['comentarios']); ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- ADENOMIOSIS -->
<?php if ($adenomiosis): ?>
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Adenomiosis</h5></div>
    <div class="card-body">
        <p><strong>Hallazgos:</strong> <?php echo $txt($adenomiosis['hallazgos']); ?></p>
        <p><strong>Datos sonográficos:</strong> <?php echo $labels($adenomiosis, [
            'utero_globoso' => 'Útero globoso', 'asimetria_paredes' => 'Asimetría de paredes',
            'miometrio_heterogeneo' => 'Miometrio heterogéneo', 'estriaciones_lineales' => 'Estriaciones lineales',
            'quistes_miometriales' => 'Quistes miometriales', 'islas_hiperecogenicas' => 'Islas hiperecogénicas',
            'sombra_abanico' => 'Sombra en abanico', 'zona_union_irregular' => 'Zona de unión irregular/engrosada',
            'vascularidad_translesional' => 'Vascularidad translesional',
        ]); ?> <?php if (!empty($adenomiosis['datos_otro'])) echo ' — ' . htmlspecialchars($adenomiosis['datos_otro']); ?></p>
        <p><strong>Distribución:</strong> <?php echo $txt($adenomiosis['distribucion']); ?>
        <?php if ($adenomiosis['predominio_anterior']) echo ' · Anterior'; ?>
        <?php if ($adenomiosis['predominio_posterior']) echo ' · Posterior'; ?>
        <?php if ($adenomiosis['predominio_fundico']) echo ' · Fúndico'; ?></p>
    </div>
</div>
<?php endif; ?>

<!-- ENDOMETRIO -->
<?php if ($endometrio): ?>
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Endometrio</h5></div>
    <div class="card-body">
        <p><strong>Grosor:</strong> <?php echo $num($endometrio['grosor_mm']); ?> mm</p>
        <p><strong>Patrón:</strong> <?php echo $txt($endometrio['patron']); ?></p>
        <p><strong>Correlación con ciclo:</strong> <?php echo $txt($endometrio['correlacion_ciclo']); ?></p>
        <p><strong>Cavidad:</strong> <?php echo $labels($endometrio, [
            'cavidad_regular' => 'Regular', 'cavidad_distorsionada' => 'Distorsionada',
            'cavidad_liquido_intracavitario' => 'Con líquido intracavitario',
            'cavidad_imagen_focal_polipo' => 'Imagen focal sugestiva de pólipo',
            'cavidad_imagen_mioma_submucoso' => 'Imagen de mioma submucoso',
            'cavidad_sinequias' => 'Sinequias sospechadas', 'cavidad_diu_intrauterino' => 'DIU intrauterino',
        ]); ?> <?php if (!empty($endometrio['cavidad_otro'])) echo ' — ' . htmlspecialchars($endometrio['cavidad_otro']); ?></p>
        <p><strong>Doppler endometrial:</strong> <?php echo $txt($endometrio['doppler']); ?></p>
        <?php if ($endometrio['diu_posicion']): ?>
        <p><strong>DIU:</strong> <?php echo $txt($endometrio['diu_posicion']); ?> · Distancia al fondo: <?php echo $num($endometrio['diu_distancia_fondo_mm']); ?> mm</p>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- OVARIOS -->
<?php if ($ovarios): ?>
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Ovarios</h5></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold">Ovario Derecho</h6>
                <p>Dimensiones: <?php echo $num($ovarios['der_dim_x_mm']); ?> × <?php echo $num($ovarios['der_dim_y_mm']); ?> × <?php echo $num($ovarios['der_dim_z_mm']); ?> mm · Volumen: <?php echo $num($ovarios['der_volumen_cc']); ?> cc</p>
                <p>Morfología: <?php echo $labels($ovarios, [
                    'der_normal' => 'Normal', 'der_atrofico' => 'Atrófico', 'der_multifolicular' => 'Multifolicular',
                    'der_poliquistico' => 'Poliquístico', 'der_cuerpo_luteo' => 'Cuerpo lúteo',
                    'der_quiste_simple' => 'Quiste simple', 'der_quiste_hemorragico' => 'Quiste hemorrágico',
                    'der_endometrioma' => 'Endometrioma', 'der_lesion_solida' => 'Lesión sólida',
                    'der_lesion_compleja' => 'Lesión compleja', 'der_no_visible' => 'No visible',
                ]); ?></p>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold">Ovario Izquierdo</h6>
                <p>Dimensiones: <?php echo $num($ovarios['izq_dim_x_mm']); ?> × <?php echo $num($ovarios['izq_dim_y_mm']); ?> × <?php echo $num($ovarios['izq_dim_z_mm']); ?> mm · Volumen: <?php echo $num($ovarios['izq_volumen_cc']); ?> cc</p>
                <p>Morfología: <?php echo $labels($ovarios, [
                    'izq_normal' => 'Normal', 'izq_atrofico' => 'Atrófico', 'izq_multifolicular' => 'Multifolicular',
                    'izq_poliquistico' => 'Poliquístico', 'izq_cuerpo_luteo' => 'Cuerpo lúteo',
                    'izq_quiste_simple' => 'Quiste simple', 'izq_quiste_hemorragico' => 'Quiste hemorrágico',
                    'izq_endometrioma' => 'Endometrioma', 'izq_lesion_solida' => 'Lesión sólida',
                    'izq_lesion_compleja' => 'Lesión compleja', 'izq_no_visible' => 'No visible',
                ]); ?></p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ANEXOS + FONDO DE SACO -->
<?php if ($anexos): ?>
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Anexos y Fondo de Saco</h5></div>
    <div class="card-body">
        <p><strong>Anexo derecho:</strong> <?php echo $labels($anexos, [
            'der_sin_alteraciones' => 'Sin alteraciones', 'der_lesion_anexial' => 'Lesión anexial',
            'der_hidrosalpinx' => 'Hidrosálpinx', 'der_paraovarico' => 'Paraovárico',
        ]); ?> <?php if (!empty($anexos['der_otro'])) echo ' — ' . htmlspecialchars($anexos['der_otro']); ?></p>
        <p><strong>Anexo izquierdo:</strong> <?php echo $labels($anexos, [
            'izq_sin_alteraciones' => 'Sin alteraciones', 'izq_lesion_anexial' => 'Lesión anexial',
            'izq_hidrosalpinx' => 'Hidrosálpinx', 'izq_paraovarico' => 'Paraovárico',
        ]); ?> <?php if (!empty($anexos['izq_otro'])) echo ' — ' . htmlspecialchars($anexos['izq_otro']); ?></p>
        <p><strong>Fondo de saco:</strong> <?php echo $labels($anexos, [
            'fondo_saco_libre' => 'Libre', 'fondo_saco_liquido_escaso' => 'Líquido escaso',
            'fondo_saco_liquido_moderado' => 'Líquido moderado', 'fondo_saco_liquido_abundante' => 'Líquido abundante',
            'fondo_saco_liquido_ecos' => 'Líquido con ecos', 'fondo_saco_nodulo_implante' => 'Nódulo/implante',
            'fondo_saco_dolor_presion' => 'Dolor a la presión con transductor',
        ]); ?></p>
        <p><strong>Sliding sign:</strong> <?php echo $txt($anexos['sliding_sign']); ?></p>
    </div>
</div>
<?php endif; ?>

<!-- CLASIFICACIÓN -->
<?php if ($clasificacion): ?>
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Clasificación Orientativa</h5></div>
    <div class="card-body">
        <p><strong>PALM-COEIN:</strong> <?php echo $labels($clasificacion, [
            'palm_polipo' => 'P: Pólipo', 'palm_adenomiosis' => 'A: Adenomiosis',
            'palm_leiomioma' => 'L: Leiomioma', 'palm_malignidad' => 'M: Malignidad/hiperplasia',
            'palm_coagulopatia' => 'C: Coagulopatía', 'palm_ovulatoria' => 'O: Disfunción ovulatoria',
            'palm_endometrial' => 'E: Endometrial funcional', 'palm_iatrogenica' => 'I: Iatrogénica',
            'palm_no_clasificada' => 'N: No clasificada',
        ]); ?></p>
        <p><strong>Clasificación anexial:</strong> <?php echo $labels($clasificacion, [
            'anexial_funcional' => 'Funcional', 'anexial_benigna' => 'Benigna',
            'anexial_indeterminada' => 'Indeterminada', 'anexial_sospechosa' => 'Sospechosa',
            'anexial_sugiere_o_rads' => 'Sugiere O-RADS/IOTA',
        ]); ?></p>
    </div>
</div>
<?php endif; ?>

<!-- IMPRESIÓN DIAGNÓSTICA -->
<?php if ($impresion): ?>
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Impresión Diagnóstica</h5></div>
    <div class="card-body">
        <p><strong>Útero:</strong> tamaño <?php echo $txt($impresion['utero_tamano']); ?><?php if (!empty($impresion['utero_morfologia'])) echo ', ' . htmlspecialchars($impresion['utero_morfologia']); ?></p>
        <p><strong>Miometrio:</strong> <?php echo $labels($impresion, [
            'miometrio_sin_alteraciones' => 'Sin alteraciones', 'miometrio_miomatosis' => 'Miomatosis', 'miometrio_adenomiosis' => 'Adenomiosis',
        ]); ?> <?php if (!empty($impresion['miometrio_otro'])) echo ' — ' . htmlspecialchars($impresion['miometrio_otro']); ?></p>
        <p><strong>Endometrio:</strong> <?php echo $num($impresion['endometrio_grosor_mm']); ?> mm, patrón <?php echo $txt($impresion['endometrio_patron']); ?> |
            <?php if ($impresion['endometrio_acorde_contexto']) echo 'Acorde al contexto'; ?>
            <?php if ($impresion['endometrio_engrosado_contexto']) echo 'Engrosado para el contexto'; ?>
            <?php if ($impresion['endometrio_requiere_correlacion']) echo 'Requiere correlación'; ?></p>
        <p><strong>Ovario derecho:</strong> <?php echo $txt($impresion['ovario_derecho']); ?></p>
        <p><strong>Ovario izquierdo:</strong> <?php echo $txt($impresion['ovario_izquierdo']); ?></p>
        <p><strong>Anexos / fondo de saco:</strong> <?php echo $txt($impresion['anexos_fondo_saco']); ?></p>
    </div>
</div>
<?php endif; ?>

<!-- CONCLUSIÓN + RECOMENDACIONES -->
<?php if ($conclusion): ?>
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Conclusión y Recomendaciones</h5></div>
    <div class="card-body">
        <h6 class="fw-bold">Conclusión</h6>
        <p><?php echo $labels($conclusion, [
            'estudio_limites_esperados' => 'Dentro de límites esperados',
            'miomatosis_uterina' => 'Miomatosis uterina' . ($conclusion['conclusion_mioma_dominante_mm'] ? ' (mioma dominante ' . $conclusion['conclusion_mioma_dominante_mm'] . ' mm, FIGO ' . $conclusion['conclusion_figo'] . ')' : ''),
            'engrosamiento_endometrial' => 'Engrosamiento endometrial' . ($conclusion['conclusion_medida_endometrio_mm'] ? ' (' . $conclusion['conclusion_medida_endometrio_mm'] . ' mm)' : ''),
            'imagen_focal_polipo' => 'Imagen focal sugestiva de pólipo endometrial',
            'datos_sugestivos_adenomiosis' => 'Datos sugestivos de adenomiosis',
            'quiste_simple_der' => 'Quiste simple derecho' . ($conclusion['conclusion_quiste_medida_mm'] ? ' (' . $conclusion['conclusion_quiste_medida_mm'] . ' mm)' : ''),
            'quiste_simple_izq' => 'Quiste simple izquierdo' . ($conclusion['conclusion_quiste_medida_mm'] ? ' (' . $conclusion['conclusion_quiste_medida_mm'] . ' mm)' : ''),
            'quiste_hemorragico_der' => 'Quiste hemorrágico derecho',
            'quiste_hemorragico_izq' => 'Quiste hemorrágico izquierdo',
            'endometrioma_der' => 'Endometrioma probable derecho',
            'endometrioma_izq' => 'Endometrioma probable izquierdo',
            'masa_anexial_indeterminada' => 'Masa anexial indeterminada',
        ]); ?></p>
        <?php if (!empty($conclusion['conclusion_otro'])): ?>
        <p><strong>Otro:</strong> <?php echo nl2br(htmlspecialchars($conclusion['conclusion_otro'])); ?></p>
        <?php endif; ?>

        <h6 class="fw-bold mt-3">Recomendaciones</h6>
        <p><?php echo $labels($conclusion, [
            'rec_correlacion_edad_fum' => 'Correlacionar con edad, FUM y patrón de sangrado',
            'rec_correlacion_hb_hormonal' => 'Correlacionar con hemoglobina y perfil hormonal',
            'rec_estudio_histologico' => 'Considerar estudio histológico endometrial',
            'rec_histeroscopia_endometrio' => 'Considerar histeroscopia diagnóstica/terapéutica',
            'rec_sonohisterografia_histeroscopia' => 'Considerar sonohisterografía o histeroscopia',
            'rec_valorar_manejo_miomatosis' => 'Valorar manejo médico vs quirúrgico de miomatosis',
            'rec_iorads_marcadores_oncologia' => 'Complementar con IOTA/O-RADS y marcadores tumorales',
            'rec_control_ultrasonografico' => 'Control ultrasonográfico' . ($conclusion['rec_control_tiempo'] ? ' en ' . $conclusion['rec_control_tiempo'] . ' ' . $conclusion['rec_control_unidad'] : ''),
        ]); ?></p>
        <?php if (!empty($conclusion['rec_otro'])): ?>
        <p><strong>Otro:</strong> <?php echo nl2br(htmlspecialchars($conclusion['rec_otro'])); ?></p>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
