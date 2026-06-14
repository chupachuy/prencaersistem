<?php
require_once __DIR__ . '/../core/Database.php';

class UltrasonidoTemprano
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($medicoId = null)
    {
        $sql = "
            SELECT u.*, p.nombre as paciente_nombre, p.apellido as paciente_apellido,
                   us.nombre as medico_nombre, us.apellido as medico_apellido
            FROM ultrasonido_temprano u
            JOIN pacientes p ON u.paciente_id = p.id
            JOIN usuarios us ON u.medico_id = us.id
            WHERE u.activo = 1
        ";
        if ($medicoId !== null) {
            $sql .= " AND u.medico_id = ?";
            $stmt = $this->db->prepare($sql . " ORDER BY u.fecha_estudio DESC");
            $stmt->execute([$medicoId]);
        } else {
            $stmt = $this->db->query($sql . " ORDER BY u.fecha_estudio DESC");
        }
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, p.nombre as paciente_nombre, p.apellido as paciente_apellido, p.fecha_nacimiento, p.email as paciente_email,
                   us.nombre as medico_nombre, us.apellido as medico_apellido, us.email as medico_email,
                   usol.nombre as medico_solicitante_nombre, usol.apellido as medico_solicitante_apellido, usol.email as medico_solicitante_email,
                   uref.nombre as medico_referido_nombre, uref.apellido as medico_referido_apellido, uref.email as medico_referido_email
            FROM ultrasonido_temprano u
            JOIN pacientes p ON u.paciente_id = p.id
            JOIN usuarios us ON u.medico_id = us.id
            LEFT JOIN usuarios usol ON u.medico_solicitante_id = usol.id
            LEFT JOIN usuarios uref ON u.medico_referido_id = uref.id
            WHERE u.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByPaciente($pacienteId)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, us.nombre as medico_nombre, us.apellido as medico_apellido
            FROM ultrasonido_temprano u
            JOIN usuarios us ON u.medico_id = us.id
            WHERE u.paciente_id = ? AND u.activo = 1
            ORDER BY u.fecha_estudio DESC
        ");
        $stmt->execute([$pacienteId]);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO ultrasonido_temprano (
                paciente_id, medico_id, medico_solicitante_id, medico_referido_id, codigo_reporte, fecha_estudio, edad, fum,
                edad_gest_semanas, edad_gest_dias,
                indic_confirmacion_embarazo, indic_sangrado, indic_dolor_pelvico,
                indic_viabilidad, indic_perdidas_gestacionales, indic_reproduccion_asistida, indic_otro,
                via_transvaginal, via_transabdominal, via_ambas,
                utero_posicion, utero_contornos, utero_ecogenicidad_conservada,
                utero_dim_x, utero_dim_y, utero_dim_z, endometrio,
                localizacion, localizacion_otra,
                sg_tipo, sg_morfologia, sg_medida_mm, sg_cantidad,
                sv_presente, sv_cantidad, sv_diametro_mm,
                decidua,
                corion_amnios_normal,
                ovario_der_dim_x, ovario_der_dim_y, ovario_der_dim_z,
                ovario_der_normal, ovario_der_cuerpo_luteo_mm, ovario_der_quiste_simple_mm, ovario_der_otra_alteracion,
                ovario_izq_dim_x, ovario_izq_dim_y, ovario_izq_dim_z,
                ovario_izq_normal, ovario_izq_cuerpo_luteo_mm, ovario_izq_quiste_simple_mm, ovario_izq_otra_alteracion,
                douglas,
                hematoma_subcorionico, hematoma_localizacion, hematoma_dim_x, hematoma_dim_y, hematoma_dim_z, hematoma_volumen_ml,
                miomas_uterinos, adenomiosis, malformacion_uterina, hallazgos_otro,
                impresion_crl_mm, impresion_semanas, impresion_dias, impresion_fcf_lpm, viabilidad, impresion_texto,
                estado, created_by, updated_by
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )
        ");
        $stmt->execute([
            $data['paciente_id'],
            $data['medico_id'],
            $data['medico_solicitante_id'] ?? null,
            $data['medico_referido_id'] ?? null,
            $data['codigo_reporte'],
            $data['fecha_estudio'],
            $data['edad'] ?? null,
            $data['fum'] ?? null,
            $data['edad_gest_semanas'] ?? null,
            $data['edad_gest_dias'] ?? null,
            $data['indic_confirmacion_embarazo'] ?? 0,
            $data['indic_sangrado'] ?? 0,
            $data['indic_dolor_pelvico'] ?? 0,
            $data['indic_viabilidad'] ?? 0,
            $data['indic_perdidas_gestacionales'] ?? 0,
            $data['indic_reproduccion_asistida'] ?? 0,
            $data['indic_otro'] ?? null,
            $data['via_transvaginal'] ?? 0,
            $data['via_transabdominal'] ?? 0,
            $data['via_ambas'] ?? 0,
            $data['utero_posicion'] ?? null,
            $data['utero_contornos'] ?? 'Regulares',
            $data['utero_ecogenicidad_conservada'] ?? 1,
            $data['utero_dim_x'] ?? null,
            $data['utero_dim_y'] ?? null,
            $data['utero_dim_z'] ?? null,
            $data['endometrio'] ?? null,
            $data['localizacion'] ?? null,
            $data['localizacion_otra'] ?? null,
            $data['sg_tipo'] ?? null,
            $data['sg_morfologia'] ?? null,
            $data['sg_medida_mm'] ?? null,
            $data['sg_cantidad'] ?? null,
            $data['sv_presente'] ?? null,
            $data['sv_cantidad'] ?? null,
            $data['sv_diametro_mm'] ?? null,
            $data['decidua'] ?? null,
            $data['corion_amnios_normal'] ?? 1,
            $data['ovario_der_dim_x'] ?? null,
            $data['ovario_der_dim_y'] ?? null,
            $data['ovario_der_dim_z'] ?? null,
            $data['ovario_der_normal'] ?? 1,
            $data['ovario_der_cuerpo_luteo_mm'] ?? null,
            $data['ovario_der_quiste_simple_mm'] ?? null,
            $data['ovario_der_otra_alteracion'] ?? null,
            $data['ovario_izq_dim_x'] ?? null,
            $data['ovario_izq_dim_y'] ?? null,
            $data['ovario_izq_dim_z'] ?? null,
            $data['ovario_izq_normal'] ?? 1,
            $data['ovario_izq_cuerpo_luteo_mm'] ?? null,
            $data['ovario_izq_quiste_simple_mm'] ?? null,
            $data['ovario_izq_otra_alteracion'] ?? null,
            $data['douglas'] ?? null,
            $data['hematoma_subcorionico'] ?? 0,
            $data['hematoma_localizacion'] ?? null,
            $data['hematoma_dim_x'] ?? null,
            $data['hematoma_dim_y'] ?? null,
            $data['hematoma_dim_z'] ?? null,
            $data['hematoma_volumen_ml'] ?? null,
            $data['miomas_uterinos'] ?? 0,
            $data['adenomiosis'] ?? 0,
            $data['malformacion_uterina'] ?? 0,
            $data['hallazgos_otro'] ?? null,
            $data['impresion_crl_mm'] ?? null,
            $data['impresion_semanas'] ?? null,
            $data['impresion_dias'] ?? null,
            $data['impresion_fcf_lpm'] ?? null,
            $data['viabilidad'] ?? null,
            $data['impresion_texto'] ?? null,
            $data['estado'] ?? 'Pendiente',
            $data['created_by'],
            $data['updated_by']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE ultrasonido_temprano SET
                paciente_id = ?, medico_id = ?, medico_solicitante_id = ?, medico_referido_id = ?, fecha_estudio = ?, edad = ?, fum = ?,
                edad_gest_semanas = ?, edad_gest_dias = ?,
                indic_confirmacion_embarazo = ?, indic_sangrado = ?, indic_dolor_pelvico = ?,
                indic_viabilidad = ?, indic_perdidas_gestacionales = ?, indic_reproduccion_asistida = ?, indic_otro = ?,
                via_transvaginal = ?, via_transabdominal = ?, via_ambas = ?,
                utero_posicion = ?, utero_contornos = ?, utero_ecogenicidad_conservada = ?,
                utero_dim_x = ?, utero_dim_y = ?, utero_dim_z = ?, endometrio = ?,
                localizacion = ?, localizacion_otra = ?,
                sg_tipo = ?, sg_morfologia = ?, sg_medida_mm = ?, sg_cantidad = ?,
                sv_presente = ?, sv_cantidad = ?, sv_diametro_mm = ?,
                decidua = ?,
                corion_amnios_normal = ?,
                ovario_der_dim_x = ?, ovario_der_dim_y = ?, ovario_der_dim_z = ?,
                ovario_der_normal = ?, ovario_der_cuerpo_luteo_mm = ?, ovario_der_quiste_simple_mm = ?, ovario_der_otra_alteracion = ?,
                ovario_izq_dim_x = ?, ovario_izq_dim_y = ?, ovario_izq_dim_z = ?,
                ovario_izq_normal = ?, ovario_izq_cuerpo_luteo_mm = ?, ovario_izq_quiste_simple_mm = ?, ovario_izq_otra_alteracion = ?,
                douglas = ?,
                hematoma_subcorionico = ?, hematoma_localizacion = ?, hematoma_dim_x = ?, hematoma_dim_y = ?, hematoma_dim_z = ?, hematoma_volumen_ml = ?,
                miomas_uterinos = ?, adenomiosis = ?, malformacion_uterina = ?, hallazgos_otro = ?,
                impresion_crl_mm = ?, impresion_semanas = ?, impresion_dias = ?, impresion_fcf_lpm = ?, viabilidad = ?, impresion_texto = ?,
                estado = ?, updated_by = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['paciente_id'],
            $data['medico_id'],
            $data['medico_solicitante_id'] ?? null,
            $data['medico_referido_id'] ?? null,
            $data['fecha_estudio'],
            $data['edad'] ?? null,
            $data['fum'] ?? null,
            $data['edad_gest_semanas'] ?? null,
            $data['edad_gest_dias'] ?? null,
            $data['indic_confirmacion_embarazo'] ?? 0,
            $data['indic_sangrado'] ?? 0,
            $data['indic_dolor_pelvico'] ?? 0,
            $data['indic_viabilidad'] ?? 0,
            $data['indic_perdidas_gestacionales'] ?? 0,
            $data['indic_reproduccion_asistida'] ?? 0,
            $data['indic_otro'] ?? null,
            $data['via_transvaginal'] ?? 0,
            $data['via_transabdominal'] ?? 0,
            $data['via_ambas'] ?? 0,
            $data['utero_posicion'] ?? null,
            $data['utero_contornos'] ?? 'Regulares',
            $data['utero_ecogenicidad_conservada'] ?? 1,
            $data['utero_dim_x'] ?? null,
            $data['utero_dim_y'] ?? null,
            $data['utero_dim_z'] ?? null,
            $data['endometrio'] ?? null,
            $data['localizacion'] ?? null,
            $data['localizacion_otra'] ?? null,
            $data['sg_tipo'] ?? null,
            $data['sg_morfologia'] ?? null,
            $data['sg_medida_mm'] ?? null,
            $data['sg_cantidad'] ?? null,
            $data['sv_presente'] ?? null,
            $data['sv_cantidad'] ?? null,
            $data['sv_diametro_mm'] ?? null,
            $data['decidua'] ?? null,
            $data['corion_amnios_normal'] ?? 1,
            $data['ovario_der_dim_x'] ?? null,
            $data['ovario_der_dim_y'] ?? null,
            $data['ovario_der_dim_z'] ?? null,
            $data['ovario_der_normal'] ?? 1,
            $data['ovario_der_cuerpo_luteo_mm'] ?? null,
            $data['ovario_der_quiste_simple_mm'] ?? null,
            $data['ovario_der_otra_alteracion'] ?? null,
            $data['ovario_izq_dim_x'] ?? null,
            $data['ovario_izq_dim_y'] ?? null,
            $data['ovario_izq_dim_z'] ?? null,
            $data['ovario_izq_normal'] ?? 1,
            $data['ovario_izq_cuerpo_luteo_mm'] ?? null,
            $data['ovario_izq_quiste_simple_mm'] ?? null,
            $data['ovario_izq_otra_alteracion'] ?? null,
            $data['douglas'] ?? null,
            $data['hematoma_subcorionico'] ?? 0,
            $data['hematoma_localizacion'] ?? null,
            $data['hematoma_dim_x'] ?? null,
            $data['hematoma_dim_y'] ?? null,
            $data['hematoma_dim_z'] ?? null,
            $data['hematoma_volumen_ml'] ?? null,
            $data['miomas_uterinos'] ?? 0,
            $data['adenomiosis'] ?? 0,
            $data['malformacion_uterina'] ?? 0,
            $data['hallazgos_otro'] ?? null,
            $data['impresion_crl_mm'] ?? null,
            $data['impresion_semanas'] ?? null,
            $data['impresion_dias'] ?? null,
            $data['impresion_fcf_lpm'] ?? null,
            $data['viabilidad'] ?? null,
            $data['impresion_texto'] ?? null,
            $data['estado'] ?? 'Pendiente',
            $data['updated_by'],
            $data['id']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE ultrasonido_temprano SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function generateCodigoReporte()
    {
        $year = date('Y');
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM ultrasonido_temprano WHERE YEAR(fecha_estudio) = ?");
        $stmt->execute([$year]);
        $result = $stmt->fetch();
        $numero = $result['total'] + 1;
        return 'UST-' . str_pad($numero, 4, '0', STR_PAD_LEFT) . '-' . $year;
    }
}
