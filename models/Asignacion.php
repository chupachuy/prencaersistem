<?php
require_once __DIR__ . '/../core/Database.php';

class Asignacion
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getActiveByMedico($medicoId)
    {
        $stmt = $this->db->prepare("
            SELECT a.*, p.nombre as paciente_nombre, p.apellido as paciente_apellido 
            FROM asignaciones a 
            JOIN pacientes p ON a.paciente_id = p.id 
            WHERE a.medico_id = ? AND a.activo = 1
        ");
        $stmt->execute([$medicoId]);
        return $stmt->fetchAll();
    }

    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT a.*, 
                   CONCAT(p.nombre, ' ', p.apellido) as paciente, 
                   CONCAT(u.nombre, ' ', u.apellido) as medico,
                   CONCAT(au.nombre, ' ', au.apellido) as asignador
            FROM asignaciones a 
            JOIN pacientes p ON a.paciente_id = p.id 
            JOIN usuarios u ON a.medico_id = u.id
            LEFT JOIN usuarios au ON a.asignado_por = au.id
            ORDER BY a.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO asignaciones (medico_id, paciente_id, asignado_por, fecha_asignacion, motivo) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['medico_id'],
            $data['paciente_id'],
            $data['asignado_por'],
            $data['fecha_asignacion'],
            $data['motivo']
        ]);
    }
}
