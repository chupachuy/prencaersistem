<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Evaluacion2doTrimestre.php';
require_once __DIR__ . '/../models/Evaluacion1erTrimestre.php';
require_once __DIR__ . '/../models/Biometria2doTrimestre.php';
require_once __DIR__ . '/../models/AnatomiaFetal2doTrimestre.php';
require_once __DIR__ . '/../models/MarcadoresEcograficos2doTrimestre.php';
require_once __DIR__ . '/../models/EntornoPlacentario2doTrimestre.php';
require_once __DIR__ . '/../models/ImpresionDiagnostica2doTrimestre.php';
require_once __DIR__ . '/../models/HistorialClinico.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/ImagenEvaluacion.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';

class Evaluaciones2doTrimestreController extends Controller
{
    private $evaluacionModel;
    private $mailer;
    private $evaluacion1erModel;
    private $biometriaModel;
    private $anatomiaModel;
    private $marcadoresModel;
    private $entornoModel;
    private $diagnosticaModel;
    private $historialModel;
    private $pacienteModel;
    private $userModel;
    private $imagenModel;

    public function __construct()
    {
        $this->evaluacionModel = new Evaluacion2doTrimestre();
        $this->mailer = new Mailer();
        $this->evaluacion1erModel = new Evaluacion1erTrimestre();
        $this->biometriaModel = new Biometria2doTrimestre();
        $this->anatomiaModel = new AnatomiaFetal2doTrimestre();
        $this->marcadoresModel = new MarcadoresEcograficos2doTrimestre();
        $this->entornoModel = new EntornoPlacentario2doTrimestre();
        $this->diagnosticaModel = new ImpresionDiagnostica2doTrimestre();
        $this->historialModel = new HistorialClinico();
        $this->pacienteModel = new Paciente();
        $this->userModel = new User();
        $this->imagenModel = new ImagenEvaluacion();
    }

    public function index()
    {
        if (!Auth::check()) { $this->redirect('/login'); }
        $medicoId = Auth::hasRole(Auth::ROLE_MEDICO) ? Auth::id() : null;
        $evaluaciones = $this->evaluacionModel->getAll($medicoId);
        $this->render('evaluaciones_2do_trimestre/index', ['evaluaciones' => $evaluaciones]);
    }

    public function create()
    {
        if (!Auth::check()) { $this->redirect('/login'); }

        $pacienteId = $_GET['paciente_id'] ?? null;
        $pacientes = $this->pacienteModel->getAll();
        $medicos = $this->userModel->getMedicos();
        $codigoReporte = $this->evaluacionModel->generateCodigoReporte();
        $historial = $pacienteId ? $this->historialModel->getByPaciente($pacienteId) : null;
        $data1er = $pacienteId ? $this->evaluacion1erModel->getLatestFullData($pacienteId) : null;

        $this->render('evaluaciones_2do_trimestre/create', [
            'pacientes' => $pacientes, 'medicos' => $medicos,
            'paciente_id' => $pacienteId, 'codigo_reporte' => $codigoReporte,
            'historial' => $historial, 'data1er' => $data1er
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('/evaluaciones_2do_trimestre'); }
        if (!Auth::check()) { $this->redirect('/login'); }

        $userId = Auth::id();
        $pacienteId = (int) ($_POST['paciente_id'] ?? 0);
        $medicoId = (int) ($_POST['medico_id'] ?? 0);

        if (empty($pacienteId) || empty($medicoId)) {
            Session::set('error', 'Debe seleccionar un paciente y un médico.');
            $this->redirect('/evaluaciones_2do_trimestre/create');
        }

        try {
            $data1er = $this->evaluacion1erModel->getLatestFullData($pacienteId);
            $peso1erTrim = $data1er['peso_kg'] ?? null;
            $pesoActual = $_POST['peso_kg'] ?? null;
            $gananciaPeso = ($peso1erTrim !== null && $pesoActual !== null && $peso1erTrim > 0)
                ? round($pesoActual - $peso1erTrim, 2) : null;

            $evaluacionId = $this->evaluacionModel->create([
                'paciente_id' => $pacienteId, 'medico_id' => $medicoId,
                'codigo_reporte' => $_POST['codigo_reporte'],
                'fecha_evaluacion' => !empty($_POST['fecha_evaluacion']) ? $_POST['fecha_evaluacion'] : date('Y-m-d'),
                'fecha_estudio' => $_POST['fecha_estudio'] ?? null,
                'edad_gestacional_semanas' => $_POST['edad_gestacional_semanas'] ?? null,
                'fpp_actual' => $_POST['fpp_actual'] ?? null,
                'peso_kg' => $pesoActual,
                'talla_cm' => $_POST['talla_cm'] ?? null,
                'pam_mmhg' => $_POST['pam_mmhg'] ?? null,
                'uta_pi_promedio' => $_POST['uta_pi_promedio'] ?? null,
                'estado' => $_POST['estado'] ?? 'Pendiente',
                'peso_1er_trimestre_kg' => $peso1erTrim,
                'ganancia_peso_kg' => $gananciaPeso,
                'created_by' => $userId, 'updated_by' => $userId
            ]);

            if (!$evaluacionId) { throw new Exception('Error al crear evaluación'); }

            $this->biometriaModel->create([
                'evaluacion_id' => $evaluacionId,
                'estado_feto' => $_POST['estado_feto'] ?? 'Vivo',
                'fcf_lpm' => $_POST['fcf_lpm'] ?? null,
                'peso_fetal_estimado_gr' => $_POST['peso_fetal_estimado_gr'] ?? null,
                'percentil_hadlock' => $_POST['percentil_hadlock'] ?? null,
                'crecimiento_armonico' => isset($_POST['crecimiento_armonico']) ? 1 : 0,
                'indice_cefalico_ci' => $_POST['indice_cefalico_ci'] ?? null,
                'fl_ac_pct' => $_POST['fl_ac_pct'] ?? null,
                'hc_ac_campbell' => $_POST['hc_ac_campbell'] ?? null
            ]);

            $this->anatomiaModel->create([
                'evaluacion_id' => $evaluacionId,
                'craneo_snc_normal' => isset($_POST['craneo_snc_normal']) ? 1 : 0,
                'cara_cuello_normal' => isset($_POST['cara_cuello_normal']) ? 1 : 0,
                'corazon_normal' => isset($_POST['corazon_normal']) ? 1 : 0,
                'torax_diafragma_normal' => isset($_POST['torax_diafragma_normal']) ? 1 : 0,
                'abdomen_normal' => isset($_POST['abdomen_normal']) ? 1 : 0,
                'genitourinario_normal' => isset($_POST['genitourinario_normal']) ? 1 : 0,
                'columna_normal' => isset($_POST['columna_normal']) ? 1 : 0,
                'extremidades_normal' => isset($_POST['extremidades_normal']) ? 1 : 0,
                'detalles_anomalias' => $_POST['detalles_anomalias'] ?? null
            ]);

            $this->marcadoresModel->create([
                'evaluacion_id' => $evaluacionId,
                'ventriculomegalia_leve' => isset($_POST['ventriculomegalia_leve']) ? 1 : 0,
                'quistes_plexos_coroideos' => isset($_POST['quistes_plexos_coroideos']) ? 1 : 0,
                'pliegue_nucal_aumentado' => isset($_POST['pliegue_nucal_aumentado']) ? 1 : 0,
                'hueso_nasal_ausente' => isset($_POST['hueso_nasal_ausente']) ? 1 : 0,
                'foco_ecogenico_cardiaco' => isset($_POST['foco_ecogenico_cardiaco']) ? 1 : 0,
                'intestino_hiperecogenico' => isset($_POST['intestino_hiperecogenico']) ? 1 : 0,
                'femur_corto' => isset($_POST['femur_corto']) ? 1 : 0,
                'arteria_umbilical_unica' => isset($_POST['arteria_umbilical_unica']) ? 1 : 0
            ]);

            $this->entornoModel->create([
                'evaluacion_id' => $evaluacionId,
                'placenta_posicion' => $_POST['placenta_posicion'] ?? null,
                'distancia_borde_oci_mm' => $_POST['distancia_borde_oci_mm'] ?? null,
                'acretismo_figo_grado' => $_POST['acretismo_figo_grado'] ?? null,
                'bolsillo_max_liquido_mm' => $_POST['bolsillo_max_liquido_mm'] ?? null,
                'longitud_cervical_mm' => $_POST['longitud_cervical_mm'] ?? null,
                'indice_consistencia_cervical' => $_POST['indice_consistencia_cervical'] ?? null,
                'funneling_presente' => isset($_POST['funneling_presente']) ? 1 : 0,
                'funneling_mm' => $_POST['funneling_mm'] ?? null,
                'sludge_intraamniotico' => $_POST['sludge_intraamniotico'] ?? null,
                'morfologia_uterina_eshre' => $_POST['morfologia_uterina_eshre'] ?? null,
                'miomas_visibles' => isset($_POST['miomas_visibles']) ? 1 : 0,
                'miomas_figo_tipo' => $_POST['miomas_figo_tipo'] ?? null,
                'miomas_dimensiones_mm' => $_POST['miomas_dimensiones_mm'] ?? null,
                'miomas_vascularizacion' => $_POST['miomas_vascularizacion'] ?? null
            ]);

            $this->diagnosticaModel->create([
                'evaluacion_id' => $evaluacionId,
                'riesgo_cromosomopatias' => $_POST['riesgo_cromosomopatias'] ?? null,
                'riesgo_parto_pretermino' => $_POST['riesgo_parto_pretermino'] ?? null,
                'riesgo_preeclampsia' => $_POST['riesgo_preeclampsia'] ?? null,
                'observaciones_medicas' => $_POST['observaciones_medicas'] ?? null
            ]);

            $historialExistente = $this->historialModel->getByPaciente($pacienteId);
            $historialData = [
                'paciente_id' => $pacienteId,
                'hipertension_cronica' => isset($_POST['hipertension_cronica']) ? 1 : 0,
                'diabetes' => isset($_POST['diabetes']) ? 1 : 0,
                'lupus_les' => isset($_POST['lupus_les']) ? 1 : 0,
                'sindrome_antifosfolipido_saf' => isset($_POST['sindrome_antifosfolipido_saf']) ? 1 : 0,
                'antecedente_preeclampsia_rciu' => isset($_POST['antecedente_preeclampsia_rciu']) ? 1 : 0,
                'fertilizacion_in_vitro' => isset($_POST['fertilizacion_in_vitro']) ? 1 : 0,
                'antecedente_parto_pretermino' => isset($_POST['antecedente_parto_pretermino']) ? 1 : 0,
                'num_embarazos' => $_POST['num_embarazos'] ?? null ? (int)$_POST['num_embarazos'] : null,
                'num_cesareas' => $_POST['num_cesareas'] ?? null ? (int)$_POST['num_cesareas'] : null,
                'num_abortos' => $_POST['num_abortos'] ?? null ? (int)$_POST['num_abortos'] : null,
                'num_ectopicos' => $_POST['num_ectopicos'] ?? null ? (int)$_POST['num_ectopicos'] : null
            ];
            $historialExistente ? $this->historialModel->update($historialData) : $this->historialModel->create($historialData);

            ImagenEvaluacion::procesarUpload('2', $evaluacionId);

            Session::set('success', 'Evaluación 2do Trimestre guardada correctamente.');
            $this->redirect('/evaluaciones_2do_trimestre');
        } catch (Exception $e) {
            Session::set('error', 'Error: ' . $e->getMessage());
            $this->redirect('/evaluaciones_2do_trimestre/create');
        }
    }

    public function show()
    {
        if (!Auth::check()) { $this->redirect('/login'); }
        $id = $_GET['id'] ?? null;
        if (!$id) { $this->redirect('/evaluaciones_2do_trimestre'); }

        $evaluacion = $this->evaluacionModel->getById($id);
        if (!$evaluacion) {
            Session::set('error', 'Evaluación no encontrada.');
            $this->redirect('/evaluaciones_2do_trimestre');
        }

        if (Auth::hasRole(Auth::ROLE_MEDICO) && $evaluacion['medico_id'] != Auth::id()) {
            Session::set('error', 'No tienes permiso para ver esta evaluación.');
            $this->redirect('/evaluaciones_2do_trimestre');
        }

        $data1er = $this->evaluacion1erModel->getLatestFullData($evaluacion['paciente_id']);

        $this->render('evaluaciones_2do_trimestre/show', [
            'evaluacion' => $evaluacion,
            'biometria' => $this->biometriaModel->getByEvaluacion($id),
            'anatomia' => $this->anatomiaModel->getByEvaluacion($id),
            'marcadores' => $this->marcadoresModel->getByEvaluacion($id),
            'entorno' => $this->entornoModel->getByEvaluacion($id),
            'diagnostica' => $this->diagnosticaModel->getByEvaluacion($id),
            'historial' => $this->historialModel->getByPaciente($evaluacion['paciente_id']),
            'data1er' => $data1er,
            'imagenes' => $this->imagenModel->getByEvaluacion('2', $id)
        ]);
    }

    public function edit()
    {
        if (!Auth::check()) { $this->redirect('/login'); }
        $id = $_GET['id'] ?? null;
        if (!$id) { $this->redirect('/evaluaciones_2do_trimestre'); }

        $evaluacion = $this->evaluacionModel->getById($id);
        if (!$evaluacion) {
            Session::set('error', 'Evaluación no encontrada.');
            $this->redirect('/evaluaciones_2do_trimestre');
        }

        if (Auth::hasRole(Auth::ROLE_MEDICO) && $evaluacion['medico_id'] != Auth::id()) {
            Session::set('error', 'No tienes permiso para editar esta evaluación.');
            $this->redirect('/evaluaciones_2do_trimestre');
        }

        $data1er = $this->evaluacion1erModel->getLatestFullData($evaluacion['paciente_id']);

        $this->render('evaluaciones_2do_trimestre/edit', [
            'evaluacion' => $evaluacion,
            'pacientes' => $this->pacienteModel->getAll(),
            'medicos' => $this->userModel->getMedicos(),
            'biometria' => $this->biometriaModel->getByEvaluacion($id),
            'anatomia' => $this->anatomiaModel->getByEvaluacion($id),
            'marcadores' => $this->marcadoresModel->getByEvaluacion($id),
            'entorno' => $this->entornoModel->getByEvaluacion($id),
            'diagnostica' => $this->diagnosticaModel->getByEvaluacion($id),
            'historial' => $this->historialModel->getByPaciente($evaluacion['paciente_id']),
            'data1er' => $data1er,
            'imagenes' => $this->imagenModel->getByEvaluacion('2', $id)
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('/evaluaciones_2do_trimestre'); }
        if (!Auth::check()) { $this->redirect('/login'); }

        $id = (int) ($_POST['id'] ?? 0);
        if (!$id) { $this->redirect('/evaluaciones_2do_trimestre'); }

        $evaluacion = $this->evaluacionModel->getById($id);
        if (Auth::hasRole(Auth::ROLE_MEDICO) && (!$evaluacion || $evaluacion['medico_id'] != Auth::id())) {
            Session::set('error', 'No tienes permiso para modificar esta evaluación.');
            $this->redirect('/evaluaciones_2do_trimestre');
        }

        $userId = Auth::id();
        $pacienteId = (int) ($_POST['paciente_id'] ?? 0);
        $medicoId = (int) ($_POST['medico_id'] ?? 0);

        try {
            $data1er = $this->evaluacion1erModel->getLatestFullData($pacienteId);
            $peso1erTrim = $data1er['peso_kg'] ?? null;
            $pesoActual = $_POST['peso_kg'] ?? null;
            $gananciaPeso = ($peso1erTrim !== null && $pesoActual !== null && $peso1erTrim > 0)
                ? round($pesoActual - $peso1erTrim, 2) : null;

            $this->evaluacionModel->update([
                'id' => $id,
                'paciente_id' => $pacienteId, 'medico_id' => $medicoId,
                'fecha_estudio' => $_POST['fecha_estudio'] ?? null,
                'edad_gestacional_semanas' => $_POST['edad_gestacional_semanas'] ?? null,
                'fpp_actual' => $_POST['fpp_actual'] ?? null,
                'peso_kg' => $pesoActual,
                'talla_cm' => $_POST['talla_cm'] ?? null,
                'pam_mmhg' => $_POST['pam_mmhg'] ?? null,
                'uta_pi_promedio' => $_POST['uta_pi_promedio'] ?? null,
                'estado' => $_POST['estado'] ?? 'Pendiente',
                'peso_1er_trimestre_kg' => $peso1erTrim,
                'ganancia_peso_kg' => $gananciaPeso,
                'updated_by' => $userId
            ]);

            $bk = function($k) { return isset($_POST[$k]) ? 1 : 0; };
            $nv = function($k) { return $_POST[$k] ?? null; };

            $this->biometriaModel->update([
                'evaluacion_id' => $id, 'estado_feto' => $_POST['estado_feto'] ?? 'Vivo',
                'fcf_lpm' => $nv('fcf_lpm'), 'peso_fetal_estimado_gr' => $nv('peso_fetal_estimado_gr'),
                'percentil_hadlock' => $nv('percentil_hadlock'), 'crecimiento_armonico' => $bk('crecimiento_armonico'),
                'indice_cefalico_ci' => $nv('indice_cefalico_ci'), 'fl_ac_pct' => $nv('fl_ac_pct'),
                'hc_ac_campbell' => $nv('hc_ac_campbell')
            ]);

            $this->anatomiaModel->update([
                'evaluacion_id' => $id,
                'craneo_snc_normal' => $bk('craneo_snc_normal'), 'cara_cuello_normal' => $bk('cara_cuello_normal'),
                'corazon_normal' => $bk('corazon_normal'), 'torax_diafragma_normal' => $bk('torax_diafragma_normal'),
                'abdomen_normal' => $bk('abdomen_normal'), 'genitourinario_normal' => $bk('genitourinario_normal'),
                'columna_normal' => $bk('columna_normal'), 'extremidades_normal' => $bk('extremidades_normal'),
                'detalles_anomalias' => $nv('detalles_anomalias')
            ]);

            $this->marcadoresModel->update([
                'evaluacion_id' => $id,
                'ventriculomegalia_leve' => $bk('ventriculomegalia_leve'), 'quistes_plexos_coroideos' => $bk('quistes_plexos_coroideos'),
                'pliegue_nucal_aumentado' => $bk('pliegue_nucal_aumentado'), 'hueso_nasal_ausente' => $bk('hueso_nasal_ausente'),
                'foco_ecogenico_cardiaco' => $bk('foco_ecogenico_cardiaco'), 'intestino_hiperecogenico' => $bk('intestino_hiperecogenico'),
                'femur_corto' => $bk('femur_corto'), 'arteria_umbilical_unica' => $bk('arteria_umbilical_unica')
            ]);

            $this->entornoModel->update([
                'evaluacion_id' => $id, 'placenta_posicion' => $nv('placenta_posicion'),
                'distancia_borde_oci_mm' => $nv('distancia_borde_oci_mm'), 'acretismo_figo_grado' => $nv('acretismo_figo_grado'),
                'bolsillo_max_liquido_mm' => $nv('bolsillo_max_liquido_mm'), 'longitud_cervical_mm' => $nv('longitud_cervical_mm'),
                'indice_consistencia_cervical' => $nv('indice_consistencia_cervical'), 'funneling_presente' => $bk('funneling_presente'),
                'funneling_mm' => $nv('funneling_mm'), 'sludge_intraamniotico' => $nv('sludge_intraamniotico'),
                'morfologia_uterina_eshre' => $nv('morfologia_uterina_eshre'), 'miomas_visibles' => $bk('miomas_visibles'),
                'miomas_figo_tipo' => $nv('miomas_figo_tipo'), 'miomas_dimensiones_mm' => $nv('miomas_dimensiones_mm'),
                'miomas_vascularizacion' => $nv('miomas_vascularizacion')
            ]);

            $this->diagnosticaModel->update([
                'evaluacion_id' => $id, 'riesgo_cromosomopatias' => $nv('riesgo_cromosomopatias'),
                'riesgo_parto_pretermino' => $nv('riesgo_parto_pretermino'), 'riesgo_preeclampsia' => $nv('riesgo_preeclampsia'),
                'observaciones_medicas' => $nv('observaciones_medicas')
            ]);

            $historialExistente = $this->historialModel->getByPaciente($pacienteId);
            $hdata = [
                'paciente_id' => $pacienteId, 'hipertension_cronica' => $bk('hipertension_cronica'),
                'diabetes' => $bk('diabetes'), 'lupus_les' => $bk('lupus_les'),
                'sindrome_antifosfolipido_saf' => $bk('sindrome_antifosfolipido_saf'),
                'antecedente_preeclampsia_rciu' => $bk('antecedente_preeclampsia_rciu'),
                'fertilizacion_in_vitro' => $bk('fertilizacion_in_vitro'), 'antecedente_parto_pretermino' => $bk('antecedente_parto_pretermino'),
                'num_embarazos' => $nv('num_embarazos') !== null ? (int)$nv('num_embarazos') : null,
                'num_cesareas' => $nv('num_cesareas') !== null ? (int)$nv('num_cesareas') : null,
                'num_abortos' => $nv('num_abortos') !== null ? (int)$nv('num_abortos') : null,
                'num_ectopicos' => $nv('num_ectopicos') !== null ? (int)$nv('num_ectopicos') : null
            ];
            $historialExistente ? $this->historialModel->update($hdata) : $this->historialModel->create($hdata);

            ImagenEvaluacion::eliminarMarcadas($_POST['imagenes_eliminar'] ?? '');
            ImagenEvaluacion::procesarUpload('2', $id);

            Session::set('success', 'Evaluación actualizada correctamente.');
        } catch (Exception $e) {
            Session::set('error', 'Error: ' . $e->getMessage());
        }
        $this->redirect('/evaluaciones_2do_trimestre');
    }

    public function delete()
    {
        if (!Auth::check()) { $this->redirect('/login'); }
        $id = $_POST['id'] ?? null;
        if ($id) {
            $evaluacion = $this->evaluacionModel->getById($id);
            if (Auth::hasRole(Auth::ROLE_MEDICO) && (!$evaluacion || $evaluacion['medico_id'] != Auth::id())) {
                Session::set('error', 'No tienes permiso para eliminar esta evaluación.');
                $this->redirect('/evaluaciones_2do_trimestre');
            }
            $this->evaluacionModel->delete($id);
            Session::set('success', 'Evaluación eliminada.');
        }
        $this->redirect('/evaluaciones_2do_trimestre');
    }

    public function print()
    {
        if (!Auth::check()) { $this->redirect('/login'); }
        $id = $_GET['id'] ?? null;
        $evaluacion = $this->evaluacionModel->getById($id);
        if (!$evaluacion) { $this->redirect('/evaluaciones_2do_trimestre'); }

        if (Auth::hasRole(Auth::ROLE_MEDICO) && $evaluacion['medico_id'] != Auth::id()) {
            Session::set('error', 'No tienes permiso para imprimir esta evaluación.');
            $this->redirect('/evaluaciones_2do_trimestre');
        }

        $this->render('evaluaciones_2do_trimestre/print', [
            'evaluacion' => $evaluacion,
            'biometria' => $this->biometriaModel->getByEvaluacion($id),
            'anatomia' => $this->anatomiaModel->getByEvaluacion($id),
            'marcadores' => $this->marcadoresModel->getByEvaluacion($id),
            'entorno' => $this->entornoModel->getByEvaluacion($id),
            'diagnostica' => $this->diagnosticaModel->getByEvaluacion($id),
            'historial' => $this->historialModel->getByPaciente($evaluacion['paciente_id']),
            'data1er' => $this->evaluacion1erModel->getLatestFullData($evaluacion['paciente_id']),
            'imagenes' => $this->imagenModel->getByEvaluacion('2', $id)
        ]);
    }

    public function pdf()
    {
        if (!Auth::check()) { $this->redirect("/login"); }
        $id = $_GET["id"] ?? null;
        if (!$id) { $this->redirect("/evaluaciones_2do_trimestre"); }
        $ev = $this->evaluacionModel->getById($id);
        if (!$ev) { Session::set("error", "No encontrado."); $this->redirect("/evaluaciones_2do_trimestre"); }
        $this->streamPdf("evaluaciones_2do_trimestre/imprimir", ["ev" => $ev], $ev["codigo_reporte"] . ".pdf");
    }
}