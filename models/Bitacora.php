<?php

class Bitacora
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function registrar($usuarioId, $accion, $descripcion, $modulo, $registroId = null)
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $stmt = $this->db->prepare(
            "INSERT INTO bitacora (usuario_id, accion, descripcion, modulo, registro_id, ip) VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$usuarioId, $accion, $descripcion, $modulo, $registroId, $ip]);
    }

    public function getAll($limit = 50, $offset = 0)
    {
        $stmt = $this->db->prepare(
            "SELECT b.*, u.nombre, u.apellido, u.email
             FROM bitacora b
             JOIN usuarios u ON b.usuario_id = u.id
             ORDER BY b.created_at DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count()
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM bitacora")->fetchColumn();
    }
}
