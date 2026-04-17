<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Reporte1erTrimestre.php';
require_once __DIR__ . '/../models/Consulta.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';

class Reporte1erTrimestreController extends Controller
{
    private $reporteModel;

    public function __construct()
    {
        $this->reporteModel = new Reporte1erTrimestre();
    }

    public function create()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $consultaId = $_GET['consulta_id'] ?? null;
        $this->render('reporte_1er_trimestre/create', ['consulta_id' => $consultaId]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/pacientes');
        }

        $id_consulta = isset($_POST['id_consulta']) ? (int) $_POST['id_consulta'] : 0;
        
        $data = [
            'id_consulta' => $id_consulta,
            'fecha_ultima_regla' => !empty($_POST['fecha_ultima_regla']) ? $_POST['fecha_ultima_regla'] : null,
            'fpp_fum' => !empty($_POST['fpp_fum']) ? $_POST['fpp_fum'] : null,
            'fpp_usg' => !empty($_POST['fpp_usg']) ? $_POST['fpp_usg'] : null,
            'longitud_craneo_cauda_mm' => !empty($_POST['longitud_craneo_cauda_mm']) ? $_POST['longitud_craneo_cauda_mm'] : null,
            'translucencia_nucal_mm' => !empty($_POST['translucencia_nucal_mm']) ? $_POST['translucencia_nucal_mm'] : null,
            'frecuencia_cardiaca_fetal' => !empty($_POST['frecuencia_cardiaca_fetal']) ? (int) $_POST['frecuencia_cardiaca_fetal'] : null,
            'longitud_cervical_mm' => !empty($_POST['longitud_cervical_mm']) ? $_POST['longitud_cervical_mm'] : null,
            'riesgo_cromosomopatias' => $_POST['riesgo_cromosomopatias'] ?? null,
            'riesgo_placentaria_temprana' => $_POST['riesgo_placentaria_temprana'] ?? null,
            'riesgo_placentaria_tardia' => $_POST['riesgo_placentaria_tardia'] ?? null,
            'riesgo_parto_pretermino' => $_POST['riesgo_parto_pretermino'] ?? null,
            'hueso_nasal_presente' => isset($_POST['hueso_nasal_presente']) ? 1 : 0,
            'anatomia_craneo_snc_normal' => isset($_POST['anatomia_craneo_snc_normal']) ? 1 : 0,
            'anatomia_corazon_normal' => isset($_POST['anatomia_corazon_normal']) ? 1 : 0,
            'anatomia_abdomen_normal' => isset($_POST['anatomia_abdomen_normal']) ? 1 : 0,
            'anatomia_extremidades_normal' => isset($_POST['anatomia_extremidades_normal']) ? 1 : 0,
            'localizacion_placenta' => !empty($_POST['localizacion_placenta']) ? $_POST['localizacion_placenta'] : null,
            'liquido_amniotico_normal' => isset($_POST['liquido_amniotico_normal']) ? 1 : 0,
            'observaciones_comentarios' => !empty($_POST['observaciones_comentarios']) ? $_POST['observaciones_comentarios'] : null
        ];

        if (empty($data['id_consulta']) || $data['id_consulta'] <= 0) {
            Session::set('error', 'Debe seleccionar una consulta.');
            $this->redirect('/consultas/create');
        }

        try {
            $reporteId = $this->reporteModel->create($data);

            if ($reporteId) {
                Session::set('success', 'Reporte del 1er trimestre guardado correctamente.');
                $this->redirect('/consultas');
            } else {
                Session::set('error', 'Error al guardar el reporte.');
                $this->redirect('/consultas');
            }
        } catch (Exception $e) {
            Session::set('error', 'Error: ' . $e->getMessage());
            $this->redirect('/consultas');
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

        $reporte = $this->reporteModel->getById($id);
        $this->render('reporte_1er_trimestre/show', ['reporte' => $reporte]);
    }

    public function edit()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/consultas');
        }

        $reporte = $this->reporteModel->getById($id);
        
        $consultaModel = new Consulta();
        $consulta = $consultaModel->getByIdWithPaciente($reporte['id_consulta']);
        
        $this->render('reporte_1er_trimestre/edit', ['reporte' => $reporte, 'consulta' => $consulta]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/consultas');
        }

        $id = $_POST['id_reporte_1t'] ?? null;
        if (!$id) {
            $this->redirect('/consultas');
        }

        $data = [
            'id_consulta' => $_POST['id_consulta'] ?? null,
            'fecha_ultima_regla' => !empty($_POST['fecha_ultima_regla']) ? $_POST['fecha_ultima_regla'] : null,
            'fpp_fum' => !empty($_POST['fpp_fum']) ? $_POST['fpp_fum'] : null,
            'fpp_usg' => !empty($_POST['fpp_usg']) ? $_POST['fpp_usg'] : null,
            'longitud_craneo_cauda_mm' => !empty($_POST['longitud_craneo_cauda_mm']) ? $_POST['longitud_craneo_cauda_mm'] : null,
            'translucencia_nucal_mm' => !empty($_POST['translucencia_nucal_mm']) ? $_POST['translucencia_nucal_mm'] : null,
            'frecuencia_cardiaca_fetal' => !empty($_POST['frecuencia_cardiaca_fetal']) ? (int) $_POST['frecuencia_cardiaca_fetal'] : null,
            'longitud_cervical_mm' => !empty($_POST['longitud_cervical_mm']) ? $_POST['longitud_cervical_mm'] : null,
            'riesgo_cromosomopatias' => $_POST['riesgo_cromosomopatias'] ?? null,
            'riesgo_placentaria_temprana' => $_POST['riesgo_placentaria_temprana'] ?? null,
            'riesgo_placentaria_tardia' => $_POST['riesgo_placentaria_tardia'] ?? null,
            'riesgo_parto_pretermino' => $_POST['riesgo_parto_pretermino'] ?? null,
            'hueso_nasal_presente' => isset($_POST['hueso_nasal_presente']) ? 1 : 0,
            'anatomia_craneo_snc_normal' => isset($_POST['anatomia_craneo_snc_normal']) ? 1 : 0,
            'anatomia_corazon_normal' => isset($_POST['anatomia_corazon_normal']) ? 1 : 0,
            'anatomia_abdomen_normal' => isset($_POST['anatomia_abdomen_normal']) ? 1 : 0,
            'anatomia_extremidades_normal' => isset($_POST['anatomia_extremidades_normal']) ? 1 : 0,
            'localizacion_placenta' => !empty($_POST['localizacion_placenta']) ? $_POST['localizacion_placenta'] : null,
            'liquido_amniotico_normal' => isset($_POST['liquido_amniotico_normal']) ? 1 : 0,
            'observaciones_comentarios' => !empty($_POST['observaciones_comentarios']) ? $_POST['observaciones_comentarios'] : null
        ];

        if ($this->reporteModel->update($id, $data)) {
            Session::set('success', 'Reporte actualizado correctamente.');
        } else {
            Session::set('error', 'Error al actualizar el reporte.');
        }

        $this->redirect('/consultas');
    }

    public function print()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/consultas');
        }

        $reporte = $this->reporteModel->getById($id);
        if (!$reporte) {
            $this->redirect('/consultas');
        }

        $consultaModel = new Consulta();
        $consulta = $consultaModel->getByIdWithPaciente($reporte['id_consulta']);

        $user = Session::get('user');
        
        $this->render('reporte_1er_trimestre/print', ['reporte' => $reporte, 'consulta' => $consulta, 'user' => $user]);
    }
}
