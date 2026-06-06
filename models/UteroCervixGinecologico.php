<?php
require_once __DIR__ . '/../core/Database.php';

class UteroCervixGinecologico
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM utero_cervix_ginecologico WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO utero_cervix_ginecologico (
                evaluacion_id, situacion,
                morfologia_regular, morfologia_bordes_irregulares, morfologia_globoso,
                morfologia_aumentado, morfologia_disminuido, morfologia_otro,
                dim_longitud_mm, dim_anteroposterior_mm, dim_transverso_mm, volumen_cc,
                miometrio_homogeneo, miometrio_heterogeneo, miometrio_imagenes_leiomiomas,
                miometrio_sugestivo_adenomiosis, miometrio_calcificaciones,
                miometrio_areas_quisticas, miometrio_sombra_acustica, miometrio_otro,
                cervix_longitud_mm, cervix_sin_alteraciones, cervix_quistes_naboth,
                cervix_polipo_endocervical, cervix_lesion_visible_usg, cervix_liquido_canal, cervix_otro
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['situacion'] ?? null,
            $data['morfologia_regular'] ?? 0,
            $data['morfologia_bordes_irregulares'] ?? 0,
            $data['morfologia_globoso'] ?? 0,
            $data['morfologia_aumentado'] ?? 0,
            $data['morfologia_disminuido'] ?? 0,
            $data['morfologia_otro'] ?? null,
            $data['dim_longitud_mm'] ?? null,
            $data['dim_anteroposterior_mm'] ?? null,
            $data['dim_transverso_mm'] ?? null,
            $data['volumen_cc'] ?? null,
            $data['miometrio_homogeneo'] ?? 0,
            $data['miometrio_heterogeneo'] ?? 0,
            $data['miometrio_imagenes_leiomiomas'] ?? 0,
            $data['miometrio_sugestivo_adenomiosis'] ?? 0,
            $data['miometrio_calcificaciones'] ?? 0,
            $data['miometrio_areas_quisticas'] ?? 0,
            $data['miometrio_sombra_acustica'] ?? 0,
            $data['miometrio_otro'] ?? null,
            $data['cervix_longitud_mm'] ?? null,
            $data['cervix_sin_alteraciones'] ?? 0,
            $data['cervix_quistes_naboth'] ?? 0,
            $data['cervix_polipo_endocervical'] ?? 0,
            $data['cervix_lesion_visible_usg'] ?? 0,
            $data['cervix_liquido_canal'] ?? 0,
            $data['cervix_otro'] ?? null
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE utero_cervix_ginecologico SET
                situacion = ?,
                morfologia_regular = ?, morfologia_bordes_irregulares = ?, morfologia_globoso = ?,
                morfologia_aumentado = ?, morfologia_disminuido = ?, morfologia_otro = ?,
                dim_longitud_mm = ?, dim_anteroposterior_mm = ?, dim_transverso_mm = ?, volumen_cc = ?,
                miometrio_homogeneo = ?, miometrio_heterogeneo = ?, miometrio_imagenes_leiomiomas = ?,
                miometrio_sugestivo_adenomiosis = ?, miometrio_calcificaciones = ?,
                miometrio_areas_quisticas = ?, miometrio_sombra_acustica = ?, miometrio_otro = ?,
                cervix_longitud_mm = ?, cervix_sin_alteraciones = ?, cervix_quistes_naboth = ?,
                cervix_polipo_endocervical = ?, cervix_lesion_visible_usg = ?, cervix_liquido_canal = ?, cervix_otro = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['situacion'] ?? null,
            $data['morfologia_regular'] ?? 0,
            $data['morfologia_bordes_irregulares'] ?? 0,
            $data['morfologia_globoso'] ?? 0,
            $data['morfologia_aumentado'] ?? 0,
            $data['morfologia_disminuido'] ?? 0,
            $data['morfologia_otro'] ?? null,
            $data['dim_longitud_mm'] ?? null,
            $data['dim_anteroposterior_mm'] ?? null,
            $data['dim_transverso_mm'] ?? null,
            $data['volumen_cc'] ?? null,
            $data['miometrio_homogeneo'] ?? 0,
            $data['miometrio_heterogeneo'] ?? 0,
            $data['miometrio_imagenes_leiomiomas'] ?? 0,
            $data['miometrio_sugestivo_adenomiosis'] ?? 0,
            $data['miometrio_calcificaciones'] ?? 0,
            $data['miometrio_areas_quisticas'] ?? 0,
            $data['miometrio_sombra_acustica'] ?? 0,
            $data['miometrio_otro'] ?? null,
            $data['cervix_longitud_mm'] ?? null,
            $data['cervix_sin_alteraciones'] ?? 0,
            $data['cervix_quistes_naboth'] ?? 0,
            $data['cervix_polipo_endocervical'] ?? 0,
            $data['cervix_lesion_visible_usg'] ?? 0,
            $data['cervix_liquido_canal'] ?? 0,
            $data['cervix_otro'] ?? null,
            $data['evaluacion_id']
        ]);
    }

    public function delete($evaluacionId)
    {
        $stmt = $this->db->prepare("DELETE FROM utero_cervix_ginecologico WHERE evaluacion_id = ?");
        return $stmt->execute([$evaluacionId]);
    }
}
