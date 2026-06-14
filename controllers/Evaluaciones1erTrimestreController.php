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
require_once __DIR__ . '/../models/ImagenEvaluacion.php';
require_once __DIR__ . '/../models/Bitacora.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Url.php';
require_once __DIR__ . '/../core/Mailer.php';

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
    private $imagenModel;
    private $mailer;

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
        $this->imagenModel = new ImagenEvaluacion();
        $this->mailer = new Mailer();
    }

    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $medicoId = Auth::hasRole(Auth::ROLE_MEDICO) ? Auth::id() : null;
        $evaluaciones = $this->evaluacionModel->getAll($medicoId);
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
        $medicoSolicitanteId = !empty($_POST['medico_solicitante_id']) ? (int)$_POST['medico_solicitante_id'] : null;
        $medicoReferidoId = !empty($_POST['medico_referido_id']) ? (int)$_POST['medico_referido_id'] : null;

        if (empty($pacienteId) || empty($medicoId)) {
            Session::set('error', 'Debe seleccionar un paciente y un médico.');
            $this->redirect('/evaluaciones_1er_trimestre/create');
        }

        $nv = function($k) { $v = $_POST[$k] ?? null; return ($v === '') ? null : $v; };

        $dataEvaluacion = [
            'paciente_id' => $pacienteId,
            'medico_id' => $medicoId,
            'medico_solicitante_id' => $medicoSolicitanteId,
            'medico_referido_id' => $medicoReferidoId,
            'codigo_reporte' => $_POST['codigo_reporte'],
            'fecha_evaluacion' => !empty($_POST['fecha_evaluacion']) ? $_POST['fecha_evaluacion'] : date('Y-m-d'),
            'fecha_estudio' => $_POST['fecha_estudio'] ?? null,
            'peso_kg' => $nv('peso_kg'),
            'talla_cm' => $nv('talla_cm'),
            'ta_sistolica' => $nv('ta_sistolica'),
            'ta_diastolica' => $nv('ta_diastolica'),
            'fum' => $_POST['fum'] ?? null,
            'fpp_usg' => $_POST['fpp_usg'] ?? null,
            'embarazo_multiple' => isset($_POST['embarazo_multiple']) ? 1 : 0,
            'estado_feto' => $_POST['estado_feto'] ?? 'Vivo',
            'fcf_lpm' => $nv('fcf_lpm'),
            'lcc_mm' => $nv('lcc_mm'),
            'edad_gestacional_semanas' => $nv('edad_gestacional_semanas'),
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
                'torax_eje_cardiaco_grados' => $nv('torax_eje_cardiaco_grados'),
                'abdomen_camara_gastrica' => isset($_POST['abdomen_camara_gastrica']) ? 1 : 0,
                'extremidades_completas' => isset($_POST['extremidades_completas']) ? 1 : 0,
                'observaciones_anomalias' => $_POST['observaciones_anomalias'] ?? null
            ]);

            $this->marcadoresModel->create([
                'evaluacion_id' => $evaluacionId,
                'translucencia_nucal_mm' => $nv('translucencia_nucal_mm'),
                'hueso_nasal_presente' => isset($_POST['hueso_nasal_presente']) ? 1 : 0,
                'ductus_venoso_onda_a' => $_POST['ductus_venoso_onda_a'] ?? null,
                'regurgitacion_tricuspidea_ausente' => isset($_POST['regurgitacion_tricuspidea_ausente']) ? 1 : 0,
                'vejiga_fetal_mm' => $nv('vejiga_fetal_mm'),
                'uta_pi_promedio' => $nv('uta_pi_promedio'),
                'muesca_bilateral' => isset($_POST['muesca_bilateral']) ? 1 : 0,
                'papp_a_mom' => $nv('papp_a_mom'),
                'plgf_mom' => $nv('plgf_mom'),
                'tamizaje_genetico_tipo' => $_POST['tamizaje_genetico_tipo'] ?? 'No realizado',
                'tamizaje_genetico_resultado' => $_POST['tamizaje_genetico_resultado'] ?? null
            ]);

            $this->entornoModel->create([
                'evaluacion_id' => $evaluacionId,
                'liquido_amniotico' => $_POST['liquido_amniotico'] ?? 'Normal',
                'placenta_posicion' => $_POST['placenta_posicion'] ?? null,
                'placenta_insercion' => $_POST['placenta_insercion'] ?? null,
                'longitud_cervical_mm' => $nv('longitud_cervical_mm'),
                'indice_consistencia_cervical_pct' => $nv('indice_consistencia_cervical_pct'),
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
                'antecedente_parto_pretermino' => isset($_POST['antecedente_parto_pretermino']) ? 1 : 0,
                'num_embarazos'  => isset($_POST['num_embarazos'])  && $_POST['num_embarazos']  !== '' ? (int)$_POST['num_embarazos']  : null,
                'num_cesareas'   => isset($_POST['num_cesareas'])   && $_POST['num_cesareas']   !== '' ? (int)$_POST['num_cesareas']   : null,
                'num_abortos'    => isset($_POST['num_abortos'])    && $_POST['num_abortos']    !== '' ? (int)$_POST['num_abortos']    : null,
                'num_ectopicos'  => isset($_POST['num_ectopicos'])  && $_POST['num_ectopicos']  !== '' ? (int)$_POST['num_ectopicos']  : null
            ];

            if ($historialExistente) {
                $this->historialModel->update($historialData);
            } else {
                $this->historialModel->create($historialData);
            }

            ImagenEvaluacion::procesarUpload('1', $evaluacionId);

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

        if (Auth::hasRole(Auth::ROLE_MEDICO) && $evaluacion['medico_id'] != Auth::id()) {
            Session::set('error', 'No tienes permiso para ver esta evaluación.');
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
            'historial' => $historial,
            'imagenes' => $this->imagenModel->getByEvaluacion('1', $id)
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

        if (Auth::hasRole(Auth::ROLE_MEDICO) && $evaluacion['medico_id'] != Auth::id()) {
            Session::set('error', 'No tienes permiso para editar esta evaluación.');
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
            'historial' => $historial,
            'imagenes' => $this->imagenModel->getByEvaluacion('1', $id)
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

        $evaluacion = $this->evaluacionModel->getById($id);
        if (Auth::hasRole(Auth::ROLE_MEDICO) && (!$evaluacion || $evaluacion['medico_id'] != Auth::id())) {
            Session::set('error', 'No tienes permiso para modificar esta evaluación.');
            $this->redirect('/evaluaciones_1er_trimestre');
        }

        $userId = Auth::id();
        $pacienteId = (int) ($_POST['paciente_id'] ?? 0);
        $medicoId = (int) ($_POST['medico_id'] ?? 0);
        $medicoSolicitanteId = !empty($_POST['medico_solicitante_id']) ? (int)$_POST['medico_solicitante_id'] : null;
        $medicoReferidoId = !empty($_POST['medico_referido_id']) ? (int)$_POST['medico_referido_id'] : null;

        if (empty($pacienteId) || empty($medicoId)) {
            Session::set('error', 'Debe seleccionar un paciente y un médico.');
            $this->redirect('/evaluaciones_1er_trimestre/edit?id=' . $id);
        }

        $nv = function($k) { $v = $_POST[$k] ?? null; return ($v === '') ? null : $v; };

        $dataEvaluacion = [
            'id' => $id,
            'paciente_id' => $pacienteId,
            'medico_id' => $medicoId,
            'medico_solicitante_id' => $medicoSolicitanteId,
            'medico_referido_id' => $medicoReferidoId,
            'fecha_estudio' => $_POST['fecha_estudio'] ?? null,
            'peso_kg' => $nv('peso_kg'),
            'talla_cm' => $nv('talla_cm'),
            'ta_sistolica' => $nv('ta_sistolica'),
            'ta_diastolica' => $nv('ta_diastolica'),
            'fum' => $_POST['fum'] ?? null,
            'fpp_usg' => $_POST['fpp_usg'] ?? null,
            'embarazo_multiple' => isset($_POST['embarazo_multiple']) ? 1 : 0,
            'estado_feto' => $_POST['estado_feto'] ?? 'Vivo',
            'fcf_lpm' => $nv('fcf_lpm'),
            'lcc_mm' => $nv('lcc_mm'),
            'edad_gestacional_semanas' => $nv('edad_gestacional_semanas'),
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
                'torax_eje_cardiaco_grados' => $nv('torax_eje_cardiaco_grados'),
                'abdomen_camara_gastrica' => isset($_POST['abdomen_camara_gastrica']) ? 1 : 0,
                'extremidades_completas' => isset($_POST['extremidades_completas']) ? 1 : 0,
                'observaciones_anomalias' => $_POST['observaciones_anomalias'] ?? null
            ]);

            $this->marcadoresModel->update([
                'evaluacion_id' => $id,
                'translucencia_nucal_mm' => $nv('translucencia_nucal_mm'),
                'hueso_nasal_presente' => isset($_POST['hueso_nasal_presente']) ? 1 : 0,
                'ductus_venoso_onda_a' => $_POST['ductus_venoso_onda_a'] ?? null,
                'regurgitacion_tricuspidea_ausente' => isset($_POST['regurgitacion_tricuspidea_ausente']) ? 1 : 0,
                'vejiga_fetal_mm' => $nv('vejiga_fetal_mm'),
                'uta_pi_promedio' => $nv('uta_pi_promedio'),
                'muesca_bilateral' => isset($_POST['muesca_bilateral']) ? 1 : 0,
                'papp_a_mom' => $nv('papp_a_mom'),
                'plgf_mom' => $nv('plgf_mom'),
                'tamizaje_genetico_tipo' => $_POST['tamizaje_genetico_tipo'] ?? 'No realizado',
                'tamizaje_genetico_resultado' => $_POST['tamizaje_genetico_resultado'] ?? null
            ]);

            $this->entornoModel->update([
                'evaluacion_id' => $id,
                'liquido_amniotico' => $_POST['liquido_amniotico'] ?? 'Normal',
                'placenta_posicion' => $_POST['placenta_posicion'] ?? null,
                'placenta_insercion' => $_POST['placenta_insercion'] ?? null,
                'longitud_cervical_mm' => $nv('longitud_cervical_mm'),
                'indice_consistencia_cervical_pct' => $nv('indice_consistencia_cervical_pct'),
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
                'antecedente_parto_pretermino' => isset($_POST['antecedente_parto_pretermino']) ? 1 : 0,
                'num_embarazos'  => isset($_POST['num_embarazos'])  && $_POST['num_embarazos']  !== '' ? (int)$_POST['num_embarazos']  : null,
                'num_cesareas'   => isset($_POST['num_cesareas'])   && $_POST['num_cesareas']   !== '' ? (int)$_POST['num_cesareas']   : null,
                'num_abortos'    => isset($_POST['num_abortos'])    && $_POST['num_abortos']    !== '' ? (int)$_POST['num_abortos']    : null,
                'num_ectopicos'  => isset($_POST['num_ectopicos'])  && $_POST['num_ectopicos']  !== '' ? (int)$_POST['num_ectopicos']  : null
            ];

            if ($historialExistente) {
                $this->historialModel->update($historialData);
            } else {
                $this->historialModel->create($historialData);
            }

            ImagenEvaluacion::eliminarMarcadas($_POST['imagenes_eliminar'] ?? '');
            ImagenEvaluacion::procesarUpload('1', $id);

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

        $evaluacion = $this->evaluacionModel->getById($id);
        if (Auth::hasRole(Auth::ROLE_MEDICO) && (!$evaluacion || $evaluacion['medico_id'] != Auth::id())) {
            Session::set('error', 'No tienes permiso para eliminar esta evaluación.');
            $this->redirect('/evaluaciones_1er_trimestre');
        }

        if ($this->evaluacionModel->delete($id)) {
            Session::set('success', 'Evaluación eliminada correctamente.');
        } else {
            Session::set('error', 'Error al eliminar la evaluación.');
        }

        $this->redirect('/evaluaciones_1er_trimestre');
    }

    public function enviar()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        // BUG-03: La ruta es POST; leer el id de POST y hacer fallback a GET
        $id = intval($_POST['id'] ?? $_GET['id'] ?? 0);
        $evaluacion = $this->evaluacionModel->getById($id);

        if (!$evaluacion) {
            Session::set('error', 'Evaluación no encontrada.');
            $this->redirect('/evaluaciones_1er_trimestre');
        }

        if ($evaluacion['estado'] !== 'Completado') {
            Session::set('error', 'Solo se pueden enviar evaluaciones con estado "Completado".');
            $this->redirect('/evaluaciones_1er_trimestre/show?id=' . $id);
        }

        $pacienteEmail = $evaluacion['paciente_email'] ?? null;
        $medicoEmail = $evaluacion['medico_email'] ?? null;
        $medicoSolEmail = $evaluacion['medico_solicitante_email'] ?? null;
        $medicoRefEmail = $evaluacion['medico_referido_email'] ?? null;

        $pacienteNombre = $evaluacion['paciente_nombre'] . ' ' . $evaluacion['paciente_apellido'];
        $medicoNombre = $evaluacion['medico_nombre'] . ' ' . $evaluacion['medico_apellido'];

        $subject = 'Evaluación 1er Trimestre ' . $evaluacion['codigo_reporte'] . ' - PRENACER';

        $body = '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
            <h2 style="color: #0d6efd;">Evaluación de 1er Trimestre</h2>
            <p>La siguiente evaluación ha sido completada y está disponible para su revisión:</p>
            <table style="width: 100%; border-collapse: collapse; margin: 15px 0;">
                <tr><td style="padding: 8px; border: 1px solid #dee2e6; background: #f8f9fa; font-weight: bold;">Código</td><td style="padding: 8px; border: 1px solid #dee2e6;">' . htmlspecialchars($evaluacion['codigo_reporte']) . '</td></tr>
                <tr><td style="padding: 8px; border: 1px solid #dee2e6; background: #f8f9fa; font-weight: bold;">Paciente</td><td style="padding: 8px; border: 1px solid #dee2e6;">' . htmlspecialchars($pacienteNombre) . '</td></tr>
                <tr><td style="padding: 8px; border: 1px solid #dee2e6; background: #f8f9fa; font-weight: bold;">Fecha</td><td style="padding: 8px; border: 1px solid #dee2e6;">' . date('d/m/Y', strtotime($evaluacion['fecha_evaluacion'])) . '</td></tr>
                <tr><td style="padding: 8px; border: 1px solid #dee2e6; background: #f8f9fa; font-weight: bold;">Médico</td><td style="padding: 8px; border: 1px solid #dee2e6;">' . htmlspecialchars($medicoNombre) . '</td></tr>
                <tr><td style="padding: 8px; border: 1px solid #dee2e6; background: #f8f9fa; font-weight: bold;">Estado</td><td style="padding: 8px; border: 1px solid #dee2e6;"><span style="background: #198754; color: #fff; padding: 2px 8px; border-radius: 4px;">Completado</span></td></tr>
            </table>
            <p style="color: #6c757d; font-size: 12px; text-align: center; margin-top: 20px;">Este es un correo automático del sistema PRENACER. Por favor no responda a este mensaje.</p>
        </div>';

        // --- Generar PDF adjunto ---
        $pdfDir = __DIR__ . '/../storage/tmp/';
        if (!is_dir($pdfDir)) mkdir($pdfDir, 0775, true);
        $pdfFile = preg_replace('/[^a-zA-Z0-9_-]/', '_', $evaluacion['codigo_reporte']) . '.pdf';
        $pdfPath = $pdfDir . $pdfFile;

        $imagenes = $this->imagenModel->getByEvaluacion('1', $id);

        $pdfData = [
            'evaluacion' => $evaluacion,
            'anatomia' => $this->anatomiaModel->getByEvaluacion($id),
            'marcadores' => $this->marcadoresModel->getByEvaluacion($id),
            'entorno' => $this->entornoModel->getByEvaluacion($id),
            'diagnostica' => $this->diagnosticaModel->getByEvaluacion($id),
            'historial' => $this->historialModel->getByPaciente($evaluacion['paciente_id']),
            'imagenes' => $imagenes
        ];

        if ($this->generatePdfAttachment('evaluaciones_1er_trimestre/imprimir', $pdfData, $pdfPath)) {
            $this->mailer->clearAttachments();
            $this->mailer->addAttachment($pdfPath, $evaluacion['codigo_reporte'] . '.pdf');
        }
        // --- Fin PDF ---

        $enviados = 0;
        $errores = [];

        $dest = $_POST['destinatario'] ?? '';

        if ($dest === 'todos') {
            if ($medicoEmail) { if ($this->mailer->sendEmail($medicoEmail, $subject, $body)) $enviados++; else $errores[] = 'Médico (' . $medicoEmail . ')'; }
            if ($medicoSolEmail && $medicoSolEmail !== $medicoEmail) { if ($this->mailer->sendEmail($medicoSolEmail, $subject, $body)) $enviados++; else $errores[] = 'Médico Solicitante (' . $medicoSolEmail . ')'; }
            if ($medicoRefEmail && $medicoRefEmail !== $medicoEmail && $medicoRefEmail !== $medicoSolEmail) { if ($this->mailer->sendEmail($medicoRefEmail, $subject, $body)) $enviados++; else $errores[] = 'Médico Referido (' . $medicoRefEmail . ')'; }
            if ($pacienteEmail) { if ($this->mailer->sendEmail($pacienteEmail, $subject, $body)) $enviados++; else $errores[] = 'Paciente (' . $pacienteEmail . ')'; }
        } else {
            $map = [
                'medico' => [$medicoEmail, 'Médico (' . $medicoEmail . ')'],
                'solicitante' => [$medicoSolEmail, 'Médico Solicitante (' . $medicoSolEmail . ')'],
                'referido' => [$medicoRefEmail, 'Médico Referido (' . $medicoRefEmail . ')'],
                'paciente' => [$pacienteEmail, 'Paciente (' . $pacienteEmail . ')'],
            ];
            if (isset($map[$dest]) && $map[$dest][0]) {
                list($email, $label) = $map[$dest];
                if ($this->mailer->sendEmail($email, $subject, $body)) $enviados++; else $errores[] = $label;
            } else {
                Session::set('error', 'Destinatario no válido o sin correo registrado.');
                $this->redirect('/evaluaciones_1er_trimestre/show?id=' . $id);
                return;
            }
        }

        @unlink($pdfPath);

        $bitacora = new Bitacora();
        $bitacora->registrar(
            Auth::id(),
            'Envío de email',
            "Evaluación 1er Trimestre {$evaluacion['codigo_reporte']} enviada a {$enviados} destinatario(s)",
            'evaluaciones_1er_trimestre',
            $id
        );

        if ($enviados > 0 && empty($errores)) {
            Session::set('success', 'Evaluación enviada correctamente a ' . $enviados . ' destinatario(s).');
        } elseif ($enviados > 0) {
            $msg = 'Evaluación enviada parcialmente: ' . $enviados . ' exitoso(s). Error en: ' . implode(', ', $errores);
            if ($this->mailer->lastError) $msg .= ' | SMTP: ' . $this->mailer->lastError;
            Session::set('warning', $msg);
        } else {
            $msg = 'No se pudo enviar la evaluación. Verifique que el destinatario tenga correo electrónico registrado.';
            if ($this->mailer->lastError) $msg .= ' | SMTP: ' . $this->mailer->lastError;
            Session::set('error', $msg);
        }

        $this->redirect('/evaluaciones_1er_trimestre/show?id=' . $id);
    }

    public function pdf()
    {
        if (!Auth::check()) { $this->redirect('/login'); }

        $id = $_GET['id'] ?? null;
        if (!$id) { $this->redirect('/evaluaciones_1er_trimestre'); }

        $evaluacion = $this->evaluacionModel->getById($id);
        if (!$evaluacion) {
            Session::set('error', 'Evaluación no encontrada.');
            $this->redirect('/evaluaciones_1er_trimestre');
        }

        if (Auth::hasRole(Auth::ROLE_MEDICO) && $evaluacion['medico_id'] != Auth::id()) {
            Session::set('error', 'No tienes permiso para generar el PDF.');
            $this->redirect('/evaluaciones_1er_trimestre');
        }

        $anatomia = $this->anatomiaModel->getByEvaluacion($id);
        $marcadores = $this->marcadoresModel->getByEvaluacion($id);
        $entorno = $this->entornoModel->getByEvaluacion($id);
        $diagnostica = $this->diagnosticaModel->getByEvaluacion($id);
        $historial = $this->historialModel->getByPaciente($evaluacion['paciente_id']);
        $imagenes = $this->imagenModel->getByEvaluacion('1', $id);

        $this->streamPdf('evaluaciones_1er_trimestre/imprimir', [
            'evaluacion' => $evaluacion,
            'anatomia' => $anatomia,
            'marcadores' => $marcadores,
            'entorno' => $entorno,
            'diagnostica' => $diagnostica,
            'historial' => $historial,
            'imagenes' => $imagenes
        ], $evaluacion['codigo_reporte'] . '.pdf');
    }

}

