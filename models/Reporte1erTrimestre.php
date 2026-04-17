<?php
require_once __DIR__ . '/../core/Database.php';

class Reporte1erTrimestre
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data)
    {
        $sql = "INSERT INTO Reporte_1er_Trimestre (
            id_consulta, fecha_ultima_regla, fpp_fum, fpp_usg,
            longitud_craneo_cauda_mm, translucencia_nucal_mm, frecuencia_cardiaca_fetal, longitud_cervical_mm,
            riesgo_cromosomopatias, riesgo_placentaria_temprana, riesgo_placentaria_tardia, riesgo_parto_pretermino,
            hueso_nasal_presente, anatomia_craneo_snc_normal, anatomia_corazon_normal, anatomia_abdomen_normal, anatomia_extremidades_normal,
            localizacion_placenta, liquido_amniotico_normal, observaciones_comentarios
        ) VALUES (
            :id_consulta, :fecha_ultima_regla, :fpp_fum, :fpp_usg,
            :longitud_craneo_cauda_mm, :translucencia_nucal_mm, :frecuencia_cardiaca_fetal, :longitud_cervical_mm,
            :riesgo_cromosomopatias, :riesgo_placentaria_temprana, :riesgo_placentaria_tardia, :riesgo_parto_pretermino,
            :hueso_nasal_presente, :anatomia_craneo_snc_normal, :anatomia_corazon_normal, :anatomia_abdomen_normal, :anatomia_extremidades_normal,
            :localizacion_placenta, :liquido_amniotico_normal, :observaciones_comentarios
        )";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_consulta' => $data['id_consulta'],
            ':fecha_ultima_regla' => $data['fecha_ultima_regla'],
            ':fpp_fum' => $data['fpp_fum'],
            ':fpp_usg' => $data['fpp_usg'],
            ':longitud_craneo_cauda_mm' => $data['longitud_craneo_cauda_mm'],
            ':translucencia_nucal_mm' => $data['translucencia_nucal_mm'],
            ':frecuencia_cardiaca_fetal' => $data['frecuencia_cardiaca_fetal'],
            ':longitud_cervical_mm' => $data['longitud_cervical_mm'],
            ':riesgo_cromosomopatias' => $data['riesgo_cromosomopatias'],
            ':riesgo_placentaria_temprana' => $data['riesgo_placentaria_temprana'],
            ':riesgo_placentaria_tardia' => $data['riesgo_placentaria_tardia'],
            ':riesgo_parto_pretermino' => $data['riesgo_parto_pretermino'],
            ':hueso_nasal_presente' => $data['hueso_nasal_presente'],
            ':anatomia_craneo_snc_normal' => $data['anatomia_craneo_snc_normal'],
            ':anatomia_corazon_normal' => $data['anatomia_corazon_normal'],
            ':anatomia_abdomen_normal' => $data['anatomia_abdomen_normal'],
            ':anatomia_extremidades_normal' => $data['anatomia_extremidades_normal'],
            ':localizacion_placenta' => $data['localizacion_placenta'],
            ':liquido_amniotico_normal' => $data['liquido_amniotico_normal'],
            ':observaciones_comentarios' => $data['observaciones_comentarios']
        ]);

        return $this->db->lastInsertId();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM Reporte_1er_Trimestre WHERE id_reporte_1t = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByConsultaId($consultaId)
    {
        $stmt = $this->db->prepare("SELECT * FROM Reporte_1er_Trimestre WHERE id_consulta = ?");
        $stmt->execute([$consultaId]);
        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE Reporte_1er_Trimestre SET
            id_consulta = :id_consulta,
            fecha_ultima_regla = :fecha_ultima_regla,
            fpp_fum = :fpp_fum,
            fpp_usg = :fpp_usg,
            longitud_craneo_cauda_mm = :longitud_craneo_cauda_mm,
            translucencia_nucal_mm = :translucencia_nucal_mm,
            frecuencia_cardiaca_fetal = :frecuencia_cardiaca_fetal,
            longitud_cervical_mm = :longitud_cervical_mm,
            riesgo_cromosomopatias = :riesgo_cromosomopatias,
            riesgo_placentaria_temprana = :riesgo_placentaria_temprana,
            riesgo_placentaria_tardia = :riesgo_placentaria_tardia,
            riesgo_parto_pretermino = :riesgo_parto_pretermino,
            hueso_nasal_presente = :hueso_nasal_presente,
            anatomia_craneo_snc_normal = :anatomia_craneo_snc_normal,
            anatomia_corazon_normal = :anatomia_corazon_normal,
            anatomia_abdomen_normal = :anatomia_abdomen_normal,
            anatomia_extremidades_normal = :anatomia_extremidades_normal,
            localizacion_placenta = :localizacion_placenta,
            liquido_amniotico_normal = :liquido_amniotico_normal,
            observaciones_comentarios = :observaciones_comentarios
        WHERE id_reporte_1t = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':id_consulta' => $data['id_consulta'],
            ':fecha_ultima_regla' => $data['fecha_ultima_regla'],
            ':fpp_fum' => $data['fpp_fum'],
            ':fpp_usg' => $data['fpp_usg'],
            ':longitud_craneo_cauda_mm' => $data['longitud_craneo_cauda_mm'],
            ':translucencia_nucal_mm' => $data['translucencia_nucal_mm'],
            ':frecuencia_cardiaca_fetal' => $data['frecuencia_cardiaca_fetal'],
            ':longitud_cervical_mm' => $data['longitud_cervical_mm'],
            ':riesgo_cromosomopatias' => $data['riesgo_cromosomopatias'],
            ':riesgo_placentaria_temprana' => $data['riesgo_placentaria_temprana'],
            ':riesgo_placentaria_tardia' => $data['riesgo_placentaria_tardia'],
            ':riesgo_parto_pretermino' => $data['riesgo_parto_pretermino'],
            ':hueso_nasal_presente' => $data['hueso_nasal_presente'],
            ':anatomia_craneo_snc_normal' => $data['anatomia_craneo_snc_normal'],
            ':anatomia_corazon_normal' => $data['anatomia_corazon_normal'],
            ':anatomia_abdomen_normal' => $data['anatomia_abdomen_normal'],
            ':anatomia_extremidades_normal' => $data['anatomia_extremidades_normal'],
            ':localizacion_placenta' => $data['localizacion_placenta'],
            ':liquido_amniotico_normal' => $data['liquido_amniotico_normal'],
            ':observaciones_comentarios' => $data['observaciones_comentarios']
        ]);
    }
}
