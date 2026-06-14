<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/UltrasonidoTemprano.php';
require_once __DIR__ . '/../models/EmbrioTemprano.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Validator.php';

class UltrasonidoTempranoController extends Controller
{
    private $ultrasonidoModel;
    private $mailer;
    private $embrionModel;
    private $pacienteModel;
    private $userModel;

    public function __construct()
    {
        $this->ultrasonidoModel = new UltrasonidoTemprano();
        $this->mailer = new Mailer();
        $this->embrionModel = new EmbrioTemprano();
        $this->pacienteModel = new Paciente();
        $this->userModel = new User();
    }

    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $user = Auth::user();
        $medicoId = ($user['rol_id'] == Auth::ROLE_MEDICO) ? $user['id'] : null;
        $evaluaciones = $this->ultrasonidoModel->getAll($medicoId);

        $this->render('ultrasonido_temprano/index', [
            'evaluaciones' => $evaluaciones
        ]);
    }

    public function create()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $codigo_reporte = $this->ultrasonidoModel->generateCodigoReporte();
        $pacientes = $this->pacienteModel->getAll();
        $medicos = $this->userModel->getMedicos();

        $this->render('ultrasonido_temprano/create', [
            'codigo_reporte' => $codigo_reporte,
            'pacientes' => $pacientes,
            'medicos' => $medicos
        ]);
    }

    public function store()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/ultrasonido_temprano/create');
            return;
        }

        $userId = Auth::id();

        $data = [
            'paciente_id' => $_POST['paciente_id'] ?? null,
            'medico_id' => $_POST['medico_id'] ?? null,
            'codigo_reporte' => $_POST['codigo_reporte'] ?? null,
            'fecha_estudio' => $_POST['fecha_estudio'] ?? null,
            'edad' => $_POST['edad'] ?? null,
            'fum' => $_POST['fum'] ?: null,
            'edad_gest_semanas' => $_POST['edad_gest_semanas'] ?? null,
            'edad_gest_dias' => $_POST['edad_gest_dias'] ?? null,

            'indic_confirmacion_embarazo' => isset($_POST['indic_confirmacion_embarazo']) ? 1 : 0,
            'indic_sangrado' => isset($_POST['indic_sangrado']) ? 1 : 0,
            'indic_dolor_pelvico' => isset($_POST['indic_dolor_pelvico']) ? 1 : 0,
            'indic_viabilidad' => isset($_POST['indic_viabilidad']) ? 1 : 0,
            'indic_perdidas_gestacionales' => isset($_POST['indic_perdidas_gestacionales']) ? 1 : 0,
            'indic_reproduccion_asistida' => isset($_POST['indic_reproduccion_asistida']) ? 1 : 0,
            'indic_otro' => $_POST['indic_otro'] ?: null,

            'via_transvaginal' => isset($_POST['via_transvaginal']) ? 1 : 0,
            'via_transabdominal' => isset($_POST['via_transabdominal']) ? 1 : 0,
            'via_ambas' => isset($_POST['via_ambas']) ? 1 : 0,

            'utero_posicion' => $_POST['utero_posicion'] ?? null,
            'utero_contornos_regulares' => isset($_POST['utero_contornos_regulares']) ? 1 : 0,
            'utero_ecogenicidad_conservada' => isset($_POST['utero_ecogenicidad_conservada']) ? 1 : 0,
            'utero_dim_x' => $_POST['utero_dim_x'] ?: null,
            'utero_dim_y' => $_POST['utero_dim_y'] ?: null,
            'utero_dim_z' => $_POST['utero_dim_z'] ?: null,
            'endometrio' => $_POST['endometrio'] ?: null,

            'localizacion' => $_POST['localizacion'] ?? null,
            'localizacion_otra' => $_POST['localizacion_otra'] ?: null,

            'sg_tipo' => $_POST['sg_tipo'] ?? null,
            'sg_morfologia' => $_POST['sg_morfologia'] ?? null,
            'sg_medida_mm' => $_POST['sg_medida_mm'] ?: null,

            'sv_presente' => isset($_POST['sv_presente']) ? ($_POST['sv_presente'] === '1' ? 1 : 0) : null,
            'sv_cantidad' => $_POST['sv_cantidad'] ?: null,
            'sv_diametro_mm' => $_POST['sv_diametro_mm'] ?: null,

            'corion_amnios_normal' => isset($_POST['corion_amnios_normal']) ? 1 : 0,

            'ovario_der_dim_x' => $_POST['ovario_der_dim_x'] ?: null,
            'ovario_der_dim_y' => $_POST['ovario_der_dim_y'] ?: null,
            'ovario_der_dim_z' => $_POST['ovario_der_dim_z'] ?: null,
            'ovario_der_normal' => isset($_POST['ovario_der_normal']) ? 1 : 0,
            'ovario_der_cuerpo_luteo_mm' => $_POST['ovario_der_cuerpo_luteo_mm'] ?: null,
            'ovario_der_quiste_simple_mm' => $_POST['ovario_der_quiste_simple_mm'] ?: null,
            'ovario_der_otra_alteracion' => $_POST['ovario_der_otra_alteracion'] ?: null,

            'ovario_izq_dim_x' => $_POST['ovario_izq_dim_x'] ?: null,
            'ovario_izq_dim_y' => $_POST['ovario_izq_dim_y'] ?: null,
            'ovario_izq_dim_z' => $_POST['ovario_izq_dim_z'] ?: null,
            'ovario_izq_normal' => isset($_POST['ovario_izq_normal']) ? 1 : 0,
            'ovario_izq_cuerpo_luteo_mm' => $_POST['ovario_izq_cuerpo_luteo_mm'] ?: null,
            'ovario_izq_quiste_simple_mm' => $_POST['ovario_izq_quiste_simple_mm'] ?: null,
            'ovario_izq_otra_alteracion' => $_POST['ovario_izq_otra_alteracion'] ?: null,

            'douglas' => $_POST['douglas'] ?? null,

            'hematoma_subcorionico' => isset($_POST['hematoma_subcorionico']) ? 1 : 0,
            'hematoma_localizacion' => $_POST['hematoma_localizacion'] ?: null,
            'hematoma_dim_x' => $_POST['hematoma_dim_x'] ?: null,
            'hematoma_dim_y' => $_POST['hematoma_dim_y'] ?: null,
            'hematoma_dim_z' => $_POST['hematoma_dim_z'] ?: null,
            'hematoma_volumen_ml' => $_POST['hematoma_volumen_ml'] ?: null,

            'miomas_uterinos' => isset($_POST['miomas_uterinos']) ? 1 : 0,
            'adenomiosis' => isset($_POST['adenomiosis']) ? 1 : 0,
            'malformacion_uterina' => isset($_POST['malformacion_uterina']) ? 1 : 0,
            'hallazgos_otro' => $_POST['hallazgos_otro'] ?: null,

            'impresion_crl_mm' => $_POST['impresion_crl_mm'] ?: null,
            'impresion_semanas' => $_POST['impresion_semanas'] ?: null,
            'impresion_dias' => $_POST['impresion_dias'] ?: null,
            'impresion_fcf_lpm' => $_POST['impresion_fcf_lpm'] ?: null,
            'impresion_texto' => $_POST['impresion_texto'] ?: null,

            'estado' => $_POST['estado'] ?? 'Pendiente',
            'created_by' => $userId,
            'updated_by' => $userId
        ];

        $errors = [];
        if (empty($data['paciente_id'])) $errors[] = 'El paciente es obligatorio.';
        if (empty($data['medico_id'])) $errors[] = 'El médico es obligatorio.';
        if (empty($data['fecha_estudio'])) $errors[] = 'La fecha de estudio es obligatoria.';

        if (!empty($errors)) {
            Session::set('error', implode('<br>', $errors));
            $this->redirect('/ultrasonido_temprano/create');
            return;
        }

        $id = $this->ultrasonidoModel->create($data);

        $embrionVisible = isset($_POST['embrion_visible']) && $_POST['embrion_visible'] === '1';
        if ($embrionVisible) {
            $numEmbriones = max(1, min(3, intval($_POST['num_embriones'] ?? 1)));
            for ($i = 1; $i <= $numEmbriones; $i++) {
                $embData = [
                    'ultrasonido_id' => $id,
                    'numero' => $i,
                    'crl_mm' => $_POST["embrion_{$i}_crl"] ?: null,
                    'fcf_visible' => isset($_POST["embrion_{$i}_fcf_visible"]) ? 1 : 0,
                    'fcf_lpm' => $_POST["embrion_{$i}_fcf_lpm"] ?: null,
                    'localizacion' => $_POST["embrion_{$i}_localizacion"] ?: null
                ];
                $this->embrionModel->create($embData);
            }
        }

        Session::set('success', 'Ultrasonido temprano registrado correctamente.');
        $this->redirect('/ultrasonido_temprano');
    }

    public function show()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/ultrasonido_temprano');
            return;
        }

        $evaluacion = $this->ultrasonidoModel->getById($id);
        if (!$evaluacion) {
            Session::set('error', 'Ultrasonido no encontrado.');
            $this->redirect('/ultrasonido_temprano');
            return;
        }

        $user = Auth::user();
        if ($user['rol_id'] == Auth::ROLE_MEDICO && $evaluacion['medico_id'] != $user['id']) {
            Session::set('error', 'No tienes permiso para ver este ultrasonido.');
            $this->redirect('/ultrasonido_temprano');
            return;
        }

        $embriones = $this->embrionModel->getByUltrasonido($id);

        $this->render('ultrasonido_temprano/show', [
            'evaluacion' => $evaluacion,
            'embriones' => $embriones
        ]);
    }

    public function edit()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/ultrasonido_temprano');
            return;
        }

        $evaluacion = $this->ultrasonidoModel->getById($id);
        if (!$evaluacion) {
            Session::set('error', 'Ultrasonido no encontrado.');
            $this->redirect('/ultrasonido_temprano');
            return;
        }

        $user = Auth::user();
        if ($user['rol_id'] == Auth::ROLE_MEDICO && $evaluacion['medico_id'] != $user['id']) {
            Session::set('error', 'No tienes permiso para editar este ultrasonido.');
            $this->redirect('/ultrasonido_temprano');
            return;
        }

        $embriones = $this->embrionModel->getByUltrasonido($id);
        $pacientes = $this->pacienteModel->getAll();
        $medicos = $this->userModel->getMedicos();

        $this->render('ultrasonido_temprano/edit', [
            'evaluacion' => $evaluacion,
            'embriones' => $embriones,
            'pacientes' => $pacientes,
            'medicos' => $medicos
        ]);
    }

    public function update()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/ultrasonido_temprano');
            return;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $this->redirect('/ultrasonido_temprano');
            return;
        }

        $evaluacion = $this->ultrasonidoModel->getById($id);
        if (!$evaluacion) {
            Session::set('error', 'Ultrasonido no encontrado.');
            $this->redirect('/ultrasonido_temprano');
            return;
        }

        $user = Auth::user();
        if ($user['rol_id'] == Auth::ROLE_MEDICO && $evaluacion['medico_id'] != $user['id']) {
            Session::set('error', 'No tienes permiso para editar este ultrasonido.');
            $this->redirect('/ultrasonido_temprano');
            return;
        }

        $userId = Auth::id();

        $data = [
            'id' => $id,
            'paciente_id' => $_POST['paciente_id'] ?? null,
            'medico_id' => $_POST['medico_id'] ?? null,
            'fecha_estudio' => $_POST['fecha_estudio'] ?? null,
            'edad' => $_POST['edad'] ?? null,
            'fum' => $_POST['fum'] ?: null,
            'edad_gest_semanas' => $_POST['edad_gest_semanas'] ?? null,
            'edad_gest_dias' => $_POST['edad_gest_dias'] ?? null,

            'indic_confirmacion_embarazo' => isset($_POST['indic_confirmacion_embarazo']) ? 1 : 0,
            'indic_sangrado' => isset($_POST['indic_sangrado']) ? 1 : 0,
            'indic_dolor_pelvico' => isset($_POST['indic_dolor_pelvico']) ? 1 : 0,
            'indic_viabilidad' => isset($_POST['indic_viabilidad']) ? 1 : 0,
            'indic_perdidas_gestacionales' => isset($_POST['indic_perdidas_gestacionales']) ? 1 : 0,
            'indic_reproduccion_asistida' => isset($_POST['indic_reproduccion_asistida']) ? 1 : 0,
            'indic_otro' => $_POST['indic_otro'] ?: null,

            'via_transvaginal' => isset($_POST['via_transvaginal']) ? 1 : 0,
            'via_transabdominal' => isset($_POST['via_transabdominal']) ? 1 : 0,
            'via_ambas' => isset($_POST['via_ambas']) ? 1 : 0,

            'utero_posicion' => $_POST['utero_posicion'] ?? null,
            'utero_contornos_regulares' => isset($_POST['utero_contornos_regulares']) ? 1 : 0,
            'utero_ecogenicidad_conservada' => isset($_POST['utero_ecogenicidad_conservada']) ? 1 : 0,
            'utero_dim_x' => $_POST['utero_dim_x'] ?: null,
            'utero_dim_y' => $_POST['utero_dim_y'] ?: null,
            'utero_dim_z' => $_POST['utero_dim_z'] ?: null,
            'endometrio' => $_POST['endometrio'] ?: null,

            'localizacion' => $_POST['localizacion'] ?? null,
            'localizacion_otra' => $_POST['localizacion_otra'] ?: null,

            'sg_tipo' => $_POST['sg_tipo'] ?? null,
            'sg_morfologia' => $_POST['sg_morfologia'] ?? null,
            'sg_medida_mm' => $_POST['sg_medida_mm'] ?: null,

            'sv_presente' => isset($_POST['sv_presente']) ? ($_POST['sv_presente'] === '1' ? 1 : 0) : null,
            'sv_cantidad' => $_POST['sv_cantidad'] ?: null,
            'sv_diametro_mm' => $_POST['sv_diametro_mm'] ?: null,

            'corion_amnios_normal' => isset($_POST['corion_amnios_normal']) ? 1 : 0,

            'ovario_der_dim_x' => $_POST['ovario_der_dim_x'] ?: null,
            'ovario_der_dim_y' => $_POST['ovario_der_dim_y'] ?: null,
            'ovario_der_dim_z' => $_POST['ovario_der_dim_z'] ?: null,
            'ovario_der_normal' => isset($_POST['ovario_der_normal']) ? 1 : 0,
            'ovario_der_cuerpo_luteo_mm' => $_POST['ovario_der_cuerpo_luteo_mm'] ?: null,
            'ovario_der_quiste_simple_mm' => $_POST['ovario_der_quiste_simple_mm'] ?: null,
            'ovario_der_otra_alteracion' => $_POST['ovario_der_otra_alteracion'] ?: null,

            'ovario_izq_dim_x' => $_POST['ovario_izq_dim_x'] ?: null,
            'ovario_izq_dim_y' => $_POST['ovario_izq_dim_y'] ?: null,
            'ovario_izq_dim_z' => $_POST['ovario_izq_dim_z'] ?: null,
            'ovario_izq_normal' => isset($_POST['ovario_izq_normal']) ? 1 : 0,
            'ovario_izq_cuerpo_luteo_mm' => $_POST['ovario_izq_cuerpo_luteo_mm'] ?: null,
            'ovario_izq_quiste_simple_mm' => $_POST['ovario_izq_quiste_simple_mm'] ?: null,
            'ovario_izq_otra_alteracion' => $_POST['ovario_izq_otra_alteracion'] ?: null,

            'douglas' => $_POST['douglas'] ?? null,

            'hematoma_subcorionico' => isset($_POST['hematoma_subcorionico']) ? 1 : 0,
            'hematoma_localizacion' => $_POST['hematoma_localizacion'] ?: null,
            'hematoma_dim_x' => $_POST['hematoma_dim_x'] ?: null,
            'hematoma_dim_y' => $_POST['hematoma_dim_y'] ?: null,
            'hematoma_dim_z' => $_POST['hematoma_dim_z'] ?: null,
            'hematoma_volumen_ml' => $_POST['hematoma_volumen_ml'] ?: null,

            'miomas_uterinos' => isset($_POST['miomas_uterinos']) ? 1 : 0,
            'adenomiosis' => isset($_POST['adenomiosis']) ? 1 : 0,
            'malformacion_uterina' => isset($_POST['malformacion_uterina']) ? 1 : 0,
            'hallazgos_otro' => $_POST['hallazgos_otro'] ?: null,

            'impresion_crl_mm' => $_POST['impresion_crl_mm'] ?: null,
            'impresion_semanas' => $_POST['impresion_semanas'] ?: null,
            'impresion_dias' => $_POST['impresion_dias'] ?: null,
            'impresion_fcf_lpm' => $_POST['impresion_fcf_lpm'] ?: null,
            'impresion_texto' => $_POST['impresion_texto'] ?: null,

            'estado' => $_POST['estado'] ?? 'Pendiente',
            'updated_by' => $userId
        ];

        $errors = [];
        if (empty($data['paciente_id'])) $errors[] = 'El paciente es obligatorio.';
        if (empty($data['medico_id'])) $errors[] = 'El médico es obligatorio.';
        if (empty($data['fecha_estudio'])) $errors[] = 'La fecha de estudio es obligatoria.';

        if (!empty($errors)) {
            Session::set('error', implode('<br>', $errors));
            $this->redirect('/ultrasonido_temprano/edit?id=' . $id);
            return;
        }

        $this->ultrasonidoModel->update($data);

        $this->embrionModel->deleteByUltrasonido($id);
        $embrionVisible = isset($_POST['embrion_visible']) && $_POST['embrion_visible'] === '1';
        if ($embrionVisible) {
            $numEmbriones = max(1, min(3, intval($_POST['num_embriones'] ?? 1)));
            for ($i = 1; $i <= $numEmbriones; $i++) {
                $embData = [
                    'ultrasonido_id' => $id,
                    'numero' => $i,
                    'crl_mm' => $_POST["embrion_{$i}_crl"] ?: null,
                    'fcf_visible' => isset($_POST["embrion_{$i}_fcf_visible"]) ? 1 : 0,
                    'fcf_lpm' => $_POST["embrion_{$i}_fcf_lpm"] ?: null,
                    'localizacion' => $_POST["embrion_{$i}_localizacion"] ?: null
                ];
                $this->embrionModel->create($embData);
            }
        }

        Session::set('success', 'Ultrasonido temprano actualizado correctamente.');
        $this->redirect('/ultrasonido_temprano');
    }

    public function delete()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/ultrasonido_temprano');
            return;
        }

        $evaluacion = $this->ultrasonidoModel->getById($id);
        if (!$evaluacion) {
            Session::set('error', 'Ultrasonido no encontrado.');
            $this->redirect('/ultrasonido_temprano');
            return;
        }

        $user = Auth::user();
        if ($user['rol_id'] == Auth::ROLE_MEDICO) {
            Session::set('error', 'No tienes permiso para eliminar registros.');
            $this->redirect('/ultrasonido_temprano');
            return;
        }

        $this->ultrasonidoModel->delete($id);
        Session::set('success', 'Ultrasonido temprano eliminado correctamente.');
        $this->redirect('/ultrasonido_temprano');
    }

    public function print()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/ultrasonido_temprano');
            return;
        }

        $evaluacion = $this->ultrasonidoModel->getById($id);
        if (!$evaluacion) {
            Session::set('error', 'Ultrasonido no encontrado.');
            $this->redirect('/ultrasonido_temprano');
            return;
        }

        $user = Auth::user();
        if ($user['rol_id'] == Auth::ROLE_MEDICO && $evaluacion['medico_id'] != $user['id']) {
            Session::set('error', 'No tienes permiso para imprimir este ultrasonido.');
            $this->redirect('/ultrasonido_temprano');
            return;
        }

        $embriones = $this->embrionModel->getByUltrasonido($id);

        $this->render('ultrasonido_temprano/print', [
            'evaluacion' => $evaluacion,
            'embriones' => $embriones
        ]);
    }

    public function pdf()
    {
        if (!Auth::check()) { $this->redirect("/login"); }
        $id = $_GET["id"] ?? null;
        if (!$id) { $this->redirect("/ultrasonido_temprano"); }
        $evaluacion = $this->ultrasonidoModel->getById($id);
        if (!$evaluacion) { Session::set("error", "No encontrado."); $this->redirect("/ultrasonido_temprano"); }
        $this->streamPdf("ultrasonido_temprano/imprimir", ["evaluacion" => $evaluacion], $evaluacion["codigo_reporte"] . ".pdf");
    }
}