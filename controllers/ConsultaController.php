<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Consulta.php';
require_once __DIR__ . '/../models/Reporte1erTrimestre.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';

class ConsultaController extends Controller
{
    private $consultaModel;
    private $reporteModel;

    public function __construct()
    {
        $this->consultaModel = new Consulta();
        $this->reporteModel = new Reporte1erTrimestre();
    }

    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $consultas = $this->consultaModel->getAllWithPaciente();
        
        $reportesData = [];
        foreach ($consultas as $consulta) {
            $reportesData[$consulta['id_consulta']] = $this->reporteModel->getByConsultaId($consulta['id_consulta']);
        }
        
        $this->render('consultas/index', ['consultas' => $consultas, 'reportesData' => $reportesData]);
    }

    public function create()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $pacientes = $this->consultaModel->getPacientes();
        $this->render('consultas/create', ['pacientes' => $pacientes]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/consultas/create');
        }

        $data = [
            'id_paciente' => $_POST['id_paciente'] ?? null,
            'motivo_consulta' => $_POST['motivo_consulta'] ?? null,
            'observaciones' => $_POST['observaciones'] ?? null
        ];

        if (empty($data['id_paciente'])) {
            Session::set('error', 'Debe seleccionar un paciente.');
            $this->redirect('/consultas/create');
        }

        $consultaId = $this->consultaModel->create($data);

        if ($consultaId) {
            Session::set('success', 'Consulta registrada correctamente.');
            $this->redirect('/reporte_1er_trimestre/create?consulta_id=' . $consultaId);
        } else {
            Session::set('error', 'Error al registrar la consulta.');
            $this->redirect('/consultas/create');
        }
    }

    public function show()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/consultas');
        }

        $consulta = $this->consultaModel->getByIdWithPaciente($id);
        $this->render('consultas/show', ['consulta' => $consulta]);
    }
}
