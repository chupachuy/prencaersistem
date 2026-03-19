<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/Asignacion.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';

class PacienteController extends Controller
{
    private $pacienteModel;
    private $asignacionModel;

    public function __construct()
    {
        $this->pacienteModel = new Paciente();
        $this->asignacionModel = new Asignacion();
    }

    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $roleId = Session::get('user_role_id');
        
        if ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_JEFE || $roleId == Auth::ROLE_ADMINISTRADOR) {
            $pacientes = $this->pacienteModel->getAll();
        } else {
            // Medico sees only their assigned patients or ones they created directly
            $pacientes = $this->pacienteModel->getAllByMedico(Auth::id());
        }

        $this->render('pacientes/index', ['pacientes' => $pacientes]);
    }

    public function create()
    {
        if (!Auth::check()) {
            $this->redirect('/dashboard');
        }

        $this->render('pacientes/create');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/pacientes/create');
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');

        if (empty($nombre) || empty($apellido) || empty($fecha_nacimiento)) {
            Session::set('error', 'Nombre, Apellido y Fecha de Nacimiento son obligatorios.');
            $this->redirect('/pacientes/create');
        }

        $pacienteId = $this->pacienteModel->create($nombre, $apellido, Auth::id(), $fecha_nacimiento);

        if ($pacienteId) {
            // If it's a doctor creating a patient, automatically assign the patient to them
            if (Auth::hasRole(Auth::ROLE_MEDICO)) {
                $this->asignacionModel->create([
                    'medico_id' => Auth::id(),
                    'paciente_id' => $pacienteId,
                    'asignado_por' => Auth::id(),
                    'fecha_asignacion' => date('Y-m-d'),
                    'motivo' => 'Creación directa por el médico'
                ]);
            }
            
            Session::set('success', 'Paciente registrado correctamente.');
            $this->redirect('/pacientes');
        } else {
            Session::set('error', 'Error al registrar el paciente.');
            $this->redirect('/pacientes/create');
        }
    }
}
