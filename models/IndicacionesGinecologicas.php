<?php
require_once __DIR__ . '/../core/Database.php';

class IndicacionesGinecologicas
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM indicaciones_ginecologicas WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO indicaciones_ginecologicas (
                evaluacion_id, sangrado_uterino_anormal, dolor_pelvico, miomatosis_uterina,
                sospecha_polipo_endometrial, engrosamiento_endometrial, control_diu,
                infertilidad_reproduccion, quiste_ovarico_masa_anexial, sindrome_climaterico,
                sangrado_posmenopausico, motivo_estudio_otro,
                premenopausica, perimenopausica, posmenopausica,
                terapia_hormonal, tamoxifeno, anticonceptivos_hormonales, estatus_no_especificado
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['sangrado_uterino_anormal'] ?? 0,
            $data['dolor_pelvico'] ?? 0,
            $data['miomatosis_uterina'] ?? 0,
            $data['sospecha_polipo_endometrial'] ?? 0,
            $data['engrosamiento_endometrial'] ?? 0,
            $data['control_diu'] ?? 0,
            $data['infertilidad_reproduccion'] ?? 0,
            $data['quiste_ovarico_masa_anexial'] ?? 0,
            $data['sindrome_climaterico'] ?? 0,
            $data['sangrado_posmenopausico'] ?? 0,
            $data['motivo_estudio_otro'] ?? null,
            $data['premenopausica'] ?? 0,
            $data['perimenopausica'] ?? 0,
            $data['posmenopausica'] ?? 0,
            $data['terapia_hormonal'] ?? 0,
            $data['tamoxifeno'] ?? 0,
            $data['anticonceptivos_hormonales'] ?? 0,
            $data['estatus_no_especificado'] ?? 0
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE indicaciones_ginecologicas SET
                sangrado_uterino_anormal = ?, dolor_pelvico = ?, miomatosis_uterina = ?,
                sospecha_polipo_endometrial = ?, engrosamiento_endometrial = ?, control_diu = ?,
                infertilidad_reproduccion = ?, quiste_ovarico_masa_anexial = ?, sindrome_climaterico = ?,
                sangrado_posmenopausico = ?, motivo_estudio_otro = ?,
                premenopausica = ?, perimenopausica = ?, posmenopausica = ?,
                terapia_hormonal = ?, tamoxifeno = ?, anticonceptivos_hormonales = ?, estatus_no_especificado = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['sangrado_uterino_anormal'] ?? 0,
            $data['dolor_pelvico'] ?? 0,
            $data['miomatosis_uterina'] ?? 0,
            $data['sospecha_polipo_endometrial'] ?? 0,
            $data['engrosamiento_endometrial'] ?? 0,
            $data['control_diu'] ?? 0,
            $data['infertilidad_reproduccion'] ?? 0,
            $data['quiste_ovarico_masa_anexial'] ?? 0,
            $data['sindrome_climaterico'] ?? 0,
            $data['sangrado_posmenopausico'] ?? 0,
            $data['motivo_estudio_otro'] ?? null,
            $data['premenopausica'] ?? 0,
            $data['perimenopausica'] ?? 0,
            $data['posmenopausica'] ?? 0,
            $data['terapia_hormonal'] ?? 0,
            $data['tamoxifeno'] ?? 0,
            $data['anticonceptivos_hormonales'] ?? 0,
            $data['estatus_no_especificado'] ?? 0,
            $data['evaluacion_id']
        ]);
    }

    public function delete($evaluacionId)
    {
        $stmt = $this->db->prepare("DELETE FROM indicaciones_ginecologicas WHERE evaluacion_id = ?");
        return $stmt->execute([$evaluacionId]);
    }
}
