<?php
require_once __DIR__ . '/../core/Database.php';

class Paciente
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByIdOrName($input)
    {
        $stmt = $this->db->prepare("SELECT * FROM pacientes WHERE id = ? OR CONCAT(nombre, ' ', apellido) LIKE ? LIMIT 1");
        $stmt->execute([$input, "%$input%"]);
        return $stmt->fetch();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM pacientes ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getAllByMedico($medicoId)
    {
        // Get patients that were either assigned to this doctor OR created by this doctor but not assigned yet.
        $stmt = $this->db->prepare("
            SELECT DISTINCT p.* 
            FROM pacientes p
            LEFT JOIN asignaciones a ON a.paciente_id = p.id
            WHERE p.created_by = ? OR (a.medico_id = ? AND a.activo = 1)
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$medicoId, $medicoId]);
        return $stmt->fetchAll();
    }

    public function create($nombre, $apellido, $userId, $fecha_nacimiento = null)
    {
        if (!$fecha_nacimiento) {
            $fecha_nacimiento = date('Y-m-d');
        }
        $stmt = $this->db->prepare("INSERT INTO pacientes (nombre, apellido, fecha_nacimiento, created_by) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $apellido, $fecha_nacimiento, $userId]);
        return $this->db->lastInsertId();
    }
}
