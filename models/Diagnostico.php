<?php
require_once __DIR__ . '/../core/Database.php';

class Diagnostico
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllByMedico($medicoId)
    {
        $stmt = $this->db->prepare("
            SELECT d.*, 
                   CONCAT(p.nombre, ' ', p.apellido) as paciente, 
                   CONCAT(u.nombre, ' ', u.apellido) as medico,
                   d.fecha_diagnostico as fecha
            FROM diagnosticos d 
            JOIN pacientes p ON d.paciente_id = p.id 
            JOIN usuarios u ON d.medico_id = u.id
            WHERE d.medico_id = ?
            ORDER BY d.fecha_diagnostico DESC, d.id DESC
        ");
        $stmt->execute([$medicoId]);
        return $stmt->fetchAll();
    }

    public function getAll()
    {
        // For Superadmin
        $stmt = $this->db->query("
            SELECT d.*, 
                   CONCAT(p.nombre, ' ', p.apellido) as paciente, 
                   CONCAT(u.nombre, ' ', u.apellido) as medico,
                   d.fecha_diagnostico as fecha
            FROM diagnosticos d 
            JOIN pacientes p ON d.paciente_id = p.id
            JOIN usuarios u ON d.medico_id = u.id
            ORDER BY d.fecha_diagnostico DESC, d.id DESC
        ");
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("
            SELECT d.*, 
                   CONCAT(p.nombre, ' ', p.apellido) as paciente, 
                   CONCAT(u.nombre, ' ', u.apellido) as medico,
                   d.fecha_diagnostico as fecha,
                   t.instrucciones as tratamiento
            FROM diagnosticos d 
            JOIN pacientes p ON d.paciente_id = p.id 
            JOIN usuarios u ON d.medico_id = u.id
            LEFT JOIN tratamientos t ON t.diagnostico_id = d.id
            WHERE d.id = ?
            LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO diagnosticos (paciente_id, medico_id, asignado_por, codigo_diagnostico, titulo, descripcion, fecha_diagnostico, gravedad, estado, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['paciente_id'],
            $data['medico_id'],
            $data['asignado_por'],
            $data['codigo_diagnostico'],
            $data['titulo'],
            $data['descripcion'],
            $data['fecha_diagnostico'],
            $data['gravedad'],
            $data['estado'],
            $data['created_by']
        ]);
    }

    public function getLastInsertId()
    {
        return $this->db->lastInsertId();
    }

    public function addTratamiento($diagnosticoId, $instrucciones, $userId)
    {
        $stmt = $this->db->prepare("
            INSERT INTO tratamientos (diagnostico_id, instrucciones, fecha_inicio, created_by)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$diagnosticoId, $instrucciones, date('Y-m-d'), $userId]);
    }
    public function update($id, $data)
    {
        $sql = "UPDATE diagnosticos SET paciente_id = ?, titulo = ?, descripcion = ?, updated_by = ?";
        $params = [
            $data['paciente_id'],
            $data['titulo'],
            $data['descripcion'],
            $data['updated_by']
        ];

        if (isset($data['medico_id'])) {
            $sql .= ", medico_id = ?";
            $params[] = $data['medico_id'];
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function updateTratamiento($diagnosticoId, $instrucciones, $userId)
    {
        // Check if treatment exists
        $stmt = $this->db->prepare("SELECT id FROM tratamientos WHERE diagnostico_id = ? LIMIT 1");
        $stmt->execute([$diagnosticoId]);
        $tratamiento = $stmt->fetch();

        if ($tratamiento) {
            if (empty($instrucciones)) {
                // Remove treatment if empty
                $deleteStmt = $this->db->prepare("DELETE FROM tratamientos WHERE id = ?");
                return $deleteStmt->execute([$tratamiento['id']]);
            } else {
                // Update
                $updateStmt = $this->db->prepare("UPDATE tratamientos SET instrucciones = ? WHERE id = ?");
                return $updateStmt->execute([$instrucciones, $tratamiento['id']]);
            }
        } else {
            if (!empty($instrucciones)) {
                // Insert
                return $this->addTratamiento($diagnosticoId, $instrucciones, $userId);
            }
        }
        return true;
    }
}
