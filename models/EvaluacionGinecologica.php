<?php
require_once __DIR__ . '/../core/Database.php';

class EvaluacionGinecologica
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($medicoId = null)
    {
        $sql = "
            SELECT e.*, p.nombre as paciente_nombre, p.apellido as paciente_apellido,
                   us.nombre as medico_nombre, us.apellido as medico_apellido,
                   usol.nombre as medico_solicitante_nombre, usol.apellido as medico_solicitante_apellido,
                   uref.nombre as medico_referido_nombre, uref.apellido as medico_referido_apellido
            FROM evaluaciones_ginecologicas e
            JOIN pacientes p ON e.paciente_id = p.id
            JOIN usuarios us ON e.medico_id = us.id
            LEFT JOIN usuarios usol ON e.medico_solicitante_id = usol.id
            LEFT JOIN usuarios uref ON e.medico_referido_id = uref.id
            WHERE e.activo = 1
        ";
        if ($medicoId !== null) {
            $sql .= " AND e.medico_id = ?";
            $stmt = $this->db->prepare($sql . " ORDER BY e.fecha_estudio DESC");
            $stmt->execute([$medicoId]);
        } else {
            $stmt = $this->db->query($sql . " ORDER BY e.fecha_estudio DESC");
        }
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("
            SELECT e.*, p.nombre as paciente_nombre, p.apellido as paciente_apellido, p.fecha_nacimiento, p.email as paciente_email,
                   us.nombre as medico_nombre, us.apellido as medico_apellido, us.email as medico_email,
                   usol.nombre as medico_solicitante_nombre, usol.apellido as medico_solicitante_apellido, usol.email as medico_solicitante_email,
                   uref.nombre as medico_referido_nombre, uref.apellido as medico_referido_apellido, uref.email as medico_referido_email
            FROM evaluaciones_ginecologicas e
            JOIN pacientes p ON e.paciente_id = p.id
            JOIN usuarios us ON e.medico_id = us.id
            LEFT JOIN usuarios usol ON e.medico_solicitante_id = usol.id
            LEFT JOIN usuarios uref ON e.medico_referido_id = uref.id
            WHERE e.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByPaciente($pacienteId)
    {
        $stmt = $this->db->prepare("
            SELECT e.*, us.nombre as medico_nombre, us.apellido as medico_apellido
            FROM evaluaciones_ginecologicas e
            JOIN usuarios us ON e.medico_id = us.id
            WHERE e.paciente_id = ? AND e.activo = 1
            ORDER BY e.fecha_estudio DESC
        ");
        $stmt->execute([$pacienteId]);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO evaluaciones_ginecologicas (
                paciente_id, medico_id, medico_solicitante_id, medico_referido_id, codigo_reporte, fecha_estudio,
                indicacion_clinica, fum, dia_ciclo_menstrual, observaciones,
                estado, created_by, updated_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['paciente_id'],
            $data['medico_id'],
            $data['medico_solicitante_id'] ?? null,
            $data['medico_referido_id'] ?? null,
            $data['codigo_reporte'],
            $data['fecha_estudio'],
            $data['indicacion_clinica'] ?? null,
            $data['fum'] ?? null,
            $data['dia_ciclo_menstrual'] ?? null,
            $data['observaciones'] ?? null,
            $data['estado'] ?? 'Pendiente',
            $data['created_by'],
            $data['updated_by']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE evaluaciones_ginecologicas SET
                paciente_id = ?, medico_id = ?, medico_solicitante_id = ?, medico_referido_id = ?, fecha_estudio = ?,
                indicacion_clinica = ?, fum = ?, dia_ciclo_menstrual = ?, observaciones = ?,
                estado = ?, updated_by = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['paciente_id'],
            $data['medico_id'],
            $data['medico_solicitante_id'] ?? null,
            $data['medico_referido_id'] ?? null,
            $data['fecha_estudio'],
            $data['indicacion_clinica'] ?? null,
            $data['fum'] ?? null,
            $data['dia_ciclo_menstrual'] ?? null,
            $data['observaciones'] ?? null,
            $data['estado'] ?? 'Pendiente',
            $data['updated_by'],
            $data['id']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE evaluaciones_ginecologicas SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function generateCodigoReporte()
    {
        $year = date('Y');
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM evaluaciones_ginecologicas WHERE YEAR(fecha_estudio) = ?");
        $stmt->execute([$year]);
        $result = $stmt->fetch();
        $numero = $result['total'] + 1;
        return 'USG-' . str_pad($numero, 4, '0', STR_PAD_LEFT) . '-' . $year;
    }
}
