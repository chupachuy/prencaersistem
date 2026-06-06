<?php
require_once __DIR__ . '/../core/Database.php';

class OvariosGinecologicos
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM ovarios_ginecologicos WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO ovarios_ginecologicos (
                evaluacion_id,
                der_dim_x_mm, der_dim_y_mm, der_dim_z_mm, der_volumen_cc,
                der_normal, der_atrofico, der_multifolicular, der_poliquistico,
                der_cuerpo_luteo, der_quiste_simple, der_quiste_hemorragico,
                der_endometrioma, der_lesion_solida, der_lesion_compleja, der_no_visible,
                der_foliculo_med_x_mm, der_foliculo_med_y_mm, der_foliculo_med_z_mm,
                der_foliculo_contenido, der_foliculo_pared,
                der_foliculo_septos, der_foliculo_septos_grosor,
                der_foliculo_papilares, der_foliculo_papilares_num,
                der_foliculo_sombra, der_foliculo_doppler,
                izq_dim_x_mm, izq_dim_y_mm, izq_dim_z_mm, izq_volumen_cc,
                izq_normal, izq_atrofico, izq_multifolicular, izq_poliquistico,
                izq_cuerpo_luteo, izq_quiste_simple, izq_quiste_hemorragico,
                izq_endometrioma, izq_lesion_solida, izq_lesion_compleja, izq_no_visible,
                izq_foliculo_med_x_mm, izq_foliculo_med_y_mm, izq_foliculo_med_z_mm,
                izq_foliculo_contenido, izq_foliculo_pared,
                izq_foliculo_septos, izq_foliculo_septos_grosor,
                izq_foliculo_papilares, izq_foliculo_papilares_num,
                izq_foliculo_sombra, izq_foliculo_doppler
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['der_dim_x_mm'] ?? null, $data['der_dim_y_mm'] ?? null, $data['der_dim_z_mm'] ?? null, $data['der_volumen_cc'] ?? null,
            $data['der_normal'] ?? 0, $data['der_atrofico'] ?? 0, $data['der_multifolicular'] ?? 0, $data['der_poliquistico'] ?? 0,
            $data['der_cuerpo_luteo'] ?? 0, $data['der_quiste_simple'] ?? 0, $data['der_quiste_hemorragico'] ?? 0,
            $data['der_endometrioma'] ?? 0, $data['der_lesion_solida'] ?? 0, $data['der_lesion_compleja'] ?? 0, $data['der_no_visible'] ?? 0,
            $data['der_foliculo_med_x_mm'] ?? null, $data['der_foliculo_med_y_mm'] ?? null, $data['der_foliculo_med_z_mm'] ?? null,
            $data['der_foliculo_contenido'] ?? null, $data['der_foliculo_pared'] ?? null,
            $data['der_foliculo_septos'] ?? 0, $data['der_foliculo_septos_grosor'] ?? null,
            $data['der_foliculo_papilares'] ?? 0, $data['der_foliculo_papilares_num'] ?? null,
            $data['der_foliculo_sombra'] ?? 0, $data['der_foliculo_doppler'] ?? null,
            $data['izq_dim_x_mm'] ?? null, $data['izq_dim_y_mm'] ?? null, $data['izq_dim_z_mm'] ?? null, $data['izq_volumen_cc'] ?? null,
            $data['izq_normal'] ?? 0, $data['izq_atrofico'] ?? 0, $data['izq_multifolicular'] ?? 0, $data['izq_poliquistico'] ?? 0,
            $data['izq_cuerpo_luteo'] ?? 0, $data['izq_quiste_simple'] ?? 0, $data['izq_quiste_hemorragico'] ?? 0,
            $data['izq_endometrioma'] ?? 0, $data['izq_lesion_solida'] ?? 0, $data['izq_lesion_compleja'] ?? 0, $data['izq_no_visible'] ?? 0,
            $data['izq_foliculo_med_x_mm'] ?? null, $data['izq_foliculo_med_y_mm'] ?? null, $data['izq_foliculo_med_z_mm'] ?? null,
            $data['izq_foliculo_contenido'] ?? null, $data['izq_foliculo_pared'] ?? null,
            $data['izq_foliculo_septos'] ?? 0, $data['izq_foliculo_septos_grosor'] ?? null,
            $data['izq_foliculo_papilares'] ?? 0, $data['izq_foliculo_papilares_num'] ?? null,
            $data['izq_foliculo_sombra'] ?? 0, $data['izq_foliculo_doppler'] ?? null
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE ovarios_ginecologicos SET
                der_dim_x_mm = ?, der_dim_y_mm = ?, der_dim_z_mm = ?, der_volumen_cc = ?,
                der_normal = ?, der_atrofico = ?, der_multifolicular = ?, der_poliquistico = ?,
                der_cuerpo_luteo = ?, der_quiste_simple = ?, der_quiste_hemorragico = ?,
                der_endometrioma = ?, der_lesion_solida = ?, der_lesion_compleja = ?, der_no_visible = ?,
                der_foliculo_med_x_mm = ?, der_foliculo_med_y_mm = ?, der_foliculo_med_z_mm = ?,
                der_foliculo_contenido = ?, der_foliculo_pared = ?,
                der_foliculo_septos = ?, der_foliculo_septos_grosor = ?,
                der_foliculo_papilares = ?, der_foliculo_papilares_num = ?,
                der_foliculo_sombra = ?, der_foliculo_doppler = ?,
                izq_dim_x_mm = ?, izq_dim_y_mm = ?, izq_dim_z_mm = ?, izq_volumen_cc = ?,
                izq_normal = ?, izq_atrofico = ?, izq_multifolicular = ?, izq_poliquistico = ?,
                izq_cuerpo_luteo = ?, izq_quiste_simple = ?, izq_quiste_hemorragico = ?,
                izq_endometrioma = ?, izq_lesion_solida = ?, izq_lesion_compleja = ?, izq_no_visible = ?,
                izq_foliculo_med_x_mm = ?, izq_foliculo_med_y_mm = ?, izq_foliculo_med_z_mm = ?,
                izq_foliculo_contenido = ?, izq_foliculo_pared = ?,
                izq_foliculo_septos = ?, izq_foliculo_septos_grosor = ?,
                izq_foliculo_papilares = ?, izq_foliculo_papilares_num = ?,
                izq_foliculo_sombra = ?, izq_foliculo_doppler = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['der_dim_x_mm'] ?? null, $data['der_dim_y_mm'] ?? null, $data['der_dim_z_mm'] ?? null, $data['der_volumen_cc'] ?? null,
            $data['der_normal'] ?? 0, $data['der_atrofico'] ?? 0, $data['der_multifolicular'] ?? 0, $data['der_poliquistico'] ?? 0,
            $data['der_cuerpo_luteo'] ?? 0, $data['der_quiste_simple'] ?? 0, $data['der_quiste_hemorragico'] ?? 0,
            $data['der_endometrioma'] ?? 0, $data['der_lesion_solida'] ?? 0, $data['der_lesion_compleja'] ?? 0, $data['der_no_visible'] ?? 0,
            $data['der_foliculo_med_x_mm'] ?? null, $data['der_foliculo_med_y_mm'] ?? null, $data['der_foliculo_med_z_mm'] ?? null,
            $data['der_foliculo_contenido'] ?? null, $data['der_foliculo_pared'] ?? null,
            $data['der_foliculo_septos'] ?? 0, $data['der_foliculo_septos_grosor'] ?? null,
            $data['der_foliculo_papilares'] ?? 0, $data['der_foliculo_papilares_num'] ?? null,
            $data['der_foliculo_sombra'] ?? 0, $data['der_foliculo_doppler'] ?? null,
            $data['izq_dim_x_mm'] ?? null, $data['izq_dim_y_mm'] ?? null, $data['izq_dim_z_mm'] ?? null, $data['izq_volumen_cc'] ?? null,
            $data['izq_normal'] ?? 0, $data['izq_atrofico'] ?? 0, $data['izq_multifolicular'] ?? 0, $data['izq_poliquistico'] ?? 0,
            $data['izq_cuerpo_luteo'] ?? 0, $data['izq_quiste_simple'] ?? 0, $data['izq_quiste_hemorragico'] ?? 0,
            $data['izq_endometrioma'] ?? 0, $data['izq_lesion_solida'] ?? 0, $data['izq_lesion_compleja'] ?? 0, $data['izq_no_visible'] ?? 0,
            $data['izq_foliculo_med_x_mm'] ?? null, $data['izq_foliculo_med_y_mm'] ?? null, $data['izq_foliculo_med_z_mm'] ?? null,
            $data['izq_foliculo_contenido'] ?? null, $data['izq_foliculo_pared'] ?? null,
            $data['izq_foliculo_septos'] ?? 0, $data['izq_foliculo_septos_grosor'] ?? null,
            $data['izq_foliculo_papilares'] ?? 0, $data['izq_foliculo_papilares_num'] ?? null,
            $data['izq_foliculo_sombra'] ?? 0, $data['izq_foliculo_doppler'] ?? null,
            $data['evaluacion_id']
        ]);
    }

    public function delete($evaluacionId)
    {
        $stmt = $this->db->prepare("DELETE FROM ovarios_ginecologicos WHERE evaluacion_id = ?");
        return $stmt->execute([$evaluacionId]);
    }
}
