<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>USG Ginecológico — <?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></title>
    <style>
        @media print { .no-print { display: none !important; } }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #2A2A2A;
            line-height: 1.5;
            font-size: 11px;
            padding: 0 10px;
        }
        .title-container {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1B4F5A;
            padding-bottom: 10px;
        }
        .title-container h1 {
            font-size: 18px;
            font-weight: bold;
            color: #1B4F5A;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .title-container .subtitle {
            font-size: 12px;
            color: #81BABB;
            margin-top: 5px;
            font-weight: bold;
        }
        .patient-card {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #F4F7F6;
            border-left: 4px solid #1B4F5A;
        }
        .patient-card td {
            padding: 8px 12px;
            font-size: 11px;
            vertical-align: top;
        }
        .patient-card .lbl {
            font-weight: bold;
            color: #1B4F5A;
            width: 120px;
        }
        .patient-card .val {
            color: #2A2A2A;
        }
        .section-title {
            background-color: #1B4F5A;
            color: #FFFFFF;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 10px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 2px solid #81BABB;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .data-table td {
            padding: 6px 10px;
            font-size: 11px;
            border-bottom: 1px solid #E5EDED;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) td {
            background-color: #F9FBFA;
        }
        .data-table tr:last-child td {
            border-bottom: none;
        }
        .data-table .lbl {
            font-weight: bold;
            color: #1B4F5A;
            width: 180px;
        }
        .data-table .val {
            color: #2A2A2A;
        }
        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 10px;
        }
        .print-table th {
            background-color: #1B4F5A;
            color: #FFFFFF;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 8px;
            border: 1px solid #D1DDDC;
        }
        .print-table td {
            padding: 6px 8px;
            border: 1px solid #E5EDED;
            vertical-align: top;
        }
        .print-table tr:nth-child(even) td {
            background-color: #F9FBFA;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
            margin-right: 2px;
            margin-bottom: 2px;
        }
        .badge-success {
            background-color: #EAF8F0;
            color: #226E43;
        }
        .badge-danger {
            background-color: #FDF0F0;
            color: #A82424;
        }
        .badge-warning {
            background-color: #FCF7E6;
            color: #8F6E0A;
        }
        .badge-secondary {
            background-color: #F0F4F8;
            color: #475D74;
        }
        .badge-info {
            background-color: #EAF2F8;
            color: #2A6F97;
        }
        .checkbox-label {
            display: inline-block;
            margin-right: 12px;
            font-size: 10px;
        }
        .block-text {
            background-color: #F9FBFA;
            padding: 10px;
            border: 1px solid #E5EDED;
            border-radius: 4px;
            line-height: 1.5;
            margin-top: 5px;
        }
        .grid-table {
            width: 100%;
            border-collapse: collapse;
        }
        .grid-table td {
            vertical-align: top;
            padding: 0;
        }
    </style>
</head>
<body>

<?php
$siNo = fn($v) => $v ? 'Sí' : 'No';
$siNoNA = fn($v) => $v === null ? '—' : ($v ? 'Sí' : 'No');
$txt = fn($v) => htmlspecialchars($v ?: '—');
$num = fn($v) => ($v !== null && $v !== '') ? htmlspecialchars($v) : '—';

$labels = function($data, $map) {
    $r = [];
    foreach ($map as $f => $l) {
        if (!empty($data[$f])) {
            $class = 'badge-secondary';
            if (strpos($f, 'malignidad') !== false || strpos($f, 'sospechosa') !== false) {
                $class = 'badge-danger';
            } elseif (strpos($f, 'benigna') !== false || strpos($f, 'funcional') !== false) {
                $class = 'badge-success';
            } elseif (strpos($f, 'indeterminada') !== false || strpos($f, 'adenomiosis') !== false || strpos($f, 'leiomioma') !== false || strpos($f, 'polipo') !== false) {
                $class = 'badge-warning';
            }
            $r[] = '<span class="badge ' . $class . '">' . htmlspecialchars($l) . '</span>';
        }
    }
    return !empty($r) ? implode(' ', $r) : '—';
};

$checks = function($data, $map) {
    $o = '';
    foreach ($map as $f => $l) {
        $glyph = !empty($data[$f]) ? '☑ ' : '☐ ';
        $o .= '<span class="checkbox-label">' . $glyph . htmlspecialchars($l) . '</span> ';
    }
    return $o ?: '—';
};

$pacienteEdad = $evaluacion['fecha_nacimiento'] ? (date('Y') - date('Y', strtotime($evaluacion['fecha_nacimiento']))) . ' años' : '—';
$medicoNombre = htmlspecialchars($evaluacion['medico_nombre'] . ' ' . $evaluacion['medico_apellido']);
$medicoSolicitante = $evaluacion['medico_solicitante_nombre'] ? htmlspecialchars($evaluacion['medico_solicitante_nombre'] . ' ' . $evaluacion['medico_solicitante_apellido']) : '—';
?>

<div class="title-container">
    <h1>Ultrasonido Ginecológico Endovaginal</h1>
    <div class="subtitle">Código: <?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></div>
</div>

<table class="patient-card">
    <tr>
        <td class="lbl">Paciente:</td>
        <td class="val"><strong><?php echo $txt($evaluacion['paciente_nombre'] . ' ' . $evaluacion['paciente_apellido']); ?></strong></td>
        <td class="lbl">Fecha del Estudio:</td>
        <td class="val"><?php echo date('d/m/Y', strtotime($evaluacion['fecha_estudio'])); ?></td>
    </tr>
    <tr>
        <td class="lbl">Médico que realiza:</td>
        <td class="val"><?php echo $medicoNombre; ?></td>
        <td class="lbl">Edad de Paciente:</td>
        <td class="val"><?php echo $pacienteEdad; ?></td>
    </tr>
    <tr>
        <td class="lbl">Médico Solicitante:</td>
        <td class="val"><?php echo $medicoSolicitante; ?></td>
        <td class="lbl">FUM / Ciclo:</td>
        <td class="val">
            <?php echo $evaluacion['fum'] ? date('d/m/Y', strtotime($evaluacion['fum'])) : '—'; ?> 
            <?php if ($evaluacion['dia_ciclo_menstrual']): ?>
                (Día <?php echo htmlspecialchars($evaluacion['dia_ciclo_menstrual']); ?>)
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td class="lbl">Indicación Clínica:</td>
        <td class="val" colspan="3"><?php echo $txt($evaluacion['indicacion_clinica']); ?></td>
    </tr>
</table>

<?php if ($indicaciones): ?>
<div class="section-title">Motivo del Estudio y Estatus Hormonal</div>
<table class="data-table">
    <tr>
        <td class="lbl" style="width: 20%;">Motivo:</td>
        <td class="val">
            <?php echo $checks($indicaciones, ['sangrado_uterino_anormal'=>'Sangrado uterino anormal','dolor_pelvico'=>'Dolor pélvico','miomatosis_uterina'=>'Miomatosis','sospecha_polipo_endometrial'=>'Sospecha pólipo','engrosamiento_endometrial'=>'Engrosamiento endometrial','control_diu'=>'Control DIU','infertilidad_reproduccion'=>'Infertilidad','quiste_ovarico_masa_anexial'=>'Quiste/masa anexial','sindrome_climaterico'=>'S. climaterio','sangrado_posmenopausico'=>'Sangrado posmenopáusico']); ?>
            <?php if (!empty($indicaciones['motivo_estudio_otro'])) echo ' (' . htmlspecialchars($indicaciones['motivo_estudio_otro']) . ')'; ?>
        </td>
    </tr>
    <tr>
        <td class="lbl" style="width: 20%;">Estatus Hormonal:</td>
        <td class="val">
            <?php echo $checks($indicaciones, ['premenopausica'=>'Premenopáusica','perimenopausica'=>'Perimenopáusica','posmenopausica'=>'Posmenopáusica','terapia_hormonal'=>'Terapia hormonal','tamoxifeno'=>'Tamoxifeno','anticonceptivos_hormonales'=>'Anticonceptivos','estatus_no_especificado'=>'No especificado']); ?>
        </td>
    </tr>
</table>
<?php endif; ?>

<table class="grid-table">
    <tr>
        <!-- Columna Izquierda: Antecedentes y Técnica -->
        <td style="width: 48%;">
            <?php if ($antecedentes): ?>
            <div class="section-title">Antecedentes Gineco-Obstétricos</div>
            <table class="data-table">
                <tr>
                    <td class="lbl">Gesta / Partos:</td>
                    <td class="val">G: <?php echo $num($antecedentes['gesta']); ?> / P: <?php echo $num($antecedentes['para']); ?></td>
                </tr>
                <tr>
                    <td class="lbl">Cesáreas / Abortos:</td>
                    <td class="val">C: <?php echo $num($antecedentes['cesareas']); ?> / A: <?php echo $num($antecedentes['abortos']); ?></td>
                </tr>
                <tr>
                    <td class="lbl">Paridad Satisfecha:</td>
                    <td class="val"><?php echo $siNoNA($antecedentes['paridad_satisfecha']); ?></td>
                </tr>
                <tr>
                    <td class="lbl">Cirugía Uterina / Legrado:</td>
                    <td class="val"><?php echo $siNo($antecedentes['legrado_cirugia_uterina']); ?></td>
                </tr>
                <tr>
                    <td class="lbl">Miomectomía / Endometriosis:</td>
                    <td class="val">Miom: <?php echo $siNo($antecedentes['miomectomia']); ?> / Endo: <?php echo $siNo($antecedentes['endometriosis_adenomiosis']); ?></td>
                </tr>
                <?php if (!empty($antecedentes['otros'])): ?>
                <tr>
                    <td class="lbl">Otros Antecedentes:</td>
                    <td class="val"><?php echo nl2br(htmlspecialchars($antecedentes['otros'])); ?></td>
                </tr>
                <?php endif; ?>
            </table>
            <?php endif; ?>

            <?php if ($tecnica): ?>
            <div class="section-title">Técnica de Exploración</div>
            <table class="data-table">
                <tr>
                    <td class="lbl">Vía:</td>
                    <td class="val"><?php echo $checks($tecnica, ['via_endovaginal'=>'Endovaginal','via_transabdominal'=>'Transabdominal','via_doppler_color'=>'Doppler','via_evaluacion_3d'=>'3D','via_sonohisterografia'=>'Sonohisterografía']); ?></td>
                </tr>
                <tr>
                    <td class="lbl">Calidad visual:</td>
                    <td class="val">
                        <?php echo $txt($tecnica['calidad']); ?>
                        <?php if (!empty($tecnica['calidad_otra'])) echo ' (' . htmlspecialchars($tecnica['calidad_otra']) . ')'; ?>
                    </td>
                </tr>
                <?php if ($tecnica['limitada_dolor'] || $tecnica['limitada_distension_intestinal'] || $tecnica['limitada_habitus_corporal'] || $tecnica['limitada_posicion_uterina']): ?>
                <tr>
                    <td class="lbl">Limitada por:</td>
                    <td class="val"><?php echo $checks($tecnica, ['limitada_dolor'=>'Dolor','limitada_distension_intestinal'=>'Distensión','limitada_habitus_corporal'=>'Habitus','limitada_posicion_uterina'=>'Posición']); ?></td>
                </tr>
                <?php endif; ?>
            </table>
            <?php endif; ?>
        </td>

        <!-- Espaciador -->
        <td style="width: 4%;">&nbsp;</td>

        <!-- Columna Derecha: Útero, Cérvix y Endometrio -->
        <td style="width: 48%;">
            <?php if ($uteroCervix): ?>
            <div class="section-title">Hallazgos en Útero y Cérvix</div>
            <table class="data-table">
                <tr>
                    <td class="lbl">Situación:</td>
                    <td class="val"><?php echo $txt($uteroCervix['situacion']); ?></td>
                </tr>
                <tr>
                    <td class="lbl">Morfología:</td>
                    <td class="val">
                        <?php echo $labels($uteroCervix, ['morfologia_regular'=>'Regular','morfologia_bordes_irregulares'=>'Bordes irregulares','morfologia_globoso'=>'Globoso','morfologia_aumentado'=>'Aumentado','morfologia_disminuido'=>'Disminuido']); ?>
                        <?php if (!empty($uteroCervix['morfologia_otro'])) echo ' (' . htmlspecialchars($uteroCervix['morfologia_otro']) . ')'; ?>
                    </td>
                </tr>
                <tr>
                    <td class="lbl">Dimensiones Útero:</td>
                    <td class="val">
                        Long: <?php echo $num($uteroCervix['dim_longitud_mm']); ?> mm | 
                        AP: <?php echo $num($uteroCervix['dim_anteroposterior_mm']); ?> mm | 
                        Trans: <?php echo $num($uteroCervix['dim_transverso_mm']); ?> mm
                    </td>
                </tr>
                <tr>
                    <td class="lbl">Volumen Útero:</td>
                    <td class="val"><?php echo $num($uteroCervix['volumen_cc']); ?> cc</td>
                </tr>
                <tr>
                    <td class="lbl">Miometrio:</td>
                    <td class="val">
                        <?php echo $labels($uteroCervix, ['miometrio_homogeneo'=>'Homogéneo','miometrio_heterogeneo'=>'Heterogéneo','miometrio_imagenes_leiomiomas'=>'Leiomiomas','miometrio_sugestivo_adenomiosis'=>'Adenomiosis','miometrio_calcificaciones'=>'Calcificaciones','miometrio_areas_quisticas'=>'Áreas quísticas','miometrio_sombra_acustica'=>'Sombra acústica']); ?>
                        <?php if (!empty($uteroCervix['miometrio_otro'])) echo ' (' . htmlspecialchars($uteroCervix['miometrio_otro']) . ')'; ?>
                    </td>
                </tr>
                <tr>
                    <td class="lbl">Cérvix:</td>
                    <td class="val">
                        Long: <?php echo $num($uteroCervix['cervix_longitud_mm']); ?> mm | 
                        <?php echo $labels($uteroCervix, ['cervix_sin_alteraciones'=>'Sin alteraciones','cervix_quistes_naboth'=>'Quistes Naboth','cervix_polipo_endocervical'=>'Pólipo','cervix_lesion_visible_usg'=>'Lesión visible','cervix_liquido_canal'=>'Líquido canal']); ?>
                        <?php if (!empty($uteroCervix['cervix_otro'])) echo ' (' . htmlspecialchars($uteroCervix['cervix_otro']) . ')'; ?>
                    </td>
                </tr>
            </table>
            <?php endif; ?>

            <?php if ($endometrio): ?>
            <div class="section-title">Endometrio y Cavidad</div>
            <table class="data-table">
                <tr>
                    <td class="lbl">Grosor y Patrón:</td>
                    <td class="val"><?php echo $num($endometrio['grosor_mm']); ?> mm, <?php echo $txt($endometrio['patron']); ?></td>
                </tr>
                <tr>
                    <td class="lbl">Correlación Ciclo:</td>
                    <td class="val"><?php echo $txt($endometrio['correlacion_ciclo']); ?></td>
                </tr>
                <tr>
                    <td class="lbl">Cavidad Uterina:</td>
                    <td class="val">
                        <?php echo $labels($endometrio, ['cavidad_regular'=>'Regular','cavidad_distorsionada'=>'Distorsionada','cavidad_liquido_intracavitario'=>'Líquido','cavidad_imagen_focal_polipo'=>'Pólipo','cavidad_imagen_mioma_submucoso'=>'Mioma submucoso','cavidad_sinequias'=>'Sinequias','cavidad_diu_intrauterino'=>'DIU']); ?>
                        <?php if (!empty($endometrio['cavidad_otro'])) echo ' (' . htmlspecialchars($endometrio['cavidad_otro']) . ')'; ?>
                    </td>
                </tr>
                <tr>
                    <td class="lbl">Doppler:</td>
                    <td class="val"><?php echo $txt($endometrio['doppler']); ?></td>
                </tr>
                <?php if ($endometrio['diu_posicion']): ?>
                <tr>
                    <td class="lbl">DIU Posición:</td>
                    <td class="val"><?php echo $txt($endometrio['diu_posicion']); ?> (Distancia al fondo: <?php echo $num($endometrio['diu_distancia_fondo_mm']); ?> mm)</td>
                </tr>
                <?php endif; ?>
            </table>
            <?php endif; ?>
        </td>
    </tr>
</table>

<?php if ($miomas && $miomas['identificados']): ?>
<div class="section-title">Evaluación de Miomas</div>
<table class="data-table">
    <tr>
        <td class="lbl">Total aproximado / Dominante:</td>
        <td class="val"><?php echo $num($miomas['numero_aproximado']); ?> miomas | Dominante de <?php echo $num($miomas['mioma_dominante_mm']); ?> mm</td>
    </tr>
    <tr>
        <td class="lbl">Predominio / Distribución:</td>
        <td class="val"><?php echo $labels($miomas, ['predominio_submucosos'=>'Submucosos','predominio_intramurales'=>'Intramurales','predominio_subserosos'=>'Subserosos','predominio_pediculados'=>'Pediculados','predominio_cervicales'=>'Cervicales','predominio_distribucion_difusa'=>'Difusos']); ?></td>
    </tr>
</table>

<?php if (!empty($miomasDetalle)): ?>
<table class="print-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Localización</th>
            <th>Medidas (mm)</th>
            <th>Relación Endometrio</th>
            <th>Clasif. FIGO</th>
            <th>Doppler</th>
            <th>Comentarios</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($miomasDetalle as $md): ?>
        <tr>
            <td style="text-align: center; font-weight: bold;"><?php echo $md['numero']; ?></td>
            <td><?php echo $txt($md['localizacion']); ?></td>
            <td style="text-align: center;"><?php echo $num($md['medida_x_mm']).' × '.$num($md['medida_y_mm']).' × '.$num($md['medida_z_mm']); ?></td>
            <td><?php echo $txt($md['relacion_endometrio']); ?></td>
            <td style="text-align: center;"><span class="badge badge-info"><?php echo $txt($md['clasificacion_figo']); ?></span></td>
            <td style="text-align: center;"><?php echo $txt($md['doppler']); ?></td>
            <td><?php echo $txt($md['comentarios']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<?php endif; ?>

<?php if ($adenomiosis): ?>
<div class="section-title">Adenomiosis</div>
<table class="data-table">
    <tr>
        <td class="lbl">Grado / Hallazgos:</td>
        <td class="val"><?php echo $txt($adenomiosis['hallazgos']); ?></td>
    </tr>
    <tr>
        <td class="lbl">Datos Ecográficos:</td>
        <td class="val">
            <?php echo $labels($adenomiosis, ['utero_globoso'=>'Útero globoso','asimetria_paredes'=>'Asimetría paredes','miometrio_heterogeneo'=>'Miometrio heterogéneo','estriaciones_lineales'=>'Estriaciones lineales','quistes_miometriales'=>'Quistes miometriales','islas_hiperecogenicas'=>'Islas hiperecogénicas','sombra_abanico'=>'Sombra en abanico','zona_union_irregular'=>'Zona unión irregular','vascularidad_translesional'=>'Vascularidad translesional']); ?>
            <?php if (!empty($adenomiosis['datos_otro'])) echo ' (' . htmlspecialchars($adenomiosis['datos_otro']) . ')'; ?>
        </td>
    </tr>
    <tr>
        <td class="lbl">Distribución:</td>
        <td class="val">
            <?php echo $txt($adenomiosis['distribucion']); ?>
            <?php 
            $dist = [];
            if ($adenomiosis['predominio_anterior']) $dist[] = 'Anterior';
            if ($adenomiosis['predominio_posterior']) $dist[] = 'Posterior';
            if ($adenomiosis['predominio_fundico']) $dist[] = 'Fúndico';
            if (!empty($dist)) echo ' (Predominio: ' . implode(', ', $dist) . ')';
            ?>
        </td>
    </tr>
</table>
<?php endif; ?>

<table class="grid-table">
    <tr>
        <!-- Ovarios -->
        <td style="width: 48%;">
            <?php if ($ovarios): ?>
            <div class="section-title">Ovarios</div>
            <div class="sub-header">Ovario Derecho</div>
            <table class="data-table">
                <tr>
                    <td class="lbl">Dimensiones:</td>
                    <td class="val"><?php echo $num($ovarios['der_dim_x_mm']).' × '.$num($ovarios['der_dim_y_mm']).' × '.$num($ovarios['der_dim_z_mm']); ?> mm</td>
                </tr>
                <tr>
                    <td class="lbl">Volumen:</td>
                    <td class="val"><?php echo $num($ovarios['der_volumen_cc']); ?> cc</td>
                </tr>
                <tr>
                    <td class="lbl">Morfología:</td>
                    <td class="val"><?php echo $labels($ovarios, ['der_normal'=>'Normal','der_atrofico'=>'Atrófico','der_multifolicular'=>'Multifolicular','der_poliquistico'=>'Poliquístico','der_cuerpo_luteo'=>'Cuerpo lúteo','der_quiste_simple'=>'Quiste simple','der_quiste_hemorragico'=>'Q. hemorrágico','der_endometrioma'=>'Endometrioma','der_lesion_solida'=>'Lesión sólida','der_lesion_compleja'=>'Lesión compleja','der_no_visible'=>'No visible']); ?></td>
                </tr>
            </table>

            <div class="sub-header">Ovario Izquierdo</div>
            <table class="data-table">
                <tr>
                    <td class="lbl">Dimensiones:</td>
                    <td class="val"><?php echo $num($ovarios['izq_dim_x_mm']).' × '.$num($ovarios['izq_dim_y_mm']).' × '.$num($ovarios['izq_dim_z_mm']); ?> mm</td>
                </tr>
                <tr>
                    <td class="lbl">Volumen:</td>
                    <td class="val"><?php echo $num($ovarios['izq_volumen_cc']); ?> cc</td>
                </tr>
                <tr>
                    <td class="lbl">Morfología:</td>
                    <td class="val"><?php echo $labels($ovarios, ['izq_normal'=>'Normal','izq_atrofico'=>'Atrófico','izq_multifolicular'=>'Multifolicular','izq_poliquistico'=>'Poliquístico','izq_cuerpo_luteo'=>'Cuerpo lúteo','izq_quiste_simple'=>'Quiste simple','izq_quiste_hemorragico'=>'Q. hemorrágico','izq_endometrioma'=>'Endometrioma','izq_lesion_solida'=>'Lesión sólida','izq_lesion_compleja'=>'Lesión compleja','izq_no_visible'=>'No visible']); ?></td>
                </tr>
            </table>
            <?php endif; ?>
        </td>

        <!-- Espaciador -->
        <td style="width: 4%;">&nbsp;</td>

        <!-- Anexos y Clasificación -->
        <td style="width: 48%;">
            <?php if ($anexos): ?>
            <div class="section-title">Anexos y Fondo de Saco</div>
            <table class="data-table">
                <tr>
                    <td class="lbl">Anexo Derecho:</td>
                    <td class="val">
                        <?php echo $labels($anexos, ['der_sin_alteraciones'=>'Sin alteraciones','der_lesion_anexial'=>'Lesión','der_hidrosalpinx'=>'Hidrosálpinx','der_paraovarico'=>'Paraovárico']); ?>
                        <?php if (!empty($anexos['der_otro'])) echo ' (' . htmlspecialchars($anexos['der_otro']) . ')'; ?>
                    </td>
                </tr>
                <tr>
                    <td class="lbl">Anexo Izquierdo:</td>
                    <td class="val">
                        <?php echo $labels($anexos, ['izq_sin_alteraciones'=>'Sin alteraciones','izq_lesion_anexial'=>'Lesión','izq_hidrosalpinx'=>'Hidrosálpinx','izq_paraovarico'=>'Paraovárico']); ?>
                        <?php if (!empty($anexos['izq_otro'])) echo ' (' . htmlspecialchars($anexos['izq_otro']) . ')'; ?>
                    </td>
                </tr>
                <tr>
                    <td class="lbl">Fondo de Saco Douglas:</td>
                    <td class="val"><?php echo $labels($anexos, ['fondo_saco_libre'=>'Libre','fondo_saco_liquido_escaso'=>'Líquido escaso','fondo_saco_liquido_moderado'=>'Líquido moderado','fondo_saco_liquido_abundante'=>'Líquido abundante','fondo_saco_liquido_ecos'=>'Líquido con ecos','fondo_saco_nodulo_implante'=>'Nódulo','fondo_saco_dolor_presion'=>'Dolor presión']); ?></td>
                </tr>
                <tr>
                    <td class="lbl">Signo de Deslizamiento:</td>
                    <td class="val"><?php echo $txt($anexos['sliding_sign']); ?></td>
                </tr>
            </table>
            <?php endif; ?>

            <?php if ($clasificacion): ?>
            <div class="section-title">Clasificación Orientativa</div>
            <table class="data-table">
                <tr>
                    <td class="lbl">PALM-COEIN:</td>
                    <td class="val"><?php echo $labels($clasificacion, ['palm_polipo'=>'Pólipo','palm_adenomiosis'=>'Adenomiosis','palm_leiomioma'=>'Leiomioma','palm_malignidad'=>'Malignidad','palm_coagulopatia'=>'Coagulopatía','palm_ovulatoria'=>'Ovulatoria','palm_endometrial'=>'Endometrial','palm_iatrogenica'=>'Iatrogénica','palm_no_clasificada'=>'No clasificada']); ?></td>
                </tr>
                <tr>
                    <td class="lbl">Clasificación Anexial:</td>
                    <td class="val"><?php echo $labels($clasificacion, ['anexial_funcional'=>'Funcional','anexial_benigna'=>'Benigna','anexial_indeterminada'=>'Indeterminada','anexial_sospechosa'=>'Sospechosa','anexial_sugiere_o_rads'=>'O-RADS/IOTA']); ?></td>
                </tr>
            </table>
            <?php endif; ?>
        </td>
    </tr>
</table>

<?php if ($impresion): ?>
<div class="section-title">Impresión Diagnóstica</div>
<table class="data-table">
    <tr>
        <td class="lbl">Útero:</td>
        <td class="val">Útero <?php echo $txt($impresion['utero_tamano']); ?><?php if (!empty($impresion['utero_morfologia'])) echo ', ' . htmlspecialchars($impresion['utero_morfologia']); ?></td>
    </tr>
    <tr>
        <td class="lbl">Miometrio:</td>
        <td class="val">
            <?php echo $labels($impresion, ['miometrio_sin_alteraciones'=>'Sin alteraciones','miometrio_miomatosis'=>'Miomatosis','miometrio_adenomiosis'=>'Adenomiosis']); ?>
            <?php if (!empty($impresion['miometrio_otro'])) echo ' ' . htmlspecialchars($impresion['miometrio_otro']); ?>
        </td>
    </tr>
    <tr>
        <td class="lbl">Endometrio:</td>
        <td class="val">
            <?php echo $num($impresion['endometrio_grosor_mm']); ?> mm, <?php echo $txt($impresion['endometrio_patron']); ?>. 
            <?php if ($impresion['endometrio_acorde_contexto']) echo '<span class="badge badge-success">Acorde al contexto</span> '; ?>
            <?php if ($impresion['endometrio_engrosado_contexto']) echo '<span class="badge badge-danger">Engrosado</span> '; ?>
            <?php if ($impresion['endometrio_requiere_correlacion']) echo '<span class="badge badge-warning">Requiere correlación histológica</span>'; ?>
        </td>
    </tr>
    <tr>
        <td class="lbl">Ovarios (Der / Izq):</td>
        <td class="val"><strong>Der:</strong> <?php echo $txt($impresion['ovario_derecho']); ?> | <strong>Izq:</strong> <?php echo $txt($impresion['ovario_izquierdo']); ?></td>
    </tr>
    <tr>
        <td class="lbl">Anexos / Fondo de saco:</td>
        <td class="val"><?php echo $txt($impresion['anexos_fondo_saco']); ?></td>
    </tr>
</table>
<?php endif; ?>

<?php if ($conclusion): ?>
<div class="section-title">Conclusión y Recomendaciones</div>
<table class="data-table">
    <tr>
        <td class="lbl">Diagnósticos principales:</td>
        <td class="val">
            <?php echo $labels($conclusion, ['estudio_limites_esperados'=>'Dentro de límites esperados','miomatosis_uterina'=>'Miomatosis uterina','engrosamiento_endometrial'=>'Engrosamiento endometrial','imagen_focal_polipo'=>'Pólipo endometrial','datos_sugestivos_adenomiosis'=>'Adenomiosis','quiste_simple_der'=>'Quiste simple derecho','quiste_simple_izq'=>'Quiste simple izquierdo','quiste_hemorragico_der'=>'Q. hemorrágico derecho','quiste_hemorragico_izq'=>'Q. hemorrágico izquierdo','endometrioma_der'=>'Endometrioma derecho','endometrioma_izq'=>'Endometrioma izquierdo','masa_anexial_indeterminada'=>'Masa anexial indeterminada']); ?>
            <?php if (!empty($conclusion['conclusion_otro'])) echo '. ' . htmlspecialchars($conclusion['conclusion_otro']); ?>
        </td>
    </tr>
    <tr>
        <td class="lbl">Recomendaciones sugeridas:</td>
        <td class="val">
            <?php echo $labels($conclusion, ['rec_correlacion_edad_fum'=>'Correlacionar edad/FUM/sangrado','rec_correlacion_hb_hormonal'=>'Correlacionar Hb/perfil hormonal','rec_estudio_histologico'=>'Estudio histológico endometrial','rec_histeroscopia_endometrio'=>'Histeroscopia','rec_sonohisterografia_histeroscopia'=>'Sonohisterografía/histeroscopia','rec_valorar_manejo_miomatosis'=>'Valorar manejo miomatosis','rec_iorads_marcadores_oncologia'=>'O-RADS/IOTA, valoración por oncología ginecológica','rec_control_ultrasonografico'=>'Control ultrasonográfico' . ($conclusion['rec_control_tiempo'] ? ' en ' . $conclusion['rec_control_tiempo'] . ' ' . ($conclusion['rec_control_unidad'] ?? '') : '')]); ?>
            <?php if (!empty($conclusion['rec_otro'])) echo '. ' . htmlspecialchars($conclusion['rec_otro']); ?>
        </td>
    </tr>
</table>
<?php endif; ?>

<table style="width: 100%; margin-top: 40px; border-collapse: collapse;">
    <tr>
        <td style="width: 50%; text-align: center; vertical-align: top;">
            <div style="border-top: 1px solid #1B4F5A; width: 220px; margin: 0 auto; padding-top: 5px; font-size: 10px;">
                <strong>Dr(a). <?php echo $medicoNombre; ?></strong><br>
                Firma del Médico
            </div>
        </td>
        <td style="width: 50%; text-align: center; font-size: 10px; color: #777; padding-top: 15px; vertical-align: top;">
            Fecha de impresión: <?php echo date('d/m/Y H:i'); ?>
        </td>
    </tr>
</table>

<div class="no-print" style="text-align:center;margin:20px 0;">
    <a href="<?php echo Url::to('/evaluaciones_ginecologicas/pdf?id=' . $evaluacion['id']); ?>"
       style="padding:10px 30px;font-size:14px;cursor:pointer;border:none;background:#1B4F5A;color:#fff;border-radius:8px;text-decoration:none;display:inline-block;font-weight:bold;">
        <i class="fa-solid fa-download"></i> Descargar PDF
    </a>
</div>
</body>
</html>
