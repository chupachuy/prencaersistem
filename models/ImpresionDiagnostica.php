<?php
require_once __DIR__ . '/../core/Database.php';

class ImpresionDiagnostica
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM impresion_diagnostica WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO impresion_diagnostica (
                evaluacion_id, riesgo_basal_cromosomopatias, riesgo_ajustado_cromosomopatias,
                probabilidad_cromosomopatias, riesgo_preeclampsia_temprana,
                riesgo_enfermedad_placentaria_tardia, riesgo_parto_pretermino
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['riesgo_basal_cromosomopatias'] ?? null,
            $data['riesgo_ajustado_cromosomopatias'] ?? null,
            $data['probabilidad_cromosomopatias'] ?? null,
            $data['riesgo_preeclampsia_temprana'] ?? null,
            $data['riesgo_enfermedad_placentaria_tardia'] ?? null,
            $data['riesgo_parto_pretermino'] ?? null
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE impresion_diagnostica SET
                riesgo_basal_cromosomopatias = ?, riesgo_ajustado_cromosomopatias = ?,
                probabilidad_cromosomopatias = ?, riesgo_preeclampsia_temprana = ?,
                riesgo_enfermedad_placentaria_tardia = ?, riesgo_parto_pretermino = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['riesgo_basal_cromosomopatias'] ?? null,
            $data['riesgo_ajustado_cromosomopatias'] ?? null,
            $data['probabilidad_cromosomopatias'] ?? null,
            $data['riesgo_preeclampsia_temprana'] ?? null,
            $data['riesgo_enfermedad_placentaria_tardia'] ?? null,
            $data['riesgo_parto_pretermino'] ?? null,
            $data['evaluacion_id']
        ]);
    }
}
