<?php
require_once __DIR__ . '/../core/Database.php';

class EmbrioTemprano
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByUltrasonido($ultrasonidoId)
    {
        $stmt = $this->db->prepare("SELECT * FROM embriones_temprano WHERE ultrasonido_id = ? ORDER BY numero ASC");
        $stmt->execute([$ultrasonidoId]);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO embriones_temprano (ultrasonido_id, numero, crl_mm, fcf_visible, fcf_lpm, localizacion)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['ultrasonido_id'],
            $data['numero'],
            $data['crl_mm'] ?? null,
            $data['fcf_visible'] ?? null,
            $data['fcf_lpm'] ?? null,
            $data['localizacion'] ?? null
        ]);
    }

    public function deleteByUltrasonido($ultrasonidoId)
    {
        $stmt = $this->db->prepare("DELETE FROM embriones_temprano WHERE ultrasonido_id = ?");
        return $stmt->execute([$ultrasonidoId]);
    }
}
