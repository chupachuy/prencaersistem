<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/InformesExploracion.php';
require_once __DIR__ . '/../models/DiagnosticoExploracion.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';

class InformesExploracionController extends Controller
{
    private $informeModel;
    private $mailer;
    private $diagnosticoModel;
    private $userModel;

    public function __construct()
    {
        $this->informeModel = new InformesExploracion();
        $this->mailer = new Mailer();
        $this->diagnosticoModel = new DiagnosticoExploracion();
        $this->userModel = new User();
    }

    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $roleId = Session::get('user_role_id');
        $filters = [];

        if (isset($_GET['paciente_id'])) {
            $filters['paciente_id'] = intval($_GET['paciente_id']);
        }
        if (isset($_GET['trimestre'])) {
            $filters['trimestre'] = intval($_GET['trimestre']);
        }
        if (isset($_GET['estado'])) {
            $filters['estado'] = $_GET['estado'];
        }

        if ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_ADMINISTRADOR || $roleId == Auth::ROLE_JEFE) {
            $informes = $this->informeModel->getInformesCompletos($filters);
        } else {
            $filters['medico_id'] = Auth::id();
            $informes = $this->informeModel->getInformesCompletos($filters);
        }

        $this->render('informes_exploracion/index', ['informes' => $informes]);
    }

    public function create()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $roleId = Session::get('user_role_id');
        $medicos = $this->userModel->getAllDoctors();
        $pacientes = [];

        if ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_ADMINISTRADOR) {
            $pacienteModel = new Paciente();
            $pacientes = $pacienteModel->getAll();
        }

        $this->render('informes_exploracion/create', [
            'medicos' => $medicos,
            'pacientes' => $pacientes,
            'roleId' => $roleId
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/informes_exploracion/create');
            return;
        }

        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $roleId = Session::get('user_role_id');
        $pacienteId = intval($_POST['paciente_id'] ?? 0);
        $trimestre = $_POST['trimestre'] ?? '';
        $medicoId = Auth::id();
        $medicoReferidoId = intval($_POST['medico_referido_id'] ?? 0);

        if ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_ADMINISTRADOR) {
            if (!empty($_POST['medico_referido_id'])) {
                $medicoReferidoId = intval($_POST['medico_referido_id']);
            }
        }

        if (!$pacienteId || empty($trimestre) || !$medicoReferidoId) {
            Session::set('error', 'Por favor, complete todos los campos obligatorios.');
            $this->redirect('/informes_exploracion/create');
            return;
        }

        $informeId = $this->informeModel->crearInforme([
            'paciente_id' => $pacienteId,
            'medico_id' => $medicoId,
            'medico_referido_id' => $medicoReferidoId,
            'trimestre' => $trimestre,
            'fecha_informe' => date('Y-m-d'),
            'estudio_solicitado' => $_POST['estudio_solicitado'] ?? '',
            'estado' => 'Pendiente',
            'created_by' => Auth::id(),
            'updated_by' => Auth::id()
        ]);

        if ($informeId) {
            Session::set('success', 'Informe de exploración creado correctamente.');
            $this->redirect('/informes_exploracion/edit?id=' . $informeId);
        } else {
            Session::set('error', 'Error al crear el informe.');
            $this->redirect('/informes_exploracion/create');
        }
    }

    public function edit()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $id = intval($_GET['id'] ?? 0);
        $informe = $this->informeModel->findById($id);

        if (!$informe) {
            Session::set('error', 'Informe no encontrado.');
            $this->redirect('/informes_exploracion');
            return;
        }

        $diagnosticos = $this->diagnosticoModel->getByInforme($id);
        $medicos = $this->userModel->getAllDoctors();
        $roleId = Session::get('user_role_id');

        $this->render('informes_exploracion/edit', [
            'informe' => $informe,
            'diagnosticos' => $diagnosticos,
            'medicos' => $medicos,
            'roleId' => $roleId
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/informes_exploracion');
            return;
        }

        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        
        $data = [
            'medico_referido_id' => intval($_POST['medico_referido_id'] ?? 0),
            'fecha_informe' => $_POST['fecha_informe'] ?? date('Y-m-d'),
            'estudio_solicitado' => $_POST['estudio_solicitado'] ?? '',
            'fecha_publicacion_parto_usg' => !empty($_POST['fecha_publicacion_parto_usg']) ? $_POST['fecha_publicacion_parto_usg'] : null,
            'fecha_probable_parto_usg' => !empty($_POST['fecha_probable_parto_usg']) ? $_POST['fecha_probable_parto_usg'] : null,
            'resumen_ultrasonido' => $_POST['resumen_ultrasonido'] ?? '',
            'observaciones' => $_POST['observaciones'] ?? '',
            'estado' => $_POST['estado'] ?? 'Pendiente'
        ];

        $success = $this->informeModel->actualizarInforme($id, $data);

        if ($success) {
            if (!empty($_POST['diagnostico_titulo'])) {
                $informe = $this->informeModel->findById($id);
                $this->diagnosticoModel->eliminarPorInforme($id);
                
                foreach ($_POST['diagnostico_titulo'] as $index => $titulo) {
                    if (!empty($titulo)) {
                        $this->diagnosticoModel->crearDiagnostico([
                            'informe_exploracion_id' => $id,
                            'paciente_id' => $informe['paciente_id'],
                            'medico_id' => $informe['medico_referido_id'],
                            'codigo_diagnostico' => $_POST['diagnostico_codigo'][$index] ?? null,
                            'titulo' => $titulo,
                            'descripcion' => $_POST['diagnostico_descripcion'][$index] ?? '',
                            'fecha_diagnostico' => !empty($_POST['diagnostico_fecha'][$index]) ? $_POST['diagnostico_fecha'][$index] : date('Y-m-d')
                        ]);
                    }
                }
            }
            Session::set('success', 'Informe actualizado correctamente.');
        } else {
            Session::set('error', 'Error al actualizar el informe.');
        }

        $this->redirect('/informes_exploracion/edit?id=' . $id);
    }

    public function show()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $id = intval($_GET['id'] ?? 0);
        $informe = $this->informeModel->findById($id);

        if (!$informe) {
            Session::set('error', 'Informe no encontrado.');
            $this->redirect('/informes_exploracion');
            return;
        }

        $diagnosticos = $this->diagnosticoModel->getByInforme($id);
        
        $pacienteModel = new Paciente();
        // BUG-03 FIX: Usar findById() en lugar de findByIdOrName() para búsqueda por ID numérico
        $paciente = $pacienteModel->findById($informe['paciente_id']);
        $medico = $this->userModel->findById($informe['medico_id']);
        $medicoReferido = $this->userModel->findById($informe['medico_referido_id']);

        $this->render('informes_exploracion/show', [
            'informe' => $informe,
            'diagnosticos' => $diagnosticos,
            'paciente' => $paciente,
            'medico' => $medico,
            'medicoReferido' => $medicoReferido
        ]);
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/informes_exploracion');
            return;
        }

        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        if (!Auth::hasRole(Auth::ROLE_SUPERADMIN)) {
            Session::set('error', 'No tiene permisos para eliminar informes.');
            $this->redirect('/informes_exploracion');
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        
        if ($this->informeModel->eliminar($id)) {
            $this->diagnosticoModel->eliminarPorInforme($id);
            Session::set('success', 'Informe eliminado correctamente.');
        } else {
            Session::set('error', 'Error al eliminar el informe.');
        }

        $this->redirect('/informes_exploracion');
    }

    public function porPaciente()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $pacienteId = intval($_GET['paciente_id'] ?? 0);
        
        if (!$pacienteId) {
            Session::set('error', 'Paciente no especificado.');
            $this->redirect('/pacientes');
            return;
        }

        $informes = $this->informeModel->getByPaciente($pacienteId);
        
        $pacienteModel = new Paciente();
        // BUG-03 FIX: Usar findById() en lugar de findByIdOrName() para búsqueda por ID numérico
        $paciente = $pacienteModel->findById($pacienteId);

        $this->render('informes_exploracion/por_paciente', [
            'informes' => $informes,
            'paciente' => $paciente
        ]);
    }

    public function pdf()
    {
        if (!Auth::check()) { $this->redirect("/login"); }
        $id = $_GET["id"] ?? null;
        if (!$id) { $this->redirect("/informes_exploracion"); }
        $informe = $this->informeModel->findById($id);
        if (!$informe) { Session::set("error", "No encontrado."); $this->redirect("/informes_exploracion"); }
        
        $pacienteModel = new Paciente();
        $paciente = $pacienteModel->findById($informe['paciente_id']);
        $medico = $this->userModel->findById($informe['medico_id']);
        $medicoReferido = $this->userModel->findById($informe['medico_referido_id']);
        
        $this->streamPdf("informes_exploracion/imprimir", [
            "informe" => $informe,
            "paciente" => $paciente,
            "medico" => $medico,
            "medicoReferido" => $medicoReferido
        ], $informe["codigo_informe"] . ".pdf");
    }
}