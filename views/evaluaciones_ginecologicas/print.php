<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USG Ginecológico — <?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-size: 11px; color: #000; padding-top: 150px; padding-bottom: 40px;
        }
        .container { max-width: 800px; }
        .report-title { font-size: 16px; font-weight: 700; text-align: center; margin-bottom: 4px; }
        .report-subtitle { font-size: 12px; text-align: center; color: #555; margin-bottom: 16px; }
        .section-title { font-size: 12px; font-weight: 700; border-bottom: 1px solid #999; margin: 14px 0 6px; padding-bottom: 2px; text-transform: uppercase; }
        .data-row { display: flex; margin-bottom: 2px; }
        .data-label { width: 200px; font-weight: 600; }
        .data-value { flex: 1; }
        table.print-table { font-size: 10px; width: 100%; border-collapse: collapse; margin: 8px 0; }
        table.print-table th, table.print-table td { border: 1px solid #ccc; padding: 4px 6px; text-align: left; }
        table.print-table th { background: #f5f5f5; font-weight: 600; }
        .checkbox-label { display: inline-block; margin-right: 12px; }
        .checkbox-label.check::before { content: "☑ "; }
        .checkbox-label.uncheck::before { content: "☐ "; }
        <?php
        $siNo = fn($v) => $v ? 'Sí' : 'No';
        $siNoNA = fn($v) => $v === null ? '—' : ($v ? 'Sí' : 'No');
        $txt = fn($v) => htmlspecialchars($v ?: '—');
        $num = fn($v) => ($v !== null && $v !== '') ? htmlspecialchars($v) : '—';
        $labels = function($data, $map) {
            $r = [];
            foreach ($map as $f => $l) {
                if (!empty($data[$f])) $r[] = $l;
            }
            return !empty($r) ? implode(', ', $r) : '—';
        };
        $checks = function($data, $map) {
            $o = '';
            foreach ($map as $f => $l) {
                $cls = !empty($data[$f]) ? 'check' : 'uncheck';
                $o .= '<span class="checkbox-label ' . $cls . '">' . $l . '</span> ';
            }
            return $o ?: '—';
        };
        ?>
    </style>
</head>
<body>
<div class="container py-4">

    <div class="report-title">REPORTE DE ULTRASONIDO GINECOLÓGICO ENDOVAGINAL</div>
    <div class="report-subtitle">Código: <?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></div>

    <div class="data-row"><span class="data-label">Paciente:</span><span class="data-value"><?php echo $txt($evaluacion['paciente_nombre'] . ' ' . $evaluacion['paciente_apellido']); ?></span></div>
    <div class="data-row"><span class="data-label">Edad:</span><span class="data-value"><?php echo $evaluacion['fecha_nacimiento'] ? (date('Y') - date('Y', strtotime($evaluacion['fecha_nacimiento']))) . ' años' : '—'; ?></span></div>
    <div class="data-row"><span class="data-label">Fecha del estudio:</span><span class="data-value"><?php echo date('d/m/Y', strtotime($evaluacion['fecha_estudio'])); ?></span></div>
    <div class="data-row"><span class="data-label">Médico solicitante:</span><span class="data-value"><?php echo $txt($evaluacion['medico_solicitante_nombre'] ? $evaluacion['medico_solicitante_nombre'] . ' ' . $evaluacion['medico_solicitante_apellido'] : null); ?></span></div>
    <div class="data-row"><span class="data-label">Médico que realiza:</span><span class="data-value"><?php echo htmlspecialchars($evaluacion['medico_nombre'] . ' ' . $evaluacion['medico_apellido']); ?></span></div>
    <div class="data-row"><span class="data-label">Indicación clínica:</span><span class="data-value"><?php echo $txt($evaluacion['indicacion_clinica']); ?></span></div>
    <div class="data-row"><span class="data-label">FUM:</span><span class="data-value"><?php echo $evaluacion['fum'] ? date('d/m/Y', strtotime($evaluacion['fum'])) : '—'; ?></span></div>
    <div class="data-row"><span class="data-label">Día del ciclo:</span><span class="data-value"><?php echo $num($evaluacion['dia_ciclo_menstrual']); ?></span></div>

    <?php if ($indicaciones): ?>
    <div class="section-title">Motivo del Estudio y Estatus Hormonal</div>
    <strong>Motivo:</strong> <?php echo $checks($indicaciones, ['sangrado_uterino_anormal'=>'Sangrado uterino anormal','dolor_pelvico'=>'Dolor pélvico','miomatosis_uterina'=>'Miomatosis','sospecha_polipo_endometrial'=>'Sospecha pólipo','engrosamiento_endometrial'=>'Engrosamiento endometrial','control_diu'=>'Control DIU','infertilidad_reproduccion'=>'Infertilidad','quiste_ovarico_masa_anexial'=>'Quiste/masa anexial','sindrome_climaterico'=>'S. climatérico','sangrado_posmenopausico'=>'Sangrado posmenopáusico']); ?>
    <?php if (!empty($indicaciones['motivo_estudio_otro'])) echo ' (' . htmlspecialchars($indicaciones['motivo_estudio_otro']) . ')'; ?>
    <br>
    <strong>Estatus hormonal:</strong> <?php echo $checks($indicaciones, ['premenopausica'=>'Premenopáusica','perimenopausica'=>'Perimenopáusica','posmenopausica'=>'Posmenopáusica','terapia_hormonal'=>'Terapia hormonal','tamoxifeno'=>'Tamoxifeno','anticonceptivos_hormonales'=>'Anticonceptivos','estatus_no_especificado'=>'No especificado']); ?>
    <?php endif; ?>

    <?php if ($antecedentes): ?>
    <div class="section-title">Antecedentes</div>
    <div class="data-row"><span class="data-label">Gesta:</span><?php echo $num($antecedentes['gesta']); ?> | <span class="data-label">Para:</span><?php echo $num($antecedentes['para']); ?> | <span class="data-label">Cesáreas:</span><?php echo $num($antecedentes['cesareas']); ?> | <span class="data-label">Abortos:</span><?php echo $num($antecedentes['abortos']); ?></div>
    <div>Paridad satisfecha: <?php echo $siNoNA($antecedentes['paridad_satisfecha']); ?> | Legrado: <?php echo $siNo($antecedentes['legrado_cirugia_uterina']); ?> | Miomectomía: <?php echo $siNo($antecedentes['miomectomia']); ?> | Endometriosis: <?php echo $siNo($antecedentes['endometriosis_adenomiosis']); ?></div>
    <?php if (!empty($antecedentes['otros'])): ?><div>Otros: <?php echo nl2br(htmlspecialchars($antecedentes['otros'])); ?></div><?php endif; ?>
    <?php endif; ?>

    <?php if ($tecnica): ?>
    <div class="section-title">Técnica</div>
    <strong>Vía:</strong> <?php echo $checks($tecnica, ['via_endovaginal'=>'Endovaginal','via_transabdominal'=>'Transabdominal','via_doppler_color'=>'Doppler','via_evaluacion_3d'=>'3D','via_sonohisterografia'=>'Sonohisterografía']); ?>
    <br><strong>Calidad:</strong> <?php echo $txt($tecnica['calidad']); ?>
    <?php if ($tecnica['limitada_dolor'] || $tecnica['limitada_distension_intestinal'] || $tecnica['limitada_habitus_corporal'] || $tecnica['limitada_posicion_uterina']): ?>
    <br><strong>Limitada por:</strong> <?php echo $checks($tecnica, ['limitada_dolor'=>'Dolor','limitada_distension_intestinal'=>'Distensión','limitada_habitus_corporal'=>'Habitus','limitada_posicion_uterina'=>'Posición']); ?>
    <?php endif; ?>
    <?php if (!empty($tecnica['calidad_otra'])) echo ' (' . htmlspecialchars($tecnica['calidad_otra']) . ')'; ?>
    <?php endif; ?>

    <div class="section-title">Hallazgos</div>

    <?php if ($uteroCervix): ?>
    <p><strong>ÚTERO</strong><br>
    Situación: <?php echo $txt($uteroCervix['situacion']); ?> |
    Morfología: <?php echo $labels($uteroCervix, ['morfologia_regular'=>'Regular','morfologia_bordes_irregulares'=>'Bordes irregulares','morfologia_globoso'=>'Globoso','morfologia_aumentado'=>'Aumentado','morfologia_disminuido'=>'Disminuido']); ?> <?php if (!empty($uteroCervix['morfologia_otro'])) echo '(' . htmlspecialchars($uteroCervix['morfologia_otro']) . ')'; ?><br>
    Longitud: <?php echo $num($uteroCervix['dim_longitud_mm']); ?> mm | AP: <?php echo $num($uteroCervix['dim_anteroposterior_mm']); ?> mm | Transverso: <?php echo $num($uteroCervix['dim_transverso_mm']); ?> mm | Volumen: <?php echo $num($uteroCervix['volumen_cc']); ?> cc<br>
    Miometrio: <?php echo $labels($uteroCervix, ['miometrio_homogeneo'=>'Homogéneo','miometrio_heterogeneo'=>'Heterogéneo','miometrio_imagenes_leiomiomas'=>'Leiomiomas','miometrio_sugestivo_adenomiosis'=>'Adenomiosis','miometrio_calcificaciones'=>'Calcificaciones','miometrio_areas_quisticas'=>'Áreas quísticas','miometrio_sombra_acustica'=>'Sombra acústica']); ?> <?php if (!empty($uteroCervix['miometrio_otro'])) echo '(' . htmlspecialchars($uteroCervix['miometrio_otro']) . ')'; ?><br>
    <strong>Cérvix:</strong> Longitud: <?php echo $num($uteroCervix['cervix_longitud_mm']); ?> mm |
    <?php echo $labels($uteroCervix, ['cervix_sin_alteraciones'=>'Sin alteraciones','cervix_quistes_naboth'=>'Quistes Naboth','cervix_polipo_endocervical'=>'Pólipo','cervix_lesion_visible_usg'=>'Lesión visible','cervix_liquido_canal'=>'Líquido canal']); ?> <?php if (!empty($uteroCervix['cervix_otro'])) echo '(' . htmlspecialchars($uteroCervix['cervix_otro']) . ')'; ?></p>
    <?php endif; ?>

    <?php if ($miomas && $miomas['identificados']): ?>
    <p><strong>MIOMAS:</strong> Identificados (<?php echo $num($miomas['numero_aproximado']); ?> aprox., dominante <?php echo $num($miomas['mioma_dominante_mm']); ?> mm). Predominio: <?php echo $labels($miomas, ['predominio_submucosos'=>'Submucosos','predominio_intramurales'=>'Intramurales','predominio_subserosos'=>'Subserosos','predominio_pediculados'=>'Pediculados','predominio_cervicales'=>'Cervicales','predominio_distribucion_difusa'=>'Difusos']); ?></p>
    <?php if (!empty($miomasDetalle)): ?>
    <table class="print-table"><tr><th>#</th><th>Localización</th><th>Medidas (mm)</th><th>Relación</th><th>FIGO</th><th>Doppler</th><th>Comentarios</th></tr>
    <?php foreach ($miomasDetalle as $md): ?>
    <tr><td><?php echo $md['numero']; ?></td><td><?php echo $txt($md['localizacion']); ?></td><td><?php echo $num($md['medida_x_mm']).'×'.$num($md['medida_y_mm']).'×'.$num($md['medida_z_mm']); ?></td><td><?php echo $txt($md['relacion_endometrio']); ?></td><td><?php echo $txt($md['clasificacion_figo']); ?></td><td><?php echo $txt($md['doppler']); ?></td><td><?php echo $txt($md['comentarios']); ?></td></tr>
    <?php endforeach; ?></table>
    <?php endif; ?>
    <?php endif; ?>

    <?php if ($adenomiosis): ?>
    <p><strong>ADENOMIOSIS:</strong> <?php echo $txt($adenomiosis['hallazgos']); ?><br>
    Datos: <?php echo $labels($adenomiosis, ['utero_globoso'=>'Útero globoso','asimetria_paredes'=>'Asimetría','miometrio_heterogeneo'=>'Miometrio heterogéneo','estriaciones_lineales'=>'Estriaciones','quistes_miometriales'=>'Quistes','islas_hiperecogenicas'=>'Islas hiperecogénicas','sombra_abanico'=>'Sombra abanico','zona_union_irregular'=>'Zona unión irregular','vascularidad_translesional'=>'Vascularidad translesional']); ?> <?php if (!empty($adenomiosis['datos_otro'])) echo '(' . htmlspecialchars($adenomiosis['datos_otro']) . ')'; ?><br>
    Distribución: <?php echo $txt($adenomiosis['distribucion']); ?> <?php if ($adenomiosis['predominio_anterior']) echo '·Anterior '; ?><?php if ($adenomiosis['predominio_posterior']) echo '·Posterior '; ?><?php if ($adenomiosis['predominio_fundico']) echo '·Fúndico'; ?></p>
    <?php endif; ?>

    <?php if ($endometrio): ?>
    <p><strong>ENDOMETRIO:</strong> <?php echo $num($endometrio['grosor_mm']); ?> mm, <?php echo $txt($endometrio['patron']); ?>. <?php echo $txt($endometrio['correlacion_ciclo']); ?><br>
    Cavidad: <?php echo $labels($endometrio, ['cavidad_regular'=>'Regular','cavidad_distorsionada'=>'Distorsionada','cavidad_liquido_intracavitario'=>'Líquido','cavidad_imagen_focal_polipo'=>'Pólipo','cavidad_imagen_mioma_submucoso'=>'Mioma submucoso','cavidad_sinequias'=>'Sinequias','cavidad_diu_intrauterino'=>'DIU']); ?> <?php if (!empty($endometrio['cavidad_otro'])) echo '(' . htmlspecialchars($endometrio['cavidad_otro']) . ')'; ?><br>
    Doppler: <?php echo $txt($endometrio['doppler']); ?>
    <?php if ($endometrio['diu_posicion']): ?> | DIU: <?php echo $txt($endometrio['diu_posicion']); ?> (<?php echo $num($endometrio['diu_distancia_fondo_mm']); ?> mm al fondo)<?php endif; ?></p>
    <?php endif; ?>

    <?php if ($ovarios): ?>
    <p><strong>OVARIO DERECHO:</strong> <?php echo $num($ovarios['der_dim_x_mm']).'×'.$num($ovarios['der_dim_y_mm']).'×'.$num($ovarios['der_dim_z_mm']); ?> mm, <?php echo $num($ovarios['der_volumen_cc']); ?> cc. <?php echo $labels($ovarios, ['der_normal'=>'Normal','der_atrofico'=>'Atrófico','der_multifolicular'=>'Multifolicular','der_poliquistico'=>'Poliquístico','der_cuerpo_luteo'=>'Cuerpo lúteo','der_quiste_simple'=>'Quiste simple','der_quiste_hemorragico'=>'Q. hemorrágico','der_endometrioma'=>'Endometrioma','der_lesion_solida'=>'Lesión sólida','der_lesion_compleja'=>'Lesión compleja','der_no_visible'=>'No visible']); ?><br>
    <strong>OVARIO IZQUIERDO:</strong> <?php echo $num($ovarios['izq_dim_x_mm']).'×'.$num($ovarios['izq_dim_y_mm']).'×'.$num($ovarios['izq_dim_z_mm']); ?> mm, <?php echo $num($ovarios['izq_volumen_cc']); ?> cc. <?php echo $labels($ovarios, ['izq_normal'=>'Normal','izq_atrofico'=>'Atrófico','izq_multifolicular'=>'Multifolicular','izq_poliquistico'=>'Poliquístico','izq_cuerpo_luteo'=>'Cuerpo lúteo','izq_quiste_simple'=>'Quiste simple','izq_quiste_hemorragico'=>'Q. hemorrágico','izq_endometrioma'=>'Endometrioma','izq_lesion_solida'=>'Lesión sólida','izq_lesion_compleja'=>'Lesión compleja','izq_no_visible'=>'No visible']); ?></p>
    <?php endif; ?>

    <?php if ($anexos): ?>
    <p><strong>ANEXOS:</strong><br>Derecho: <?php echo $labels($anexos, ['der_sin_alteraciones'=>'Sin alteraciones','der_lesion_anexial'=>'Lesión','der_hidrosalpinx'=>'Hidrosálpinx','der_paraovarico'=>'Paraovárico']); ?> <?php if (!empty($anexos['der_otro'])) echo '(' . htmlspecialchars($anexos['der_otro']) . ')'; ?><br>
    Izquierdo: <?php echo $labels($anexos, ['izq_sin_alteraciones'=>'Sin alteraciones','izq_lesion_anexial'=>'Lesión','izq_hidrosalpinx'=>'Hidrosálpinx','izq_paraovarico'=>'Paraovárico']); ?> <?php if (!empty($anexos['izq_otro'])) echo '(' . htmlspecialchars($anexos['izq_otro']) . ')'; ?><br>
    <strong>Fondo de saco:</strong> <?php echo $labels($anexos, ['fondo_saco_libre'=>'Libre','fondo_saco_liquido_escaso'=>'Líquido escaso','fondo_saco_liquido_moderado'=>'Líquido moderado','fondo_saco_liquido_abundante'=>'Líquido abundante','fondo_saco_liquido_ecos'=>'Líquido con ecos','fondo_saco_nodulo_implante'=>'Nódulo','fondo_saco_dolor_presion'=>'Dolor presión']); ?><br>
    Sliding sign: <?php echo $txt($anexos['sliding_sign']); ?></p>
    <?php endif; ?>

    <?php if ($clasificacion): ?>
    <div class="section-title">Clasificación Orientativa</div>
    <p><strong>PALM-COEIN:</strong> <?php echo $labels($clasificacion, ['palm_polipo'=>'P: Pólipo','palm_adenomiosis'=>'A: Adenomiosis','palm_leiomioma'=>'L: Leiomioma','palm_malignidad'=>'M: Malignidad','palm_coagulopatia'=>'C: Coagulopatía','palm_ovulatoria'=>'O: Ovulatoria','palm_endometrial'=>'E: Endometrial','palm_iatrogenica'=>'I: Iatrogénica','palm_no_clasificada'=>'N: No clasificada']); ?><br>
    <strong>Anexial:</strong> <?php echo $labels($clasificacion, ['anexial_funcional'=>'Funcional','anexial_benigna'=>'Benigna','anexial_indeterminada'=>'Indeterminada','anexial_sospechosa'=>'Sospechosa','anexial_sugiere_o_rads'=>'O-RADS/IOTA']); ?></p>
    <?php endif; ?>

    <?php if ($impresion): ?>
    <div class="section-title">Impresión Diagnóstica</div>
    <p>1. Útero <?php echo $txt($impresion['utero_tamano']); ?><?php if (!empty($impresion['utero_morfologia'])) echo ', ' . htmlspecialchars($impresion['utero_morfologia']); ?>.<br>
    2. Miometrio: <?php echo $labels($impresion, ['miometrio_sin_alteraciones'=>'Sin alteraciones','miometrio_miomatosis'=>'Miomatosis','miometrio_adenomiosis'=>'Adenomiosis']); ?> <?php if (!empty($impresion['miometrio_otro'])) echo htmlspecialchars($impresion['miometrio_otro']); ?><br>
    3. Endometrio <?php echo $num($impresion['endometrio_grosor_mm']); ?> mm, <?php echo $txt($impresion['endometrio_patron']); ?>. <?php if ($impresion['endometrio_acorde_contexto']) echo 'Acorde al contexto. '; ?><?php if ($impresion['endometrio_engrosado_contexto']) echo 'Engrosado. '; ?><?php if ($impresion['endometrio_requiere_correlacion']) echo 'Requiere correlación.'; ?><br>
    4. Ovario derecho: <?php echo $txt($impresion['ovario_derecho']); ?><br>
    5. Ovario izquierdo: <?php echo $txt($impresion['ovario_izquierdo']); ?><br>
    6. Anexos/fondo de saco: <?php echo $txt($impresion['anexos_fondo_saco']); ?></p>
    <?php endif; ?>

    <?php if ($conclusion): ?>
    <div class="section-title">Conclusión</div>
    <p><?php echo $labels($conclusion, ['estudio_limites_esperados'=>'Dentro de límites esperados','miomatosis_uterina'=>'Miomatosis uterina','engrosamiento_endometrial'=>'Engrosamiento endometrial','imagen_focal_polipo'=>'Pólipo endometrial','datos_sugestivos_adenomiosis'=>'Adenomiosis','quiste_simple_der'=>'Quiste simple derecho','quiste_simple_izq'=>'Quiste simple izquierdo','quiste_hemorragico_der'=>'Q. hemorrágico derecho','quiste_hemorragico_izq'=>'Q. hemorrágico izquierdo','endometrioma_der'=>'Endometrioma derecho','endometrioma_izq'=>'Endometrioma izquierdo','masa_anexial_indeterminada'=>'Masa anexial indeterminada']); ?><?php if (!empty($conclusion['conclusion_otro'])) echo '. ' . htmlspecialchars($conclusion['conclusion_otro']); ?></p>

    <div class="section-title">Recomendaciones</div>
    <p><?php echo $labels($conclusion, ['rec_correlacion_edad_fum'=>'Correlacionar edad/FUM/sangrado','rec_correlacion_hb_hormonal'=>'Correlacionar Hb/perfil hormonal','rec_estudio_histologico'=>'Estudio histológico endometrial','rec_histeroscopia_endometrio'=>'Histeroscopia','rec_sonohisterografia_histeroscopia'=>'Sonohisterografía/histeroscopia','rec_valorar_manejo_miomatosis'=>'Valorar manejo miomatosis','rec_iorads_marcadores_oncologia'=>'O-RADS/IOTA, oncología','rec_control_ultrasonografico'=>'Control USG' . ($conclusion['rec_control_tiempo'] ? ' en ' . $conclusion['rec_control_tiempo'] . ' ' . ($conclusion['rec_control_unidad'] ?? '') : '')]); ?><?php if (!empty($conclusion['rec_otro'])) echo '. ' . htmlspecialchars($conclusion['rec_otro']); ?></p>
    <?php endif; ?>

    <div style="margin-top: 40px; border-top: 1px solid #ccc; padding-top: 10px;">
        <div class="data-row"><span class="data-label">Fecha de impresión:</span><span class="data-value"><?php echo date('d/m/Y H:i'); ?></span></div>
        <div class="data-row"><span class="data-label">Médico:</span><span class="data-value"><?php echo htmlspecialchars($evaluacion['medico_nombre'] . ' ' . $evaluacion['medico_apellido']); ?></span></div>
    </div>

</div>
</body>
</html>
