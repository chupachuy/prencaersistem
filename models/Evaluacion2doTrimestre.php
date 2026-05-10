<?php
require_once __DIR__ . '/../core/Database.php';

class Evaluacion2doTrimestre
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT e.*, p.nombre as paciente_nombre, p.apellido as paciente_apellido,
                   u.nombre as medico_nombre, u.apellido as medico_apellido
            FROM evaluaciones_2do_trimestre e
            JOIN pacientes p ON e.paciente_id = p.id
            JOIN usuarios u ON e.medico_id = u.id
            WHERE e.activo = 1
            ORDER BY e.fecha_evaluacion DESC
        ");
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("
            SELECT e.*, p.nombre as paciente_nombre, p.apellido as paciente_apellido, p.fecha_nacimiento,
                   u.nombre as medico_nombre, u.apellido as medico_apellido
            FROM evaluaciones_2do_trimestre e
            JOIN pacientes p ON e.paciente_id = p.id
            JOIN usuarios u ON e.medico_id = u.id
            WHERE e.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByPaciente($pacienteId)
    {
        $stmt = $this->db->prepare("
            SELECT e.*, u.nombre as medico_nombre, u.apellido as medico_apellido
            FROM evaluaciones_2do_trimestre e
            JOIN usuarios u ON e.medico_id = u.id
            WHERE e.paciente_id = ? AND e.activo = 1
            ORDER BY e.fecha_evaluacion DESC
        ");
        $stmt->execute([$pacienteId]);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO evaluaciones_2do_trimestre (
                paciente_id, medico_id, codigo_reporte, fecha_evaluacion, fecha_estudio,
                edad_gestacional_semanas, fpp_actual, peso_kg, talla_cm,
                pam_mmhg, uta_pi_promedio, estado, created_by, updated_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['paciente_id'], $data['medico_id'], $data['codigo_reporte'],
            $data['fecha_evaluacion'], $data['fecha_estudio'] ?? null,
            $data['edad_gestacional_semanas'] ?? null, $data['fpp_actual'] ?? null,
            $data['peso_kg'] ?? null, $data['talla_cm'] ?? null,
            $data['pam_mmhg'] ?? null, $data['uta_pi_promedio'] ?? null,
            $data['estado'] ?? 'Pendiente', $data['created_by'], $data['updated_by']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE evaluaciones_2do_trimestre SET
                paciente_id = ?, medico_id = ?, fecha_estudio = ?,
                edad_gestacional_semanas = ?, fpp_actual = ?, peso_kg = ?, talla_cm = ?,
                pam_mmhg = ?, uta_pi_promedio = ?, estado = ?, updated_by = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['paciente_id'], $data['medico_id'], $data['fecha_estudio'] ?? null,
            $data['edad_gestacional_semanas'] ?? null, $data['fpp_actual'] ?? null,
            $data['peso_kg'] ?? null, $data['talla_cm'] ?? null,
            $data['pam_mmhg'] ?? null, $data['uta_pi_promedio'] ?? null,
            $data['estado'] ?? 'Pendiente', $data['updated_by'], $data['id']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE evaluaciones_2do_trimestre SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function generateCodigoReporte()
    {
        $year = date('Y');
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM evaluaciones_2do_trimestre WHERE YEAR(fecha_evaluacion) = ?");
        $stmt->execute([$year]);
        $result = $stmt->fetch();
        $numero = $result['total'] + 1;
        return 'EV2T-' . str_pad($numero, 4, '0', STR_PAD_LEFT) . '-' . $year;
    }
}
