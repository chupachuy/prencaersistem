<?php
require_once __DIR__ . '/../core/Database.php';

class EvaluacionPlacentaria3erTrimestre
{
    private $db;

    public function __construct() { $this->db = Database::getInstance()->getConnection(); }

    public function getByEvaluacion($id) {
        $stmt = $this->db->prepare("SELECT * FROM evaluacion_placentaria_3er_trimestre WHERE evaluacion_id = ?");
        $stmt->execute([$id]); return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO evaluacion_placentaria_3er_trimestre (evaluacion_id, distancia_oci_mm, grosor_placentario_mm, grado_madurez, lagunas_vasculares, interfase_miometrial, vasos_puente, acretismo_figo_pas) VALUES (?,?,?,?,?,?,?,?)");
        return $stmt->execute([$data['evaluacion_id'], $data['distancia_oci_mm']??null, $data['grosor_placentario_mm']??null, $data['grado_madurez']??null, $data['lagunas_vasculares']??'Ausentes/minimas', $data['interfase_miometrial']??'Intacta', $data['vasos_puente']??0, $data['acretismo_figo_pas']??'Grado 0']);
    }

    public function update($data) {
        $stmt = $this->db->prepare("UPDATE evaluacion_placentaria_3er_trimestre SET distancia_oci_mm=?, grosor_placentario_mm=?, grado_madurez=?, lagunas_vasculares=?, interfase_miometrial=?, vasos_puente=?, acretismo_figo_pas=? WHERE evaluacion_id=?");
        return $stmt->execute([$data['distancia_oci_mm']??null, $data['grosor_placentario_mm']??null, $data['grado_madurez']??null, $data['lagunas_vasculares']??'Ausentes/minimas', $data['interfase_miometrial']??'Intacta', $data['vasos_puente']??0, $data['acretismo_figo_pas']??'Grado 0', $data['evaluacion_id']]);
    }
}
