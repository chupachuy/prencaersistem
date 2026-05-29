<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/models/Evaluacion1erTrimestre.php';
require_once __DIR__ . '/models/AnatomiaFetal.php';
require_once __DIR__ . '/models/MarcadoresFmf.php';
require_once __DIR__ . '/models/EntornoMaterno.php';
require_once __DIR__ . '/models/ImpresionDiagnostica.php';
require_once __DIR__ . '/models/Evaluacion2doTrimestre.php';
require_once __DIR__ . '/models/Biometria2doTrimestre.php';
require_once __DIR__ . '/models/AnatomiaFetal2doTrimestre.php';
require_once __DIR__ . '/models/MarcadoresEcograficos2doTrimestre.php';
require_once __DIR__ . '/models/EntornoPlacentario2doTrimestre.php';
require_once __DIR__ . '/models/ImpresionDiagnostica2doTrimestre.php';
require_once __DIR__ . '/models/Evaluacion3erTrimestre.php';
require_once __DIR__ . '/models/Antecedentes3erTrimestre.php';
require_once __DIR__ . '/models/Crecimiento3erTrimestre.php';
require_once __DIR__ . '/models/Doppler3erTrimestre.php';
require_once __DIR__ . '/models/AnatomiaLiquido3erTrimestre.php';
require_once __DIR__ . '/models/EvaluacionPlacentaria3erTrimestre.php';
require_once __DIR__ . '/models/HistorialClinico.php';

$db = Database::getInstance()->getConnection();

$db->beginTransaction();

try {
    // Usamos paciente_id=1, medico_id=1 (superadmin) para datos demo
    $pacienteId = 1;
    $medicoId = 1;
    $userId = 1;

    // Historial Clínico (compartido)
    $h = new HistorialClinico();
    $hExistente = $h->getByPaciente($pacienteId);
    $hdata = [
        'paciente_id' => $pacienteId,
        'hipertension_cronica' => 0,
        'diabetes' => 0,
        'lupus_les' => 0,
        'sindrome_antifosfolipido_saf' => 0,
        'antecedente_preeclampsia_rciu' => 1,
        'fertilizacion_in_vitro' => 0,
        'antecedente_parto_pretermino' => 1
    ];
    if ($hExistente) {
        $h->update($hdata);
        echo "Historial clínico actualizado.<br>";
    } else {
        $h->create($hdata);
        echo "Historial clínico creado.<br>";
    }

    // ========== 1er TRIMESTRE ==========
    $ev1 = new Evaluacion1erTrimestre();
    $eid1 = $ev1->create([
        'paciente_id' => $pacienteId, 'medico_id' => $medicoId,
        'codigo_reporte' => $ev1->generateCodigoReporte(),
        'fecha_evaluacion' => '2026-03-15', 'fecha_estudio' => '2026-03-14',
        'peso_kg' => 62.3, 'talla_cm' => 160, 'ta_sistolica' => 115, 'ta_diastolica' => 72,
        'fum' => '2026-01-01', 'fpp_usg' => '2026-10-08',
        'embarazo_multiple' => 0, 'estado_feto' => 'Vivo',
        'fcf_lpm' => 168, 'lcc_mm' => 55.2, 'edad_gestacional_semanas' => 10.3,
        'estado' => 'Completado', 'created_by' => $userId, 'updated_by' => $userId
    ]);
    echo "Evaluación 1er Trimestre creada (ID: $eid1).<br>";

    $af1 = new AnatomiaFetal();
    $af1->create(['evaluacion_id' => $eid1, 'estado_exploracion' => 'Completa',
        'snc_simetria_plexos' => 1, 'macizo_facial_integro' => 1,
        'torax_situs' => 'Solitus', 'torax_eje_cardiaco_grados' => 45,
        'abdomen_camara_gastrica' => 1, 'extremidades_completas' => 1,
        'observaciones_anomalias' => 'Sin hallazgos anormales']);

    $mf1 = new MarcadoresFmf();
    $mf1->create(['evaluacion_id' => $eid1, 'translucencia_nucal_mm' => 1.2,
        'hueso_nasal_presente' => 1, 'ductus_venoso_onda_a' => 'Positiva',
        'regurgitacion_tricuspidea_ausente' => 1, 'vejiga_fetal_mm' => 7.0,
        'uta_pi_promedio' => 1.45, 'muesca_bilateral' => 0]);

    $em1 = new EntornoMaterno();
    $em1->create(['evaluacion_id' => $eid1, 'liquido_amniotico' => 'Normal',
        'placenta_posicion' => 'Posterior', 'placenta_insercion' => 'Normal',
        'longitud_cervical_mm' => 38.0, 'indice_consistencia_cervical_pct' => 90,
        'morfologia_uterina_eshre' => 'U0', 'miomas_visibles' => 0]);

    $id1 = new ImpresionDiagnostica();
    $id1->create(['evaluacion_id' => $eid1, 'riesgo_basal_cromosomopatias' => '1:250',
        'riesgo_ajustado_cromosomopatias' => '1:1500', 'probabilidad_cromosomopatias' => 'Baja',
        'riesgo_preeclampsia_temprana' => 'Baja', 'riesgo_enfermedad_placentaria_tardia' => 'Baja',
        'riesgo_parto_pretermino' => 'Bajo']);
    echo "   Sub-tablas 1er Trimestre completadas.<br>";

    // ========== 2do TRIMESTRE ==========
    $ev2 = new Evaluacion2doTrimestre();
    $eid2 = $ev2->create([
        'paciente_id' => $pacienteId, 'medico_id' => $medicoId,
        'codigo_reporte' => $ev2->generateCodigoReporte(),
        'fecha_evaluacion' => '2026-05-28', 'fecha_estudio' => '2026-05-27',
        'edad_gestacional_semanas' => 20.5, 'fpp_actual' => '2026-10-10',
        'peso_kg' => 67.8, 'talla_cm' => 160, 'pam_mmhg' => 82.3, 'uta_pi_promedio' => 0.95,
        'estado' => 'Completado', 'created_by' => $userId, 'updated_by' => $userId
    ]);
    echo "Evaluación 2do Trimestre creada (ID: $eid2).<br>";

    $b2 = new Biometria2doTrimestre();
    $b2->create(['evaluacion_id' => $eid2, 'estado_feto' => 'Vivo', 'fcf_lpm' => 148,
        'peso_fetal_estimado_gr' => 380, 'percentil_hadlock' => 55,
        'crecimiento_armonico' => 1, 'indice_cefalico_ci' => 78.2,
        'fl_ac_pct' => 22.0, 'hc_ac_campbell' => 1.14]);

    $a2 = new AnatomiaFetal2doTrimestre();
    $a2->create(['evaluacion_id' => $eid2, 'craneo_snc_normal' => 1, 'cara_cuello_normal' => 1,
        'corazon_normal' => 1, 'torax_diafragma_normal' => 1, 'abdomen_normal' => 1,
        'genitourinario_normal' => 1, 'columna_normal' => 1, 'extremidades_normal' => 1,
        'detalles_anomalias' => null]);

    $m2 = new MarcadoresEcograficos2doTrimestre();
    $m2->create(['evaluacion_id' => $eid2, 'ventriculomegalia_leve' => 0,
        'quistes_plexos_coroideos' => 0, 'pliegue_nucal_aumentado' => 0,
        'hueso_nasal_ausente' => 0, 'foco_ecogenico_cardiaco' => 0,
        'intestino_hiperecogenico' => 0, 'femur_corto' => 0, 'arteria_umbilical_unica' => 0]);

    $ep2 = new EntornoPlacentario2doTrimestre();
    $ep2->create(['evaluacion_id' => $eid2, 'placenta_posicion' => 'Anterior',
        'distancia_borde_oci_mm' => 35.0, 'acretismo_figo_grado' => '0',
        'bolsillo_max_liquido_mm' => 55, 'longitud_cervical_mm' => 34.5,
        'indice_consistencia_cervical' => 82, 'funneling_presente' => 0,
        'sludge_intraamniotico' => 'No']);

    $id2 = new ImpresionDiagnostica2doTrimestre();
    $id2->create(['evaluacion_id' => $eid2, 'riesgo_cromosomopatias' => 'Bajo',
        'riesgo_parto_pretermino' => 'Bajo', 'riesgo_preeclampsia' => 'Bajo',
        'observaciones_medicas' => 'Embarazo de evolución normal. Control en 4 semanas.']);
    echo "   Sub-tablas 2do Trimestre completadas.<br>";

    // ========== 3er TRIMESTRE ==========
    $ev3 = new Evaluacion3erTrimestre();
    $eid3 = $ev3->create([
        'paciente_id' => $pacienteId, 'medico_id' => $medicoId,
        'codigo_reporte' => $ev3->generateCodigoReporte(),
        'fecha_evaluacion' => '2026-08-20', 'fecha_estudio' => '2026-08-19',
        'edad_gestacional_semanas' => 32.3, 'peso_kg' => 73.5,
        'ta_sistolica' => 118, 'ta_diastolica' => 76,
        'situacion_fetal' => 'Longitudinal', 'presentacion_fetal' => 'Cefalico',
        'posicion_fetal' => 'Dorso anterior', 'fcf_lpm' => 142,
        'estado' => 'En proceso', 'created_by' => $userId, 'updated_by' => $userId
    ]);
    echo "Evaluación 3er Trimestre creada (ID: $eid3).<br>";

    $ant3 = new Antecedentes3erTrimestre();
    $ant3->create(['evaluacion_id' => $eid3, 'curva_tolerancia_glucosa' => 'Normal',
        'diabetes_gestacional_actual' => 0, 'movimientos_fetales' => 'Normales',
        'signos_amenaza_parto_pretermino' => 0, 'plan_nacimiento_definido' => 1]);

    $cr3 = new Crecimiento3erTrimestre();
    $cr3->create(['evaluacion_id' => $eid3, 'peso_fetal_estimado_gr' => 1980,
        'percentil_ajustado' => 42, 'clasificacion_crecimiento' => 'Adecuado',
        'estadio_rciu_barcelona' => 'Ninguno']);

    $dp3 = new Doppler3erTrimestre();
    $dp3->create(['evaluacion_id' => $eid3, 'au_pi' => 0.95,
        'au_flujo_diastolico' => 'Presente', 'acm_pi' => 1.55,
        'dv_onda_a' => 'Positiva', 'uta_pi_promedio' => 0.78,
        'ratio_cu_icp' => 1.63, 'alteracion_doppler_detectada' => 0]);

    $al3 = new AnatomiaLiquido3erTrimestre();
    $al3->create(['evaluacion_id' => $eid3, 'circular_cordon_cuello' => 'Simple',
        'liquido_amniotico_mm' => 130, 'metodo_medicion_liquido' => 'Bolsillo Maximo',
        'diagnostico_liquido' => 'Normal', 'estructuras_normales' => 1]);

    $pl3 = new EvaluacionPlacentaria3erTrimestre();
    $pl3->create(['evaluacion_id' => $eid3, 'distancia_oci_mm' => 42.0,
        'grosor_placentario_mm' => 32, 'grado_madurez' => 'Grado 0-1',
        'lagunas_vasculares' => 'Ausentes/minimas', 'interfase_miometrial' => 'Intacta',
        'vasos_puente' => 0, 'acretismo_figo_pas' => 'Grado 0']);
    echo "   Sub-tablas 3er Trimestre completadas.<br>";

    $db->commit();

    echo "<br><strong style='color:green'>DATOS DE PRUEBA INSERTADOS CORRECTAMENTE.</strong><br>";
    echo "<br>Códigos generados:<br>";
    echo "  1er Trimestre: EV1T-0001-2026<br>";
    echo "  2do Trimestre: EV2T-0001-2026<br>";
    echo "  3er Trimestre: EV3T-0001-2026<br>";
    echo "<br><a href='" . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://" . $_SERVER['HTTP_HOST'] . "/evaluaciones_1er_trimestre'>Ir a Evaluaciones 1er Trimestre</a>";

} catch (Exception $e) {
    $db->rollBack();
    echo "<strong style='color:red'>ERROR: " . $e->getMessage() . "</strong>";
    echo "<br><br>StackTrace:<br><pre>" . $e->getTraceAsString() . "</pre>";
}
