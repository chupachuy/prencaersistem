<?php
require_once __DIR__ . '/../core/Database.php';

class ImpresionDiagnosticaGinecologica
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM impresion_diagnostica_ginecologica WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO impresion_diagnostica_ginecologica (
                evaluacion_id,
                utero_tamano, utero_morfologia,
                miometrio_sin_alteraciones, miometrio_miomatosis, miometrio_adenomiosis, miometrio_otro,
                endometrio_grosor_mm, endometrio_patron,
                endometrio_acorde_contexto, endometrio_engrosado_contexto, endometrio_requiere_correlacion,
                ovario_derecho, ovario_izquierdo, anexos_fondo_saco
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['utero_tamano'] ?? null,
            $data['utero_morfologia'] ?? null,
            $data['miometrio_sin_alteraciones'] ?? 0,
            $data['miometrio_miomatosis'] ?? 0,
            $data['miometrio_adenomiosis'] ?? 0,
            $data['miometrio_otro'] ?? null,
            $data['endometrio_grosor_mm'] ?? null,
            $data['endometrio_patron'] ?? null,
            $data['endometrio_acorde_contexto'] ?? 0,
            $data['endometrio_engrosado_contexto'] ?? 0,
            $data['endometrio_requiere_correlacion'] ?? 0,
            $data['ovario_derecho'] ?? null,
            $data['ovario_izquierdo'] ?? null,
            $data['anexos_fondo_saco'] ?? null
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE impresion_diagnostica_ginecologica SET
                utero_tamano = ?, utero_morfologia = ?,
                miometrio_sin_alteraciones = ?, miometrio_miomatosis = ?, miometrio_adenomiosis = ?, miometrio_otro = ?,
                endometrio_grosor_mm = ?, endometrio_patron = ?,
                endometrio_acorde_contexto = ?, endometrio_engrosado_contexto = ?, endometrio_requiere_correlacion = ?,
                ovario_derecho = ?, ovario_izquierdo = ?, anexos_fondo_saco = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['utero_tamano'] ?? null,
            $data['utero_morfologia'] ?? null,
            $data['miometrio_sin_alteraciones'] ?? 0,
            $data['miometrio_miomatosis'] ?? 0,
            $data['miometrio_adenomiosis'] ?? 0,
            $data['miometrio_otro'] ?? null,
            $data['endometrio_grosor_mm'] ?? null,
            $data['endometrio_patron'] ?? null,
            $data['endometrio_acorde_contexto'] ?? 0,
            $data['endometrio_engrosado_contexto'] ?? 0,
            $data['endometrio_requiere_correlacion'] ?? 0,
            $data['ovario_derecho'] ?? null,
            $data['ovario_izquierdo'] ?? null,
            $data['anexos_fondo_saco'] ?? null,
            $data['evaluacion_id']
        ]);
    }

    public function delete($evaluacionId)
    {
        $stmt = $this->db->prepare("DELETE FROM impresion_diagnostica_ginecologica WHERE evaluacion_id = ?");
        return $stmt->execute([$evaluacionId]);
    }
}
