<?php
require_once __DIR__ . '/../core/Database.php';

class MiomasGinecologicos
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM miomas_ginecologicos WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO miomas_ginecologicos (
                evaluacion_id, identificados, numero_aproximado, mioma_dominante_mm,
                predominio_submucosos, predominio_intramurales, predominio_subserosos,
                predominio_pediculados, predominio_cervicales, predominio_distribucion_difusa
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['identificados'] ?? 0,
            $data['numero_aproximado'] ?? null,
            $data['mioma_dominante_mm'] ?? null,
            $data['predominio_submucosos'] ?? 0,
            $data['predominio_intramurales'] ?? 0,
            $data['predominio_subserosos'] ?? 0,
            $data['predominio_pediculados'] ?? 0,
            $data['predominio_cervicales'] ?? 0,
            $data['predominio_distribucion_difusa'] ?? 0
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE miomas_ginecologicos SET
                identificados = ?, numero_aproximado = ?, mioma_dominante_mm = ?,
                predominio_submucosos = ?, predominio_intramurales = ?, predominio_subserosos = ?,
                predominio_pediculados = ?, predominio_cervicales = ?, predominio_distribucion_difusa = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['identificados'] ?? 0,
            $data['numero_aproximado'] ?? null,
            $data['mioma_dominante_mm'] ?? null,
            $data['predominio_submucosos'] ?? 0,
            $data['predominio_intramurales'] ?? 0,
            $data['predominio_subserosos'] ?? 0,
            $data['predominio_pediculados'] ?? 0,
            $data['predominio_cervicales'] ?? 0,
            $data['predominio_distribucion_difusa'] ?? 0,
            $data['evaluacion_id']
        ]);
    }

    public function delete($evaluacionId)
    {
        $stmt = $this->db->prepare("DELETE FROM miomas_ginecologicos WHERE evaluacion_id = ?");
        return $stmt->execute([$evaluacionId]);
    }
}
