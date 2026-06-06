<?php
require_once __DIR__ . '/../core/Database.php';

class Referencia
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("
            SELECT r.*, 
                p.nombre as paciente_nombre, p.apellido as paciente_apellido,
                ms.nombre as solicitante_nombre, ms.apellido as solicitante_apellido, ms.especialidad as solicitante_especialidad,
                mr.nombre as referido_nombre, mr.apellido as referido_apellido, mr.especialidad as referido_especialidad, mr.email as referido_email,
                mre.nombre as ref_ext_nombre, mre.apellido as ref_ext_apellido, mre.especialidad as ref_ext_especialidad, mre.email as ref_ext_email, mre.institucion as ref_ext_institucion
            FROM referencias r
            LEFT JOIN pacientes p ON r.paciente_id = p.id
            LEFT JOIN usuarios ms ON r.medico_solicitante_id = ms.id
            LEFT JOIN usuarios mr ON r.medico_referido_id = mr.id
            LEFT JOIN medicos_referidos mre ON r.medico_referido_externo_id = mre.id
            WHERE r.id = ? AND r.activo = 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT r.*, 
                p.nombre as paciente_nombre, p.apellido as paciente_apellido,
                ms.nombre as solicitante_nombre, ms.apellido as solicitante_apellido,
                mr.nombre as referido_nombre, mr.apellido as referido_apellido,
                mre.nombre as ref_ext_nombre, mre.apellido as ref_ext_apellido
            FROM referencias r
            LEFT JOIN pacientes p ON r.paciente_id = p.id
            LEFT JOIN usuarios ms ON r.medico_solicitante_id = ms.id
            LEFT JOIN usuarios mr ON r.medico_referido_id = mr.id
            LEFT JOIN medicos_referidos mre ON r.medico_referido_externo_id = mre.id
            WHERE r.activo = 1
            ORDER BY r.fecha_referencia DESC, r.id DESC
        ");
        return $stmt->fetchAll();
    }

    public function getByMedico($medicoId)
    {
        $stmt = $this->db->prepare("
            SELECT r.*, 
                p.nombre as paciente_nombre, p.apellido as paciente_apellido,
                ms.nombre as solicitante_nombre, ms.apellido as solicitante_apellido,
                mr.nombre as referido_nombre, mr.apellido as referido_apellido,
                mre.nombre as ref_ext_nombre, mre.apellido as ref_ext_apellido
            FROM referencias r
            LEFT JOIN pacientes p ON r.paciente_id = p.id
            LEFT JOIN usuarios ms ON r.medico_solicitante_id = ms.id
            LEFT JOIN usuarios mr ON r.medico_referido_id = mr.id
            LEFT JOIN medicos_referidos mre ON r.medico_referido_externo_id = mre.id
            WHERE r.activo = 1 AND (r.medico_solicitante_id = ? OR r.medico_referido_id = ?)
            ORDER BY r.fecha_referencia DESC, r.id DESC
        ");
        $stmt->execute([$medicoId, $medicoId]);
        return $stmt->fetchAll();
    }

    public function getReferenciasCompletas($filters = [])
    {
        $sql = "SELECT r.*, 
                    p.nombre as paciente_nombre, p.apellido as paciente_apellido,
                    ms.nombre as solicitante_nombre, ms.apellido as solicitante_apellido,
                    mr.nombre as referido_nombre, mr.apellido as referido_apellido,
                    mre.nombre as ref_ext_nombre, mre.apellido as ref_ext_apellido,
                    mre.institucion as ref_ext_institucion
                FROM referencias r
                LEFT JOIN pacientes p ON r.paciente_id = p.id
                LEFT JOIN usuarios ms ON r.medico_solicitante_id = ms.id
                LEFT JOIN usuarios mr ON r.medico_referido_id = mr.id
                LEFT JOIN medicos_referidos mre ON r.medico_referido_externo_id = mre.id
                WHERE r.activo = 1";

        $params = [];

        if (!empty($filters['paciente_id'])) {
            $sql .= " AND r.paciente_id = ?";
            $params[] = $filters['paciente_id'];
        }

        if (!empty($filters['medico_id'])) {
            $sql .= " AND (r.medico_solicitante_id = ? OR r.medico_referido_id = ?)";
            $params[] = $filters['medico_id'];
            $params[] = $filters['medico_id'];
        }

        if (!empty($filters['estado'])) {
            $sql .= " AND r.estado = ?";
            $params[] = $filters['estado'];
        }

        $sql .= " ORDER BY r.fecha_referencia DESC, r.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function generarCodigo()
    {
        $anio = date('Y');
        $stmt = $this->db->prepare("SELECT MAX(CAST(SUBSTRING_INDEX(codigo_referencia, '-', -1) AS UNSIGNED)) as ultimo FROM referencias WHERE codigo_referencia LIKE ?");
        $stmt->execute(["REF-%{$anio}"]);
        $result = $stmt->fetch();
        $ultimo = $result && $result['ultimo'] ? (int)$result['ultimo'] : 0;
        $siguiente = $ultimo + 1;
        return 'REF-' . str_pad($siguiente, 4, '0', STR_PAD_LEFT) . '-' . $anio;
    }

    public function create($data)
    {
        $codigo = $this->generarCodigo();

        $sql = "INSERT INTO referencias (
            codigo_referencia, paciente_id, medico_solicitante_id, medico_referido_id,
            medico_referido_externo_id,
            tipo_estudio, motivo_referencia, observaciones, estado, fecha_referencia,
            fecha_respuesta, respuesta_motivo, informe_exploracion_id,
            activo, created_by, updated_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $codigo,
            $data['paciente_id'],
            $data['medico_solicitante_id'],
            $data['medico_referido_id'] ?? null,
            $data['medico_referido_externo_id'] ?? null,
            $data['tipo_estudio'],
            $data['motivo_referencia'],
            $data['observaciones'] ?? null,
            $data['estado'] ?? 'Pendiente',
            $data['fecha_referencia'],
            $data['fecha_respuesta'] ?? null,
            $data['respuesta_motivo'] ?? null,
            $data['informe_exploracion_id'] ?? null,
            $data['created_by'],
            $data['updated_by']
        ]);

        return $this->db->lastInsertId();
    }

    public function updateEstado($id, $estado, $fechaRespuesta, $motivo = null)
    {
        $sql = "UPDATE referencias SET estado = ?, fecha_respuesta = ?, respuesta_motivo = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$estado, $fechaRespuesta, $motivo, $id]);
    }

    public function update($id, $data)
    {
        $fields = [];
        $values = [];

        $allowedFields = [
            'tipo_estudio', 'motivo_referencia', 'observaciones',
            'estado', 'fecha_respuesta', 'respuesta_motivo',
            'informe_exploracion_id'
        ];

        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "$field = ?";
                $values[] = $data[$field];
            }
        }

        if (empty($fields)) return false;

        $values[] = $id;
        $sql = "UPDATE referencias SET " . implode(', ', $fields) . " WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function eliminar($id)
    {
        $stmt = $this->db->prepare("UPDATE referencias SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
