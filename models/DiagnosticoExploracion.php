<?php
require_once __DIR__ . '/../core/Database.php';

class DiagnosticoExploracion
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM diagnosticos_exploracion WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByInforme($informeId)
    {
        $stmt = $this->db->prepare("SELECT * FROM diagnosticos_exploracion WHERE informe_exploracion_id = ?");
        $stmt->execute([$informeId]);
        return $stmt->fetchAll();
    }

    public function crearDiagnostico($data)
    {
        $sql = "INSERT INTO diagnosticos_exploracion (
            informe_exploracion_id, paciente_id, medico_id,
            codigo_diagnostico, titulo, descripcion, fecha_diagnostico
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['informe_exploracion_id'],
            $data['paciente_id'],
            $data['medico_id'],
            $data['codigo_diagnostico'] ?? null,
            $data['titulo'],
            $data['descripcion'] ?? null,
            $data['fecha_diagnostico']
        ]);
        
        return $this->db->lastInsertId();
    }

    public function actualizarDiagnostico($id, $data)
    {
        $fields = [];
        $values = [];
        
        $allowedFields = ['codigo_diagnostico', 'titulo', 'descripcion', 'fecha_diagnostico'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $values[] = $data[$field];
            }
        }
        
        if (empty($fields)) return false;
        
        $values[] = $id;
        $sql = "UPDATE diagnosticos_exploracion SET " . implode(', ', $fields) . " WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function eliminar($id)
    {
        $stmt = $this->db->prepare("DELETE FROM diagnosticos_exploracion WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function eliminarPorInforme($informeId)
    {
        $stmt = $this->db->prepare("DELETE FROM diagnosticos_exploracion WHERE informe_exploracion_id = ?");
        return $stmt->execute([$informeId]);
    }
}
