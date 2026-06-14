<?php
require_once __DIR__ . '/../core/Database.php';

class Evaluacion2doTrimestre
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
            FROM evaluaciones_2do_trimestre e
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
            FROM evaluaciones_2do_trimestre e
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
                paciente_id, medico_id, medico_solicitante_id, medico_referido_id, codigo_reporte, fecha_evaluacion, fecha_estudio,
                edad_gestacional_semanas, fpp_actual, peso_kg, talla_cm,
                pam_mmhg, uta_pi_promedio, estado, peso_1er_trimestre_kg, ganancia_peso_kg,
                created_by, updated_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['paciente_id'], $data['medico_id'],
            $data['medico_solicitante_id'] ?? null, $data['medico_referido_id'] ?? null,
            $data['codigo_reporte'],
            $data['fecha_evaluacion'], $data['fecha_estudio'] ?? null,
            $data['edad_gestacional_semanas'] ?? null, $data['fpp_actual'] ?? null,
            $data['peso_kg'] ?? null, $data['talla_cm'] ?? null,
            $data['pam_mmhg'] ?? null, $data['uta_pi_promedio'] ?? null,
            $data['estado'] ?? 'Pendiente', $data['peso_1er_trimestre_kg'] ?? null,
            $data['ganancia_peso_kg'] ?? null, $data['created_by'], $data['updated_by']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE evaluaciones_2do_trimestre SET
                paciente_id = ?, medico_id = ?, medico_solicitante_id = ?, medico_referido_id = ?, fecha_estudio = ?,
                edad_gestacional_semanas = ?, fpp_actual = ?, peso_kg = ?, talla_cm = ?,
                pam_mmhg = ?, uta_pi_promedio = ?, estado = ?,
                peso_1er_trimestre_kg = ?, ganancia_peso_kg = ?, updated_by = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['paciente_id'], $data['medico_id'],
            $data['medico_solicitante_id'] ?? null, $data['medico_referido_id'] ?? null,
            $data['fecha_estudio'] ?? null,
            $data['edad_gestacional_semanas'] ?? null, $data['fpp_actual'] ?? null,
            $data['peso_kg'] ?? null, $data['talla_cm'] ?? null,
            $data['pam_mmhg'] ?? null, $data['uta_pi_promedio'] ?? null,
            $data['estado'] ?? 'Pendiente', $data['peso_1er_trimestre_kg'] ?? null,
            $data['ganancia_peso_kg'] ?? null, $data['updated_by'], $data['id']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE evaluaciones_2do_trimestre SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getLatestFullData($pacienteId)
    {
        $stmt = $this->db->prepare("
            SELECT e.*,
                   b.estado_feto, b.fcf_lpm, b.peso_fetal_estimado_gr, b.percentil_hadlock,
                   b.crecimiento_armonico, b.indice_cefalico_ci, b.fl_ac_pct, b.hc_ac_campbell,
                   af.craneo_snc_normal, af.cara_cuello_normal, af.corazon_normal,
                   af.torax_diafragma_normal, af.abdomen_normal, af.genitourinario_normal,
                   af.columna_normal, af.extremidades_normal, af.detalles_anomalias,
                   me.ventriculomegalia_leve, me.quistes_plexos_coroideos, me.pliegue_nucal_aumentado,
                   me.hueso_nasal_ausente, me.foco_ecogenico_cardiaco, me.intestino_hiperecogenico,
                   me.femur_corto, me.arteria_umbilical_unica,
                   ep.placenta_posicion, ep.distancia_borde_oci_mm, ep.acretismo_figo_grado,
                   ep.bolsillo_max_liquido_mm, ep.longitud_cervical_mm,
                   ep.indice_consistencia_cervical, ep.funneling_presente, ep.funneling_mm,
                   ep.sludge_intraamniotico, ep.morfologia_uterina_eshre, ep.miomas_visibles,
                   ep.miomas_figo_tipo, ep.miomas_dimensiones_mm, ep.miomas_vascularizacion,
                   id2.riesgo_cromosomopatias, id2.riesgo_parto_pretermino,
                   id2.riesgo_preeclampsia, id2.observaciones_medicas
            FROM evaluaciones_2do_trimestre e
            LEFT JOIN biometria_2do_trimestre b ON e.id = b.evaluacion_id
            LEFT JOIN anatomia_fetal_2do_trimestre af ON e.id = af.evaluacion_id
            LEFT JOIN marcadores_ecograficos_2do_trimestre me ON e.id = me.evaluacion_id
            LEFT JOIN entorno_placentario_2do_trimestre ep ON e.id = ep.evaluacion_id
            LEFT JOIN impresion_diagnostica_2do_trimestre id2 ON e.id = id2.evaluacion_id
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
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM evaluaciones_2do_trimestre WHERE YEAR(fecha_evaluacion) = ?");
        $stmt->execute([$year]);
        $result = $stmt->fetch();
        $numero = $result['total'] + 1;
        return 'EV2T-' . str_pad($numero, 4, '0', STR_PAD_LEFT) . '-' . $year;
    }
}
