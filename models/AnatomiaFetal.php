<?php
require_once __DIR__ . '/../core/Database.php';

class AnatomiaFetal
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM anatomia_fetal WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO anatomia_fetal (
                evaluacion_id, estado_exploracion, snc_simetria_plexos, macizo_facial_integro,
                torax_situs, torax_eje_cardiaco_grados, abdomen_camara_gastrica,
                extremidades_completas, observaciones_anomalias
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['estado_exploracion'] ?? 'Completa',
            $data['snc_simetria_plexos'] ?? 1,
            $data['macizo_facial_integro'] ?? 1,
            $data['torax_situs'] ?? 'Solitus',
            $data['torax_eje_cardiaco_grados'] ?? null,
            $data['abdomen_camara_gastrica'] ?? 1,
            $data['extremidades_completas'] ?? 1,
            $data['observaciones_anomalias'] ?? null
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE anatomia_fetal SET
                estado_exploracion = ?, snc_simetria_plexos = ?, macizo_facial_integro = ?,
                torax_situs = ?, torax_eje_cardiaco_grados = ?, abdomen_camara_gastrica = ?,
                extremidades_completas = ?, observaciones_anomalias = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['estado_exploracion'] ?? 'Completa',
            $data['snc_simetria_plexos'] ?? 1,
            $data['macizo_facial_integro'] ?? 1,
            $data['torax_situs'] ?? 'Solitus',
            $data['torax_eje_cardiaco_grados'] ?? null,
            $data['abdomen_camara_gastrica'] ?? 1,
            $data['extremidades_completas'] ?? 1,
            $data['observaciones_anomalias'] ?? null,
            $data['evaluacion_id']
        ]);
    }
}
