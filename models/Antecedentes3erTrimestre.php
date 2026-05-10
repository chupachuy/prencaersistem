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
        $stmt = $this->db->prepare("INSERT INTO antecedentes_3er_trimestre (evaluacion_id, curva_tolerancia_glucosa, diabetes_gestacional_actual, movimientos_fetales, signos_amenaza_parto_pretermino, plan_nacimiento_definido) VALUES (?,?,?,?,?,?)");
        return $stmt->execute([$data['evaluacion_id'], $data['curva_tolerancia_glucosa']??'No realizada', $data['diabetes_gestacional_actual']??0, $data['movimientos_fetales']??'Normales', $data['signos_amenaza_parto_pretermino']??0, $data['plan_nacimiento_definido']??0]);
    }

    public function update($data) {
        $stmt = $this->db->prepare("UPDATE antecedentes_3er_trimestre SET curva_tolerancia_glucosa=?, diabetes_gestacional_actual=?, movimientos_fetales=?, signos_amenaza_parto_pretermino=?, plan_nacimiento_definido=? WHERE evaluacion_id=?");
        return $stmt->execute([$data['curva_tolerancia_glucosa']??'No realizada', $data['diabetes_gestacional_actual']??0, $data['movimientos_fetales']??'Normales', $data['signos_amenaza_parto_pretermino']??0, $data['plan_nacimiento_definido']??0, $data['evaluacion_id']]);
    }
}
