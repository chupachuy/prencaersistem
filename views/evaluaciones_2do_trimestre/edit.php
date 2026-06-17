<?php
$title = "Editar Evaluación 2do Trimestre";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$chk = fn($v) => ($v == 1 || $v === true) ? 'checked' : '';
$sel = fn($v, $t) => ($v == $t) ? 'selected' : '';
$ev = $evaluacion;
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/evaluaciones_2do_trimestre'); ?>" class="btn btn-apple btn-apple-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
        <h1 class="page-title mb-0">Editar: <?php echo htmlspecialchars($ev['codigo_reporte']); ?></h1>
    </div>
</div>

<form action="<?php echo Url::to('/evaluaciones_2do_trimestre/update'); ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $ev['id']; ?>">

    <!-- Datos Generales -->
    <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-id-card me-2"></i> Datos Generales</div><div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3"><label class="form-label">Código</label><input type="text" class="form-control" value="<?php echo htmlspecialchars($ev['codigo_reporte']); ?>" readonly></div>
            <div class="col-md-3 mb-3"><label for="fecha_evaluacion" class="form-label">Fecha Evaluación *</label><input type="date" class="form-control" name="fecha_evaluacion" value="<?php echo htmlspecialchars($ev['fecha_evaluacion']); ?>" readonly></div>
            <div class="col-md-3 mb-3"><label for="fecha_estudio" class="form-label">Fecha Estudio</label><input type="date" class="form-control" name="fecha_estudio" value="<?php echo htmlspecialchars($ev['fecha_estudio'] ?? ''); ?>"></div>
            <div class="col-md-3 mb-3"><label for="estado" class="form-label">Estado</label><select class="form-select" name="estado"><?php foreach(['Pendiente','En proceso','Completado','Archivado'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel($ev['estado'],$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3"><label for="paciente_id" class="form-label">Paciente *</label><select class="form-select" name="paciente_id" required><option value="">Seleccione</option><?php foreach($pacientes as $p): ?><option value="<?php echo $p['id']; ?>" <?php echo $sel($ev['paciente_id'],$p['id']); ?>><?php echo htmlspecialchars($p['nombre'].' '.$p['apellido']); ?></option><?php endforeach; ?></select></div>
        </div>
    </div></div>

    <!-- Referencia Médica -->
    <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-user-doctor me-2"></i> Referencia Médica</div><div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3"><label class="form-label">Médico Solicitante</label><select name="medico_solicitante_id" class="form-select"><option value="">Seleccionar...</option><?php foreach($medicos as $m): ?><option value="<?php echo $m['id']; ?>" <?php echo $sel($ev['medico_solicitante_id'] ?? '', $m['id']); ?>><?php echo htmlspecialchars($m['nombre'].' '.$m['apellido'].($m['especialidad']?' - '.$m['especialidad']:'')); ?></option><?php endforeach; ?></select></div>
            <div class="col-md-4 mb-3"><label class="form-label">Médico que Realiza <span class="text-danger">*</span></label><select name="medico_id" class="form-select" required><option value="">Seleccionar...</option><?php foreach($medicos as $m): ?><option value="<?php echo $m['id']; ?>" <?php echo $sel($ev['medico_id'],$m['id']); ?>><?php echo htmlspecialchars($m['nombre'].' '.$m['apellido'].($m['especialidad']?' - '.$m['especialidad']:'')); ?></option><?php endforeach; ?></select></div>
            <div class="col-md-4 mb-3"><label class="form-label">Médico Referido</label><select name="medico_referido_id" class="form-select"><option value="">Ninguno</option><?php foreach($medicos as $m): ?><option value="<?php echo $m['id']; ?>" <?php echo $sel($ev['medico_referido_id'] ?? '', $m['id']); ?>><?php echo htmlspecialchars($m['nombre'].' '.$m['apellido'].($m['especialidad']?' - '.$m['especialidad']:'')); ?></option><?php endforeach; ?></select></div>
        </div>
    </div></div>

    <?php if (!empty($data1er)): ?>
    <div class="accordion mb-4" id="ref1erTrimestre2T">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1er2T">
                    <i class="fa-solid fa-folder-open me-2"></i> Datos del 1er Trimestre (referencia)
                </button>
            </h2>
            <div id="collapse1er2T" class="accordion-collapse collapse" data-bs-parent="#ref1erTrimestre2T">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-3"><strong>FPP por USG:</strong> <?php echo !empty($data1er['fpp_usg']) ? date('d/m/Y', strtotime($data1er['fpp_usg'])) : '—'; ?></div>
                        <div class="col-md-3"><strong>Edad Gestacional 1T:</strong> <?php echo !empty($data1er['edad_gestacional_semanas']) ? $data1er['edad_gestacional_semanas'].' sem' : '—'; ?></div>
                        <div class="col-md-3"><strong>Peso:</strong> <?php echo !empty($data1er['peso_kg']) ? $data1er['peso_kg'].' kg' : '—'; ?></div>
                        <div class="col-md-3"><strong>Talla:</strong> <?php echo !empty($data1er['talla_cm']) ? $data1er['talla_cm'].' cm' : '—'; ?></div>
                        <div class="col-md-3 mt-2"><strong>TA Sistólica:</strong> <?php echo !empty($data1er['ta_sistolica']) ? $data1er['ta_sistolica'].' mmHg' : '—'; ?></div>
                        <div class="col-md-3 mt-2"><strong>TA Diastólica:</strong> <?php echo !empty($data1er['ta_diastolica']) ? $data1er['ta_diastolica'].' mmHg' : '—'; ?></div>
                        <div class="col-md-3 mt-2"><strong>LCC:</strong> <?php echo !empty($data1er['lcc_mm']) ? $data1er['lcc_mm'].' mm' : '—'; ?></div>
                        <div class="col-md-3 mt-2"><strong>FUM:</strong> <?php echo !empty($data1er['fum']) ? date('d/m/Y', strtotime($data1er['fum'])) : '—'; ?></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3"><strong>Riesgo Preeclampsia:</strong> <?php echo $data1er['riesgo_preeclampsia_temprana'] ?? '—'; ?></div>
                        <div class="col-md-3"><strong>Riesgo Cromosomopatías:</strong> <?php echo $data1er['probabilidad_cromosomopatias'] ?? '—'; ?></div>
                        <div class="col-md-3"><strong>Riesgo Parto Pretérmino:</strong> <?php echo $data1er['riesgo_parto_pretermino'] ?? '—'; ?></div>
                        <div class="col-md-3"><strong>Miomas:</strong> <?php echo !empty($data1er['miomas_visibles']) ? 'Sí (FIGO: '.($data1er['miomas_figo_tipo'] ?? '—').')' : 'No'; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Datos Clínicos -->
    <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-heart-pulse me-2"></i> Datos Clínicos y Obstétricos</div><div class="card-body">
        <?php
        $pam1T = null;
        if (!empty($data1er['ta_sistolica']) && !empty($data1er['ta_diastolica'])) {
            $pam1T = round(($data1er['ta_sistolica'] + 2 * $data1er['ta_diastolica']) / 3, 2);
        }
        $diffPAM = null;
        if ($pam1T !== null && !empty($ev['pam_mmhg'])) {
            $diffPAM = round($ev['pam_mmhg'] - $pam1T, 2);
        }
        $diffUta = null;
        if (!empty($data1er['uta_pi_promedio']) && !empty($ev['uta_pi_promedio'])) {
            $diffUta = round($ev['uta_pi_promedio'] - $data1er['uta_pi_promedio'], 2);
        }
        ?>
        <div class="row">
            <div class="col-md-3 mb-3"><label for="peso_kg" class="form-label">Peso (kg) <?php if(!empty($data1er['peso_kg'])): ?><small class="text-muted">| 1T: <?php echo $data1er['peso_kg']; ?> kg</small><?php endif; ?><?php if(!empty($ev['ganancia_peso_kg'])): ?> <span class="text-success">(+<?php echo $ev['ganancia_peso_kg']; ?> kg)</span><?php endif; ?></label><input type="number" step="0.01" class="form-control" name="peso_kg" value="<?php echo htmlspecialchars($ev['peso_kg']??''); ?>"></div>
            <div class="col-md-3 mb-3"><label for="talla_cm" class="form-label">Talla (cm) <?php if(!empty($data1er['talla_cm'])): ?><small class="text-muted">| 1T: <?php echo $data1er['talla_cm']; ?> cm</small><?php endif; ?></label><input type="number" step="0.01" class="form-control" name="talla_cm" value="<?php echo htmlspecialchars($ev['talla_cm']??''); ?>"></div>
            <div class="col-md-3 mb-3"><label for="pam_mmhg" class="form-label">PAM (mmHg) <?php if($pam1T !== null): ?><small class="text-muted">| 1T: <?php echo $pam1T; ?> mmHg</small><?php endif; ?><?php if($diffPAM !== null): ?> <span class="<?php echo $diffPAM >= 0 ? 'text-success' : 'text-danger'; ?>">(<?php echo $diffPAM >= 0 ? '+' : ''; ?><?php echo $diffPAM; ?> mmHg)</span><?php endif; ?><i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="PAM = Presión Arterial Media: (sistólica + 2×diastólica) ÷ 3"></i></label><input type="number" step="0.01" class="form-control" name="pam_mmhg" value="<?php echo htmlspecialchars($ev['pam_mmhg']??''); ?>"></div>
            <div class="col-md-3 mb-3"><label for="uta_pi_promedio" class="form-label">UTA PI Promedio <?php if(!empty($data1er['uta_pi_promedio'])): ?><small class="text-muted">| 1T: <?php echo $data1er['uta_pi_promedio']; ?></small><?php endif; ?><?php if($diffUta !== null): ?> <span class="<?php echo $diffUta >= 0 ? 'text-success' : 'text-danger'; ?>">(<?php echo $diffUta >= 0 ? '+' : ''; ?><?php echo $diffUta; ?>)</span><?php endif; ?><i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="UTA = Arterias Uterinas · PI = Índice de Pulsatilidad (resistencia vascular)"></i></label><input type="number" step="0.01" class="form-control" name="uta_pi_promedio" value="<?php echo htmlspecialchars($ev['uta_pi_promedio']??''); ?>"></div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3"><label for="edad_gestacional_semanas" class="form-label">Edad Gestacional (sem)</label><input type="number" step="0.1" class="form-control" name="edad_gestacional_semanas" value="<?php echo htmlspecialchars($ev['edad_gestacional_semanas']??''); ?>"></div>
            <div class="col-md-4 mb-3"><label for="fpp_actual" class="form-label">FPP Actual</label><input type="date" class="form-control" name="fpp_actual" value="<?php echo htmlspecialchars($ev['fpp_actual']??''); ?>"></div>
            <div class="col-md-4 mb-3"><label for="estado_feto" class="form-label">Estado del Feto</label><select class="form-select" name="estado_feto"><option value="Vivo" <?php echo $sel($biometria['estado_feto']??'Vivo','Vivo'); ?>>Vivo</option><option value="Muerto" <?php echo $sel($biometria['estado_feto']??'','Muerto'); ?>>Muerto</option></select></div>
        </div>
    </div></div>

    <!-- Biometría -->
    <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-weight-scale me-2"></i> Biometría y Crecimiento</div><div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3"><label for="fcf_lpm" class="form-label">FCF (lpm)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="FCF = Frecuencia Cardíaca Fetal en latidos por minuto"></i></label><input type="number" class="form-control" name="fcf_lpm" value="<?php echo htmlspecialchars($biometria['fcf_lpm']??''); ?>"></div>
            <div class="col-md-3 mb-3"><label for="peso_fetal_estimado_gr" class="form-label">Peso Fetal (gr)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="PFE = peso del feto calculado mediante biometría fórmula de Hadlock"></i></label><input type="number" class="form-control" name="peso_fetal_estimado_gr" value="<?php echo htmlspecialchars($biometria['peso_fetal_estimado_gr']??''); ?>"></div>
            <div class="col-md-3 mb-3"><label for="percentil_hadlock" class="form-label">Percentil Hadlock<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="Percentil según tablas Hadlock; &lt;p10 = restricción de crecimiento"></i></label><input type="number" class="form-control" name="percentil_hadlock" value="<?php echo htmlspecialchars($biometria['percentil_hadlock']??''); ?>"></div>
            <div class="col-md-3 mb-3"><label class="form-label">&nbsp;</label><div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="crecimiento_armonico" <?php echo $chk($biometria['crecimiento_armonico']??true); ?>><label class="form-check-label">Crecimiento Armónico</label></div></div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3"><label for="indice_cefalico_ci" class="form-label">Índice Cefálico (CI)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="CI = (DB/OF)×100; mide la forma de la cabeza fetal"></i></label><input type="number" step="0.01" class="form-control" name="indice_cefalico_ci" value="<?php echo htmlspecialchars($biometria['indice_cefalico_ci']??''); ?>"></div>
            <div class="col-md-4 mb-3"><label for="fl_ac_pct" class="form-label">FL/AC (%)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="FL = Fémur · AC = Circunferencia Abdominal; ratio para detectar fémur corto relativo"></i></label><input type="number" step="0.01" class="form-control" name="fl_ac_pct" value="<?php echo htmlspecialchars($biometria['fl_ac_pct']??''); ?>"></div>
            <div class="col-md-4 mb-3"><label for="hc_ac_campbell" class="form-label">HC/AC (Campbell)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="HC = Circunferencia Cefálica · AC = Abdominal; ratio de Campbell para simetría de crecimiento"></i></label><input type="number" step="0.01" class="form-control" name="hc_ac_campbell" value="<?php echo htmlspecialchars($biometria['hc_ac_campbell']??''); ?>"></div>
        </div>
    </div></div>

    <!-- Anatomía Fetal -->
    <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-baby me-2"></i> Anatomía Fetal</div><div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="craneo_snc_normal" <?php echo $chk($anatomia['craneo_snc_normal']??true); ?> onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Cráneo/SNC sin alteraciones</label><small class="text-muted d-block ms-4">Forma y tamaño normal, SNC íntegro, ventriculomegalia &lt; 10 mm, plexos coroideos simétricos</small></div></div>
            <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="cara_cuello_normal" <?php echo $chk($anatomia['cara_cuello_normal']??true); ?> onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Cara/Cuello sin alteraciones</label><small class="text-muted d-block ms-4">Órbitas presentes y simétricas, labio superior íntegro, perfil facial normal, pliegue nucal &lt; 6 mm</small></div></div>
            <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="corazon_normal" <?php echo $chk($anatomia['corazon_normal']??true); ?> onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Corazón sin alteraciones</label><small class="text-muted d-block ms-4">Situs solitus, eje cardíaco normal (45°), 4 cámaras presentes, cruce de grandes vasos normal, ritmo regular</small></div></div>
            <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="torax_diafragma_normal" <?php echo $chk($anatomia['torax_diafragma_normal']??true); ?> onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Tórax/Diafragma sin alteraciones</label><small class="text-muted d-block ms-4">Pulmones homogéneos de tamaño normal, diafragma íntegro, no hay derrame pleural</small></div></div>
            <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="abdomen_normal" <?php echo $chk($anatomia['abdomen_normal']??true); ?> onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Abdomen sin alteraciones</label><small class="text-muted d-block ms-4">Estómago presente en hipocondrio izquierdo, intestino sin dilataciones, pared abdominal íntegra, cordón umbilical de inserción normal</small></div></div>
            <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="genitourinario_normal" <?php echo $chk($anatomia['genitourinario_normal']??true); ?> onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Genitourinario sin alteraciones</label><small class="text-muted d-block ms-4">Riñones en fosas lumbares de tamaño normal, sin dilatación pielocalicial (&lt;4 mm), vejiga presente</small></div></div>
            <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="columna_normal" <?php echo $chk($anatomia['columna_normal']??true); ?> onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Columna sin alteraciones</label><small class="text-muted d-block ms-4">Alineación normal en 3 planos, arcos vertebrales íntegros, piel sobre raquis íntegra, sin defectos de cierre</small></div></div>
            <div class="col-md-6 mb-3"><div class="form-check"><input class="form-check-input anat-check" type="checkbox" name="extremidades_normal" <?php echo $chk($anatomia['extremidades_normal']??true); ?> onchange="toggleMarcadores()"><label class="form-check-label fw-bold">Extremidades sin alteraciones</label><small class="text-muted d-block ms-4">Los 4 miembros presentes, 3 segmentos por miembro, manos y pies con dedos contables, movimientos activos normales</small></div></div>
        </div>
        <div class="mt-3"><label for="detalles_anomalias" class="form-label">Detalles de Anomalías</label><textarea class="form-control" name="detalles_anomalias" rows="2"><?php echo htmlspecialchars($anatomia['detalles_anomalias']??''); ?></textarea></div>
    </div></div>

    <!-- Marcadores -->
    <div id="marcadoresSection" class="card mb-4" style="<?php
        $todosSinAlt = true;
        $anatFields = ['craneo_snc_normal','cara_cuello_normal','corazon_normal','torax_diafragma_normal','abdomen_normal','genitourinario_normal','columna_normal','extremidades_normal'];
        foreach ($anatFields as $f) { if (!($anatomia[$f] ?? true)) { $todosSinAlt = false; break; } }
        echo $todosSinAlt ? 'display:none;' : '';
    ?>"><div class="card-header"><i class="fa-solid fa-magnifying-glass me-2"></i> Marcadores Ecográficos <span class="badge bg-warning text-dark ms-2">Activado por alteración anatómica</span></div><div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="ventriculomegalia_leve" <?php echo $chk($marcadores['ventriculomegalia_leve']??false); ?>><label class="form-check-label">Ventriculomegalia Leve</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="quistes_plexos_coroideos" <?php echo $chk($marcadores['quistes_plexos_coroideos']??false); ?>><label class="form-check-label">Quistes Plexos Coroideos</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="pliegue_nucal_aumentado" <?php echo $chk($marcadores['pliegue_nucal_aumentado']??false); ?>><label class="form-check-label">Pliegue Nucal Aumentado</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="hueso_nasal_ausente" <?php echo $chk($marcadores['hueso_nasal_ausente']??false); ?>><label class="form-check-label">Hueso Nasal Ausente</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="foco_ecogenico_cardiaco" <?php echo $chk($marcadores['foco_ecogenico_cardiaco']??false); ?>><label class="form-check-label">Foco Ecogénico Cardíaco</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="intestino_hiperecogenico" <?php echo $chk($marcadores['intestino_hiperecogenico']??false); ?>><label class="form-check-label">Intestino Hiperecogénico</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="femur_corto" <?php echo $chk($marcadores['femur_corto']??false); ?>><label class="form-check-label">Fémur Corto</label></div></div>
            <div class="col-md-3 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="arteria_umbilical_unica" <?php echo $chk($marcadores['arteria_umbilical_unica']??false); ?>><label class="form-check-label">Arteria Umbilical Única</label></div></div>
        </div>
    </div></div>

    <!-- Entorno Placentario -->
    <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-uterus me-2"></i> Entorno Placentario</div><div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3"><label for="placenta_posicion" class="form-label">Posición Placenta</label><select class="form-select" name="placenta_posicion"><option value="">No evaluado</option><?php foreach(['Anterior','Posterior','Lateral Derecho','Lateral Izquierdo'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel($entorno['placenta_posicion']??'',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
            <div class="col-md-4 mb-3"><label for="distancia_borde_oci_mm" class="form-label">Distancia Borde OCI (mm)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="OCI = Orificio Cervical Interno; distancia de la placenta al OCI (&ge;20mm = normal)"></i></label><input type="number" step="0.01" class="form-control" name="distancia_borde_oci_mm" value="<?php echo htmlspecialchars($entorno['distancia_borde_oci_mm']??''); ?>"></div>
            <div class="col-md-4 mb-3"><label for="acretismo_figo_grado" class="form-label">Acretismo FIGO</label><select class="form-select" name="acretismo_figo_grado"><option value="">No evaluado</option><?php foreach(['0'=>'0 - Normal','1'=>'1 - Parcial','2'=>'2 - Invasión','3'=>'3 - Percretismo'] as $k=>$l): ?><option value="<?php echo $k; ?>" <?php echo $sel($entorno['acretismo_figo_grado']??'',$k); ?>><?php echo $l; ?></option><?php endforeach; ?></select></div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3"><label for="bolsillo_max_liquido_mm" class="form-label">Bolsillo Máx. Líquido</label><input type="number" class="form-control" name="bolsillo_max_liquido_mm" value="<?php echo htmlspecialchars($entorno['bolsillo_max_liquido_mm']??''); ?>"></div>
            <div class="col-md-3 mb-3"><label for="longitud_cervical_mm" class="form-label">Longitud Cervical (mm)<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="Medida del cuello uterino; &lt;25mm = riesgo de parto pretérmino"></i></label><input type="number" step="0.01" class="form-control" name="longitud_cervical_mm" value="<?php echo htmlspecialchars($entorno['longitud_cervical_mm']??''); ?>"></div>
            <div class="col-md-3 mb-3"><label for="indice_consistencia_cervical" class="form-label">Índice Consistencia Cx<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="ICC = Ratio AP/Long del cérvix; valora la elasticidad del cuello uterino"></i></label><input type="number" class="form-control" name="indice_consistencia_cervical" value="<?php echo htmlspecialchars($entorno['indice_consistencia_cervical']??''); ?>"></div>
            <div class="col-md-3 mb-3"><label for="sludge_intraamniotico" class="form-label">Sludge Intraamniótico<i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="Sludge = material ecogénico flotante en el líquido amniótico; asociado a parto pretérmino"></i></label><select class="form-select" name="sludge_intraamniotico"><option value="">No evaluado</option><?php foreach(['Si','No','Dudoso'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel($entorno['sludge_intraamniotico']??'',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="funneling_presente" <?php echo $chk($entorno['funneling_presente']??false); ?>><label class="form-check-label">Funneling Presente <i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="Funneling = embudo del OCI; apertura del orificio cervical interno, indicador de parto pretérmino"></i></label></div></div>
            <div class="col-md-4 mb-2"><label for="funneling_mm" class="form-label">Funneling (mm)</label><input type="number" step="0.01" class="form-control form-control-sm" name="funneling_mm" value="<?php echo htmlspecialchars($entorno['funneling_mm']??''); ?>"></div>
        </div>
        <hr><h6 class="text-muted">Miomas Uterinos y Morfología</h6>
        <div class="row">
            <div class="col-md-4 mb-3"><label for="morfologia_uterina_eshre" class="form-label">Morfología Uterina <?php if(!empty($data1er['morfologia_uterina_eshre'])): ?><small class="text-muted">| 1T: <?php echo $data1er['morfologia_uterina_eshre']; ?></small><?php endif; ?></label><select class="form-select" name="morfologia_uterina_eshre"><option value="">No evaluado</option><?php foreach(['U0','U1','U2','U3','U4','U5','U6'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel($entorno['morfologia_uterina_eshre']??'',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
            <div class="col-md-4 mb-3"><label class="form-label">Miomas <?php if(!empty($data1er['miomas_visibles'])): ?><small class="text-muted">| 1T: Sí (FIGO: <?php echo $data1er['miomas_figo_tipo']??'—'; ?>)</small><?php endif; ?></label><div class="form-check"><input class="form-check-input" type="checkbox" name="miomas_visibles" <?php echo $chk($entorno['miomas_visibles']??false); ?>><label class="form-check-label">Miomas Visibles</label></div></div>
            <div class="col-md-4 mb-3"><label for="miomas_figo_tipo" class="form-label">FIGO Tipo</label><select class="form-select" name="miomas_figo_tipo"><option value="">No aplica</option><?php for($i=0;$i<=8;$i++): ?><option value="<?php echo $i; ?>" <?php echo $sel($entorno['miomas_figo_tipo']??'',$i); ?>>Tipo <?php echo $i; ?></option><?php endfor; ?></select></div>
            <div class="col-md-4 mb-3"><label for="miomas_dimensiones_mm" class="form-label">Dimensiones (mm)</label><input type="text" class="form-control" name="miomas_dimensiones_mm" value="<?php echo htmlspecialchars($entorno['miomas_dimensiones_mm']??''); ?>" placeholder="Ej: 25x20"></div>
            <div class="col-md-4 mb-3"><label for="miomas_vascularizacion" class="form-label">Vascularización</label><input type="text" class="form-control" name="miomas_vascularizacion" value="<?php echo htmlspecialchars($entorno['miomas_vascularizacion']??''); ?>" placeholder="Ej: Moderada"></div>
        </div>
    </div></div>

    <!-- Historial -->
    <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-notes-medical me-2"></i> Historial Clínico</div><div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="hipertension_cronica" <?php echo $chk($historial['hipertension_cronica']??false); ?>><label class="form-check-label">Hipertensión Crónica</label></div></div>
            <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="diabetes" <?php echo $chk($historial['diabetes']??false); ?>><label class="form-check-label">Diabetes</label></div></div>
            <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="lupus_les" <?php echo $chk($historial['lupus_les']??false); ?>><label class="form-check-label">Lupus / LES <i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="LES = Lupus Eritematoso Sistémico, enfermedad autoinmune"></i></label></div></div>
            <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="sindrome_antifosfolipido_saf" <?php echo $chk($historial['sindrome_antifosfolipido_saf']??false); ?>><label class="form-check-label">Síndrome Antifosfolípido <i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="SAF = trastorno autoinmune que favorece trombosis y complicaciones en el embarazo"></i></label></div></div>
            <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="antecedente_preeclampsia_rciu" <?php echo $chk($historial['antecedente_preeclampsia_rciu']??false); ?>><label class="form-check-label">Ant. Preeclampsia/RCIU <i class="fa-solid fa-circle-question text-muted ms-1 fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="RCIU = Restricción del Crecimiento Intrauterino"></i></label></div></div>
            <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="fertilizacion_in_vitro" <?php echo $chk($historial['fertilizacion_in_vitro']??false); ?>><label class="form-check-label">Fertilización In Vitro</label></div></div>
            <div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="antecedente_parto_pretermino" <?php echo $chk($historial['antecedente_parto_pretermino']??false); ?>><label class="form-check-label">Ant. Parto Pretérmino</label></div></div>
        </div>
    </div></div>

    <!-- Impresión Diagnóstica -->
    <div class="card mb-4"><div class="card-header"><i class="fa-solid fa-clipboard-check me-2"></i> Impresión Diagnóstica</div><div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3"><label for="riesgo_cromosomopatias" class="form-label">Riesgo Cromosomopatías</label><select class="form-select" name="riesgo_cromosomopatias"><option value="">No evaluado</option><?php foreach(['Bajo','Intermedio','Alto'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel($diagnostica['riesgo_cromosomopatias']??'',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
            <div class="col-md-4 mb-3"><label for="riesgo_parto_pretermino" class="form-label">Riesgo Parto Pretérmino</label><select class="form-select" name="riesgo_parto_pretermino"><option value="">No evaluado</option><?php foreach(['Bajo','Intermedio','Alto','Muy Alto'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel($diagnostica['riesgo_parto_pretermino']??'',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
            <div class="col-md-4 mb-3"><label for="riesgo_preeclampsia" class="form-label">Riesgo Preeclampsia</label><select class="form-select" name="riesgo_preeclampsia"><option value="">No evaluado</option><?php foreach(['Bajo','Intermedio','Alto','Muy Alto'] as $o): ?><option value="<?php echo $o; ?>" <?php echo $sel($diagnostica['riesgo_preeclampsia']??'',$o); ?>><?php echo $o; ?></option><?php endforeach; ?></select></div>
        </div>
        <div class="mt-2"><label for="observaciones_medicas" class="form-label">Observaciones Médicas</label><textarea class="form-control" name="observaciones_medicas" rows="2"><?php echo htmlspecialchars($diagnostica['observaciones_medicas']??''); ?></textarea></div>
    </div></div>

    <!-- Imágenes del Estudio -->
    <div class="card mb-4">
        <div class="card-header"><i class="fa-solid fa-images me-2"></i> Imágenes del Estudio</div>
        <div class="card-body">
            <?php if (!empty($imagenes)): ?>
            <div class="mb-3">
                <h6 class="text-muted small mb-2">Imágenes actuales (clic en × para eliminar)</h6>
                <div class="row g-2" id="existingImages">
                    <?php foreach ($imagenes as $img): ?>
                    <div class="col-auto position-relative" data-img-id="<?php echo $img['id']; ?>">
                        <img src="<?php echo Url::to($img['ruta_imagen']); ?>" class="rounded" style="width:120px;height:120px;object-fit:cover;">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-1 bg-white rounded-circle p-1 shadow-sm delete-existing" style="font-size:10px;width:20px;height:20px;" data-id="<?php echo $img['id']; ?>"></button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="upload-zone border border-2 border-dashed rounded-3 p-4 text-center" id="uploadZone" style="border-color:#ccc!important;cursor:pointer;">
                <i class="fa-solid fa-cloud-arrow-up fa-2x text-muted mb-3 d-block"></i>
                <p class="text-muted mb-1">Arrastra imágenes o haz clic para seleccionar</p>
                <small class="text-muted">Máximo 10 imágenes · 5 MB por imagen · JPG, PNG</small>
            </div>
            <input type="file" id="imagenesInput" name="imagenes[]" multiple accept="image/jpeg,image/png" style="display:none;">
            <input type="hidden" id="imagenesEliminar" name="imagenes_eliminar" value="">
            <div class="row mt-3 g-2" id="previewGrid"></div>
            <div id="uploadCount" class="text-muted mt-2 small" style="display:none;"></div>
        </div>
    </div>

    <div class="card mb-4"><div class="card-body">
        <div class="row align-items-end">
            <div class="col-md-8 text-end">
                <form action="<?php echo Url::to('/evaluaciones_2do_trimestre/delete'); ?>" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">
                    <input type="hidden" name="id" value="<?php echo $ev['id']; ?>">
                    <button type="submit" class="btn btn-apple btn-apple-danger me-2"><i class="fa-solid fa-trash"></i> Eliminar</button>
                </form>
                <a href="<?php echo Url::to('/evaluaciones_2do_trimestre'); ?>" class="btn btn-apple btn-apple-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-apple btn-apple-primary btn-lg"><i class="fa-solid fa-save"></i> Actualizar</button>
            </div>
        </div>
    </div></div>
</form>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Uploader de imágenes
    var uploadZone = document.getElementById('uploadZone');
    var imgInput = document.getElementById('imagenesInput');
    var previewGrid = document.getElementById('previewGrid');
    var uploadCount = document.getElementById('uploadCount');
    var eliminarInput = document.getElementById('imagenesEliminar');
    var selectedFiles = [];
    var deletedIds = [];

    if (uploadZone && imgInput) {
        uploadZone.addEventListener('click', function() { imgInput.click(); });
        uploadZone.addEventListener('dragover', function(e) { e.preventDefault(); uploadZone.style.borderColor = '#999'; });
        uploadZone.addEventListener('dragleave', function() { uploadZone.style.borderColor = '#ccc'; });
        uploadZone.addEventListener('drop', function(e) { e.preventDefault(); uploadZone.style.borderColor = '#ccc'; handleFiles(e.dataTransfer.files); });
        imgInput.addEventListener('change', function() { handleFiles(imgInput.files); });
    }

    var delBtns = document.querySelectorAll('.delete-existing');
    for (var d = 0; d < delBtns.length; d++) {
        delBtns[d].addEventListener('click', function() {
            var id = parseInt(this.dataset.id);
            if (id) { deletedIds.push(id); eliminarInput.value = deletedIds.join(','); }
            this.closest('.col-auto').remove();
        });
    }

    function handleFiles(files) {
        if (selectedFiles.length >= 10) { alert('Máximo 10 imágenes.'); return; }
        for (var i = 0; i < files.length; i++) {
            if (selectedFiles.length >= 10) break;
            var f = files[i];
            if (f.size > 5*1024*1024) { alert('La imagen '+f.name+' excede 5 MB.'); continue; }
            if (['image/jpeg','image/png'].indexOf(f.type) === -1) { alert(f.name+' no es JPG/PNG.'); continue; }
            selectedFiles.push(f);
            var reader = new FileReader();
            reader.onload = (function(file) { return function(e) {
                var col = document.createElement('div');
                col.className = 'col-auto position-relative';
                col.innerHTML = '<img src="'+e.target.result+'" class="rounded" style="width:120px;height:120px;object-fit:cover;"><button type="button" class="btn-close position-absolute top-0 end-0 m-1 bg-white rounded-circle p-1 shadow-sm" style="font-size:10px;width:20px;height:20px;"></button>';
                previewGrid.appendChild(col);
                col.querySelector('.btn-close').addEventListener('click', function() {
                    var idx = selectedFiles.indexOf(file);
                    if (idx > -1) { selectedFiles.splice(idx, 1); }
                    col.remove(); updateCount(); syncFileInput();
                });
                updateCount();
            }; })(f);
            reader.readAsDataURL(f);
        }
        syncFileInput();
    }

    function syncFileInput() {
        var dt = new DataTransfer();
        for (var i = 0; i < selectedFiles.length; i++) dt.items.add(selectedFiles[i]);
        imgInput.files = dt.files;
    }

    function updateCount() {
        if (selectedFiles.length > 0) {
            uploadCount.style.display = 'block';
            uploadCount.textContent = selectedFiles.length + ' imagen(es) nueva(s) seleccionada(s)';
        } else uploadCount.style.display = 'none';
    }
});

function toggleMarcadores() {
    var checks = document.querySelectorAll('.anat-check');
    var allChecked = true;
    checks.forEach(function(chk) { if (!chk.checked) allChecked = false; });
    var section = document.getElementById('marcadoresSection');
    if (section) {
        section.style.display = allChecked ? 'none' : 'block';
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
