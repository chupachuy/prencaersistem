<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Evaluacion1erTrimestre.php';
require_once __DIR__ . '/../models/AnatomiaFetal.php';
require_once __DIR__ . '/../models/MarcadoresFmf.php';
require_once __DIR__ . '/../models/EntornoMaterno.php';
require_once __DIR__ . '/../models/ImpresionDiagnostica.php';
require_once __DIR__ . '/../models/HistorialClinico.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';

class Evaluaciones1erTrimestreController extends Controller
{
    private $evaluacionModel;
    private $anatomiaModel;
    private $marcadoresModel;
    private $entornoModel;
    private $diagnosticaModel;
    private $historialModel;
    private $pacienteModel;
    private $userModel;

    public function __construct()
    {
        $this->evaluacionModel = new Evaluacion1erTrimestre();
        $this->anatomiaModel = new AnatomiaFetal();
        $this->marcadoresModel = new MarcadoresFmf();
        $this->entornoModel = new EntornoMaterno();
        $this->diagnosticaModel = new ImpresionDiagnostica();
        $this->historialModel = new HistorialClinico();
        $this->pacienteModel = new Paciente();
        $this->userModel = new User();
    }

    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $evaluaciones = $this->evaluacionModel->getAll();
        $this->render('evaluaciones_1er_trimestre/index', ['evaluaciones' => $evaluaciones]);
    }

    public function create()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $pacienteId = $_GET['paciente_id'] ?? null;
        $pacientes = $this->pacienteModel->getAll();
        $medicos = $this->userModel->getMedicos();
        $codigoReporte = $this->evaluacionModel->generateCodigoReporte();

        $historial = null;
        if ($pacienteId) {
            $historial = $this->historialModel->getByPaciente($pacienteId);
        }

        $this->render('evaluaciones_1er_trimestre/create', [
            'pacientes' => $pacientes,
            'medicos' => $medicos,
            'paciente_id' => $pacienteId,
            'codigo_reporte' => $codigoReporte,
            'historial' => $historial
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/evaluaciones_1er_trimestre');
        }

        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $userId = Auth::id();
        $pacienteId = (int) ($_POST['paciente_id'] ?? 0);
        $medicoId = (int) ($_POST['medico_id'] ?? 0);

        if (empty($pacienteId) || empty($medicoId)) {
            Session::set('error', 'Debe seleccionar un paciente y un médico.');
            $this->redirect('/evaluaciones_1er_trimestre/create');
        }

        $dataEvaluacion = [
            'paciente_id' => $pacienteId,
            'medico_id' => $medicoId,
            'codigo_reporte' => $_POST['codigo_reporte'],
            'fecha_evaluacion' => !empty($_POST['fecha_evaluacion']) ? $_POST['fecha_evaluacion'] : date('Y-m-d'),
            'fecha_estudio' => $_POST['fecha_estudio'] ?? null,
            'peso_kg' => $_POST['peso_kg'] ?? null,
            'talla_cm' => $_POST['talla_cm'] ?? null,
            'ta_sistolica' => $_POST['ta_sistolica'] ?? null,
            'ta_diastolica' => $_POST['ta_diastolica'] ?? null,
            'fum' => $_POST['fum'] ?? null,
            'fpp_usg' => $_POST['fpp_usg'] ?? null,
            'embarazo_multiple' => isset($_POST['embarazo_multiple']) ? 1 : 0,
            'estado_feto' => $_POST['estado_feto'] ?? 'Vivo',
            'fcf_lpm' => $_POST['fcf_lpm'] ?? null,
            'lcc_mm' => $_POST['lcc_mm'] ?? null,
            'edad_gestacional_semanas' => $_POST['edad_gestacional_semanas'] ?? null,
            'estado' => $_POST['estado'] ?? 'Pendiente',
            'created_by' => $userId,
            'updated_by' => $userId
        ];

        try {
            $evaluacionId = $this->evaluacionModel->create($dataEvaluacion);

            if (!$evaluacionId) {
                Session::set('error', 'Error al guardar la evaluación.');
                $this->redirect('/evaluaciones_1er_trimestre/create');
            }

            $this->anatomiaModel->create([
                'evaluacion_id' => $evaluacionId,
                'estado_exploracion' => $_POST['estado_exploracion'] ?? 'Completa',
                'snc_simetria_plexos' => isset($_POST['snc_simetria_plexos']) ? 1 : 0,
                'macizo_facial_integro' => isset($_POST['macizo_facial_integro']) ? 1 : 0,
                'torax_situs' => $_POST['torax_situs'] ?? 'Solitus',
                'torax_eje_cardiaco_grados' => $_POST['torax_eje_cardiaco_grados'] ?? null,
                'abdomen_camara_gastrica' => isset($_POST['abdomen_camara_gastrica']) ? 1 : 0,
                'extremidades_completas' => isset($_POST['extremidades_completas']) ? 1 : 0,
                'observaciones_anomalias' => $_POST['observaciones_anomalias'] ?? null
            ]);

            $this->marcadoresModel->create([
                'evaluacion_id' => $evaluacionId,
                'translucencia_nucal_mm' => $_POST['translucencia_nucal_mm'] ?? null,
                'hueso_nasal_presente' => isset($_POST['hueso_nasal_presente']) ? 1 : 0,
                'ductus_venoso_onda_a' => $_POST['ductus_venoso_onda_a'] ?? null,
                'regurgitacion_tricuspidea_ausente' => isset($_POST['regurgitacion_tricuspidea_ausente']) ? 1 : 0,
                'vejiga_fetal_mm' => $_POST['vejiga_fetal_mm'] ?? null,
                'uta_pi_promedio' => $_POST['uta_pi_promedio'] ?? null,
                'muesca_bilateral' => isset($_POST['muesca_bilateral']) ? 1 : 0
            ]);

            $this->entornoModel->create([
                'evaluacion_id' => $evaluacionId,
                'liquido_amniotico' => $_POST['liquido_amniotico'] ?? 'Normal',
                'placenta_posicion' => $_POST['placenta_posicion'] ?? null,
                'placenta_insercion' => $_POST['placenta_insercion'] ?? null,
                'longitud_cervical_mm' => $_POST['longitud_cervical_mm'] ?? null,
                'indice_consistencia_cervical_pct' => $_POST['indice_consistencia_cervical_pct'] ?? null,
                'morfologia_uterina_eshre' => $_POST['morfologia_uterina_eshre'] ?? null,
                'miomas_visibles' => isset($_POST['miomas_visibles']) ? 1 : 0,
                'miomas_figo_tipo' => $_POST['miomas_figo_tipo'] ?? null
            ]);

            $this->diagnosticaModel->create([
                'evaluacion_id' => $evaluacionId,
                'riesgo_basal_cromosomopatias' => $_POST['riesgo_basal_cromosomopatias'] ?? null,
                'riesgo_ajustado_cromosomopatias' => $_POST['riesgo_ajustado_cromosomopatias'] ?? null,
                'probabilidad_cromosomopatias' => $_POST['probabilidad_cromosomopatias'] ?? null,
                'riesgo_preeclampsia_temprana' => $_POST['riesgo_preeclampsia_temprana'] ?? null,
                'riesgo_enfermedad_placentaria_tardia' => $_POST['riesgo_enfermedad_placentaria_tardia'] ?? null,
                'riesgo_parto_pretermino' => $_POST['riesgo_parto_pretermino'] ?? null
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
                'antecedente_parto_pretermino' => isset($_POST['antecedente_parto_pretermino']) ? 1 : 0
            ];

            if ($historialExistente) {
                $this->historialModel->update($historialData);
            } else {
                $this->historialModel->create($historialData);
            }

            Session::set('success', 'Evaluación 1er Trimestre guardada correctamente.');
            $this->redirect('/evaluaciones_1er_trimestre');

        } catch (Exception $e) {
            Session::set('error', 'Error: ' . $e->getMessage());
            $this->redirect('/evaluaciones_1er_trimestre/create');
        }
    }

    public function show()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/evaluaciones_1er_trimestre');
        }

        $evaluacion = $this->evaluacionModel->getById($id);
        if (!$evaluacion) {
            Session::set('error', 'Evaluación no encontrada.');
            $this->redirect('/evaluaciones_1er_trimestre');
        }

        $anatomia = $this->anatomiaModel->getByEvaluacion($id);
        $marcadores = $this->marcadoresModel->getByEvaluacion($id);
        $entorno = $this->entornoModel->getByEvaluacion($id);
        $diagnostica = $this->diagnosticaModel->getByEvaluacion($id);
        $historial = $this->historialModel->getByPaciente($evaluacion['paciente_id']);

        $this->render('evaluaciones_1er_trimestre/show', [
            'evaluacion' => $evaluacion,
            'anatomia' => $anatomia,
            'marcadores' => $marcadores,
            'entorno' => $entorno,
            'diagnostica' => $diagnostica,
            'historial' => $historial
        ]);
    }

    public function edit()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/evaluaciones_1er_trimestre');
        }

        $evaluacion = $this->evaluacionModel->getById($id);
        if (!$evaluacion) {
            Session::set('error', 'Evaluación no encontrada.');
            $this->redirect('/evaluaciones_1er_trimestre');
        }

        $pacientes = $this->pacienteModel->getAll();
        $medicos = $this->userModel->getMedicos();
        $anatomia = $this->anatomiaModel->getByEvaluacion($id);
        $marcadores = $this->marcadoresModel->getByEvaluacion($id);
        $entorno = $this->entornoModel->getByEvaluacion($id);
        $diagnostica = $this->diagnosticaModel->getByEvaluacion($id);
        $historial = $this->historialModel->getByPaciente($evaluacion['paciente_id']);

        $this->render('evaluaciones_1er_trimestre/edit', [
            'evaluacion' => $evaluacion,
            'pacientes' => $pacientes,
            'medicos' => $medicos,
            'anatomia' => $anatomia,
            'marcadores' => $marcadores,
            'entorno' => $entorno,
            'diagnostica' => $diagnostica,
            'historial' => $historial
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/evaluaciones_1er_trimestre');
        }

        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if (!$id) {
            $this->redirect('/evaluaciones_1er_trimestre');
        }

        $userId = Auth::id();
        $pacienteId = (int) ($_POST['paciente_id'] ?? 0);
        $medicoId = (int) ($_POST['medico_id'] ?? 0);

        if (empty($pacienteId) || empty($medicoId)) {
            Session::set('error', 'Debe seleccionar un paciente y un médico.');
            $this->redirect('/evaluaciones_1er_trimestre/edit?id=' . $id);
        }

        $dataEvaluacion = [
            'id' => $id,
            'paciente_id' => $pacienteId,
            'medico_id' => $medicoId,
            'fecha_estudio' => $_POST['fecha_estudio'] ?? null,
            'peso_kg' => $_POST['peso_kg'] ?? null,
            'talla_cm' => $_POST['talla_cm'] ?? null,
            'ta_sistolica' => $_POST['ta_sistolica'] ?? null,
            'ta_diastolica' => $_POST['ta_diastolica'] ?? null,
            'fum' => $_POST['fum'] ?? null,
            'fpp_usg' => $_POST['fpp_usg'] ?? null,
            'embarazo_multiple' => isset($_POST['embarazo_multiple']) ? 1 : 0,
            'estado_feto' => $_POST['estado_feto'] ?? 'Vivo',
            'fcf_lpm' => $_POST['fcf_lpm'] ?? null,
            'lcc_mm' => $_POST['lcc_mm'] ?? null,
            'edad_gestacional_semanas' => $_POST['edad_gestacional_semanas'] ?? null,
            'estado' => $_POST['estado'] ?? 'Pendiente',
            'updated_by' => $userId
        ];

        try {
            $this->evaluacionModel->update($dataEvaluacion);

            $this->anatomiaModel->update([
                'evaluacion_id' => $id,
                'estado_exploracion' => $_POST['estado_exploracion'] ?? 'Completa',
                'snc_simetria_plexos' => isset($_POST['snc_simetria_plexos']) ? 1 : 0,
                'macizo_facial_integro' => isset($_POST['macizo_facial_integro']) ? 1 : 0,
                'torax_situs' => $_POST['torax_situs'] ?? 'Solitus',
                'torax_eje_cardiaco_grados' => $_POST['torax_eje_cardiaco_grados'] ?? null,
                'abdomen_camara_gastrica' => isset($_POST['abdomen_camara_gastrica']) ? 1 : 0,
                'extremidades_completas' => isset($_POST['extremidades_completas']) ? 1 : 0,
                'observaciones_anomalias' => $_POST['observaciones_anomalias'] ?? null
            ]);

            $this->marcadoresModel->update([
                'evaluacion_id' => $id,
                'translucencia_nucal_mm' => $_POST['translucencia_nucal_mm'] ?? null,
                'hueso_nasal_presente' => isset($_POST['hueso_nasal_presente']) ? 1 : 0,
                'ductus_venoso_onda_a' => $_POST['ductus_venoso_onda_a'] ?? null,
                'regurgitacion_tricuspidea_ausente' => isset($_POST['regurgitacion_tricuspidea_ausente']) ? 1 : 0,
                'vejiga_fetal_mm' => $_POST['vejiga_fetal_mm'] ?? null,
                'uta_pi_promedio' => $_POST['uta_pi_promedio'] ?? null,
                'muesca_bilateral' => isset($_POST['muesca_bilateral']) ? 1 : 0
            ]);

            $this->entornoModel->update([
                'evaluacion_id' => $id,
                'liquido_amniotico' => $_POST['liquido_amniotico'] ?? 'Normal',
                'placenta_posicion' => $_POST['placenta_posicion'] ?? null,
                'placenta_insercion' => $_POST['placenta_insercion'] ?? null,
                'longitud_cervical_mm' => $_POST['longitud_cervical_mm'] ?? null,
                'indice_consistencia_cervical_pct' => $_POST['indice_consistencia_cervical_pct'] ?? null,
                'morfologia_uterina_eshre' => $_POST['morfologia_uterina_eshre'] ?? null,
                'miomas_visibles' => isset($_POST['miomas_visibles']) ? 1 : 0,
                'miomas_figo_tipo' => $_POST['miomas_figo_tipo'] ?? null
            ]);

            $this->diagnosticaModel->update([
                'evaluacion_id' => $id,
                'riesgo_basal_cromosomopatias' => $_POST['riesgo_basal_cromosomopatias'] ?? null,
                'riesgo_ajustado_cromosomopatias' => $_POST['riesgo_ajustado_cromosomopatias'] ?? null,
                'probabilidad_cromosomopatias' => $_POST['probabilidad_cromosomopatias'] ?? null,
                'riesgo_preeclampsia_temprana' => $_POST['riesgo_preeclampsia_temprana'] ?? null,
                'riesgo_enfermedad_placentaria_tardia' => $_POST['riesgo_enfermedad_placentaria_tardia'] ?? null,
                'riesgo_parto_pretermino' => $_POST['riesgo_parto_pretermino'] ?? null
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
                'antecedente_parto_pretermino' => isset($_POST['antecedente_parto_pretermino']) ? 1 : 0
            ];

            if ($historialExistente) {
                $this->historialModel->update($historialData);
            } else {
                $this->historialModel->create($historialData);
            }

            Session::set('success', 'Evaluación actualizada correctamente.');

        } catch (Exception $e) {
            Session::set('error', 'Error: ' . $e->getMessage());
        }

        $this->redirect('/evaluaciones_1er_trimestre');
    }

    public function delete()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $this->redirect('/evaluaciones_1er_trimestre');
        }

        if ($this->evaluacionModel->delete($id)) {
            Session::set('success', 'Evaluación eliminada correctamente.');
        } else {
            Session::set('error', 'Error al eliminar la evaluación.');
        }

        $this->redirect('/evaluaciones_1er_trimestre');
    }

    public function print()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/evaluaciones_1er_trimestre');
        }

        $evaluacion = $this->evaluacionModel->getById($id);
        if (!$evaluacion) {
            Session::set('error', 'Evaluación no encontrada.');
            $this->redirect('/evaluaciones_1er_trimestre');
        }

        $anatomia = $this->anatomiaModel->getByEvaluacion($id);
        $marcadores = $this->marcadoresModel->getByEvaluacion($id);
        $entorno = $this->entornoModel->getByEvaluacion($id);
        $diagnostica = $this->diagnosticaModel->getByEvaluacion($id);
        $historial = $this->historialModel->getByPaciente($evaluacion['paciente_id']);

        $this->render('evaluaciones_1er_trimestre/print', [
            'evaluacion' => $evaluacion,
            'anatomia' => $anatomia,
            'marcadores' => $marcadores,
            'entorno' => $entorno,
            'diagnostica' => $diagnostica,
            'historial' => $historial
        ]);
    }
}
