<?php
require_once __DIR__ . '/../core/Database.php';

class Doppler3erTrimestre
{
    private $db;

    public function __construct() { $this->db = Database::getInstance()->getConnection(); }

    public function getByEvaluacion($id) {
        $stmt = $this->db->prepare("SELECT * FROM doppler_3er_trimestre WHERE evaluacion_id = ?");
        $stmt->execute([$id]); return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO doppler_3er_trimestre (evaluacion_id, au_pi, au_flujo_diastolico, acm_pi, dv_onda_a, uta_pi_promedio, ratio_cu_icp, alteracion_doppler_detectada) VALUES (?,?,?,?,?,?,?,?)");
        return $stmt->execute([$data['evaluacion_id'], $data['au_pi']??null, $data['au_flujo_diastolico']??null, $data['acm_pi']??null, $data['dv_onda_a']??null, $data['uta_pi_promedio']??null, $data['ratio_cu_icp']??null, $data['alteracion_doppler_detectada']??0]);
    }

    public function update($data) {
        $stmt = $this->db->prepare("UPDATE doppler_3er_trimestre SET au_pi=?, au_flujo_diastolico=?, acm_pi=?, dv_onda_a=?, uta_pi_promedio=?, ratio_cu_icp=?, alteracion_doppler_detectada=? WHERE evaluacion_id=?");
        return $stmt->execute([$data['au_pi']??null, $data['au_flujo_diastolico']??null, $data['acm_pi']??null, $data['dv_onda_a']??null, $data['uta_pi_promedio']??null, $data['ratio_cu_icp']??null, $data['alteracion_doppler_detectada']??0, $data['evaluacion_id']]);
    }
}
