<?php
require_once __DIR__ . '/../core/Database.php';

class RegistroFirma
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByAsignacion($asignacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM registro_firmas WHERE asignacion_id = ? ORDER BY fecha_firma ASC");
        $stmt->execute([$asignacionId]);
        return $stmt->fetchAll();
    }

    public function create($asignacionId, $rolFirmante, $nombreFirmante, $rutaImagenFirma, $ipOrigen, $tipoAccion = 'Aceptacion')
    {
        $stmt = $this->db->prepare("INSERT INTO registro_firmas (asignacion_id, rol_firmante, nombre_firmante, ruta_imagen_firma, ip_origen, tipo_accion) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$asignacionId, $rolFirmante, $nombreFirmante, $rutaImagenFirma, $ipOrigen, $tipoAccion]);
        return $this->db->lastInsertId();
    }

    public function getFirmasCompletadas($asignacionId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM registro_firmas WHERE asignacion_id = ?");
        $stmt->execute([$asignacionId]);
        $result = $stmt->fetch();
        return $result ? (int) $result['total'] : 0;
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM registro_firmas WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
