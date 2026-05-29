<?php
require_once __DIR__ . '/../core/Database.php';

class Evaluacion1erTrimestre
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
            FROM evaluaciones_1er_trimestre e
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
            FROM evaluaciones_1er_trimestre e
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
            FROM evaluaciones_1er_trimestre e
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
            INSERT INTO evaluaciones_1er_trimestre (
                paciente_id, medico_id, codigo_reporte, fecha_evaluacion, fecha_estudio,
                peso_kg, talla_cm, ta_sistolica, ta_diastolica,
                fum, fpp_usg, embarazo_multiple, estado_feto,
                fcf_lpm, lcc_mm, edad_gestacional_semanas,
                estado, created_by, updated_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['paciente_id'],
            $data['medico_id'],
            $data['codigo_reporte'],
            $data['fecha_evaluacion'],
            $data['fecha_estudio'] ?? null,
            $data['peso_kg'] ?? null,
            $data['talla_cm'] ?? null,
            $data['ta_sistolica'] ?? null,
            $data['ta_diastolica'] ?? null,
            $data['fum'] ?? null,
            $data['fpp_usg'] ?? null,
            $data['embarazo_multiple'] ?? 0,
            $data['estado_feto'] ?? 'Vivo',
            $data['fcf_lpm'] ?? null,
            $data['lcc_mm'] ?? null,
            $data['edad_gestacional_semanas'] ?? null,
            $data['estado'] ?? 'Pendiente',
            $data['created_by'],
            $data['updated_by']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE evaluaciones_1er_trimestre SET
                paciente_id = ?, medico_id = ?, fecha_estudio = ?,
                peso_kg = ?, talla_cm = ?, ta_sistolica = ?, ta_diastolica = ?,
                fum = ?, fpp_usg = ?, embarazo_multiple = ?, estado_feto = ?,
                fcf_lpm = ?, lcc_mm = ?, edad_gestacional_semanas = ?,
                estado = ?, updated_by = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['paciente_id'],
            $data['medico_id'],
            $data['fecha_estudio'] ?? null,
            $data['peso_kg'] ?? null,
            $data['talla_cm'] ?? null,
            $data['ta_sistolica'] ?? null,
            $data['ta_diastolica'] ?? null,
            $data['fum'] ?? null,
            $data['fpp_usg'] ?? null,
            $data['embarazo_multiple'] ?? 0,
            $data['estado_feto'] ?? 'Vivo',
            $data['fcf_lpm'] ?? null,
            $data['lcc_mm'] ?? null,
            $data['edad_gestacional_semanas'] ?? null,
            $data['estado'] ?? 'Pendiente',
            $data['updated_by'],
            $data['id']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE evaluaciones_1er_trimestre SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getLatestFullData($pacienteId)
    {
        $stmt = $this->db->prepare("
            SELECT e.*,
                   mf.uta_pi_promedio, mf.muesca_bilateral, mf.papp_a_mom, mf.plgf_mom,
                   mf.tamizaje_genetico_tipo, mf.tamizaje_genetico_resultado,
                   em.placenta_posicion, em.longitud_cervical_mm, em.indice_consistencia_cervical_pct,
                   em.morfologia_uterina_eshre, em.miomas_visibles, em.miomas_figo_tipo,
                   id1.riesgo_preeclampsia_temprana
            FROM evaluaciones_1er_trimestre e
            LEFT JOIN marcadores_fmf mf ON e.id = mf.evaluacion_id
            LEFT JOIN entorno_materno em ON e.id = em.evaluacion_id
            LEFT JOIN impresion_diagnostica id1 ON e.id = id1.evaluacion_id
            WHERE e.paciente_id = ? AND e.activo = 1
            ORDER BY e.fecha_evaluacion DESC
            LIMIT 1
        ");
        $stmt->execute([$pacienteId]);
        return $stmt->fetch();
    }

    public function generateCodigoReporte()
    {
        $year = date('Y');
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM evaluaciones_1er_trimestre WHERE YEAR(fecha_evaluacion) = ?");
        $stmt->execute([$year]);
        $result = $stmt->fetch();
        $numero = $result['total'] + 1;
        return 'EV1T-' . str_pad($numero, 4, '0', STR_PAD_LEFT) . '-' . $year;
    }
}
