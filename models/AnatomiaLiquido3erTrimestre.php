<?php
require_once __DIR__ . '/../core/Database.php';

class AnatomiaLiquido3erTrimestre
{
    private $db;

    public function __construct() { $this->db = Database::getInstance()->getConnection(); }

    public function getByEvaluacion($id) {
        $stmt = $this->db->prepare("SELECT * FROM anatomia_liquido_3er_trimestre WHERE evaluacion_id = ?");
        $stmt->execute([$id]); return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO anatomia_liquido_3er_trimestre (evaluacion_id, circular_cordon_cuello, liquido_amniotico_mm, metodo_medicion_liquido, diagnostico_liquido, estructuras_normales) VALUES (?,?,?,?,?,?)");
        return $stmt->execute([$data['evaluacion_id'], $data['circular_cordon_cuello']??'Negativo', $data['liquido_amniotico_mm']??null, $data['metodo_medicion_liquido']??'Bolsillo Maximo', $data['diagnostico_liquido']??'Normal', $data['estructuras_normales']??1]);
    }

    public function update($data) {
        $stmt = $this->db->prepare("UPDATE anatomia_liquido_3er_trimestre SET circular_cordon_cuello=?, liquido_amniotico_mm=?, metodo_medicion_liquido=?, diagnostico_liquido=?, estructuras_normales=? WHERE evaluacion_id=?");
        return $stmt->execute([$data['circular_cordon_cuello']??'Negativo', $data['liquido_amniotico_mm']??null, $data['metodo_medicion_liquido']??'Bolsillo Maximo', $data['diagnostico_liquido']??'Normal', $data['estructuras_normales']??1, $data['evaluacion_id']]);
    }
}
