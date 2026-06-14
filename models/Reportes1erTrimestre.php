<?php
require_once __DIR__ . '/../core/Database.php';

class Reportes1erTrimestre
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data)
    {
        $sql = "INSERT INTO reportes_1er_trimestre (
            paciente_id, medico_id, medico_referido_id, codigo_reporte, fecha_reporte,
            lugar, peso, talla, presion_sistolica, presion_diastolica,
            gesta, para, abortos, fecha_ultima_regla, edad_gestacional_fum,
            fecha_probable_parto_fum, longitud_craneo_cauda, edad_gestacional_usg,
            fecha_probable_parto_usg, equipo_usg, transductor_tipo, equipo_estudio,
            craneo, sistema_nervioso_central, cuello, cara, columna, torax, corazon, abdomen, extremidades, liquido_amniotico, decidua, cervix,
            activo, estado, created_by, updated_by
        ) VALUES (
            :paciente_id, :medico_id, :medico_referido_id, :codigo_reporte, :fecha_reporte,
            :lugar, :peso, :talla, :presion_sistolica, :presion_diastolica,
            :gesta, :para, :abortos, :fecha_ultima_regla, :edad_gestacional_fum,
            :fecha_probable_parto_fum, :longitud_craneo_cauda, :edad_gestacional_usg,
            :fecha_probable_parto_usg, :equipo_usg, :transductor_tipo, :equipo_estudio,
            :craneo, :sistema_nervioso_central, :cuello, :cara, :columna, :torax, :corazon, :abdomen, :extremidades, :liquido_amniotico, :decidua, :cervix,
            :activo, :estado, :created_by, :updated_by
        )";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':paciente_id' => $data['paciente_id'],
            ':medico_id' => $data['medico_id'],
            ':medico_referido_id' => $data['medico_referido_id'] ?? null,
            ':codigo_reporte' => $data['codigo_reporte'],
            ':fecha_reporte' => $data['fecha_reporte'],
            ':lugar' => $data['lugar'] ?? null,
            ':peso' => $data['peso'] ?? null,
            ':talla' => $data['talla'] ?? null,
            ':presion_sistolica' => $data['presion_sistolica'] ?? null,
            ':presion_diastolica' => $data['presion_diastolica'] ?? null,
            ':gesta' => $data['gesta'] ?? null,
            ':para' => $data['para'] ?? null,
            ':abortos' => $data['abortos'] ?? null,
            ':fecha_ultima_regla' => $data['fecha_ultima_regla'] ?? null,
            ':edad_gestacional_fum' => $data['edad_gestacional_fum'] ?? null,
            ':fecha_probable_parto_fum' => $data['fecha_probable_parto_fum'] ?? null,
            ':longitud_craneo_cauda' => $data['longitud_craneo_cauda'] ?? null,
            ':edad_gestacional_usg' => $data['edad_gestacional_usg'] ?? null,
            ':fecha_probable_parto_usg' => $data['fecha_probable_parto_usg'] ?? null,
            ':equipo_usg' => $data['equipo_usg'] ?? null,
            ':transductor_tipo' => $data['transductor_tipo'] ?? null,
            ':equipo_estudio' => $data['equipo_estudio'] ?? null,
            ':craneo' => $data['craneo'] ?? null,
            ':sistema_nervioso_central' => $data['sistema_nervioso_central'] ?? null,
            ':cuello' => $data['cuello'] ?? null,
            ':cara' => $data['cara'] ?? null,
            ':columna' => $data['columna'] ?? null,
            ':torax' => $data['torax'] ?? null,
            ':corazon' => $data['corazon'] ?? null,
            ':abdomen' => $data['abdomen'] ?? null,
            ':extremidades' => $data['extremidades'] ?? null,
            ':liquido_amniotico' => $data['liquido_amniotico'] ?? null,
            ':decidua' => $data['decidua'] ?? null,
            ':cervix' => $data['cervix'] ?? null,
            ':activo' => $data['activo'] ?? 1,
            ':estado' => $data['estado'] ?? 'Pendiente',
            ':created_by' => $data['created_by'],
            ':updated_by' => $data['updated_by']
        ]);

        return $this->db->lastInsertId();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT r.*, p.nombre as paciente_nombre, p.apellido as paciente_apellido, 
            m.nombre as medico_nombre, m.apellido as medico_apellido
            FROM reportes_1er_trimestre r
            LEFT JOIN pacientes p ON r.paciente_id = p.id
            LEFT JOIN usuarios m ON r.medico_id = m.id
            ORDER BY r.fecha_reporte DESC");
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT r.*, p.nombre as paciente_nombre, p.apellido as paciente_apellido, p.fecha_nacimiento, p.email as paciente_email,
            m.nombre as medico_nombre, m.apellido as medico_apellido, m.email as medico_email,
            mr.nombre as medico_referido_nombre, mr.apellido as medico_referido_apellido, mr.email as medico_referido_email
            FROM reportes_1er_trimestre r
            LEFT JOIN pacientes p ON r.paciente_id = p.id
            LEFT JOIN usuarios m ON r.medico_id = m.id
            LEFT JOIN usuarios mr ON r.medico_referido_id = mr.id
            WHERE r.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByPacienteId($pacienteId)
    {
        $stmt = $this->db->prepare("SELECT r.*, m.nombre as medico_nombre, m.apellido as medico_apellido
            FROM reportes_1er_trimestre r
            LEFT JOIN usuarios m ON r.medico_id = m.id
            WHERE r.paciente_id = ? AND r.activo = 1
            ORDER BY r.fecha_reporte DESC");
        $stmt->execute([$pacienteId]);
        return $stmt->fetchAll();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE reportes_1er_trimestre SET
            paciente_id = :paciente_id,
            medico_referido_id = :medico_referido_id,
            lugar = :lugar,
            peso = :peso,
            talla = :talla,
            presion_sistolica = :presion_sistolica,
            presion_diastolica = :presion_diastolica,
            gesta = :gesta,
            para = :para,
            abortos = :abortos,
            fecha_ultima_regla = :fecha_ultima_regla,
            edad_gestacional_fum = :edad_gestacional_fum,
            fecha_probable_parto_fum = :fecha_probable_parto_fum,
            longitud_craneo_cauda = :longitud_craneo_cauda,
            edad_gestacional_usg = :edad_gestacional_usg,
            fecha_probable_parto_usg = :fecha_probable_parto_usg,
            equipo_usg = :equipo_usg,
            transductor_tipo = :transductor_tipo,
            equipo_estudio = :equipo_estudio,
            craneo = :craneo,
            sistema_nervioso_central = :sistema_nervioso_central,
            cuello = :cuello,
            cara = :cara,
            columna = :columna,
            torax = :torax,
            corazon = :corazon,
            abdomen = :abdomen,
            extremidades = :extremidades,
            liquido_amniotico = :liquido_amniotico,
            decidua = :decidua,
            cervix = :cervix,
            activo = :activo,
            estado = :estado,
            updated_by = :updated_by
        WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':paciente_id' => $data['paciente_id'],
            ':medico_referido_id' => $data['medico_referido_id'] ?? null,
            ':lugar' => $data['lugar'] ?? null,
            ':peso' => $data['peso'] ?? null,
            ':talla' => $data['talla'] ?? null,
            ':presion_sistolica' => $data['presion_sistolica'] ?? null,
            ':presion_diastolica' => $data['presion_diastolica'] ?? null,
            ':gesta' => $data['gesta'] ?? null,
            ':para' => $data['para'] ?? null,
            ':abortos' => $data['abortos'] ?? null,
            ':fecha_ultima_regla' => $data['fecha_ultima_regla'] ?? null,
            ':edad_gestacional_fum' => $data['edad_gestacional_fum'] ?? null,
            ':fecha_probable_parto_fum' => $data['fecha_probable_parto_fum'] ?? null,
            ':longitud_craneo_cauda' => $data['longitud_craneo_cauda'] ?? null,
            ':edad_gestacional_usg' => $data['edad_gestacional_usg'] ?? null,
            ':fecha_probable_parto_usg' => $data['fecha_probable_parto_usg'] ?? null,
            ':equipo_usg' => $data['equipo_usg'] ?? null,
            ':transductor_tipo' => $data['transductor_tipo'] ?? null,
            ':equipo_estudio' => $data['equipo_estudio'] ?? null,
            ':craneo' => $data['craneo'] ?? null,
            ':sistema_nervioso_central' => $data['sistema_nervioso_central'] ?? null,
            ':cuello' => $data['cuello'] ?? null,
            ':cara' => $data['cara'] ?? null,
            ':columna' => $data['columna'] ?? null,
            ':torax' => $data['torax'] ?? null,
            ':corazon' => $data['corazon'] ?? null,
            ':abdomen' => $data['abdomen'] ?? null,
            ':extremidades' => $data['extremidades'] ?? null,
            ':liquido_amniotico' => $data['liquido_amniotico'] ?? null,
            ':decidua' => $data['decidua'] ?? null,
            ':cervix' => $data['cervix'] ?? null,
            ':activo' => $data['activo'] ?? 1,
            ':estado' => $data['estado'] ?? 'Pendiente',
            ':updated_by' => $data['updated_by']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE reportes_1er_trimestre SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function generateCodigoReporte()
    {
        $year = date('Y');
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM reportes_1er_trimestre WHERE YEAR(fecha_reporte) = $year");
        $result = $stmt->fetch();
        $numero = $result['total'] + 1;
        return 'R1T-' . str_pad($numero, 4, '0', STR_PAD_LEFT) . '-' . $year;
    }
}
