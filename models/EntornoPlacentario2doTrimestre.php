<?php
require_once __DIR__ . '/../core/Database.php';

class EntornoPlacentario2doTrimestre
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM entorno_placentario_2do_trimestre WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO entorno_placentario_2do_trimestre (
                evaluacion_id, placenta_posicion, distancia_borde_oci_mm,
                acretismo_figo_grado, bolsillo_max_liquido_mm, longitud_cervical_mm,
                indice_consistencia_cervical, funneling_presente, funneling_mm,
                sludge_intraamniotico, morfologia_uterina_eshre, miomas_visibles,
                miomas_figo_tipo, miomas_dimensiones_mm, miomas_vascularizacion
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'], $data['placenta_posicion'] ?? null,
            $data['distancia_borde_oci_mm'] ?? null, $data['acretismo_figo_grado'] ?? null,
            $data['bolsillo_max_liquido_mm'] ?? null, $data['longitud_cervical_mm'] ?? null,
            $data['indice_consistencia_cervical'] ?? null, $data['funneling_presente'] ?? 0,
            $data['funneling_mm'] ?? null, $data['sludge_intraamniotico'] ?? null,
            $data['morfologia_uterina_eshre'] ?? null, $data['miomas_visibles'] ?? 0,
            $data['miomas_figo_tipo'] ?? null, $data['miomas_dimensiones_mm'] ?? null,
            $data['miomas_vascularizacion'] ?? null
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE entorno_placentario_2do_trimestre SET
                placenta_posicion = ?, distancia_borde_oci_mm = ?, acretismo_figo_grado = ?,
                bolsillo_max_liquido_mm = ?, longitud_cervical_mm = ?,
                indice_consistencia_cervical = ?, funneling_presente = ?,
                funneling_mm = ?, sludge_intraamniotico = ?,
                morfologia_uterina_eshre = ?, miomas_visibles = ?,
                miomas_figo_tipo = ?, miomas_dimensiones_mm = ?, miomas_vascularizacion = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['placenta_posicion'] ?? null, $data['distancia_borde_oci_mm'] ?? null,
            $data['acretismo_figo_grado'] ?? null, $data['bolsillo_max_liquido_mm'] ?? null,
            $data['longitud_cervical_mm'] ?? null, $data['indice_consistencia_cervical'] ?? null,
            $data['funneling_presente'] ?? 0, $data['funneling_mm'] ?? null,
            $data['sludge_intraamniotico'] ?? null, $data['morfologia_uterina_eshre'] ?? null,
            $data['miomas_visibles'] ?? 0, $data['miomas_figo_tipo'] ?? null,
            $data['miomas_dimensiones_mm'] ?? null, $data['miomas_vascularizacion'] ?? null,
            $data['evaluacion_id']
        ]);
    }
}
