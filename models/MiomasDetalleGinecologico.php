<?php
require_once __DIR__ . '/../core/Database.php';

class MiomasDetalleGinecologico
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM miomas_detalle_ginecologico WHERE evaluacion_id = ? ORDER BY numero ASC");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO miomas_detalle_ginecologico (
                evaluacion_id, numero, localizacion, medida_x_mm, medida_y_mm, medida_z_mm,
                relacion_endometrio, clasificacion_figo, doppler, comentarios
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['numero'],
            $data['localizacion'] ?? null,
            $data['medida_x_mm'] ?? null,
            $data['medida_y_mm'] ?? null,
            $data['medida_z_mm'] ?? null,
            $data['relacion_endometrio'] ?? null,
            $data['clasificacion_figo'] ?? null,
            $data['doppler'] ?? null,
            $data['comentarios'] ?? null
        ]);
    }

    public function deleteByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("DELETE FROM miomas_detalle_ginecologico WHERE evaluacion_id = ?");
        return $stmt->execute([$evaluacionId]);
    }
}
