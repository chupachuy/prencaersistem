<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Diagnostico.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../models/Paciente.php';

class DiagnosticoController extends Controller
{
    private $diagnosticoModel;
    private $userModel;

    public function __construct()
    {
        $this->diagnosticoModel = new Diagnostico();
        $this->userModel = new User();
    }

    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $roleId = Session::get('user_role_id');
        $medicoId = Auth::id();

        if ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_JEFE) {
            $diagnosticos = $this->diagnosticoModel->getAll();
        }
        else {
            $diagnosticos = $this->diagnosticoModel->getAllByMedico($medicoId);
        }

        $this->render('diagnosticos/index', ['diagnosticos' => $diagnosticos]);
    }

    public function todos()
    {
        if (!Auth::check() || (!Auth::hasRole(Auth::ROLE_SUPERADMIN) && !Auth::hasRole(Auth::ROLE_JEFE))) {
            $this->redirect('/dashboard');
        }
        $diagnosticos = $this->diagnosticoModel->getAll();
        $this->render('diagnosticos/index', ['diagnosticos' => $diagnosticos, 'todos' => true]);
    }

    // Skipped full implementation of create, store, show, edit for brevity but here are stubs:
    public function create()
    {
        $medicos = [];
        $roleId = Session::get('user_role_id');
        if ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_JEFE) {
            $medicos = $this->userModel->getAllDoctors();
        }
        $this->render('diagnosticos/create', ['medicos' => $medicos, 'roleId' => $roleId]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/diagnosticos/create');
        }

        $pacienteInput = trim($_POST['paciente'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $tratamientoStr = trim($_POST['tratamiento'] ?? '');

        if (empty($pacienteInput) || empty($descripcion)) {
            Session::set('error', 'Por favor, complete todos los campos obligatorios.');
            $this->redirect('/diagnosticos/create');
        }

        $pacienteModel = new Paciente();

        $paciente = $pacienteModel->findByIdOrName($pacienteInput);
        $pacienteId = null;

        if ($paciente) {
            $pacienteId = $paciente['id'];
        }
        else {
            $parts = explode(' ', $pacienteInput, 2);
            $nombre = $parts[0];
            $apellido = $parts[1] ?? '';
            $pacienteId = $pacienteModel->create($nombre, $apellido, Auth::id());
        }

        $titulo = substr($descripcion, 0, 50);
        if (strlen($descripcion) > 50)
            $titulo .= '...';

        $codigo = 'DIAG-' . strtoupper(substr(uniqid(), -5));

        $roleId = Session::get('user_role_id');
        $medicoId = Auth::id(); // por defecto a sí mismo

        if (($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_JEFE) && !empty($_POST['medico_id'])) {
            $medicoId = intval($_POST['medico_id']);
        }

        $success = $this->diagnosticoModel->create([
            'paciente_id' => $pacienteId,
            'medico_id' => $medicoId,
            'asignado_por' => Auth::id(),
            'codigo_diagnostico' => $codigo,
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'fecha_diagnostico' => date('Y-m-d'),
            'gravedad' => 'Leve',
            'estado' => 'Activo',
            'created_by' => Auth::id()
        ]);

        if ($success) {
            if (!empty($tratamientoStr)) {
                $diagnosticoId = $this->diagnosticoModel->getLastInsertId();
                if ($diagnosticoId) {
                    $this->diagnosticoModel->addTratamiento($diagnosticoId, $tratamientoStr, Auth::id());
                }
            }
            Session::set('success', 'Diagnóstico guardado correctamente.');
        }
        else {
            Session::set('error', 'Error al guardar el diagnóstico.');
        }

        $this->redirect('/diagnosticos');
    }

    public function show()
    {
        $id = $_GET['id'] ?? 0;
        $diagnostico = $this->diagnosticoModel->findById($id);
        $this->render('diagnosticos/show', ['diagnostico' => $diagnostico]);
    }

    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $diagnostico = $this->diagnosticoModel->findById($id);

        if (!$diagnostico) {
            $this->redirect('/diagnosticos');
        }

        $medicos = [];
        $roleId = Session::get('user_role_id');
        if ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_JEFE) {
            $medicos = $this->userModel->getAllDoctors();
        }

        $this->render('diagnosticos/edit', [
            'diagnostico' => $diagnostico,
            'medicos' => $medicos,
            'roleId' => $roleId
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/diagnosticos');
        }

        $id = intval($_POST['id'] ?? 0);
        $pacienteInput = trim($_POST['paciente'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $tratamientoStr = trim($_POST['tratamiento'] ?? '');

        if (!$id || empty($pacienteInput) || empty($descripcion)) {
            Session::set('error', 'Por favor, complete todos los campos obligatorios.');
            $this->redirect('/diagnosticos/edit?id=' . $id);
        }

        $pacienteModel = new Paciente();
        $paciente = $pacienteModel->findByIdOrName($pacienteInput);
        
        if ($paciente) {
            $pacienteId = $paciente['id'];
        } else {
            $parts = explode(' ', $pacienteInput, 2);
            $nombre = $parts[0];
            $apellido = $parts[1] ?? '';
            $pacienteId = $pacienteModel->create($nombre, $apellido, Auth::id());
        }

        $titulo = substr($descripcion, 0, 50);
        if (strlen($descripcion) > 50) $titulo .= '...';

        $roleId = Session::get('user_role_id');
        $medicoId = null;

        if (($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_JEFE) && !empty($_POST['medico_id'])) {
            $medicoId = intval($_POST['medico_id']);
        }

        $success = $this->diagnosticoModel->update($id, [
            'paciente_id' => $pacienteId,
            'descripcion' => $descripcion,
            'titulo' => $titulo,
            'medico_id' => $medicoId,
            'updated_by' => Auth::id()
        ]);

        if ($success) {
            // Update treatment
            $this->diagnosticoModel->updateTratamiento($id, $tratamientoStr, Auth::id());
            Session::set('success', 'Diagnóstico actualizado correctamente.');
        } else {
            Session::set('error', 'Error al actualizar el diagnóstico.');
        }

        $this->redirect('/diagnosticos');
    }
}
