<?php
require_once __DIR__ . '/../core/Database.php';

class Crecimiento3erTrimestre
{
    private $db;

    public function __construct() { $this->db = Database::getInstance()->getConnection(); }

    public function getByEvaluacion($id) {
        $stmt = $this->db->prepare("SELECT * FROM crecimiento_3er_trimestre WHERE evaluacion_id = ?");
        $stmt->execute([$id]); return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO crecimiento_3er_trimestre (evaluacion_id, peso_fetal_estimado_gr, percentil_ajustado, clasificacion_crecimiento, estadio_rciu_barcelona) VALUES (?,?,?,?,?)");
        return $stmt->execute([$data['evaluacion_id'], $data['peso_fetal_estimado_gr']??null, $data['percentil_ajustado']??null, $data['clasificacion_crecimiento']??null, $data['estadio_rciu_barcelona']??'Ninguno']);
    }

    public function update($data) {
        $stmt = $this->db->prepare("UPDATE crecimiento_3er_trimestre SET peso_fetal_estimado_gr=?, percentil_ajustado=?, clasificacion_crecimiento=?, estadio_rciu_barcelona=? WHERE evaluacion_id=?");
        return $stmt->execute([$data['peso_fetal_estimado_gr']??null, $data['percentil_ajustado']??null, $data['clasificacion_crecimiento']??null, $data['estadio_rciu_barcelona']??'Ninguno', $data['evaluacion_id']]);
    }
}
