<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/EvaluacionGinecologica.php';
require_once __DIR__ . '/../models/IndicacionesGinecologicas.php';
require_once __DIR__ . '/../models/AntecedentesGinecologicos.php';
require_once __DIR__ . '/../models/TecnicaUltrasonidoGinecologico.php';
require_once __DIR__ . '/../models/UteroCervixGinecologico.php';
require_once __DIR__ . '/../models/MiomasGinecologicos.php';
require_once __DIR__ . '/../models/MiomasDetalleGinecologico.php';
require_once __DIR__ . '/../models/AdenomiosisGinecologica.php';
require_once __DIR__ . '/../models/EndometrioGinecologico.php';
require_once __DIR__ . '/../models/OvariosGinecologicos.php';
require_once __DIR__ . '/../models/AnexosFondoSacoGinecologico.php';
require_once __DIR__ . '/../models/ClasificacionOrientativaGinecologica.php';
require_once __DIR__ . '/../models/ImpresionDiagnosticaGinecologica.php';
require_once __DIR__ . '/../models/ConclusionRecomendacionesGinecologicas.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';

class EvaluacionGinecologicaController extends Controller
{
    private $evaluacionModel;
    private $indicacionesModel;
    private $antecedentesModel;
    private $tecnicaModel;
    private $uteroCervixModel;
    private $miomasModel;
    private $miomasDetalleModel;
    private $adenomiosisModel;
    private $endometrioModel;
    private $ovariosModel;
    private $anexosModel;
    private $clasificacionModel;
    private $impresionModel;
    private $conclusionModel;
    private $pacienteModel;
    private $userModel;

    public function __construct()
    {
        $this->evaluacionModel = new EvaluacionGinecologica();
        $this->indicacionesModel = new IndicacionesGinecologicas();
        $this->antecedentesModel = new AntecedentesGinecologicos();
        $this->tecnicaModel = new TecnicaUltrasonidoGinecologico();
        $this->uteroCervixModel = new UteroCervixGinecologico();
        $this->miomasModel = new MiomasGinecologicos();
        $this->miomasDetalleModel = new MiomasDetalleGinecologico();
        $this->adenomiosisModel = new AdenomiosisGinecologica();
        $this->endometrioModel = new EndometrioGinecologico();
        $this->ovariosModel = new OvariosGinecologicos();
        $this->anexosModel = new AnexosFondoSacoGinecologico();
        $this->clasificacionModel = new ClasificacionOrientativaGinecologica();
        $this->impresionModel = new ImpresionDiagnosticaGinecologica();
        $this->conclusionModel = new ConclusionRecomendacionesGinecologicas();
        $this->pacienteModel = new Paciente();
        $this->userModel = new User();
    }

    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
        $medicoId = Auth::hasRole(Auth::ROLE_MEDICO) ? Auth::id() : null;
        $evaluaciones = $this->evaluacionModel->getAll($medicoId);
        $this->render('evaluaciones_ginecologicas/index', ['evaluaciones' => $evaluaciones]);
    }

    public function create()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
        $pacienteId = $_GET['paciente_id'] ?? null;
        $pacientes = $this->pacienteModel->getAll();
        $medicos = $this->userModel->getMedicos();
        $codigoReporte = $this->evaluacionModel->generateCodigoReporte();
        $this->render('evaluaciones_ginecologicas/create', [
            'pacientes' => $pacientes,
            'medicos' => $medicos,
            'paciente_id' => $pacienteId,
            'codigo_reporte' => $codigoReporte
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/evaluaciones_ginecologicas');
        }
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $userId = Auth::id();
        $pacienteId = (int) ($_POST['paciente_id'] ?? 0);
        $medicoId = (int) ($_POST['medico_id'] ?? 0);

        if (empty($pacienteId) || empty($medicoId)) {
            Session::set('error', 'Debe seleccionar un paciente y un médico.');
            $this->redirect('/evaluaciones_ginecologicas/create');
        }

        $dataEvaluacion = [
            'paciente_id' => $pacienteId,
            'medico_id' => $medicoId,
            'medico_solicitante_id' => !empty($_POST['medico_solicitante_id']) ? (int)$_POST['medico_solicitante_id'] : null,
            'codigo_reporte' => $_POST['codigo_reporte'],
            'fecha_estudio' => !empty($_POST['fecha_estudio']) ? $_POST['fecha_estudio'] : date('Y-m-d'),
            'indicacion_clinica' => $_POST['indicacion_clinica'] ?? null,
            'fum' => $_POST['fum'] ?? null,
            'dia_ciclo_menstrual' => $_POST['dia_ciclo_menstrual'] ?? null,
            'observaciones' => $_POST['observaciones'] ?? null,
            'estado' => $_POST['estado'] ?? 'Pendiente',
            'created_by' => $userId,
            'updated_by' => $userId
        ];

        try {
            $evaluacionId = $this->evaluacionModel->create($dataEvaluacion);
            if (!$evaluacionId) {
                Session::set('error', 'Error al guardar la evaluación.');
                $this->redirect('/evaluaciones_ginecologicas/create');
            }

            $this->indicacionesModel->create($this->buildIndicacionesData($evaluacionId));
            $this->antecedentesModel->create($this->buildAntecedentesData($evaluacionId));
            $this->tecnicaModel->create($this->buildTecnicaData($evaluacionId));
            $this->uteroCervixModel->create($this->buildUteroCervixData($evaluacionId));
            $this->miomasModel->create($this->buildMiomasData($evaluacionId));
            $this->saveMiomasDetalle($evaluacionId);
            $this->adenomiosisModel->create($this->buildAdenomiosisData($evaluacionId));
            $this->endometrioModel->create($this->buildEndometrioData($evaluacionId));
            $this->ovariosModel->create($this->buildOvariosData($evaluacionId));
            $this->anexosModel->create($this->buildAnexosData($evaluacionId));
            $this->clasificacionModel->create($this->buildClasificacionData($evaluacionId));
            $this->impresionModel->create($this->buildImpresionData($evaluacionId));
            $this->conclusionModel->create($this->buildConclusionData($evaluacionId));

            Session::set('success', 'Ultrasonido Ginecológico guardado correctamente.');
            $this->redirect('/evaluaciones_ginecologicas');

        } catch (Exception $e) {
            Session::set('error', 'Error: ' . $e->getMessage());
            $this->redirect('/evaluaciones_ginecologicas/create');
        }
    }

    public function show()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/evaluaciones_ginecologicas');
        }
        $evaluacion = $this->evaluacionModel->getById($id);
        if (!$evaluacion) {
            Session::set('error', 'Evaluación no encontrada.');
            $this->redirect('/evaluaciones_ginecologicas');
        }
        if (Auth::hasRole(Auth::ROLE_MEDICO) && $evaluacion['medico_id'] != Auth::id()) {
            Session::set('error', 'No tienes permiso para ver esta evaluación.');
            $this->redirect('/evaluaciones_ginecologicas');
        }

        $this->render('evaluaciones_ginecologicas/show', [
            'evaluacion' => $evaluacion,
            'indicaciones' => $this->indicacionesModel->getByEvaluacion($id),
            'antecedentes' => $this->antecedentesModel->getByEvaluacion($id),
            'tecnica' => $this->tecnicaModel->getByEvaluacion($id),
            'uteroCervix' => $this->uteroCervixModel->getByEvaluacion($id),
            'miomas' => $this->miomasModel->getByEvaluacion($id),
            'miomasDetalle' => $this->miomasDetalleModel->getByEvaluacion($id),
            'adenomiosis' => $this->adenomiosisModel->getByEvaluacion($id),
            'endometrio' => $this->endometrioModel->getByEvaluacion($id),
            'ovarios' => $this->ovariosModel->getByEvaluacion($id),
            'anexos' => $this->anexosModel->getByEvaluacion($id),
            'clasificacion' => $this->clasificacionModel->getByEvaluacion($id),
            'impresion' => $this->impresionModel->getByEvaluacion($id),
            'conclusion' => $this->conclusionModel->getByEvaluacion($id)
        ]);
    }

    public function edit()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/evaluaciones_ginecologicas');
        }
        $evaluacion = $this->evaluacionModel->getById($id);
        if (!$evaluacion) {
            Session::set('error', 'Evaluación no encontrada.');
            $this->redirect('/evaluaciones_ginecologicas');
        }
        if (Auth::hasRole(Auth::ROLE_MEDICO) && $evaluacion['medico_id'] != Auth::id()) {
            Session::set('error', 'No tienes permiso para editar esta evaluación.');
            $this->redirect('/evaluaciones_ginecologicas');
        }

        $this->render('evaluaciones_ginecologicas/edit', [
            'evaluacion' => $evaluacion,
            'pacientes' => $this->pacienteModel->getAll(),
            'medicos' => $this->userModel->getMedicos(),
            'indicaciones' => $this->indicacionesModel->getByEvaluacion($id),
            'antecedentes' => $this->antecedentesModel->getByEvaluacion($id),
            'tecnica' => $this->tecnicaModel->getByEvaluacion($id),
            'uteroCervix' => $this->uteroCervixModel->getByEvaluacion($id),
            'miomas' => $this->miomasModel->getByEvaluacion($id),
            'miomasDetalle' => $this->miomasDetalleModel->getByEvaluacion($id),
            'adenomiosis' => $this->adenomiosisModel->getByEvaluacion($id),
            'endometrio' => $this->endometrioModel->getByEvaluacion($id),
            'ovarios' => $this->ovariosModel->getByEvaluacion($id),
            'anexos' => $this->anexosModel->getByEvaluacion($id),
            'clasificacion' => $this->clasificacionModel->getByEvaluacion($id),
            'impresion' => $this->impresionModel->getByEvaluacion($id),
            'conclusion' => $this->conclusionModel->getByEvaluacion($id)
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/evaluaciones_ginecologicas');
        }
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if (!$id) {
            $this->redirect('/evaluaciones_ginecologicas');
        }

        $evaluacion = $this->evaluacionModel->getById($id);
        if (Auth::hasRole(Auth::ROLE_MEDICO) && (!$evaluacion || $evaluacion['medico_id'] != Auth::id())) {
            Session::set('error', 'No tienes permiso para modificar esta evaluación.');
            $this->redirect('/evaluaciones_ginecologicas');
        }

        $userId = Auth::id();
        $pacienteId = (int) ($_POST['paciente_id'] ?? 0);
        $medicoId = (int) ($_POST['medico_id'] ?? 0);

        if (empty($pacienteId) || empty($medicoId)) {
            Session::set('error', 'Debe seleccionar un paciente y un médico.');
            $this->redirect('/evaluaciones_ginecologicas/edit?id=' . $id);
        }

        $dataEvaluacion = [
            'id' => $id,
            'paciente_id' => $pacienteId,
            'medico_id' => $medicoId,
            'medico_solicitante_id' => !empty($_POST['medico_solicitante_id']) ? (int)$_POST['medico_solicitante_id'] : null,
            'fecha_estudio' => $_POST['fecha_estudio'],
            'indicacion_clinica' => $_POST['indicacion_clinica'] ?? null,
            'fum' => $_POST['fum'] ?? null,
            'dia_ciclo_menstrual' => $_POST['dia_ciclo_menstrual'] ?? null,
            'observaciones' => $_POST['observaciones'] ?? null,
            'estado' => $_POST['estado'] ?? 'Pendiente',
            'updated_by' => $userId
        ];

        try {
            $this->evaluacionModel->update($dataEvaluacion);
            $this->indicacionesModel->update($this->buildIndicacionesData($id));
            $this->antecedentesModel->update($this->buildAntecedentesData($id));
            $this->tecnicaModel->update($this->buildTecnicaData($id));
            $this->uteroCervixModel->update($this->buildUteroCervixData($id));
            $this->miomasModel->update($this->buildMiomasData($id));
            $this->miomasDetalleModel->deleteByEvaluacion($id);
            $this->saveMiomasDetalle($id);
            $this->adenomiosisModel->update($this->buildAdenomiosisData($id));
            $this->endometrioModel->update($this->buildEndometrioData($id));
            $this->ovariosModel->update($this->buildOvariosData($id));
            $this->anexosModel->update($this->buildAnexosData($id));
            $this->clasificacionModel->update($this->buildClasificacionData($id));
            $this->impresionModel->update($this->buildImpresionData($id));
            $this->conclusionModel->update($this->buildConclusionData($id));

            Session::set('success', 'Ultrasonido Ginecológico actualizado correctamente.');
        } catch (Exception $e) {
            Session::set('error', 'Error: ' . $e->getMessage());
        }
        $this->redirect('/evaluaciones_ginecologicas');
    }

    public function delete()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
        $id = $_POST['id'] ?? null;
        if (!$id) {
            $this->redirect('/evaluaciones_ginecologicas');
        }

        $evaluacion = $this->evaluacionModel->getById($id);
        if (Auth::hasRole(Auth::ROLE_MEDICO) && (!$evaluacion || $evaluacion['medico_id'] != Auth::id())) {
            Session::set('error', 'No tienes permiso para eliminar esta evaluación.');
            $this->redirect('/evaluaciones_ginecologicas');
        }

        if ($this->evaluacionModel->delete($id)) {
            Session::set('success', 'Evaluación eliminada correctamente.');
        } else {
            Session::set('error', 'Error al eliminar la evaluación.');
        }
        $this->redirect('/evaluaciones_ginecologicas');
    }

    public function print()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/evaluaciones_ginecologicas');
        }
        $evaluacion = $this->evaluacionModel->getById($id);
        if (!$evaluacion) {
            Session::set('error', 'Evaluación no encontrada.');
            $this->redirect('/evaluaciones_ginecologicas');
        }
        if (Auth::hasRole(Auth::ROLE_MEDICO) && $evaluacion['medico_id'] != Auth::id()) {
            Session::set('error', 'No tienes permiso para imprimir esta evaluación.');
            $this->redirect('/evaluaciones_ginecologicas');
        }

        $this->render('evaluaciones_ginecologicas/print', [
            'evaluacion' => $evaluacion,
            'indicaciones' => $this->indicacionesModel->getByEvaluacion($id),
            'antecedentes' => $this->antecedentesModel->getByEvaluacion($id),
            'tecnica' => $this->tecnicaModel->getByEvaluacion($id),
            'uteroCervix' => $this->uteroCervixModel->getByEvaluacion($id),
            'miomas' => $this->miomasModel->getByEvaluacion($id),
            'miomasDetalle' => $this->miomasDetalleModel->getByEvaluacion($id),
            'adenomiosis' => $this->adenomiosisModel->getByEvaluacion($id),
            'endometrio' => $this->endometrioModel->getByEvaluacion($id),
            'ovarios' => $this->ovariosModel->getByEvaluacion($id),
            'anexos' => $this->anexosModel->getByEvaluacion($id),
            'clasificacion' => $this->clasificacionModel->getByEvaluacion($id),
            'impresion' => $this->impresionModel->getByEvaluacion($id),
            'conclusion' => $this->conclusionModel->getByEvaluacion($id)
        ]);
    }

    /* ─── Métodos privados helper para construir arrays de $_POST ─── */

    private function bool($key) { return isset($_POST[$key]) ? 1 : 0; }
    private function intVal($key) { return !empty($_POST[$key]) ? (int)$_POST[$key] : null; }
    private function decVal($key) { return $_POST[$key] !== '' && $_POST[$key] !== null ? $_POST[$key] : null; }
    private function txt($key) { return $_POST[$key] ?? null; }

    private function buildIndicacionesData($evalId)
    {
        return [
            'evaluacion_id' => $evalId,
            'sangrado_uterino_anormal' => $this->bool('sangrado_uterino_anormal'),
            'dolor_pelvico' => $this->bool('dolor_pelvico'),
            'miomatosis_uterina' => $this->bool('miomatosis_uterina'),
            'sospecha_polipo_endometrial' => $this->bool('sospecha_polipo_endometrial'),
            'engrosamiento_endometrial' => $this->bool('engrosamiento_endometrial'),
            'control_diu' => $this->bool('control_diu'),
            'infertilidad_reproduccion' => $this->bool('infertilidad_reproduccion'),
            'quiste_ovarico_masa_anexial' => $this->bool('quiste_ovarico_masa_anexial'),
            'sindrome_climaterico' => $this->bool('sindrome_climaterico'),
            'sangrado_posmenopausico' => $this->bool('sangrado_posmenopausico'),
            'motivo_estudio_otro' => $this->txt('motivo_estudio_otro'),
            'premenopausica' => $this->bool('premenopausica'),
            'perimenopausica' => $this->bool('perimenopausica'),
            'posmenopausica' => $this->bool('posmenopausica'),
            'terapia_hormonal' => $this->bool('terapia_hormonal'),
            'tamoxifeno' => $this->bool('tamoxifeno'),
            'anticonceptivos_hormonales' => $this->bool('anticonceptivos_hormonales'),
            'estatus_no_especificado' => $this->bool('estatus_no_especificado'),
        ];
    }

    private function buildAntecedentesData($evalId)
    {
        return [
            'evaluacion_id' => $evalId,
            'gesta' => $this->intVal('gesta'),
            'para' => $this->intVal('para'),
            'cesareas' => $this->intVal('cesareas'),
            'abortos' => $this->intVal('abortos'),
            'paridad_satisfecha' => isset($_POST['paridad_satisfecha']) ? ($_POST['paridad_satisfecha'] === '1' ? 1 : ($_POST['paridad_satisfecha'] === '0' ? 0 : null)) : null,
            'legrado_cirugia_uterina' => $this->bool('legrado_cirugia_uterina'),
            'miomectomia' => $this->bool('miomectomia'),
            'endometriosis_adenomiosis' => $this->bool('endometriosis_adenomiosis'),
            'otros' => $this->txt('antecedentes_otros'),
        ];
    }

    private function buildTecnicaData($evalId)
    {
        return [
            'evaluacion_id' => $evalId,
            'via_endovaginal' => $this->bool('via_endovaginal'),
            'via_transabdominal' => $this->bool('via_transabdominal'),
            'via_doppler_color' => $this->bool('via_doppler_color'),
            'via_evaluacion_3d' => $this->bool('via_evaluacion_3d'),
            'via_sonohisterografia' => $this->bool('via_sonohisterografia'),
            'calidad' => $this->txt('calidad'),
            'limitada_dolor' => $this->bool('limitada_dolor'),
            'limitada_distension_intestinal' => $this->bool('limitada_distension_intestinal'),
            'limitada_habitus_corporal' => $this->bool('limitada_habitus_corporal'),
            'limitada_posicion_uterina' => $this->bool('limitada_posicion_uterina'),
            'calidad_otra' => $this->txt('calidad_otra'),
        ];
    }

    private function buildUteroCervixData($evalId)
    {
        return [
            'evaluacion_id' => $evalId,
            'situacion' => $this->txt('situacion'),
            'morfologia_regular' => $this->bool('morfologia_regular'),
            'morfologia_bordes_irregulares' => $this->bool('morfologia_bordes_irregulares'),
            'morfologia_globoso' => $this->bool('morfologia_globoso'),
            'morfologia_aumentado' => $this->bool('morfologia_aumentado'),
            'morfologia_disminuido' => $this->bool('morfologia_disminuido'),
            'morfologia_otro' => $this->txt('morfologia_otro'),
            'dim_longitud_mm' => $this->decVal('dim_longitud_mm'),
            'dim_anteroposterior_mm' => $this->decVal('dim_anteroposterior_mm'),
            'dim_transverso_mm' => $this->decVal('dim_transverso_mm'),
            'volumen_cc' => $this->decVal('volumen_cc'),
            'miometrio_homogeneo' => $this->bool('miometrio_homogeneo'),
            'miometrio_heterogeneo' => $this->bool('miometrio_heterogeneo'),
            'miometrio_imagenes_leiomiomas' => $this->bool('miometrio_imagenes_leiomiomas'),
            'miometrio_sugestivo_adenomiosis' => $this->bool('miometrio_sugestivo_adenomiosis'),
            'miometrio_calcificaciones' => $this->bool('miometrio_calcificaciones'),
            'miometrio_areas_quisticas' => $this->bool('miometrio_areas_quisticas'),
            'miometrio_sombra_acustica' => $this->bool('miometrio_sombra_acustica'),
            'miometrio_otro' => $this->txt('miometrio_otro'),
            'cervix_longitud_mm' => $this->decVal('cervix_longitud_mm'),
            'cervix_sin_alteraciones' => $this->bool('cervix_sin_alteraciones'),
            'cervix_quistes_naboth' => $this->bool('cervix_quistes_naboth'),
            'cervix_polipo_endocervical' => $this->bool('cervix_polipo_endocervical'),
            'cervix_lesion_visible_usg' => $this->bool('cervix_lesion_visible_usg'),
            'cervix_liquido_canal' => $this->bool('cervix_liquido_canal'),
            'cervix_otro' => $this->txt('cervix_otro'),
        ];
    }

    private function buildMiomasData($evalId)
    {
        return [
            'evaluacion_id' => $evalId,
            'identificados' => $this->bool('miomas_identificados'),
            'numero_aproximado' => $this->intVal('miomas_numero_aproximado'),
            'mioma_dominante_mm' => $this->decVal('mioma_dominante_mm'),
            'predominio_submucosos' => $this->bool('predominio_submucosos'),
            'predominio_intramurales' => $this->bool('predominio_intramurales'),
            'predominio_subserosos' => $this->bool('predominio_subserosos'),
            'predominio_pediculados' => $this->bool('predominio_pediculados'),
            'predominio_cervicales' => $this->bool('predominio_cervicales'),
            'predominio_distribucion_difusa' => $this->bool('predominio_distribucion_difusa'),
        ];
    }

    private function saveMiomasDetalle($evaluacionId)
    {
        $localizaciones = $_POST['md_localizacion'] ?? [];
        $medX = $_POST['md_medida_x'] ?? [];
        $medY = $_POST['md_medida_y'] ?? [];
        $medZ = $_POST['md_medida_z'] ?? [];
        $relaciones = $_POST['md_relacion'] ?? [];
        $figos = $_POST['md_figo'] ?? [];
        $dopplers = $_POST['md_doppler'] ?? [];
        $comentarios = $_POST['md_comentarios'] ?? [];

        $count = max(count($localizaciones), count($medX));
        for ($i = 0; $i < $count; $i++) {
            $loc = trim($localizaciones[$i] ?? '');
            $mx = trim($medX[$i] ?? '');
            if ($loc === '' && $mx === '') continue;
            $this->miomasDetalleModel->create([
                'evaluacion_id' => $evaluacionId,
                'numero' => $i + 1,
                'localizacion' => $loc !== '' ? $loc : null,
                'medida_x_mm' => $mx !== '' ? $mx : null,
                'medida_y_mm' => trim($medY[$i] ?? '') !== '' ? trim($medY[$i]) : null,
                'medida_z_mm' => trim($medZ[$i] ?? '') !== '' ? trim($medZ[$i]) : null,
                'relacion_endometrio' => trim($relaciones[$i] ?? '') !== '' ? trim($relaciones[$i]) : null,
                'clasificacion_figo' => trim($figos[$i] ?? '') !== '' ? trim($figos[$i]) : null,
                'doppler' => trim($dopplers[$i] ?? '') !== '' ? trim($dopplers[$i]) : null,
                'comentarios' => trim($comentarios[$i] ?? '') !== '' ? trim($comentarios[$i]) : null,
            ]);
        }
    }

    private function buildAdenomiosisData($evalId)
    {
        return [
            'evaluacion_id' => $evalId,
            'hallazgos' => $this->txt('adeno_hallazgos'),
            'utero_globoso' => $this->bool('adeno_utero_globoso'),
            'asimetria_paredes' => $this->bool('adeno_asimetria_paredes'),
            'miometrio_heterogeneo' => $this->bool('adeno_miometrio_heterogeneo'),
            'estriaciones_lineales' => $this->bool('adeno_estriaciones_lineales'),
            'quistes_miometriales' => $this->bool('adeno_quistes_miometriales'),
            'islas_hiperecogenicas' => $this->bool('adeno_islas_hiperecogenicas'),
            'sombra_abanico' => $this->bool('adeno_sombra_abanico'),
            'zona_union_irregular' => $this->bool('adeno_zona_union_irregular'),
            'vascularidad_translesional' => $this->bool('adeno_vascularidad_translesional'),
            'datos_otro' => $this->txt('adeno_datos_otro'),
            'distribucion' => $this->txt('adeno_distribucion'),
            'predominio_anterior' => $this->bool('adeno_predominio_anterior'),
            'predominio_posterior' => $this->bool('adeno_predominio_posterior'),
            'predominio_fundico' => $this->bool('adeno_predominio_fundico'),
        ];
    }

    private function buildEndometrioData($evalId)
    {
        return [
            'evaluacion_id' => $evalId,
            'grosor_mm' => $this->decVal('endometrio_grosor_mm'),
            'patron' => $this->txt('endometrio_patron'),
            'correlacion_ciclo' => $this->txt('endometrio_correlacion_ciclo'),
            'cavidad_regular' => $this->bool('endometrio_cavidad_regular'),
            'cavidad_distorsionada' => $this->bool('endometrio_cavidad_distorsionada'),
            'cavidad_liquido_intracavitario' => $this->bool('endometrio_cavidad_liquido'),
            'cavidad_imagen_focal_polipo' => $this->bool('endometrio_cavidad_polipo'),
            'cavidad_imagen_mioma_submucoso' => $this->bool('endometrio_cavidad_mioma_submucoso'),
            'cavidad_sinequias' => $this->bool('endometrio_cavidad_sinequias'),
            'cavidad_diu_intrauterino' => $this->bool('endometrio_cavidad_diu'),
            'cavidad_otro' => $this->txt('endometrio_cavidad_otro'),
            'doppler' => $this->txt('endometrio_doppler'),
            'diu_posicion' => $this->txt('diu_posicion'),
            'diu_distancia_fondo_mm' => $this->decVal('diu_distancia_fondo_mm'),
        ];
    }

    private function buildOvariosData($evalId)
    {
        return [
            'evaluacion_id' => $evalId,
            'der_dim_x_mm' => $this->decVal('der_dim_x_mm'),
            'der_dim_y_mm' => $this->decVal('der_dim_y_mm'),
            'der_dim_z_mm' => $this->decVal('der_dim_z_mm'),
            'der_volumen_cc' => $this->decVal('der_volumen_cc'),
            'der_normal' => $this->bool('der_normal'),
            'der_atrofico' => $this->bool('der_atrofico'),
            'der_multifolicular' => $this->bool('der_multifolicular'),
            'der_poliquistico' => $this->bool('der_poliquistico'),
            'der_cuerpo_luteo' => $this->bool('der_cuerpo_luteo'),
            'der_quiste_simple' => $this->bool('der_quiste_simple'),
            'der_quiste_hemorragico' => $this->bool('der_quiste_hemorragico'),
            'der_endometrioma' => $this->bool('der_endometrioma'),
            'der_lesion_solida' => $this->bool('der_lesion_solida'),
            'der_lesion_compleja' => $this->bool('der_lesion_compleja'),
            'der_no_visible' => $this->bool('der_no_visible'),
            'der_foliculo_med_x_mm' => $this->decVal('der_foliculo_med_x_mm'),
            'der_foliculo_med_y_mm' => $this->decVal('der_foliculo_med_y_mm'),
            'der_foliculo_med_z_mm' => $this->decVal('der_foliculo_med_z_mm'),
            'der_foliculo_contenido' => $this->txt('der_foliculo_contenido'),
            'der_foliculo_pared' => $this->txt('der_foliculo_pared'),
            'der_foliculo_septos' => $this->bool('der_foliculo_septos'),
            'der_foliculo_septos_grosor' => $this->decVal('der_foliculo_septos_grosor'),
            'der_foliculo_papilares' => $this->bool('der_foliculo_papilares'),
            'der_foliculo_papilares_num' => $this->intVal('der_foliculo_papilares_num'),
            'der_foliculo_sombra' => $this->bool('der_foliculo_sombra'),
            'der_foliculo_doppler' => $this->txt('der_foliculo_doppler'),
            'izq_dim_x_mm' => $this->decVal('izq_dim_x_mm'),
            'izq_dim_y_mm' => $this->decVal('izq_dim_y_mm'),
            'izq_dim_z_mm' => $this->decVal('izq_dim_z_mm'),
            'izq_volumen_cc' => $this->decVal('izq_volumen_cc'),
            'izq_normal' => $this->bool('izq_normal'),
            'izq_atrofico' => $this->bool('izq_atrofico'),
            'izq_multifolicular' => $this->bool('izq_multifolicular'),
            'izq_poliquistico' => $this->bool('izq_poliquistico'),
            'izq_cuerpo_luteo' => $this->bool('izq_cuerpo_luteo'),
            'izq_quiste_simple' => $this->bool('izq_quiste_simple'),
            'izq_quiste_hemorragico' => $this->bool('izq_quiste_hemorragico'),
            'izq_endometrioma' => $this->bool('izq_endometrioma'),
            'izq_lesion_solida' => $this->bool('izq_lesion_solida'),
            'izq_lesion_compleja' => $this->bool('izq_lesion_compleja'),
            'izq_no_visible' => $this->bool('izq_no_visible'),
            'izq_foliculo_med_x_mm' => $this->decVal('izq_foliculo_med_x_mm'),
            'izq_foliculo_med_y_mm' => $this->decVal('izq_foliculo_med_y_mm'),
            'izq_foliculo_med_z_mm' => $this->decVal('izq_foliculo_med_z_mm'),
            'izq_foliculo_contenido' => $this->txt('izq_foliculo_contenido'),
            'izq_foliculo_pared' => $this->txt('izq_foliculo_pared'),
            'izq_foliculo_septos' => $this->bool('izq_foliculo_septos'),
            'izq_foliculo_septos_grosor' => $this->decVal('izq_foliculo_septos_grosor'),
            'izq_foliculo_papilares' => $this->bool('izq_foliculo_papilares'),
            'izq_foliculo_papilares_num' => $this->intVal('izq_foliculo_papilares_num'),
            'izq_foliculo_sombra' => $this->bool('izq_foliculo_sombra'),
            'izq_foliculo_doppler' => $this->txt('izq_foliculo_doppler'),
        ];
    }

    private function buildAnexosData($evalId)
    {
        return [
            'evaluacion_id' => $evalId,
            'der_sin_alteraciones' => $this->bool('der_sin_alteraciones'),
            'der_lesion_anexial' => $this->bool('der_lesion_anexial'),
            'der_hidrosalpinx' => $this->bool('der_hidrosalpinx'),
            'der_paraovarico' => $this->bool('der_paraovarico'),
            'der_otro' => $this->txt('der_otro'),
            'izq_sin_alteraciones' => $this->bool('izq_sin_alteraciones'),
            'izq_lesion_anexial' => $this->bool('izq_lesion_anexial'),
            'izq_hidrosalpinx' => $this->bool('izq_hidrosalpinx'),
            'izq_paraovarico' => $this->bool('izq_paraovarico'),
            'izq_otro' => $this->txt('izq_otro'),
            'fondo_saco_libre' => $this->bool('fondo_saco_libre'),
            'fondo_saco_liquido_escaso' => $this->bool('fondo_saco_liquido_escaso'),
            'fondo_saco_liquido_moderado' => $this->bool('fondo_saco_liquido_moderado'),
            'fondo_saco_liquido_abundante' => $this->bool('fondo_saco_liquido_abundante'),
            'fondo_saco_liquido_ecos' => $this->bool('fondo_saco_liquido_ecos'),
            'fondo_saco_nodulo_implante' => $this->bool('fondo_saco_nodulo_implante'),
            'fondo_saco_dolor_presion' => $this->bool('fondo_saco_dolor_presion'),
            'sliding_sign' => $this->txt('sliding_sign'),
        ];
    }

    private function buildClasificacionData($evalId)
    {
        return [
            'evaluacion_id' => $evalId,
            'palm_polipo' => $this->bool('palm_polipo'),
            'palm_adenomiosis' => $this->bool('palm_adenomiosis'),
            'palm_leiomioma' => $this->bool('palm_leiomioma'),
            'palm_malignidad' => $this->bool('palm_malignidad'),
            'palm_coagulopatia' => $this->bool('palm_coagulopatia'),
            'palm_ovulatoria' => $this->bool('palm_ovulatoria'),
            'palm_endometrial' => $this->bool('palm_endometrial'),
            'palm_iatrogenica' => $this->bool('palm_iatrogenica'),
            'palm_no_clasificada' => $this->bool('palm_no_clasificada'),
            'anexial_funcional' => $this->bool('anexial_funcional'),
            'anexial_benigna' => $this->bool('anexial_benigna'),
            'anexial_indeterminada' => $this->bool('anexial_indeterminada'),
            'anexial_sospechosa' => $this->bool('anexial_sospechosa'),
            'anexial_sugiere_o_rads' => $this->bool('anexial_sugiere_o_rads'),
        ];
    }

    private function buildImpresionData($evalId)
    {
        return [
            'evaluacion_id' => $evalId,
            'utero_tamano' => $this->txt('imp_utero_tamano'),
            'utero_morfologia' => $this->txt('imp_utero_morfologia'),
            'miometrio_sin_alteraciones' => $this->bool('imp_miometrio_sin_alteraciones'),
            'miometrio_miomatosis' => $this->bool('imp_miometrio_miomatosis'),
            'miometrio_adenomiosis' => $this->bool('imp_miometrio_adenomiosis'),
            'miometrio_otro' => $this->txt('imp_miometrio_otro'),
            'endometrio_grosor_mm' => $this->decVal('imp_endometrio_grosor_mm'),
            'endometrio_patron' => $this->txt('imp_endometrio_patron'),
            'endometrio_acorde_contexto' => $this->bool('imp_endometrio_acorde'),
            'endometrio_engrosado_contexto' => $this->bool('imp_endometrio_engrosado'),
            'endometrio_requiere_correlacion' => $this->bool('imp_endometrio_correlacion'),
            'ovario_derecho' => $this->txt('imp_ovario_derecho'),
            'ovario_izquierdo' => $this->txt('imp_ovario_izquierdo'),
            'anexos_fondo_saco' => $this->txt('imp_anexos_fondo_saco'),
        ];
    }

    private function buildConclusionData($evalId)
    {
        return [
            'evaluacion_id' => $evalId,
            'estudio_limites_esperados' => $this->bool('concl_normal'),
            'miomatosis_uterina' => $this->bool('concl_miomatosis'),
            'conclusion_mioma_dominante_mm' => $this->decVal('concl_mioma_dominante_mm'),
            'conclusion_figo' => $this->txt('concl_figo'),
            'engrosamiento_endometrial' => $this->bool('concl_engrosamiento'),
            'conclusion_medida_endometrio_mm' => $this->decVal('concl_medida_endometrio_mm'),
            'imagen_focal_polipo' => $this->bool('concl_polipo'),
            'datos_sugestivos_adenomiosis' => $this->bool('concl_adenomiosis'),
            'quiste_simple_der' => $this->bool('concl_quiste_simple_der'),
            'quiste_simple_izq' => $this->bool('concl_quiste_simple_izq'),
            'quiste_hemorragico_der' => $this->bool('concl_quiste_hemorragico_der'),
            'quiste_hemorragico_izq' => $this->bool('concl_quiste_hemorragico_izq'),
            'endometrioma_der' => $this->bool('concl_endometrioma_der'),
            'endometrioma_izq' => $this->bool('concl_endometrioma_izq'),
            'conclusion_quiste_medida_mm' => $this->decVal('concl_quiste_medida_mm'),
            'masa_anexial_indeterminada' => $this->bool('concl_masa_indeterminada'),
            'conclusion_otro' => $this->txt('concl_otro'),
            'rec_correlacion_edad_fum' => $this->bool('rec_correlacion_edad_fum'),
            'rec_correlacion_hb_hormonal' => $this->bool('rec_correlacion_hb_hormonal'),
            'rec_estudio_histologico' => $this->bool('rec_estudio_histologico'),
            'rec_histeroscopia_endometrio' => $this->bool('rec_histeroscopia_endometrio'),
            'rec_sonohisterografia_histeroscopia' => $this->bool('rec_sonohisterografia_histeroscopia'),
            'rec_valorar_manejo_miomatosis' => $this->bool('rec_valorar_manejo_miomatosis'),
            'rec_iorads_marcadores_oncologia' => $this->bool('rec_iorads_marcadores_oncologia'),
            'rec_control_ultrasonografico' => $this->bool('rec_control_ultrasonografico'),
            'rec_control_tiempo' => $this->intVal('rec_control_tiempo'),
            'rec_control_unidad' => $this->txt('rec_control_unidad'),
            'rec_otro' => $this->txt('rec_otro'),
        ];
    }
}
