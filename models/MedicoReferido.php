<?php
require_once __DIR__ . '/../core/Database.php';

class MedicoReferido
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM medicos_referidos WHERE activo = 1 ORDER BY nombre ASC, apellido ASC");
        return $stmt->fetchAll();
    }

    public function getActivos()
    {
        $stmt = $this->db->query("SELECT id, nombre, apellido, email, especialidad, institucion FROM medicos_referidos WHERE activo = 1 ORDER BY nombre ASC, apellido ASC");
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM medicos_referidos WHERE id = ? AND activo = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO medicos_referidos (nombre, apellido, email, telefono, especialidad, institucion, created_by, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['email'],
            $data['telefono'] ?? null,
            $data['especialidad'] ?? null,
            $data['institucion'] ?? null,
            $data['created_by'],
            $data['updated_by']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $fields = [];
        $values = [];

        $allowedFields = ['nombre', 'apellido', 'email', 'telefono', 'especialidad', 'institucion'];

        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "$field = ?";
                $values[] = $data[$field];
            }
        }

        if (empty($fields)) return false;

        $values[] = $id;
        $sql = "UPDATE medicos_referidos SET " . implode(', ', $fields) . " WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function eliminar($id)
    {
        $stmt = $this->db->prepare("UPDATE medicos_referidos SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
