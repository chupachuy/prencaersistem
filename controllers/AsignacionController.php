<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Asignacion.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';

class AsignacionController extends Controller
{
    private Asignacion $asignacionModel;

    public function __construct()
    {
        $this->asignacionModel = new Asignacion();
    }

    public function index()
    {
        if (!Auth::check() || (!Auth::hasRole(Auth::ROLE_SUPERADMIN) && !Auth::hasRole(Auth::ROLE_ADMINISTRADOR) && !Auth::hasRole(Auth::ROLE_JEFE))) {
            $this->redirect('/dashboard');
        }

        // Fetch all active assignments for admins
        $asignaciones = $this->asignacionModel->getAll();
        $this->render('asignaciones/index', ['asignaciones' => $asignaciones]);
    }

    public function misPacientes()
    {
        if (!Auth::check() || !Auth::hasRole(Auth::ROLE_MEDICO)) {
            $this->redirect('/dashboard');
        }

        $asignaciones = $this->asignacionModel->getActiveByMedico(Auth::id());
        $this->render('asignaciones/index', ['asignaciones' => $asignaciones, 'mis_pacientes' => true]);
    }

    public function create()
    {
        if (!Auth::check() || (!Auth::hasRole(Auth::ROLE_SUPERADMIN) && !Auth::hasRole(Auth::ROLE_ADMINISTRADOR) && !Auth::hasRole(Auth::ROLE_JEFE))) {
            $this->redirect('/dashboard');
        }

        $userModel = new User();
        $medicos = $userModel->getAllDoctors();

        $this->render('asignaciones/create', ['medicos' => $medicos]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/asignaciones');
        }

        $data = [
            'medico_id' => $_POST['medico_id'],
            'paciente_id' => $_POST['paciente_id'], // In real app, from select or hidden input
            'asignado_por' => Auth::id(),
            'fecha_asignacion' => date('Y-m-d'),
            'motivo' => $_POST['motivo'] ?? ''
        ];

        try {
            $this->asignacionModel->create($data);
            Session::set('success', 'Paciente asignado correctamente.');
            $this->redirect('/asignaciones');
        }
        catch (\PDOException $e) {
            Session::set('error', 'Error al asignar: ' . $e->getMessage());
            $this->redirect('/asignaciones/create');
        }
    }
}
