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

    public function create($nombre, $apellido, $userId, $fecha_nacimiento = null, $email = null, $telefono = null, $direccion = null, $tipo_seguimiento = 'Propia')
    {
        if (!$fecha_nacimiento) {
            $fecha_nacimiento = date('Y-m-d');
        }
        $stmt = $this->db->prepare("INSERT INTO pacientes (nombre, apellido, fecha_nacimiento, email, telefono, direccion, tipo_seguimiento, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $apellido, $fecha_nacimiento, $email, $telefono, $direccion, $tipo_seguimiento, $userId]);
        return $this->db->lastInsertId();
    }

    public function getAllWithSeguimiento($medicoId = null)
    {
        $sql = "
            SELECT p.*,
                   e1.fpp_usg,
                   e1.fecha_estudio as ultima_evaluacion,
                   GROUP_CONCAT(DISTINCT d.titulo SEPARATOR ' | ') as diagnosticos_activos
            FROM pacientes p
            LEFT JOIN (
                SELECT e.paciente_id, e.fpp_usg, e.fecha_estudio
                FROM evaluaciones_1er_trimestre e
                INNER JOIN (
                    SELECT paciente_id, MAX(fecha_evaluacion) as max_fecha
                    FROM evaluaciones_1er_trimestre
                    WHERE activo = 1
                    GROUP BY paciente_id
                ) latest ON e.paciente_id = latest.paciente_id AND e.fecha_evaluacion = latest.max_fecha
                WHERE e.activo = 1
            ) e1 ON p.id = e1.paciente_id
            LEFT JOIN diagnosticos d ON d.paciente_id = p.id AND d.estado IN ('Activo', 'En tratamiento')
        ";
        if ($medicoId !== null) {
            $sql .= "
                LEFT JOIN asignaciones a ON a.paciente_id = p.id AND a.activo = 1
                WHERE (p.created_by = ? OR a.medico_id = ?)
            ";
            $sql .= " GROUP BY p.id ORDER BY p.fecha_alta IS NOT NULL, p.nombre ASC, p.apellido ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$medicoId, $medicoId]);
        } else {
            $sql .= " GROUP BY p.id ORDER BY p.fecha_alta IS NOT NULL, p.nombre ASC, p.apellido ASC";
            $stmt = $this->db->query($sql);
        }
        return $stmt->fetchAll();
    }

    public function darAlta($pacienteId)
    {
        $stmt = $this->db->prepare("UPDATE pacientes SET fecha_alta = CURDATE() WHERE id = ?");
        return $stmt->execute([$pacienteId]);
    }

    public function updateTipoSeguimiento($id, $tipo)
    {
        $stmt = $this->db->prepare("UPDATE pacientes SET tipo_seguimiento = ? WHERE id = ?");
        return $stmt->execute([$tipo, $id]);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM pacientes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE pacientes SET nombre = ?, apellido = ?, fecha_nacimiento = ?, email = ?, telefono = ?, direccion = ?, tipo_seguimiento = ?, updated_by = ? WHERE id = ?");
        return $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['fecha_nacimiento'],
            $data['email'],
            $data['telefono'],
            $data['direccion'],
            $data['tipo_seguimiento'],
            $data['updated_by'],
            $id
        ]);
    }
}
