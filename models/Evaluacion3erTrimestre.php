<?php
require_once __DIR__ . '/../core/Database.php';

class Evaluacion3erTrimestre
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
                   u.nombre as medico_nombre, u.apellido as medico_apellido
            FROM evaluaciones_3er_trimestre e
            JOIN pacientes p ON e.paciente_id = p.id
            JOIN usuarios u ON e.medico_id = u.id
            WHERE e.activo = 1
        ";
        if ($medicoId !== null) {
            $sql .= " AND e.medico_id = ?";
            $stmt = $this->db->prepare($sql . " ORDER BY e.fecha_evaluacion DESC");
            $stmt->execute([$medicoId]);
        } else {
            $stmt = $this->db->query($sql . " ORDER BY e.fecha_evaluacion DESC");
        }
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("
            SELECT e.*, p.nombre as paciente_nombre, p.apellido as paciente_apellido, p.fecha_nacimiento, p.email as paciente_email,
                   u.nombre as medico_nombre, u.apellido as medico_apellido, u.email as medico_email,
                   usol.nombre as medico_solicitante_nombre, usol.apellido as medico_solicitante_apellido, usol.email as medico_solicitante_email,
                   uref.nombre as medico_referido_nombre, uref.apellido as medico_referido_apellido, uref.email as medico_referido_email
            FROM evaluaciones_3er_trimestre e
            JOIN pacientes p ON e.paciente_id = p.id
            JOIN usuarios u ON e.medico_id = u.id
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
            SELECT e.*, u.nombre as medico_nombre, u.apellido as medico_apellido
            FROM evaluaciones_3er_trimestre e
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
            INSERT INTO evaluaciones_3er_trimestre (
                paciente_id, medico_id, medico_solicitante_id, medico_referido_id, codigo_reporte, fecha_evaluacion, fecha_estudio,
                estudio_solicitado, edad_gestacional_semanas, fpp_fum, fpp_usg,
                peso_kg, talla_cm, ta_sistolica, ta_diastolica,
                situacion_fetal, presentacion_fetal, posicion_fetal, feto_unico_vivo,
                fcf_lpm, equipo_ultrasonido, observaciones,
                estado, created_by, updated_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['paciente_id'], $data['medico_id'],
            $data['medico_solicitante_id'] ?? null, $data['medico_referido_id'] ?? null,
            $data['codigo_reporte'],
            $data['fecha_evaluacion'], $data['fecha_estudio'] ?? null,
            $data['estudio_solicitado'] ?? null,
            $data['edad_gestacional_semanas'] ?? null,
            $data['fpp_fum'] ?? null, $data['fpp_usg'] ?? null,
            $data['peso_kg'] ?? null, $data['talla_cm'] ?? null,
            $data['ta_sistolica'] ?? null, $data['ta_diastolica'] ?? null,
            $data['situacion_fetal'] ?? null, $data['presentacion_fetal'] ?? null,
            $data['posicion_fetal'] ?? null, $data['feto_unico_vivo'] ?? null,
            $data['fcf_lpm'] ?? null,
            $data['equipo_ultrasonido'] ?? null, $data['observaciones'] ?? null,
            $data['estado'] ?? 'Pendiente', $data['created_by'], $data['updated_by']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE evaluaciones_3er_trimestre SET
                paciente_id = ?, medico_id = ?, medico_solicitante_id = ?, medico_referido_id = ?,
                fecha_estudio = ?, estudio_solicitado = ?,
                edad_gestacional_semanas = ?, fpp_fum = ?, fpp_usg = ?,
                peso_kg = ?, talla_cm = ?,
                ta_sistolica = ?, ta_diastolica = ?,
                situacion_fetal = ?, presentacion_fetal = ?, posicion_fetal = ?,
                feto_unico_vivo = ?, fcf_lpm = ?,
                equipo_ultrasonido = ?, observaciones = ?,
                estado = ?, updated_by = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['paciente_id'], $data['medico_id'],
            $data['medico_solicitante_id'] ?? null, $data['medico_referido_id'] ?? null,
            $data['fecha_estudio'] ?? null, $data['estudio_solicitado'] ?? null,
            $data['edad_gestacional_semanas'] ?? null,
            $data['fpp_fum'] ?? null, $data['fpp_usg'] ?? null,
            $data['peso_kg'] ?? null, $data['talla_cm'] ?? null,
            $data['ta_sistolica'] ?? null, $data['ta_diastolica'] ?? null,
            $data['situacion_fetal'] ?? null, $data['presentacion_fetal'] ?? null,
            $data['posicion_fetal'] ?? null, $data['feto_unico_vivo'] ?? null,
            $data['fcf_lpm'] ?? null,
            $data['equipo_ultrasonido'] ?? null, $data['observaciones'] ?? null,
            $data['estado'] ?? 'Pendiente', $data['updated_by'], $data['id']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE evaluaciones_3er_trimestre SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function generateCodigoReporte()
    {
        $year = date('Y');
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM evaluaciones_3er_trimestre WHERE YEAR(fecha_evaluacion) = ?");
        $stmt->execute([$year]);
        $result = $stmt->fetch();
        $numero = $result['total'] + 1;
        return 'EV3T-' . str_pad($numero, 4, '0', STR_PAD_LEFT) . '-' . $year;
    }
}
