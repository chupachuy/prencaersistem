<?php
require_once __DIR__ . '/../core/Database.php';

class AnatomiaFetal2doTrimestre
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM anatomia_fetal_2do_trimestre WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO anatomia_fetal_2do_trimestre (
                evaluacion_id, craneo_snc_normal, cara_cuello_normal, corazon_normal,
                torax_diafragma_normal, abdomen_normal, genitourinario_normal,
                columna_normal, extremidades_normal, detalles_anomalias
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['craneo_snc_normal'] ?? 1, $data['cara_cuello_normal'] ?? 1,
            $data['corazon_normal'] ?? 1, $data['torax_diafragma_normal'] ?? 1,
            $data['abdomen_normal'] ?? 1, $data['genitourinario_normal'] ?? 1,
            $data['columna_normal'] ?? 1, $data['extremidades_normal'] ?? 1,
            $data['detalles_anomalias'] ?? null
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE anatomia_fetal_2do_trimestre SET
                craneo_snc_normal = ?, cara_cuello_normal = ?, corazon_normal = ?,
                torax_diafragma_normal = ?, abdomen_normal = ?, genitourinario_normal = ?,
                columna_normal = ?, extremidades_normal = ?, detalles_anomalias = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['craneo_snc_normal'] ?? 1, $data['cara_cuello_normal'] ?? 1,
            $data['corazon_normal'] ?? 1, $data['torax_diafragma_normal'] ?? 1,
            $data['abdomen_normal'] ?? 1, $data['genitourinario_normal'] ?? 1,
            $data['columna_normal'] ?? 1, $data['extremidades_normal'] ?? 1,
            $data['detalles_anomalias'] ?? null, $data['evaluacion_id']
        ]);
    }
}
