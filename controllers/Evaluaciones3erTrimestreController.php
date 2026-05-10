<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Evaluacion3erTrimestre.php';
require_once __DIR__ . '/../models/Antecedentes3erTrimestre.php';
require_once __DIR__ . '/../models/Crecimiento3erTrimestre.php';
require_once __DIR__ . '/../models/Doppler3erTrimestre.php';
require_once __DIR__ . '/../models/AnatomiaLiquido3erTrimestre.php';
require_once __DIR__ . '/../models/EvaluacionPlacentaria3erTrimestre.php';
require_once __DIR__ . '/../models/HistorialClinico.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';

class Evaluaciones3erTrimestreController extends Controller
{
    private $ev, $ant, $crec, $dop, $anat, $plac, $hist, $pac, $usr;
    private $bk, $nv;

    public function __construct()
    {
        $this->ev = new Evaluacion3erTrimestre();
        $this->ant = new Antecedentes3erTrimestre();
        $this->crec = new Crecimiento3erTrimestre();
        $this->dop = new Doppler3erTrimestre();
        $this->anat = new AnatomiaLiquido3erTrimestre();
        $this->plac = new EvaluacionPlacentaria3erTrimestre();
        $this->hist = new HistorialClinico();
        $this->pac = new Paciente();
        $this->usr = new User();
        $this->bk = function($k) { return isset($_POST[$k]) ? 1 : 0; };
        $this->nv = function($k) { return $_POST[$k] ?? null; };
    }

    public function index() {
        if (!Auth::check()) { $this->redirect('/login'); }
        $this->render('evaluaciones_3er_trimestre/index', ['evaluaciones' => $this->ev->getAll()]);
    }

    public function create() {
        if (!Auth::check()) { $this->redirect('/login'); }
        $pid = $_GET['paciente_id'] ?? null;
        $this->render('evaluaciones_3er_trimestre/create', [
            'pacientes' => $this->pac->getAll(), 'medicos' => $this->usr->getMedicos(),
            'paciente_id' => $pid, 'codigo_reporte' => $this->ev->generateCodigoReporte(),
            'historial' => $pid ? $this->hist->getByPaciente($pid) : null
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('/evaluaciones_3er_trimestre'); }
        if (!Auth::check()) { $this->redirect('/login'); }
        $uid = Auth::id(); $p = (int)($_POST['paciente_id']??0); $m = (int)($_POST['medico_id']??0);
        if (!$p || !$m) { Session::set('error','Seleccione paciente y médico.'); $this->redirect('/evaluaciones_3er_trimestre/create'); }
        try {
            $eid = $this->ev->create([
                'paciente_id'=>$p,'medico_id'=>$m,'codigo_reporte'=>$_POST['codigo_reporte'],
                'fecha_evaluacion'=>!empty($_POST['fecha_evaluacion'])?$_POST['fecha_evaluacion']:date('Y-m-d'),
                'fecha_estudio'=>$this->nv('fecha_estudio'),'edad_gestacional_semanas'=>$this->nv('edad_gestacional_semanas'),
                'peso_kg'=>$this->nv('peso_kg'),'ta_sistolica'=>$this->nv('ta_sistolica'),'ta_diastolica'=>$this->nv('ta_diastolica'),
                'situacion_fetal'=>$this->nv('situacion_fetal'),'presentacion_fetal'=>$this->nv('presentacion_fetal'),
                'posicion_fetal'=>$this->nv('posicion_fetal'),'fcf_lpm'=>$this->nv('fcf_lpm'),
                'estado'=>$_POST['estado']??'Pendiente','created_by'=>$uid,'updated_by'=>$uid
            ]);
            if(!$eid) throw new Exception('Error al crear evaluación');

            $this->ant->create(['evaluacion_id'=>$eid,'curva_tolerancia_glucosa'=>$this->nv('curva_tolerancia_glucosa'),
                'diabetes_gestacional_actual'=>($this->bk)('diabetes_gestacional_actual'),'movimientos_fetales'=>$this->nv('movimientos_fetales'),
                'signos_amenaza_parto_pretermino'=>($this->bk)('signos_amenaza_parto_pretermino'),'plan_nacimiento_definido'=>($this->bk)('plan_nacimiento_definido')]);

            $this->crec->create(['evaluacion_id'=>$eid,'peso_fetal_estimado_gr'=>$this->nv('peso_fetal_estimado_gr'),
                'percentil_ajustado'=>$this->nv('percentil_ajustado'),'clasificacion_crecimiento'=>$this->nv('clasificacion_crecimiento'),
                'estadio_rciu_barcelona'=>$this->nv('estadio_rciu_barcelona')]);

            $this->dop->create(['evaluacion_id'=>$eid,'au_pi'=>$this->nv('au_pi'),'au_flujo_diastolico'=>$this->nv('au_flujo_diastolico'),
                'acm_pi'=>$this->nv('acm_pi'),'dv_onda_a'=>$this->nv('dv_onda_a'),'uta_pi_promedio'=>$this->nv('uta_pi_promedio'),
                'ratio_cu_icp'=>$this->nv('ratio_cu_icp'),'alteracion_doppler_detectada'=>($this->bk)('alteracion_doppler_detectada')]);

            $this->anat->create(['evaluacion_id'=>$eid,'circular_cordon_cuello'=>$this->nv('circular_cordon_cuello'),
                'liquido_amniotico_mm'=>$this->nv('liquido_amniotico_mm'),'metodo_medicion_liquido'=>$this->nv('metodo_medicion_liquido'),
                'diagnostico_liquido'=>$this->nv('diagnostico_liquido'),'estructuras_normales'=>($this->bk)('estructuras_normales')]);

            $this->plac->create(['evaluacion_id'=>$eid,'distancia_oci_mm'=>$this->nv('distancia_oci_mm'),
                'grosor_placentario_mm'=>$this->nv('grosor_placentario_mm'),'grado_madurez'=>$this->nv('grado_madurez'),
                'lagunas_vasculares'=>$this->nv('lagunas_vasculares'),'interfase_miometrial'=>$this->nv('interfase_miometrial'),
                'vasos_puente'=>($this->bk)('vasos_puente'),'acretismo_figo_pas'=>$this->nv('acretismo_figo_pas')]);

            $he = $this->hist->getByPaciente($p);
            $hd = ['paciente_id'=>$p,'hipertension_cronica'=>($this->bk)('hipertension_cronica'),'diabetes'=>($this->bk)('diabetes'),
                'lupus_les'=>($this->bk)('lupus_les'),'sindrome_antifosfolipido_saf'=>($this->bk)('sindrome_antifosfolipido_saf'),
                'antecedente_preeclampsia_rciu'=>($this->bk)('antecedente_preeclampsia_rciu'),'fertilizacion_in_vitro'=>($this->bk)('fertilizacion_in_vitro'),
                'antecedente_parto_pretermino'=>($this->bk)('antecedente_parto_pretermino')];
            $he ? $this->hist->update($hd) : $this->hist->create($hd);

            Session::set('success','Evaluación 3er Trimestre guardada correctamente.');
            $this->redirect('/evaluaciones_3er_trimestre');
        } catch (Exception $e) { Session::set('error','Error: '.$e->getMessage()); $this->redirect('/evaluaciones_3er_trimestre/create'); }
    }

    public function show() {
        if (!Auth::check()) { $this->redirect('/login'); }
        $id = $_GET['id']??null; if(!$id){$this->redirect('/evaluaciones_3er_trimestre');}
        $ev = $this->ev->getById($id); if(!$ev){Session::set('error','No encontrada.');$this->redirect('/evaluaciones_3er_trimestre');}
        $this->render('evaluaciones_3er_trimestre/show',['evaluacion'=>$ev,'antecedentes'=>$this->ant->getByEvaluacion($id),
            'crecimiento'=>$this->crec->getByEvaluacion($id),'doppler'=>$this->dop->getByEvaluacion($id),
            'anatomia'=>$this->anat->getByEvaluacion($id),'placentaria'=>$this->plac->getByEvaluacion($id),
            'historial'=>$this->hist->getByPaciente($ev['paciente_id'])]);
    }

    public function edit() {
        if (!Auth::check()) { $this->redirect('/login'); }
        $id = $_GET['id']??null; if(!$id){$this->redirect('/evaluaciones_3er_trimestre');}
        $ev = $this->ev->getById($id); if(!$ev){Session::set('error','No encontrada.');$this->redirect('/evaluaciones_3er_trimestre');}
        $this->render('evaluaciones_3er_trimestre/edit',['evaluacion'=>$ev,'pacientes'=>$this->pac->getAll(),
            'medicos'=>$this->usr->getMedicos(),'antecedentes'=>$this->ant->getByEvaluacion($id),
            'crecimiento'=>$this->crec->getByEvaluacion($id),'doppler'=>$this->dop->getByEvaluacion($id),
            'anatomia'=>$this->anat->getByEvaluacion($id),'placentaria'=>$this->plac->getByEvaluacion($id),
            'historial'=>$this->hist->getByPaciente($ev['paciente_id'])]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('/evaluaciones_3er_trimestre'); }
        if (!Auth::check()) { $this->redirect('/login'); }
        $id = (int)($_POST['id']??0); if(!$id){$this->redirect('/evaluaciones_3er_trimestre');}
        $uid = Auth::id(); $p = (int)($_POST['paciente_id']??0); $m = (int)($_POST['medico_id']??0);
        try {
            $this->ev->update(['id'=>$id,'paciente_id'=>$p,'medico_id'=>$m,'fecha_estudio'=>$this->nv('fecha_estudio'),
                'edad_gestacional_semanas'=>$this->nv('edad_gestacional_semanas'),'peso_kg'=>$this->nv('peso_kg'),
                'ta_sistolica'=>$this->nv('ta_sistolica'),'ta_diastolica'=>$this->nv('ta_diastolica'),
                'situacion_fetal'=>$this->nv('situacion_fetal'),'presentacion_fetal'=>$this->nv('presentacion_fetal'),
                'posicion_fetal'=>$this->nv('posicion_fetal'),'fcf_lpm'=>$this->nv('fcf_lpm'),
                'estado'=>$_POST['estado']??'Pendiente','updated_by'=>$uid]);
            $this->ant->update(['evaluacion_id'=>$id,'curva_tolerancia_glucosa'=>$this->nv('curva_tolerancia_glucosa'),
                'diabetes_gestacional_actual'=>($this->bk)('diabetes_gestacional_actual'),'movimientos_fetales'=>$this->nv('movimientos_fetales'),
                'signos_amenaza_parto_pretermino'=>($this->bk)('signos_amenaza_parto_pretermino'),'plan_nacimiento_definido'=>($this->bk)('plan_nacimiento_definido')]);
            $this->crec->update(['evaluacion_id'=>$id,'peso_fetal_estimado_gr'=>$this->nv('peso_fetal_estimado_gr'),
                'percentil_ajustado'=>$this->nv('percentil_ajustado'),'clasificacion_crecimiento'=>$this->nv('clasificacion_crecimiento'),
                'estadio_rciu_barcelona'=>$this->nv('estadio_rciu_barcelona')]);
            $this->dop->update(['evaluacion_id'=>$id,'au_pi'=>$this->nv('au_pi'),'au_flujo_diastolico'=>$this->nv('au_flujo_diastolico'),
                'acm_pi'=>$this->nv('acm_pi'),'dv_onda_a'=>$this->nv('dv_onda_a'),'uta_pi_promedio'=>$this->nv('uta_pi_promedio'),
                'ratio_cu_icp'=>$this->nv('ratio_cu_icp'),'alteracion_doppler_detectada'=>($this->bk)('alteracion_doppler_detectada')]);
            $this->anat->update(['evaluacion_id'=>$id,'circular_cordon_cuello'=>$this->nv('circular_cordon_cuello'),
                'liquido_amniotico_mm'=>$this->nv('liquido_amniotico_mm'),'metodo_medicion_liquido'=>$this->nv('metodo_medicion_liquido'),
                'diagnostico_liquido'=>$this->nv('diagnostico_liquido'),'estructuras_normales'=>($this->bk)('estructuras_normales')]);
            $this->plac->update(['evaluacion_id'=>$id,'distancia_oci_mm'=>$this->nv('distancia_oci_mm'),
                'grosor_placentario_mm'=>$this->nv('grosor_placentario_mm'),'grado_madurez'=>$this->nv('grado_madurez'),
                'lagunas_vasculares'=>$this->nv('lagunas_vasculares'),'interfase_miometrial'=>$this->nv('interfase_miometrial'),
                'vasos_puente'=>($this->bk)('vasos_puente'),'acretismo_figo_pas'=>$this->nv('acretismo_figo_pas')]);
            $he = $this->hist->getByPaciente($p);
            $hd = ['paciente_id'=>$p,'hipertension_cronica'=>($this->bk)('hipertension_cronica'),'diabetes'=>($this->bk)('diabetes'),
                'lupus_les'=>($this->bk)('lupus_les'),'sindrome_antifosfolipido_saf'=>($this->bk)('sindrome_antifosfolipido_saf'),
                'antecedente_preeclampsia_rciu'=>($this->bk)('antecedente_preeclampsia_rciu'),'fertilizacion_in_vitro'=>($this->bk)('fertilizacion_in_vitro'),
                'antecedente_parto_pretermino'=>($this->bk)('antecedente_parto_pretermino')];
            $he ? $this->hist->update($hd) : $this->hist->create($hd);
            Session::set('success','Evaluación actualizada correctamente.');
        } catch (Exception $e) { Session::set('error','Error: '.$e->getMessage()); }
        $this->redirect('/evaluaciones_3er_trimestre');
    }

    public function delete() {
        if (!Auth::check()) { $this->redirect('/login'); }
        $id = $_POST['id']??null;
        if($id){$this->ev->delete($id);Session::set('success','Evaluación eliminada.');}
        $this->redirect('/evaluaciones_3er_trimestre');
    }

    public function print() {
        if (!Auth::check()) { $this->redirect('/login'); }
        $id = $_GET['id']??null; $ev = $this->ev->getById($id);
        if(!$ev){$this->redirect('/evaluaciones_3er_trimestre');}
        $this->render('evaluaciones_3er_trimestre/print',['evaluacion'=>$ev,'antecedentes'=>$this->ant->getByEvaluacion($id),
            'crecimiento'=>$this->crec->getByEvaluacion($id),'doppler'=>$this->dop->getByEvaluacion($id),
            'anatomia'=>$this->anat->getByEvaluacion($id),'placentaria'=>$this->plac->getByEvaluacion($id),
            'historial'=>$this->hist->getByPaciente($ev['paciente_id'])]);
    }
}
