<?php
require_once __DIR__ . '/../core/Database.php';

class Consulta
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO Consultas (id_paciente, motivo_consulta, observaciones) VALUES (?, ?, ?)");
        $stmt->execute([$data['id_paciente'], $data['motivo_consulta'], $data['observaciones']]);
        return $this->db->lastInsertId();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM Consultas ORDER BY fecha_consulta DESC");
        return $stmt->fetchAll();
    }

    public function getAllWithPaciente()
    {
        $stmt = $this->db->query("
            SELECT c.*, CONCAT(p.nombre, ' ', p.apellido) as paciente_nombre 
            FROM Consultas c
            LEFT JOIN pacientes p ON c.id_paciente = p.id
            ORDER BY c.fecha_consulta DESC
        ");
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM Consultas WHERE id_consulta = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByIdWithPaciente($id)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, CONCAT(p.nombre, ' ', p.apellido) as paciente_nombre, p.fecha_nacimiento
            FROM Consultas c
            LEFT JOIN pacientes p ON c.id_paciente = p.id
            WHERE c.id_consulta = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getPacientes()
    {
        $stmt = $this->db->query("SELECT id, CONCAT(nombre, ' ', apellido) as nombre FROM pacientes ORDER BY nombre");
        return $stmt->fetchAll();
    }
}
