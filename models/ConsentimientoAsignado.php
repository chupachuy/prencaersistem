<?php
require_once __DIR__ . '/../core/Database.php';

class ConsentimientoAsignado
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT ca.*, 
                   p.nombre AS paciente_nombre, p.apellido AS paciente_apellido,
                   u.nombre AS medico_nombre, u.apellido AS medico_apellido,
                   cc.nombre_documento, cc.version
            FROM consentimientos_asignados ca
            JOIN pacientes p ON ca.paciente_id = p.id
            JOIN usuarios u ON ca.medico_id = u.id
            JOIN catalogo_consentimientos cc ON ca.documento_id = cc.id
            WHERE ca.activo = 1
            ORDER BY ca.fecha_generacion DESC
        ");
        return $stmt->fetchAll();
    }

    public function getAllByMedico($medicoId)
    {
        $stmt = $this->db->prepare("
            SELECT ca.*, 
                   p.nombre AS paciente_nombre, p.apellido AS paciente_apellido,
                   u.nombre AS medico_nombre, u.apellido AS medico_apellido,
                   cc.nombre_documento, cc.version
            FROM consentimientos_asignados ca
            JOIN pacientes p ON ca.paciente_id = p.id
            JOIN usuarios u ON ca.medico_id = u.id
            JOIN catalogo_consentimientos cc ON ca.documento_id = cc.id
            WHERE ca.medico_id = ? AND ca.activo = 1
            ORDER BY ca.fecha_generacion DESC
        ");
        $stmt->execute([$medicoId]);
        return $stmt->fetchAll();
    }

    public function getByPaciente($pacienteId)
    {
        $stmt = $this->db->prepare("
            SELECT ca.*, 
                   u.nombre AS medico_nombre, u.apellido AS medico_apellido,
                   cc.nombre_documento, cc.version, cc.contenido, cc.requiere_firma_medico, cc.cantidad_testigos
            FROM consentimientos_asignados ca
            JOIN usuarios u ON ca.medico_id = u.id
            JOIN catalogo_consentimientos cc ON ca.documento_id = cc.id
            WHERE ca.paciente_id = ? AND ca.activo = 1
            ORDER BY ca.fecha_generacion DESC
        ");
        $stmt->execute([$pacienteId]);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("
            SELECT ca.*, 
                   p.nombre AS paciente_nombre, p.apellido AS paciente_apellido,
                   u.nombre AS medico_nombre, u.apellido AS medico_apellido,
                   u.telefono AS medico_telefono,
                   cc.nombre_documento, cc.version, cc.contenido, cc.requiere_firma_medico, cc.cantidad_testigos
            FROM consentimientos_asignados ca
            JOIN pacientes p ON ca.paciente_id = p.id
            JOIN usuarios u ON ca.medico_id = u.id
            JOIN catalogo_consentimientos cc ON ca.documento_id = cc.id
            WHERE ca.id = ? AND ca.activo = 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($pacienteId, $medicoId, $documentoId, $datosDinamicos = null, $createdBy = null)
    {
        $stmt = $this->db->prepare("INSERT INTO consentimientos_asignados (paciente_id, medico_id, documento_id, datos_dinamicos, created_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$pacienteId, $medicoId, $documentoId, $datosDinamicos, $createdBy]);
        return $this->db->lastInsertId();
    }

    public function updateEstado($id, $estado)
    {
        $stmt = $this->db->prepare("UPDATE consentimientos_asignados SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }

    public function updateRutaPDF($id, $ruta)
    {
        $stmt = $this->db->prepare("UPDATE consentimientos_asignados SET ruta_pdf_final = ? WHERE id = ?");
        return $stmt->execute([$ruta, $id]);
    }

    public function getAllPaginated($search = '', $page = 1, $perPage = 10, $medicoId = null)
    {
        $offset = ($page - 1) * $perPage;
        $conditions = ['ca.activo = 1'];
        $params = [];

        if ($medicoId !== null) {
            $conditions[] = 'ca.medico_id = ?';
            $params[] = $medicoId;
        }

        if (!empty($search)) {
            $conditions[] = '(p.nombre LIKE ? OR p.apellido LIKE ? OR u.nombre LIKE ? OR u.apellido LIKE ? OR cc.nombre_documento LIKE ?)';
            $s = '%' . $search . '%';
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
        }

        $where = implode(' AND ', $conditions);
        $params[] = $perPage;
        $params[] = $offset;

        $stmt = $this->db->prepare("
            SELECT ca.*, 
                   p.nombre AS paciente_nombre, p.apellido AS paciente_apellido,
                   u.nombre AS medico_nombre, u.apellido AS medico_apellido,
                   cc.nombre_documento, cc.version
            FROM consentimientos_asignados ca
            JOIN pacientes p ON ca.paciente_id = p.id
            JOIN usuarios u ON ca.medico_id = u.id
            JOIN catalogo_consentimientos cc ON ca.documento_id = cc.id
            WHERE $where
            ORDER BY ca.fecha_generacion DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function countAll($search = '', $medicoId = null)
    {
        $conditions = ['ca.activo = 1'];
        $params = [];

        if ($medicoId !== null) {
            $conditions[] = 'ca.medico_id = ?';
            $params[] = $medicoId;
        }

        if (!empty($search)) {
            $conditions[] = '(p.nombre LIKE ? OR p.apellido LIKE ? OR u.nombre LIKE ? OR u.apellido LIKE ? OR cc.nombre_documento LIKE ?)';
            $s = '%' . $search . '%';
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
        }

        $where = implode(' AND ', $conditions);

        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total
            FROM consentimientos_asignados ca
            JOIN pacientes p ON ca.paciente_id = p.id
            JOIN usuarios u ON ca.medico_id = u.id
            JOIN catalogo_consentimientos cc ON ca.documento_id = cc.id
            WHERE $where
        ");
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ? (int) $result['total'] : 0;
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE consentimientos_asignados SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
