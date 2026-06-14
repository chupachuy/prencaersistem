<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Mailer.php';
require_once __DIR__ . '/../models/Referencia.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/InformesExploracion.php';
require_once __DIR__ . '/../models/MedicoReferido.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Url.php';
require_once __DIR__ . '/../helpers/Validator.php';

class ReferenciaController extends Controller
{
    private $referenciaModel;
    private $userModel;
    private $medicoReferidoModel;
    private $mailer;

    public function __construct()
    {
        $this->referenciaModel = new Referencia();
        $this->userModel = new User();
        $this->medicoReferidoModel = new MedicoReferido();
        $this->mailer = new Mailer();
    }

    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $roleId = Session::get('user_role_id');
        $filters = [];

        if (isset($_GET['paciente_id'])) {
            $filters['paciente_id'] = intval($_GET['paciente_id']);
        }
        if (isset($_GET['estado'])) {
            $filters['estado'] = $_GET['estado'];
        }

        if ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_ADMINISTRADOR || $roleId == Auth::ROLE_JEFE) {
            $referencias = $this->referenciaModel->getReferenciasCompletas($filters);
        } else {
            $filters['medico_id'] = Auth::id();
            $referencias = $this->referenciaModel->getReferenciasCompletas($filters);
        }

        $this->render('referencias/index', ['referencias' => $referencias]);
    }

    public function create()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $roleId = Session::get('user_role_id');
        $medicos = $this->userModel->getAllDoctors();
        $medicosExternos = $this->medicoReferidoModel->getActivos();
        $userId = Auth::id();

        $pacientes = [];
        $informes = [];

        if ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_ADMINISTRADOR) {
            $pacienteModel = new Paciente();
            $pacientes = $pacienteModel->getAll();
        } elseif ($roleId == Auth::ROLE_JEFE) {
            $pacienteModel = new Paciente();
            $pacientes = $pacienteModel->getAll();
        } else {
            $pacienteModel = new Paciente();
            $pacientes = $pacienteModel->getAllByMedico($userId);
        }

        $informesModel = new InformesExploracion();
        $informes = $informesModel->getAll();

        $this->render('referencias/create', [
            'medicos' => $medicos,
            'medicosExternos' => $medicosExternos,
            'pacientes' => $pacientes,
            'informes' => $informes,
            'roleId' => $roleId,
            'userId' => $userId
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/referencias/create');
        }

        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $roleId = Session::get('user_role_id');
        $userId = Auth::id();

        $pacienteId = intval($_POST['paciente_id'] ?? 0);
        $medicoSolicitanteId = $userId;
        $medicoReferidoTipo = $_POST['medico_referido_tipo'] ?? '';
        $tipoEstudio = trim($_POST['tipo_estudio'] ?? '');
        $motivoReferencia = trim($_POST['motivo_referencia'] ?? '');
        $observaciones = trim($_POST['observaciones'] ?? '');
        $informeExploracionId = !empty($_POST['informe_exploracion_id']) ? intval($_POST['informe_exploracion_id']) : null;

        if ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_ADMINISTRADOR) {
            if (!empty($_POST['medico_solicitante_id'])) {
                $medicoSolicitanteId = intval($_POST['medico_solicitante_id']);
            }
        }

        $medicoReferidoId = null;
        $medicoReferidoExternoId = null;

        if (strpos($medicoReferidoTipo, 'int:') === 0) {
            $medicoReferidoId = intval(substr($medicoReferidoTipo, 4));
        } elseif (strpos($medicoReferidoTipo, 'ext:') === 0) {
            $medicoReferidoExternoId = intval(substr($medicoReferidoTipo, 4));
        }

        if (!$pacienteId || empty($tipoEstudio) || empty($motivoReferencia)) {
            Session::set('error', 'Por favor, complete todos los campos obligatorios.');
            $this->redirect('/referencias/create');
        }

        if (!$medicoReferidoId && !$medicoReferidoExternoId) {
            Session::set('error', 'Debe seleccionar un medico referido.');
            $this->redirect('/referencias/create');
        }

        if ($medicoReferidoId && $medicoSolicitanteId === $medicoReferidoId) {
            Session::set('error', 'No puede referirse a usted mismo.');
            $this->redirect('/referencias/create');
        }

        $referenciaId = $this->referenciaModel->create([
            'paciente_id' => $pacienteId,
            'medico_solicitante_id' => $medicoSolicitanteId,
            'medico_referido_id' => $medicoReferidoId,
            'medico_referido_externo_id' => $medicoReferidoExternoId,
            'tipo_estudio' => $tipoEstudio,
            'motivo_referencia' => $motivoReferencia,
            'observaciones' => $observaciones ?: null,
            'estado' => 'Pendiente',
            'fecha_referencia' => date('Y-m-d'),
            'informe_exploracion_id' => $informeExploracionId,
            'created_by' => $userId,
            'updated_by' => $userId
        ]);

        if ($referenciaId) {
            $pacienteModel = new Paciente();
            $paciente = $pacienteModel->findByIdOrName($pacienteId);
            $medicoSolicitante = $this->userModel->findById($medicoSolicitanteId);

            if ($paciente && $medicoSolicitante) {
                $pacienteNombre = $paciente['nombre'] . ' ' . $paciente['apellido'];
                $solicitanteNombre = 'Dr(a). ' . $medicoSolicitante['nombre'] . ' ' . $medicoSolicitante['apellido'];

                $base = Url::base();
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                $domain = $_SERVER['HTTP_HOST'];

                if ($medicoReferidoId) {
                    $medicoReferido = $this->userModel->findById($medicoReferidoId);
                    if ($medicoReferido && !empty($medicoReferido['email'])) {
                        $link = "{$protocol}://{$domain}{$base}/referencias/responder?id={$referenciaId}";
                        $referidoNombre = $medicoReferido['nombre'];
                        $body = "<h2>Nueva Referencia Medica</h2>
                                 <p>Hola Dr(a). {$referidoNombre},</p>
                                 <p>Ha recibido una nueva referencia medica de <strong>{$solicitanteNombre}</strong>.</p>
                                 <table style='width:100%; border-collapse: collapse; margin: 20px 0;'>
                                     <tr><td style='padding:8px; border-bottom:1px solid #eee; font-weight:bold; width:150px;'>Paciente:</td><td style='padding:8px; border-bottom:1px solid #eee;'>{$pacienteNombre}</td></tr>
                                     <tr><td style='padding:8px; border-bottom:1px solid #eee; font-weight:bold;'>Tipo de estudio:</td><td style='padding:8px; border-bottom:1px solid #eee;'>{$tipoEstudio}</td></tr>
                                     <tr><td style='padding:8px; border-bottom:1px solid #eee; font-weight:bold;'>Motivo:</td><td style='padding:8px; border-bottom:1px solid #eee;'>{$motivoReferencia}</td></tr>
                                     <tr><td style='padding:8px; border-bottom:1px solid #eee; font-weight:bold;'>Fecha:</td><td style='padding:8px; border-bottom:1px solid #eee;'>" . date('d/m/Y') . "</td></tr>
                                 </table>
                                 <a href='{$link}' style='display: inline-block; background: #367d84; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px;'>Ver y Responder Referencia</a>
                                 <p style='margin-top: 20px; font-size: 12px; color: #86868b;'>PreNacer - Sistema de Atencion Prenatal</p>";
                        try {
                            $this->mailer->sendEmail($medicoReferido['email'], "Nueva Referencia Medica - PRENACER", $body);
                        } catch (Exception $e) {
                            error_log("Error al enviar email de referencia: " . $e->getMessage());
                        }
                    }
                } elseif ($medicoReferidoExternoId) {
                    $medicoExterno = $this->medicoReferidoModel->getById($medicoReferidoExternoId);
                    if ($medicoExterno && !empty($medicoExterno['email'])) {
                        $link = "{$protocol}://{$domain}{$base}/referencias/show?id={$referenciaId}";
                        $referidoNombre = $medicoExterno['nombre'];
                        $body = "<h2>Nueva Referencia Medica</h2>
                                 <p>Hola Dr(a). {$referidoNombre},</p>
                                 <p>Ha recibido una nueva referencia medica de <strong>{$solicitanteNombre}</strong> desde <strong>PreNacer</strong>.</p>
                                 <table style='width:100%; border-collapse: collapse; margin: 20px 0;'>
                                     <tr><td style='padding:8px; border-bottom:1px solid #eee; font-weight:bold; width:150px;'>Paciente:</td><td style='padding:8px; border-bottom:1px solid #eee;'>{$pacienteNombre}</td></tr>
                                     <tr><td style='padding:8px; border-bottom:1px solid #eee; font-weight:bold;'>Tipo de estudio:</td><td style='padding:8px; border-bottom:1px solid #eee;'>{$tipoEstudio}</td></tr>
                                     <tr><td style='padding:8px; border-bottom:1px solid #eee; font-weight:bold;'>Motivo:</td><td style='padding:8px; border-bottom:1px solid #eee;'>{$motivoReferencia}</td></tr>
                                     <tr><td style='padding:8px; border-bottom:1px solid #eee; font-weight:bold;'>Fecha:</td><td style='padding:8px; border-bottom:1px solid #eee;'>" . date('d/m/Y') . "</td></tr>
                                 </table>
                                 <p>Esta referencia fue enviada a traves del sistema PreNacer. El medico solicitante gestionara el seguimiento.</p>
                                 <p style='margin-top: 20px; font-size: 12px; color: #86868b;'>PreNacer - Sistema de Atencion Prenatal</p>";
                        try {
                            $this->mailer->sendEmail($medicoExterno['email'], "Nueva Referencia Medica - PRENACER", $body);
                        } catch (Exception $e) {
                            error_log("Error al enviar email de referencia externa: " . $e->getMessage());
                        }
                    }
                }
            }

            Session::set('success', 'Referencia creada correctamente. Se ha notificado al medico.');
            $this->redirect('/referencias');
        } else {
            Session::set('error', 'Error al crear la referencia.');
            $this->redirect('/referencias/create');
        }
    }

    public function show()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = intval($_GET['id'] ?? 0);
        $referencia = $this->referenciaModel->getById($id);

        if (!$referencia) {
            Session::set('error', 'Referencia no encontrada.');
            $this->redirect('/referencias');
        }

        $roleId = Session::get('user_role_id');
        $userId = Auth::id();
        $esExterno = !empty($referencia['medico_referido_externo_id']);

        $isOwner = ($referencia['medico_solicitante_id'] == $userId ||
            ($referencia['medico_referido_id'] == $userId));
        $isAdmin = ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_ADMINISTRADOR || $roleId == Auth::ROLE_JEFE);

        if (!$isOwner && !$isAdmin) {
            Session::set('error', 'No tiene permisos para ver esta referencia.');
            $this->redirect('/referencias');
        }

        $this->render('referencias/show', [
            'referencia' => $referencia,
            'roleId' => $roleId,
            'userId' => $userId,
            'esExterno' => $esExterno
        ]);
    }

    public function responder()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = intval($_GET['id'] ?? 0);
        $referencia = $this->referenciaModel->getById($id);

        if (!$referencia) {
            Session::set('error', 'Referencia no encontrada.');
            $this->redirect('/referencias');
        }

        $roleId = Session::get('user_role_id');
        $isAdmin = ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_ADMINISTRADOR || $roleId == Auth::ROLE_JEFE);

        if ($referencia['medico_referido_id'] != Auth::id() && !$isAdmin) {
            Session::set('error', 'Solo el medico referido puede responder a esta referencia.');
            $this->redirect('/referencias');
        }

        if ($referencia['estado'] !== 'Pendiente') {
            Session::set('error', 'Esta referencia ya fue respondida.');
            $this->redirect('/referencias/show?id=' . $id);
        }

        $this->render('referencias/responder', ['referencia' => $referencia]);
    }

    public function updateRespuesta()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/referencias');
        }

        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = intval($_POST['id'] ?? 0);
        $accion = $_POST['accion'] ?? '';
        $motivo = trim($_POST['respuesta_motivo'] ?? '');

        $referencia = $this->referenciaModel->getById($id);

        if (!$referencia) {
            Session::set('error', 'Referencia no encontrada.');
            $this->redirect('/referencias');
        }

        if ($referencia['medico_referido_id'] != Auth::id()) {
            Session::set('error', 'Solo el medico referido puede responder a esta referencia.');
            $this->redirect('/referencias');
        }

        if ($referencia['estado'] !== 'Pendiente') {
            Session::set('error', 'Esta referencia ya fue respondida.');
            $this->redirect('/referencias');
        }

        if ($accion === 'aceptar') {
            $this->referenciaModel->updateEstado($id, 'Aceptada', date('Y-m-d'));
            Session::set('success', 'Referencia aceptada correctamente.');
        } elseif ($accion === 'rechazar') {
            if (empty($motivo)) {
                Session::set('error', 'Debe indicar el motivo del rechazo.');
                $this->redirect('/referencias/responder?id=' . $id);
            }
            $this->referenciaModel->updateEstado($id, 'Rechazada', date('Y-m-d'), $motivo);
            Session::set('success', 'Referencia rechazada.');
        } else {
            Session::set('error', 'Accion no valida.');
            $this->redirect('/referencias/show?id=' . $id);
        }

        $this->redirect('/referencias/show?id=' . $id);
    }

    public function cambiarEstado()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = intval($_GET['id'] ?? 0);
        $referencia = $this->referenciaModel->getById($id);

        if (!$referencia) {
            Session::set('error', 'Referencia no encontrada.');
            $this->redirect('/referencias');
        }

        $roleId = Session::get('user_role_id');
        $userId = Auth::id();
        $isAdmin = ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_ADMINISTRADOR || $roleId == Auth::ROLE_JEFE);
        $isCreator = ($referencia['created_by'] == $userId);

        if (!$isAdmin && !$isCreator) {
            Session::set('error', 'No tiene permisos para cambiar el estado de esta referencia.');
            $this->redirect('/referencias');
        }

        if ($referencia['estado'] === 'Completada') {
            Session::set('error', 'Esta referencia ya fue completada.');
            $this->redirect('/referencias/show?id=' . $id);
        }

        $this->render('referencias/cambiar-estado', [
            'referencia' => $referencia,
            'isAdmin' => $isAdmin
        ]);
    }

    public function updateEstado()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/referencias');
        }

        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = intval($_POST['id'] ?? 0);
        $nuevoEstado = $_POST['nuevo_estado'] ?? '';
        $motivo = trim($_POST['respuesta_motivo'] ?? '');

        $referencia = $this->referenciaModel->getById($id);

        if (!$referencia) {
            Session::set('error', 'Referencia no encontrada.');
            $this->redirect('/referencias');
        }

        $roleId = Session::get('user_role_id');
        $userId = Auth::id();
        $isAdmin = ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_ADMINISTRADOR || $roleId == Auth::ROLE_JEFE);
        $isCreator = ($referencia['created_by'] == $userId);

        if (!$isAdmin && !$isCreator) {
            Session::set('error', 'No tiene permisos para cambiar el estado de esta referencia.');
            $this->redirect('/referencias');
        }

        $estadosValidos = ['Aceptada', 'Rechazada', 'Completada'];
        if (!in_array($nuevoEstado, $estadosValidos)) {
            Session::set('error', 'Estado no valido.');
            $this->redirect('/referencias/cambiar-estado?id=' . $id);
        }

        if ($nuevoEstado === 'Rechazada' && empty($motivo)) {
            Session::set('error', 'Debe indicar el motivo del rechazo.');
            $this->redirect('/referencias/cambiar-estado?id=' . $id);
        }

        if ($referencia['estado'] === 'Completada') {
            Session::set('error', 'No se puede cambiar el estado de una referencia completada.');
            $this->redirect('/referencias/show?id=' . $id);
        }

        $this->referenciaModel->updateEstado($id, $nuevoEstado, date('Y-m-d'), $motivo ?: null);
        Session::set('success', 'Estado de la referencia actualizado correctamente.');

        $this->redirect('/referencias/show?id=' . $id);
    }
}
