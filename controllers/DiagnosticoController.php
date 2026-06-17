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
    private $pacienteModel;

    public function __construct()
    {
        $this->diagnosticoModel = new Diagnostico();
        $this->userModel = new User();
        // OPT-01: Instanciar Paciente en el constructor en lugar de en cada método
        $this->pacienteModel = new Paciente();
    }

    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $roleId = Session::get('user_role_id');
        $medicoId = Auth::id();

        if ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_JEFE || $roleId == Auth::ROLE_ADMINISTRADOR) {
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
        if (!Auth::check()) {
            $this->redirect('/login');
        }

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
            return;
        }

        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $pacienteInput = trim($_POST['paciente'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $tratamientoStr = trim($_POST['tratamiento'] ?? '');

        if (empty($pacienteInput) || empty($descripcion)) {
            Session::set('error', 'Por favor, complete todos los campos obligatorios.');
            $this->redirect('/diagnosticos/create');
            return;
        }

        // OPT-01: Usar $this->pacienteModel en lugar de instanciar new Paciente()
        $paciente = $this->pacienteModel->findByIdOrName($pacienteInput);
        $pacienteId = null;

        if ($paciente) {
            $pacienteId = $paciente['id'];
        }
        else {
            $parts = explode(' ', $pacienteInput, 2);
            $nombre = $parts[0];
            $apellido = $parts[1] ?? '';
            $pacienteId = $this->pacienteModel->create($nombre, $apellido, Auth::id());
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
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $id = intval($_GET['id'] ?? 0);
        $diagnostico = $this->diagnosticoModel->findById($id);

        // BUG-06: Verificar que el diagnóstico existe antes de renderizar
        if (!$diagnostico) {
            Session::set('error', 'Diagnóstico no encontrado.');
            $this->redirect('/diagnosticos');
            return;
        }

        $this->render('diagnosticos/show', ['diagnostico' => $diagnostico]);
    }

    public function edit()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

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
            return;
        }

        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        $pacienteInput = trim($_POST['paciente'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $tratamientoStr = trim($_POST['tratamiento'] ?? '');

        if (!$id || empty($pacienteInput) || empty($descripcion)) {
            Session::set('error', 'Por favor, complete todos los campos obligatorios.');
            $this->redirect('/diagnosticos/edit?id=' . $id);
            return;
        }

        // OPT-01: Usar $this->pacienteModel en lugar de instanciar new Paciente()
        $paciente = $this->pacienteModel->findByIdOrName($pacienteInput);
        
        if ($paciente) {
            $pacienteId = $paciente['id'];
        } else {
            $parts = explode(' ', $pacienteInput, 2);
            $nombre = $parts[0];
            $apellido = $parts[1] ?? '';
            $pacienteId = $this->pacienteModel->create($nombre, $apellido, Auth::id());
        }

        $titulo = substr($descripcion, 0, 50);
        if (strlen($descripcion) > 50) $titulo .= '...';

        $roleId = Session::get('user_role_id');
        // BUG-09: Usar Auth::id() como fallback para no dejar medico_id en NULL
        $medicoId = Auth::id();

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
