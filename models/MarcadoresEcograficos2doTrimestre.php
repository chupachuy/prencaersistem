<?php
require_once __DIR__ . '/../core/Database.php';

class MarcadoresEcograficos2doTrimestre
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM marcadores_ecograficos_2do_trimestre WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO marcadores_ecograficos_2do_trimestre (
                evaluacion_id, ventriculomegalia_leve, quistes_plexos_coroideos,
                pliegue_nucal_aumentado, hueso_nasal_ausente, foco_ecogenico_cardiaco,
                intestino_hiperecogenico, femur_corto, arteria_umbilical_unica
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['ventriculomegalia_leve'] ?? 0, $data['quistes_plexos_coroideos'] ?? 0,
            $data['pliegue_nucal_aumentado'] ?? 0, $data['hueso_nasal_ausente'] ?? 0,
            $data['foco_ecogenico_cardiaco'] ?? 0, $data['intestino_hiperecogenico'] ?? 0,
            $data['femur_corto'] ?? 0, $data['arteria_umbilical_unica'] ?? 0
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE marcadores_ecograficos_2do_trimestre SET
                ventriculomegalia_leve = ?, quistes_plexos_coroideos = ?,
                pliegue_nucal_aumentado = ?, hueso_nasal_ausente = ?, foco_ecogenico_cardiaco = ?,
                intestino_hiperecogenico = ?, femur_corto = ?, arteria_umbilical_unica = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['ventriculomegalia_leve'] ?? 0, $data['quistes_plexos_coroideos'] ?? 0,
            $data['pliegue_nucal_aumentado'] ?? 0, $data['hueso_nasal_ausente'] ?? 0,
            $data['foco_ecogenico_cardiaco'] ?? 0, $data['intestino_hiperecogenico'] ?? 0,
            $data['femur_corto'] ?? 0, $data['arteria_umbilical_unica'] ?? 0,
            $data['evaluacion_id']
        ]);
    }
}
