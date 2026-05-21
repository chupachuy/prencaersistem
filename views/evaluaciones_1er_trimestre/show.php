<?php
$title = "Ver Evaluación 1er Trimestre";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

function badgeSiNo($val, $invert = false) {
    $ok = $invert ? !$val : $val;
    return $ok ? '<span class="badge bg-success">Normal</span>' : '<span class="badge bg-danger">Alterado</span>';
}
function showVal($val, $suffix = '') {
    if ($val === null || $val === '') return '<span class="text-muted">—</span>';
    return htmlspecialchars($val) . $suffix;
}
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/evaluaciones_1er_trimestre'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0"><?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></h1>
    </div>
    <div class="page-header-actions">
        <?php $ec = match($evaluacion['estado']) { 'Completado'=>'success','En proceso'=>'warning','Archivado'=>'secondary', default => 'info' }; ?>
        <span class="badge bg-<?php echo $ec; ?> me-2"><?php echo htmlspecialchars($evaluacion['estado']); ?></span>
        <a href="<?php echo Url::to('/evaluaciones_1er_trimestre/edit?id=' . $evaluacion['id']); ?>" class="btn btn-apple btn-apple-secondary"><i class="fa-solid fa-edit"></i> Editar</a>
        <a href="<?php echo Url::to('/evaluaciones_1er_trimestre/print?id=' . $evaluacion['id']); ?>" class="btn btn-apple btn-apple-primary" target="_blank"><i class="fa-solid fa-print"></i> Imprimir</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <!-- Datos Generales -->
        <div class="card mb-4">
            <div class="card-header"><i class="fa-solid fa-id-card me-2"></i> Datos Generales</div>
            <div class="card-body">
                <div class="row mb-2"><div class="col-md-4 fw-bold">Código:</div><div class="col-md-8"><?php echo htmlspecialchars($evaluacion['codigo_reporte']); ?></div></div>
                <div class="row mb-2"><div class="col-md-4 fw-bold">Fecha Evaluación:</div><div class="col-md-8"><?php echo date('d/m/Y', strtotime($evaluacion['fecha_evaluacion'])); ?></div></div>
                <div class="row mb-2"><div class="col-md-4 fw-bold">Fecha Estudio:</div><div class="col-md-8"><?php echo showVal($evaluacion['fecha_estudio'] ? date('d/m/Y', strtotime($evaluacion['fecha_estudio'])) : null); ?></div></div>
                <div class="row mb-2"><div class="col-md-4 fw-bold">Paciente:</div><div class="col-md-8"><?php echo htmlspecialchars($evaluacion['paciente_nombre'] . ' ' . $evaluacion['paciente_apellido']); ?></div></div>
                <div class="row mb-2"><div class="col-md-4 fw-bold">Médico:</div><div class="col-md-8"><?php echo htmlspecialchars($evaluacion['medico_nombre'] . ' ' . $evaluacion['medico_apellido']); ?></div></div>
            </div>
        </div>

        <!-- Signos Vitales -->
        <div class="card mb-4">
            <div class="card-header"><i class="fa-solid fa-heart-pulse me-2"></i> Signos Vitales</div>
            <div class="card-body">
                <div class="row mb-2"><div class="col-md-4 fw-bold">Peso:</div><div class="col-md-8"><?php echo showVal($evaluacion['peso_kg'], ' kg'); ?></div></div>
                <div class="row mb-2"><div class="col-md-4 fw-bold">Talla:</div><div class="col-md-8"><?php echo showVal($evaluacion['talla_cm'], ' cm'); ?></div></div>
                <div class="row mb-2"><div class="col-md-4 fw-bold">TA Sistólica:</div><div class="col-md-8"><?php echo showVal($evaluacion['ta_sistolica'], ' mmHg'); ?></div></div>
                <div class="row mb-2"><div class="col-md-4 fw-bold">TA Diastólica:</div><div class="col-md-8"><?php echo showVal($evaluacion['ta_diastolica'], ' mmHg'); ?></div></div>
            </div>
        </div>

        <!-- Historial Clínico -->
        <div class="card mb-4">
            <div class="card-header"><i class="fa-solid fa-notes-medical me-2"></i> Historial Clínico</div>
            <div class="card-body">
                <div class="row mb-2"><div class="col-md-6">Hipertensión Crónica</div><div class="col-md-6"><?php echo $historial['hipertension_cronica'] ? '<span class="badge bg-danger">Sí</span>' : '<span class="badge bg-success">No</span>'; ?></div></div>
                <div class="row mb-2"><div class="col-md-6">Diabetes</div><div class="col-md-6"><?php echo ($historial['diabetes'] ?? false) ? '<span class="badge bg-danger">Sí</span>' : '<span class="badge bg-success">No</span>'; ?></div></div>
                <div class="row mb-2"><div class="col-md-6">Lupus / LES</div><div class="col-md-6"><?php echo ($historial['lupus_les'] ?? false) ? '<span class="badge bg-danger">Sí</span>' : '<span class="badge bg-success">No</span>'; ?></div></div>
                <div class="row mb-2"><div class="col-md-6">SAF</div><div class="col-md-6"><?php echo ($historial['sindrome_antifosfolipido_saf'] ?? false) ? '<span class="badge bg-danger">Sí</span>' : '<span class="badge bg-success">No</span>'; ?></div></div>
                <div class="row mb-2"><div class="col-md-6">Ant. Preeclampsia/RCIU</div><div class="col-md-6"><?php echo ($historial['antecedente_preeclampsia_rciu'] ?? false) ? '<span class="badge bg-danger">Sí</span>' : '<span class="badge bg-success">No</span>'; ?></div></div>
                <div class="row mb-2"><div class="col-md-6">Fertilización In Vitro</div><div class="col-md-6"><?php echo ($historial['fertilizacion_in_vitro'] ?? false) ? '<span class="badge bg-warning">Sí</span>' : '<span class="badge bg-success">No</span>'; ?></div></div>
                <div class="row mb-2"><div class="col-md-6">Ant. Parto Pretérmino</div><div class="col-md-6"><?php echo ($historial['antecedente_parto_pretermino'] ?? false) ? '<span class="badge bg-danger">Sí</span>' : '<span class="badge bg-success">No</span>'; ?></div></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <!-- Datos Obstétricos -->
        <div class="card mb-4">
            <div class="card-header"><i class="fa-solid fa-calendar-check me-2"></i> Datos Obstétricos</div>
            <div class="card-body">
                <div class="row mb-2"><div class="col-md-5 fw-bold">FUM:</div><div class="col-md-7"><?php echo showVal($evaluacion['fum'] ? date('d/m/Y', strtotime($evaluacion['fum'])) : null); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">FPP (USG):</div><div class="col-md-7"><?php echo showVal($evaluacion['fpp_usg'] ? date('d/m/Y', strtotime($evaluacion['fpp_usg'])) : null); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Edad Gestacional:</div><div class="col-md-7"><?php echo showVal($evaluacion['edad_gestacional_semanas'], ' sem'); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">LCC:</div><div class="col-md-7"><?php echo showVal($evaluacion['lcc_mm'], ' mm'); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">FCF:</div><div class="col-md-7"><?php echo showVal($evaluacion['fcf_lpm'], ' lpm'); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Estado Feto:</div><div class="col-md-7"><?php echo htmlspecialchars($evaluacion['estado_feto'] ?? 'Vivo'); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Embarazo Múltiple:</div><div class="col-md-7"><?php echo $evaluacion['embarazo_multiple'] ? '<span class="badge bg-warning">Sí</span>' : '<span class="badge bg-success">No</span>'; ?></div></div>
            </div>
        </div>

        <!-- Anatomía Fetal -->
        <div class="card mb-4">
            <div class="card-header"><i class="fa-solid fa-baby me-2"></i> Anatomía Fetal</div>
            <div class="card-body">
                <div class="row mb-2"><div class="col-md-5 fw-bold">Estado Exploración:</div><div class="col-md-7"><?php echo htmlspecialchars($anatomia['estado_exploracion'] ?? '—'); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">SNC Simetría Plexos:</div><div class="col-md-7"><?php echo badgeSiNo($anatomia['snc_simetria_plexos'] ?? true); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Macizo Facial:</div><div class="col-md-7"><?php echo badgeSiNo($anatomia['macizo_facial_integro'] ?? true); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Situs Torácico:</div><div class="col-md-7"><?php echo htmlspecialchars($anatomia['torax_situs'] ?? '—'); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Eje Cardíaco:</div><div class="col-md-7"><?php echo showVal($anatomia['torax_eje_cardiaco_grados'], '°'); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Cámara Gástrica:</div><div class="col-md-7"><?php echo badgeSiNo($anatomia['abdomen_camara_gastrica'] ?? true); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Extremidades:</div><div class="col-md-7"><?php echo badgeSiNo($anatomia['extremidades_completas'] ?? true); ?></div></div>
                <?php if (!empty($anatomia['observaciones_anomalias'])): ?>
                <div class="mt-2 p-2 bg-light rounded"><small><?php echo nl2br(htmlspecialchars($anatomia['observaciones_anomalias'])); ?></small></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Marcadores FMF -->
        <div class="card mb-4">
            <div class="card-header"><i class="fa-solid fa-ruler me-2"></i> Marcadores FMF</div>
            <div class="card-body">
                <div class="row mb-2"><div class="col-md-5 fw-bold">Translucencia Nucal:</div><div class="col-md-7"><?php echo showVal($marcadores['translucencia_nucal_mm'], ' mm'); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Hueso Nasal:</div><div class="col-md-7"><?php echo ($marcadores['hueso_nasal_presente'] ?? true) ? '<span class="badge bg-success">Presente</span>' : '<span class="badge bg-danger">Ausente</span>'; ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Ductus Venoso (Onda A):</div><div class="col-md-7"><?php echo showVal($marcadores['ductus_venoso_onda_a']); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Reg. Tricuspídea:</div><div class="col-md-7"><?php echo ($marcadores['regurgitacion_tricuspidea_ausente'] ?? true) ? '<span class="badge bg-success">Ausente</span>' : '<span class="badge bg-danger">Presente</span>'; ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Vejiga Fetal:</div><div class="col-md-7"><?php echo showVal($marcadores['vejiga_fetal_mm'], ' mm'); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">UTA PI Promedio:</div><div class="col-md-7"><?php echo showVal($marcadores['uta_pi_promedio']); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Muesca Bilateral:</div><div class="col-md-7"><?php echo ($marcadores['muesca_bilateral'] ?? false) ? '<span class="badge bg-danger">Presente</span>' : '<span class="badge bg-success">Ausente</span>'; ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">PAPP-A (MoM):</div><div class="col-md-7"><?php echo showVal($marcadores['papp_a_mom']); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">PLGF (MoM):</div><div class="col-md-7"><?php echo showVal($marcadores['plgf_mom']); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Tamizaje Genético:</div><div class="col-md-7"><?php echo ($marcadores['tamizaje_genetico_tipo'] ?? 'No realizado') !== 'No realizado' ? htmlspecialchars($marcadores['tamizaje_genetico_tipo'] . ' — ' . ($marcadores['tamizaje_genetico_resultado'] ?? '—')) : 'No realizado'; ?></div></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <!-- Entorno Materno -->
        <div class="card mb-4">
            <div class="card-header"><i class="fa-solid fa-uterus me-2"></i> Entorno Materno</div>
            <div class="card-body">
                <div class="row mb-2"><div class="col-md-5 fw-bold">Líquido Amniótico:</div><div class="col-md-7"><?php echo htmlspecialchars($entorno['liquido_amniotico'] ?? '—'); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Posición Placenta:</div><div class="col-md-7"><?php echo showVal($entorno['placenta_posicion']); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Inserción Placenta:</div><div class="col-md-7"><?php echo showVal($entorno['placenta_insercion']); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Longitud Cervical:</div><div class="col-md-7"><?php echo showVal($entorno['longitud_cervical_mm'], ' mm'); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Índice Consistencia:</div><div class="col-md-7"><?php echo showVal($entorno['indice_consistencia_cervical_pct'], '%'); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Morfología ESHRE:</div><div class="col-md-7"><?php echo showVal($entorno['morfologia_uterina_eshre']); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Miomas Visibles:</div><div class="col-md-7"><?php echo ($entorno['miomas_visibles'] ?? false) ? '<span class="badge bg-warning">Sí</span>' : '<span class="badge bg-success">No</span>'; ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Miomas FIGO:</div><div class="col-md-7"><?php echo showVal($entorno['miomas_figo_tipo']); ?></div></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <!-- Impresión Diagnóstica -->
        <div class="card mb-4">
            <div class="card-header"><i class="fa-solid fa-clipboard-check me-2"></i> Impresión Diagnóstica</div>
            <div class="card-body">
                <div class="row mb-2"><div class="col-md-5 fw-bold">Riesgo Basal:</div><div class="col-md-7"><?php echo showVal($diagnostica['riesgo_basal_cromosomopatias']); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Riesgo Ajustado:</div><div class="col-md-7"><?php echo showVal($diagnostica['riesgo_ajustado_cromosomopatias']); ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Prob. Cromosomopatías:</div><div class="col-md-7"><?php 
                    $pc = $diagnostica['probabilidad_cromosomopatias'] ?? '';
                    $pcClass = match($pc) { 'Alta' => 'danger', 'Intermedia' => 'warning', 'Baja' => 'success', default => 'secondary' };
                    if ($pc) echo '<span class="badge bg-'.$pcClass.'">'.$pc.'</span>'; else echo '<span class="text-muted">—</span>';
                ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Preeclampsia Temprana:</div><div class="col-md-7"><?php 
                    $pt = $diagnostica['riesgo_preeclampsia_temprana'] ?? '';
                    if ($pt == 'Alta') echo '<span class="badge bg-danger">Alta</span>';
                    elseif ($pt == 'Baja') echo '<span class="badge bg-success">Baja</span>';
                    else echo '<span class="text-muted">—</span>';
                ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Enf. Placentaria Tardía:</div><div class="col-md-7"><?php 
                    $ep = $diagnostica['riesgo_enfermedad_placentaria_tardia'] ?? '';
                    if ($ep == 'Alta') echo '<span class="badge bg-danger">Alta</span>';
                    elseif ($ep == 'Baja') echo '<span class="badge bg-success">Baja</span>';
                    else echo '<span class="text-muted">—</span>';
                ?></div></div>
                <div class="row mb-2"><div class="col-md-5 fw-bold">Parto Pretérmino:</div><div class="col-md-7"><?php 
                    $pp = $diagnostica['riesgo_parto_pretermino'] ?? '';
                    if ($pp == 'Alto') echo '<span class="badge bg-danger">Alto</span>';
                    elseif ($pp == 'Bajo') echo '<span class="badge bg-success">Bajo</span>';
                    else echo '<span class="text-muted">—</span>';
                ?></div></div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
