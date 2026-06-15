<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';

class ControlesPrenatalesController extends Controller
{
    private $pacienteModel;

    public function __construct()
    {
        $this->pacienteModel = new Paciente();
    }

    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $roleId = Session::get('user_role_id');
        $medicoId = null;

        if ($roleId == Auth::ROLE_MEDICO) {
            $medicoId = Auth::id();
        }

        $pacientes = $this->pacienteModel->getAllWithSeguimiento($medicoId);

        $hoy = new DateTime();
        foreach ($pacientes as &$p) {
            if (!empty($p['fpp_usg'])) {
                $fpp = new DateTime($p['fpp_usg']);
                $diasRestantes = $hoy->diff($fpp)->days;
                $esFuturo = $fpp > $hoy ? 1 : ($fpp < $hoy ? -1 : 0);
                if ($esFuturo === 1 || $esFuturo === 0) {
                    $p['semanas_gestacionales'] = round(40 - ($diasRestantes / 7), 1);
                } else {
                    $p['semanas_gestacionales'] = round(40 + ($diasRestantes / 7), 1);
                }
            } else {
                $p['fpp_usg'] = null;
                $p['semanas_gestacionales'] = null;
            }
        }
        unset($p);

        $this->render('controles_prenatales/index', ['pacientes' => $pacientes]);
    }

    public function alta()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/controles-prenatales');
        }

        $pacienteId = $_POST['paciente_id'] ?? null;
        if (!$pacienteId) {
            Session::set('error', 'Paciente no especificado.');
            $this->redirect('/controles-prenatales');
        }

        $this->pacienteModel->darAlta($pacienteId);
        Session::set('success', 'Paciente dada de alta correctamente.');
        $this->redirect('/controles-prenatales');
    }

    public function updateTipo()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/controles-prenatales');
        }

        $pacienteId = $_POST['paciente_id'] ?? null;
        $tipo = $_POST['tipo_seguimiento'] ?? null;

        if (!$pacienteId || !$tipo) {
            Session::set('error', 'Datos incompletos.');
            $this->redirect('/controles-prenatales');
        }

        $this->pacienteModel->updateTipoSeguimiento($pacienteId, $tipo);
        Session::set('success', 'Tipo de seguimiento actualizado.');
        $this->redirect('/controles-prenatales');
    }
}
