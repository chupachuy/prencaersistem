<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/Asignacion.php';
require_once __DIR__ . '/../models/HistorialClinico.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';

class PacienteController extends Controller
{
    private $pacienteModel;
    private $asignacionModel;
    private $historialModel;

    public function __construct()
    {
        $this->pacienteModel = new Paciente();
        $this->asignacionModel = new Asignacion();
        $this->historialModel = new HistorialClinico();
    }

    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
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
            $this->redirect('/login');
            return;
        }

        $this->render('pacientes/create');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/pacientes/create');
            return;
        }

        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $tipo_seguimiento = trim($_POST['tipo_seguimiento'] ?? 'Propia');

        if (empty($nombre) || empty($apellido) || empty($fecha_nacimiento)) {
            Session::set('error', 'Nombre, Apellido y Fecha de Nacimiento son obligatorios.');
            $this->redirect('/pacientes/create');
            return;
        }

        $pacienteId = $this->pacienteModel->create($nombre, $apellido, Auth::id(), $fecha_nacimiento, $email, $telefono, $direccion, $tipo_seguimiento);

        if ($pacienteId) {
            // Guardar antecedentes obstétricos
            $this->historialModel->create([
                'paciente_id'    => $pacienteId,
                'num_embarazos'  => isset($_POST['num_embarazos'])  && $_POST['num_embarazos']  !== '' ? (int)$_POST['num_embarazos']  : null,
                'num_cesareas'   => isset($_POST['num_cesareas'])   && $_POST['num_cesareas']   !== '' ? (int)$_POST['num_cesareas']   : null,
                'num_abortos'    => isset($_POST['num_abortos'])    && $_POST['num_abortos']    !== '' ? (int)$_POST['num_abortos']    : null,
                'num_ectopicos'  => isset($_POST['num_ectopicos'])  && $_POST['num_ectopicos']  !== '' ? (int)$_POST['num_ectopicos']  : null
            ]);
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

    public function edit($id = null)
    {
        if ($id === null) {
            $id = $_GET['id'] ?? null;
        }

        if (!$id) {
            Session::set('error', 'Paciente no especificado.');
            $this->redirect('/pacientes');
            return;
        }

        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $paciente = $this->pacienteModel->findById($id);
        if (!$paciente) {
            Session::set('error', 'Paciente no encontrado.');
            $this->redirect('/pacientes');
            return;
        }

        $historial = $this->historialModel->getByPaciente($id);

        $this->render('pacientes/edit', [
            'paciente' => $paciente,
            'historial' => $historial
        ]);
    }

    public function update($id = null)
    {
        if ($id === null) {
            $id = $_POST['id'] ?? null;
        }

        if (!$id) {
            Session::set('error', 'Paciente no especificado.');
            $this->redirect('/pacientes');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/pacientes');
            return;
        }

        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $tipo_seguimiento = trim($_POST['tipo_seguimiento'] ?? 'Propia');

        if (empty($nombre) || empty($apellido) || empty($fecha_nacimiento)) {
            Session::set('error', 'Nombre, Apellido y Fecha de Nacimiento son obligatorios.');
            $this->redirect('/pacientes/edit?id=' . $id);
            return;
        }

        $updated = $this->pacienteModel->update($id, [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'fecha_nacimiento' => $fecha_nacimiento,
            'email' => $email,
            'telefono' => $telefono,
            'direccion' => $direccion,
            'tipo_seguimiento' => $tipo_seguimiento,
            'updated_by' => Auth::id()
        ]);

        if ($updated !== false) {
            // BUG-09 FIX: Leer campos del historial desde $_POST en lugar de hardcodear 0
            // Los campos booleans (checkboxes) son 0 si no están en POST
            $this->historialModel->update([
                'paciente_id'    => $id,
                'num_embarazos'  => isset($_POST['num_embarazos'])  && $_POST['num_embarazos']  !== '' ? (int)$_POST['num_embarazos']  : null,
                'num_cesareas'   => isset($_POST['num_cesareas'])   && $_POST['num_cesareas']   !== '' ? (int)$_POST['num_cesareas']   : null,
                'num_abortos'    => isset($_POST['num_abortos'])    && $_POST['num_abortos']    !== '' ? (int)$_POST['num_abortos']    : null,
                'num_ectopicos'  => isset($_POST['num_ectopicos'])  && $_POST['num_ectopicos']  !== '' ? (int)$_POST['num_ectopicos']  : null,
                'hipertension_cronica'           => isset($_POST['hipertension_cronica']) ? 1 : 0,
                'diabetes'                       => isset($_POST['diabetes']) ? 1 : 0,
                'lupus_les'                      => isset($_POST['lupus_les']) ? 1 : 0,
                'sindrome_antifosfolipido_saf'   => isset($_POST['sindrome_antifosfolipido_saf']) ? 1 : 0,
                'antecedente_preeclampsia_rciu'  => isset($_POST['antecedente_preeclampsia_rciu']) ? 1 : 0,
                'fertilizacion_in_vitro'         => isset($_POST['fertilizacion_in_vitro']) ? 1 : 0,
                'antecedente_parto_pretermino'   => isset($_POST['antecedente_parto_pretermino']) ? 1 : 0
            ]);

            Session::set('success', 'Paciente actualizado correctamente.');
            $this->redirect('/pacientes');
        } else {
            Session::set('error', 'Error al actualizar el paciente.');
            $this->redirect('/pacientes/edit?id=' . $id);
        }
    }
}
