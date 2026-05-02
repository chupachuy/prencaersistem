<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Reportes1erTrimestre.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';

class Reportes1erTrimestreController extends Controller
{
    private $reporteModel;
    private $pacienteModel;
    private $userModel;

    public function __construct()
    {
        $this->reporteModel = new Reportes1erTrimestre();
        $this->pacienteModel = new Paciente();
        $this->userModel = new User();
    }

    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $reportes = $this->reporteModel->getAll();
        $this->render('reportes_1er_trimestre/index', ['reportes' => $reportes]);
    }

    public function create()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $pacienteId = $_GET['paciente_id'] ?? null;
        $pacientes = $this->pacienteModel->getAll();
        $medicos = $this->userModel->getMedicos();
        
        $codigoReporte = $this->reporteModel->generateCodigoReporte();
        
        $this->render('reportes_1er_trimestre/create', [
            'pacientes' => $pacientes,
            'medicos' => $medicos,
            'paciente_id' => $pacienteId,
            'codigo_reporte' => $codigoReporte
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/pacientes');
        }

        $user = Session::get('user');
        
        $data = [
            'paciente_id' => (int) $_POST['paciente_id'],
            'medico_id' => (int) $_POST['medico_id'],
            'medico_referido_id' => !empty($_POST['medico_referido_id']) ? (int) $_POST['medico_referido_id'] : null,
            'codigo_reporte' => $_POST['codigo_reporte'],
            'fecha_reporte' => !empty($_POST['fecha_reporte']) ? $_POST['fecha_reporte'] : date('Y-m-d'),
            'lugar' => !empty($_POST['lugar']) ? $_POST['lugar'] : null,
            'peso' => !empty($_POST['peso']) ? $_POST['peso'] : null,
            'talla' => !empty($_POST['talla']) ? $_POST['talla'] : null,
            'presion_sistolica' => !empty($_POST['presion_sistolica']) ? (int) $_POST['presion_sistolica'] : null,
            'presion_diastolica' => !empty($_POST['presion_diastolica']) ? (int) $_POST['presion_diastolica'] : null,
            'gesta' => !empty($_POST['gesta']) ? (int) $_POST['gesta'] : null,
            'para' => !empty($_POST['para']) ? (int) $_POST['para'] : null,
            'abortos' => !empty($_POST['abortos']) ? (int) $_POST['abortos'] : null,
            'fecha_ultima_regla' => !empty($_POST['fecha_ultima_regla']) ? $_POST['fecha_ultima_regla'] : null,
            'edad_gestacional_fum' => !empty($_POST['edad_gestacional_fum']) ? $_POST['edad_gestacional_fum'] : null,
            'fecha_probable_parto_fum' => !empty($_POST['fecha_probable_parto_fum']) ? $_POST['fecha_probable_parto_fum'] : null,
            'longitud_craneo_cauda' => !empty($_POST['longitud_craneo_cauda']) ? $_POST['longitud_craneo_cauda'] : null,
            'edad_gestacional_usg' => !empty($_POST['edad_gestacional_usg']) ? $_POST['edad_gestacional_usg'] : null,
            'fecha_probable_parto_usg' => !empty($_POST['fecha_probable_parto_usg']) ? $_POST['fecha_probable_parto_usg'] : null,
            'equipo_usg' => !empty($_POST['equipo_usg']) ? $_POST['equipo_usg'] : null,
            'transductor_tipo' => !empty($_POST['transductor_tipo']) ? $_POST['transductor_tipo'] : null,
            'equipo_estudio' => !empty($_POST['equipo_estudio']) ? $_POST['equipo_estudio'] : null,
            'craneo' => !empty($_POST['craneo']) ? $_POST['craneo'] : null,
            'sistema_nervioso_central' => !empty($_POST['sistema_nervioso_central']) ? $_POST['sistema_nervioso_central'] : null,
            'cuello' => !empty($_POST['cuello']) ? $_POST['cuello'] : null,
            'cara' => !empty($_POST['cara']) ? $_POST['cara'] : null,
            'columna' => !empty($_POST['columna']) ? $_POST['columna'] : null,
            'torax' => !empty($_POST['torax']) ? $_POST['torax'] : null,
            'corazon' => !empty($_POST['corazon']) ? $_POST['corazon'] : null,
            'abdomen' => !empty($_POST['abdomen']) ? $_POST['abdomen'] : null,
            'extremidades' => !empty($_POST['extremidades']) ? $_POST['extremidades'] : null,
            'liquido_amniotico' => !empty($_POST['liquido_amniotico']) ? $_POST['liquido_amniotico'] : null,
            'decidua' => !empty($_POST['decidua']) ? $_POST['decidua'] : null,
            'cervix' => !empty($_POST['cervix']) ? $_POST['cervix'] : null,
            'activo' => 1,
            'estado' => $_POST['estado'] ?? 'Pendiente',
            'created_by' => $user['id'],
            'updated_by' => $user['id']
        ];

        if (empty($data['paciente_id']) || empty($data['medico_id'])) {
            Session::set('error', 'Debe seleccionar un paciente y un médico.');
            $this->redirect('/reportes_1er_trimestre/create');
        }

        try {
            $reporteId = $this->reporteModel->create($data);

            if ($reporteId) {
                Session::set('success', 'Reporte 1er Trimestre guardado correctamente.');
                $this->redirect('/reportes_1er_trimestre');
            } else {
                Session::set('error', 'Error al guardar el reporte.');
                $this->redirect('/reportes_1er_trimestre/create');
            }
        } catch (Exception $e) {
            Session::set('error', 'Error: ' . $e->getMessage());
            $this->redirect('/reportes_1er_trimestre/create');
        }
    }

    public function show()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/reportes_1er_trimestre');
        }

        $reporte = $this->reporteModel->getById($id);
        if (!$reporte) {
            Session::set('error', 'Reporte no encontrado.');
            $this->redirect('/reportes_1er_trimestre');
        }

        $this->render('reportes_1er_trimestre/show', ['reporte' => $reporte]);
    }

    public function edit()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/reportes_1er_trimestre');
        }

        $reporte = $this->reporteModel->getById($id);
        if (!$reporte) {
            Session::set('error', 'Reporte no encontrado.');
            $this->redirect('/reportes_1er_trimestre');
        }

        $pacientes = $this->pacienteModel->getAll();
        $medicos = $this->userModel->getMedicos();

        $this->render('reportes_1er_trimestre/edit', [
            'reporte' => $reporte,
            'pacientes' => $pacientes,
            'medicos' => $medicos
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/reportes_1er_trimestre');
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $this->redirect('/reportes_1er_trimestre');
        }

        $user = Session::get('user');

        $data = [
            'paciente_id' => (int) $_POST['paciente_id'],
            'medico_referido_id' => !empty($_POST['medico_referido_id']) ? (int) $_POST['medico_referido_id'] : null,
            'lugar' => !empty($_POST['lugar']) ? $_POST['lugar'] : null,
            'peso' => !empty($_POST['peso']) ? $_POST['peso'] : null,
            'talla' => !empty($_POST['talla']) ? $_POST['talla'] : null,
            'presion_sistolica' => !empty($_POST['presion_sistolica']) ? (int) $_POST['presion_sistolica'] : null,
            'presion_diastolica' => !empty($_POST['presion_diastolica']) ? (int) $_POST['presion_diastolica'] : null,
            'gesta' => !empty($_POST['gesta']) ? (int) $_POST['gesta'] : null,
            'para' => !empty($_POST['para']) ? (int) $_POST['para'] : null,
            'abortos' => !empty($_POST['abortos']) ? (int) $_POST['abortos'] : null,
            'fecha_ultima_regla' => !empty($_POST['fecha_ultima_regla']) ? $_POST['fecha_ultima_regla'] : null,
            'edad_gestacional_fum' => !empty($_POST['edad_gestacional_fum']) ? $_POST['edad_gestacional_fum'] : null,
            'fecha_probable_parto_fum' => !empty($_POST['fecha_probable_parto_fum']) ? $_POST['fecha_probable_parto_fum'] : null,
            'longitud_craneo_cauda' => !empty($_POST['longitud_craneo_cauda']) ? $_POST['longitud_craneo_cauda'] : null,
            'edad_gestacional_usg' => !empty($_POST['edad_gestacional_usg']) ? $_POST['edad_gestacional_usg'] : null,
            'fecha_probable_parto_usg' => !empty($_POST['fecha_probable_parto_usg']) ? $_POST['fecha_probable_parto_usg'] : null,
            'equipo_usg' => !empty($_POST['equipo_usg']) ? $_POST['equipo_usg'] : null,
            'transductor_tipo' => !empty($_POST['transductor_tipo']) ? $_POST['transductor_tipo'] : null,
            'equipo_estudio' => !empty($_POST['equipo_estudio']) ? $_POST['equipo_estudio'] : null,
            'craneo' => !empty($_POST['craneo']) ? $_POST['craneo'] : null,
            'sistema_nervioso_central' => !empty($_POST['sistema_nervioso_central']) ? $_POST['sistema_nervioso_central'] : null,
            'cuello' => !empty($_POST['cuello']) ? $_POST['cuello'] : null,
            'cara' => !empty($_POST['cara']) ? $_POST['cara'] : null,
            'columna' => !empty($_POST['columna']) ? $_POST['columna'] : null,
            'torax' => !empty($_POST['torax']) ? $_POST['torax'] : null,
            'corazon' => !empty($_POST['corazon']) ? $_POST['corazon'] : null,
            'abdomen' => !empty($_POST['abdomen']) ? $_POST['abdomen'] : null,
            'extremidades' => !empty($_POST['extremidades']) ? $_POST['extremidades'] : null,
            'liquido_amniotico' => !empty($_POST['liquido_amniotico']) ? $_POST['liquido_amniotico'] : null,
            'decidua' => !empty($_POST['decidua']) ? $_POST['decidua'] : null,
            'cervix' => !empty($_POST['cervix']) ? $_POST['cervix'] : null,
            'activo' => isset($_POST['activo']) ? 1 : 0,
            'estado' => $_POST['estado'] ?? 'Pendiente',
            'updated_by' => $user['id']
        ];

        if ($this->reporteModel->update($id, $data)) {
            Session::set('success', 'Reporte actualizado correctamente.');
        } else {
            Session::set('error', 'Error al actualizar el reporte.');
        }

        $this->redirect('/reportes_1er_trimestre');
    }

    public function delete()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $this->redirect('/reportes_1er_trimestre');
        }

        if ($this->reporteModel->delete($id)) {
            Session::set('success', 'Reporte eliminado correctamente.');
        } else {
            Session::set('error', 'Error al eliminar el reporte.');
        }

        $this->redirect('/reportes_1er_trimestre');
    }

    public function print()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/reportes_1er_trimestre');
        }

        $reporte = $this->reporteModel->getById($id);
        if (!$reporte) {
            Session::set('error', 'Reporte no encontrado.');
            $this->redirect('/reportes_1er_trimestre');
        }

        $user = Session::get('user');
        
        $this->render('reportes_1er_trimestre/print', ['reporte' => $reporte, 'user' => $user]);
    }
}
