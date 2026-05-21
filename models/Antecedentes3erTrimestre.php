<?php
require_once __DIR__ . '/../core/Database.php';

class Antecedentes3erTrimestre
{
    private $db;

    public function __construct() { $this->db = Database::getInstance()->getConnection(); }

    public function getByEvaluacion($id) {
        $stmt = $this->db->prepare("SELECT * FROM antecedentes_3er_trimestre WHERE evaluacion_id = ?");
        $stmt->execute([$id]); return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO antecedentes_3er_trimestre (evaluacion_id, curva_tolerancia_glucosa, diabetes_gestacional_actual, movimientos_fetales, signos_amenaza_parto_pretermino, plan_nacimiento_definido, checklist_riesgo_preeclampsia_1t, checklist_doppler_uterino_1t_pi, checklist_doppler_uterino_1t_muesca, checklist_papp_a_mom, checklist_plgf_mom, checklist_tamizaje_genetico_resultado, checklist_longitud_cervical_1t_mm, checklist_morfologia_fetal_2t_normal, checklist_doppler_uterino_2t_pi, checklist_placenta_2t_posicion, checklist_placenta_2t_acretismo, checklist_longitud_cervical_2t_mm, checklist_funneling_2t_presente, checklist_sludge_2t, checklist_icc_2t_pct, checklist_rciu_2t_signos) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['curva_tolerancia_glucosa']??'No realizada',
            $data['diabetes_gestacional_actual']??0,
            $data['movimientos_fetales']??'Normales',
            $data['signos_amenaza_parto_pretermino']??0,
            $data['plan_nacimiento_definido']??0,
            $data['checklist_riesgo_preeclampsia_1t'] ?? null,
            $data['checklist_doppler_uterino_1t_pi'] ?? null,
            $data['checklist_doppler_uterino_1t_muesca'] ?? null,
            $data['checklist_papp_a_mom'] ?? null,
            $data['checklist_plgf_mom'] ?? null,
            $data['checklist_tamizaje_genetico_resultado'] ?? null,
            $data['checklist_longitud_cervical_1t_mm'] ?? null,
            $data['checklist_morfologia_fetal_2t_normal'] ?? null,
            $data['checklist_doppler_uterino_2t_pi'] ?? null,
            $data['checklist_placenta_2t_posicion'] ?? null,
            $data['checklist_placenta_2t_acretismo'] ?? null,
            $data['checklist_longitud_cervical_2t_mm'] ?? null,
            $data['checklist_funneling_2t_presente'] ?? null,
            $data['checklist_sludge_2t'] ?? null,
            $data['checklist_icc_2t_pct'] ?? null,
            $data['checklist_rciu_2t_signos'] ?? null
        ]);
    }

    public function update($data) {
        $stmt = $this->db->prepare("UPDATE antecedentes_3er_trimestre SET curva_tolerancia_glucosa=?, diabetes_gestacional_actual=?, movimientos_fetales=?, signos_amenaza_parto_pretermino=?, plan_nacimiento_definido=?, checklist_riesgo_preeclampsia_1t=?, checklist_doppler_uterino_1t_pi=?, checklist_doppler_uterino_1t_muesca=?, checklist_papp_a_mom=?, checklist_plgf_mom=?, checklist_tamizaje_genetico_resultado=?, checklist_longitud_cervical_1t_mm=?, checklist_morfologia_fetal_2t_normal=?, checklist_doppler_uterino_2t_pi=?, checklist_placenta_2t_posicion=?, checklist_placenta_2t_acretismo=?, checklist_longitud_cervical_2t_mm=?, checklist_funneling_2t_presente=?, checklist_sludge_2t=?, checklist_icc_2t_pct=?, checklist_rciu_2t_signos=? WHERE evaluacion_id=?");
        return $stmt->execute([
            $data['curva_tolerancia_glucosa']??'No realizada',
            $data['diabetes_gestacional_actual']??0,
            $data['movimientos_fetales']??'Normales',
            $data['signos_amenaza_parto_pretermino']??0,
            $data['plan_nacimiento_definido']??0,
            $data['checklist_riesgo_preeclampsia_1t'] ?? null,
            $data['checklist_doppler_uterino_1t_pi'] ?? null,
            $data['checklist_doppler_uterino_1t_muesca'] ?? null,
            $data['checklist_papp_a_mom'] ?? null,
            $data['checklist_plgf_mom'] ?? null,
            $data['checklist_tamizaje_genetico_resultado'] ?? null,
            $data['checklist_longitud_cervical_1t_mm'] ?? null,
            $data['checklist_morfologia_fetal_2t_normal'] ?? null,
            $data['checklist_doppler_uterino_2t_pi'] ?? null,
            $data['checklist_placenta_2t_posicion'] ?? null,
            $data['checklist_placenta_2t_acretismo'] ?? null,
            $data['checklist_longitud_cervical_2t_mm'] ?? null,
            $data['checklist_funneling_2t_presente'] ?? null,
            $data['checklist_sludge_2t'] ?? null,
            $data['checklist_icc_2t_pct'] ?? null,
            $data['checklist_rciu_2t_signos'] ?? null,
            $data['evaluacion_id']
        ]);
    }
}
