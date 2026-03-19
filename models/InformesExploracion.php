<?php
require_once __DIR__ . '/../core/Database.php';

class InformesExploracion
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM informes_exploracion WHERE id = ? AND activo = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM informes_exploracion WHERE activo = 1 ORDER BY fecha_informe DESC");
        return $stmt->fetchAll();
    }

    public function getByPaciente($pacienteId)
    {
        $stmt = $this->db->prepare("SELECT * FROM informes_exploracion WHERE paciente_id = ? AND activo = 1 ORDER BY trimestre ASC");
        $stmt->execute([$pacienteId]);
        return $stmt->fetchAll();
    }

    public function getByTrimestre($pacienteId, $trimestre)
    {
        $stmt = $this->db->prepare("SELECT * FROM informes_exploracion WHERE paciente_id = ? AND trimestre = ? AND activo = 1");
        $stmt->execute([$pacienteId, $trimestre]);
        return $stmt->fetch();
    }

    public function generarCodigoInforme($pacienteId, $trimestre)
    {
        return 'IE-' . str_pad($pacienteId, 4, '0', STR_PAD_LEFT) . '-T' . $trimestre . '-' . date('Y');
    }

    public function crearInforme($data)
    {
        $codigo = $this->generarCodigoInforme($data['paciente_id'], $data['trimestre']);
        
        $sql = "INSERT INTO informes_exploracion (
            paciente_id, medico_id, medico_referido_id, codigo_informe, trimestre,
            fecha_informe, estudio_solicitado, fecha_publicacion_parto_usg,
            fecha_probable_parto_usg, resumen_ultrasonido, observaciones,
            estado, activo, created_by, updated_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['paciente_id'],
            $data['medico_id'],
            $data['medico_referido_id'],
            $codigo,
            $data['trimestre'],
            $data['fecha_informe'],
            $data['estudio_solicitado'] ?? null,
            $data['fecha_publicacion_parto_usg'] ?? null,
            $data['fecha_probable_parto_usg'] ?? null,
            $data['resumen_ultrasonido'] ?? null,
            $data['observaciones'] ?? null,
            $data['estado'] ?? 'Pendiente',
            $data['created_by'],
            $data['updated_by']
        ]);
        
        return $this->db->lastInsertId();
    }

    public function actualizarInforme($id, $data)
    {
        require_once __DIR__ . '/../helpers/Auth.php';
        
        $fields = [];
        $values = [];
        
        $allowedFields = [
            'medico_referido_id', 'fecha_informe', 'estudio_solicitado',
            'fecha_publicacion_parto_usg', 'fecha_probable_parto_usg',
            'resumen_ultrasonido', 'observaciones', 'estado'
        ];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field]) && $data[$field] !== '') {
                $fields[] = "$field = ?";
                $values[] = $data[$field];
            }
        }
        
        if (empty($fields)) return false;
        
        $updatedBy = Auth::id() ?? 1;
        $values[] = $updatedBy;
        $values[] = $id;
        $sql = "UPDATE informes_exploracion SET " . implode(', ', $fields) . ", updated_by = ? WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function getInformesCompletos($filters = [])
    {
        $sql = "SELECT 
                    ie.*, 
                    p.nombre as paciente_nombre,
                    p.apellido as paciente_apellido,
                    m.nombre as medico_nombre,
                    m.apellido as medico_apellido,
                    mr.nombre as medico_ref_nombre,
                    mr.apellido as medico_ref_apellido
                FROM informes_exploracion ie
                LEFT JOIN pacientes p ON ie.paciente_id = p.id
                LEFT JOIN usuarios m ON ie.medico_id = m.id
                LEFT JOIN usuarios mr ON ie.medico_referido_id = mr.id
                WHERE ie.activo = 1";
        
        $params = [];
        
        if (!empty($filters['paciente_id'])) {
            $sql .= " AND ie.paciente_id = ?";
            $params[] = $filters['paciente_id'];
        }

        if (!empty($filters['trimestre'])) {
            $sql .= " AND ie.trimestre = ?";
            $params[] = $filters['trimestre'];
        }

        if (!empty($filters['medico_id'])) {
            $sql .= " AND ie.medico_referido_id = ?";
            $params[] = $filters['medico_id'];
        }

        if (!empty($filters['estado'])) {
            $sql .= " AND ie.estado = ?";
            $params[] = $filters['estado'];
        }

        $sql .= " ORDER BY ie.fecha_informe DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function eliminar($id)
    {
        $stmt = $this->db->prepare("UPDATE informes_exploracion SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
