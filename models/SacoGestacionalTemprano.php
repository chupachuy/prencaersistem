<?php
require_once __DIR__ . '/../core/Database.php';

class SacoGestacionalTemprano
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByUltrasonido($ultrasonidoId)
    {
        $stmt = $this->db->prepare("SELECT * FROM sacos_gestacionales_temprano WHERE ultrasonido_id = ? ORDER BY numero ASC");
        $stmt->execute([$ultrasonidoId]);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO sacos_gestacionales_temprano (ultrasonido_id, numero, medida_mm, morfologia, sv_presente, sv_diametro_mm, descripcion)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['ultrasonido_id'],
            $data['numero'],
            $data['medida_mm'] ?? null,
            $data['morfologia'] ?? null,
            $data['sv_presente'] ?? null,
            $data['sv_diametro_mm'] ?? null,
            $data['descripcion'] ?? null
        ]);
    }

    public function getLastInsertId()
    {
        return $this->db->lastInsertId();
    }

    public function deleteByUltrasonido($ultrasonidoId)
    {
        $stmt = $this->db->prepare("DELETE FROM sacos_gestacionales_temprano WHERE ultrasonido_id = ?");
        return $stmt->execute([$ultrasonidoId]);
    }
}
