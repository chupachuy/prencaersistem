<?php
require_once __DIR__ . '/../core/Database.php';

class TecnicaUltrasonidoGinecologico
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM tecnica_ultrasonido_ginecologico WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO tecnica_ultrasonido_ginecologico (
                evaluacion_id, via_endovaginal, via_transabdominal, via_doppler_color,
                via_evaluacion_3d, via_sonohisterografia,
                calidad, limitada_dolor, limitada_distension_intestinal,
                limitada_habitus_corporal, limitada_posicion_uterina, calidad_otra
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['via_endovaginal'] ?? 0,
            $data['via_transabdominal'] ?? 0,
            $data['via_doppler_color'] ?? 0,
            $data['via_evaluacion_3d'] ?? 0,
            $data['via_sonohisterografia'] ?? 0,
            $data['calidad'] ?? null,
            $data['limitada_dolor'] ?? 0,
            $data['limitada_distension_intestinal'] ?? 0,
            $data['limitada_habitus_corporal'] ?? 0,
            $data['limitada_posicion_uterina'] ?? 0,
            $data['calidad_otra'] ?? null
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE tecnica_ultrasonido_ginecologico SET
                via_endovaginal = ?, via_transabdominal = ?, via_doppler_color = ?,
                via_evaluacion_3d = ?, via_sonohisterografia = ?,
                calidad = ?, limitada_dolor = ?, limitada_distension_intestinal = ?,
                limitada_habitus_corporal = ?, limitada_posicion_uterina = ?, calidad_otra = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['via_endovaginal'] ?? 0,
            $data['via_transabdominal'] ?? 0,
            $data['via_doppler_color'] ?? 0,
            $data['via_evaluacion_3d'] ?? 0,
            $data['via_sonohisterografia'] ?? 0,
            $data['calidad'] ?? null,
            $data['limitada_dolor'] ?? 0,
            $data['limitada_distension_intestinal'] ?? 0,
            $data['limitada_habitus_corporal'] ?? 0,
            $data['limitada_posicion_uterina'] ?? 0,
            $data['calidad_otra'] ?? null,
            $data['evaluacion_id']
        ]);
    }

    public function delete($evaluacionId)
    {
        $stmt = $this->db->prepare("DELETE FROM tecnica_ultrasonido_ginecologico WHERE evaluacion_id = ?");
        return $stmt->execute([$evaluacionId]);
    }
}
