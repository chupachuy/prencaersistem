<?php
require_once __DIR__ . '/../core/Database.php';

class Biometria2doTrimestre
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM biometria_2do_trimestre WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO biometria_2do_trimestre (
                evaluacion_id, estado_feto, fcf_lpm, peso_fetal_estimado_gr,
                percentil_hadlock, crecimiento_armonico, indice_cefalico_ci,
                fl_ac_pct, hc_ac_campbell
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'], $data['estado_feto'] ?? 'Vivo',
            $data['fcf_lpm'] ?? null, $data['peso_fetal_estimado_gr'] ?? null,
            $data['percentil_hadlock'] ?? null, $data['crecimiento_armonico'] ?? 1,
            $data['indice_cefalico_ci'] ?? null, $data['fl_ac_pct'] ?? null,
            $data['hc_ac_campbell'] ?? null
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE biometria_2do_trimestre SET
                estado_feto = ?, fcf_lpm = ?, peso_fetal_estimado_gr = ?,
                percentil_hadlock = ?, crecimiento_armonico = ?, indice_cefalico_ci = ?,
                fl_ac_pct = ?, hc_ac_campbell = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['estado_feto'] ?? 'Vivo', $data['fcf_lpm'] ?? null,
            $data['peso_fetal_estimado_gr'] ?? null, $data['percentil_hadlock'] ?? null,
            $data['crecimiento_armonico'] ?? 1, $data['indice_cefalico_ci'] ?? null,
            $data['fl_ac_pct'] ?? null, $data['hc_ac_campbell'] ?? null,
            $data['evaluacion_id']
        ]);
    }
}
