<?php
require_once __DIR__ . '/../core/Database.php';

class ImpresionDiagnostica2doTrimestre
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM impresion_diagnostica_2do_trimestre WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO impresion_diagnostica_2do_trimestre (
                evaluacion_id, riesgo_cromosomopatias, riesgo_parto_pretermino,
                riesgo_preeclampsia, observaciones_medicas
            ) VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'], $data['riesgo_cromosomopatias'] ?? null,
            $data['riesgo_parto_pretermino'] ?? null, $data['riesgo_preeclampsia'] ?? null,
            $data['observaciones_medicas'] ?? null
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE impresion_diagnostica_2do_trimestre SET
                riesgo_cromosomopatias = ?, riesgo_parto_pretermino = ?,
                riesgo_preeclampsia = ?, observaciones_medicas = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['riesgo_cromosomopatias'] ?? null, $data['riesgo_parto_pretermino'] ?? null,
            $data['riesgo_preeclampsia'] ?? null, $data['observaciones_medicas'] ?? null,
            $data['evaluacion_id']
        ]);
    }
}
