<?php
require_once __DIR__ . '/../core/Database.php';

class MarcadoresFmf
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM marcadores_fmf WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO marcadores_fmf (
                evaluacion_id, translucencia_nucal_mm, hueso_nasal_presente,
                ductus_venoso_onda_a, regurgitacion_tricuspidea_ausente,
                vejiga_fetal_mm, uta_pi_promedio, muesca_bilateral
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['translucencia_nucal_mm'] ?? null,
            $data['hueso_nasal_presente'] ?? 1,
            $data['ductus_venoso_onda_a'] ?? null,
            $data['regurgitacion_tricuspidea_ausente'] ?? 1,
            $data['vejiga_fetal_mm'] ?? null,
            $data['uta_pi_promedio'] ?? null,
            $data['muesca_bilateral'] ?? 0
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE marcadores_fmf SET
                translucencia_nucal_mm = ?, hueso_nasal_presente = ?,
                ductus_venoso_onda_a = ?, regurgitacion_tricuspidea_ausente = ?,
                vejiga_fetal_mm = ?, uta_pi_promedio = ?, muesca_bilateral = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['translucencia_nucal_mm'] ?? null,
            $data['hueso_nasal_presente'] ?? 1,
            $data['ductus_venoso_onda_a'] ?? null,
            $data['regurgitacion_tricuspidea_ausente'] ?? 1,
            $data['vejiga_fetal_mm'] ?? null,
            $data['uta_pi_promedio'] ?? null,
            $data['muesca_bilateral'] ?? 0,
            $data['evaluacion_id']
        ]);
    }
}
