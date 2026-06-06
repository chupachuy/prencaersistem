<?php
require_once __DIR__ . '/../core/Database.php';

class ClasificacionOrientativaGinecologica
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM clasificacion_orientativa_ginecologica WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO clasificacion_orientativa_ginecologica (
                evaluacion_id,
                palm_polipo, palm_adenomiosis, palm_leiomioma, palm_malignidad,
                palm_coagulopatia, palm_ovulatoria, palm_endometrial,
                palm_iatrogenica, palm_no_clasificada,
                anexial_funcional, anexial_benigna, anexial_indeterminada,
                anexial_sospechosa, anexial_sugiere_o_rads
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['palm_polipo'] ?? 0,
            $data['palm_adenomiosis'] ?? 0,
            $data['palm_leiomioma'] ?? 0,
            $data['palm_malignidad'] ?? 0,
            $data['palm_coagulopatia'] ?? 0,
            $data['palm_ovulatoria'] ?? 0,
            $data['palm_endometrial'] ?? 0,
            $data['palm_iatrogenica'] ?? 0,
            $data['palm_no_clasificada'] ?? 0,
            $data['anexial_funcional'] ?? 0,
            $data['anexial_benigna'] ?? 0,
            $data['anexial_indeterminada'] ?? 0,
            $data['anexial_sospechosa'] ?? 0,
            $data['anexial_sugiere_o_rads'] ?? 0
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE clasificacion_orientativa_ginecologica SET
                palm_polipo = ?, palm_adenomiosis = ?, palm_leiomioma = ?, palm_malignidad = ?,
                palm_coagulopatia = ?, palm_ovulatoria = ?, palm_endometrial = ?,
                palm_iatrogenica = ?, palm_no_clasificada = ?,
                anexial_funcional = ?, anexial_benigna = ?, anexial_indeterminada = ?,
                anexial_sospechosa = ?, anexial_sugiere_o_rads = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['palm_polipo'] ?? 0,
            $data['palm_adenomiosis'] ?? 0,
            $data['palm_leiomioma'] ?? 0,
            $data['palm_malignidad'] ?? 0,
            $data['palm_coagulopatia'] ?? 0,
            $data['palm_ovulatoria'] ?? 0,
            $data['palm_endometrial'] ?? 0,
            $data['palm_iatrogenica'] ?? 0,
            $data['palm_no_clasificada'] ?? 0,
            $data['anexial_funcional'] ?? 0,
            $data['anexial_benigna'] ?? 0,
            $data['anexial_indeterminada'] ?? 0,
            $data['anexial_sospechosa'] ?? 0,
            $data['anexial_sugiere_o_rads'] ?? 0,
            $data['evaluacion_id']
        ]);
    }

    public function delete($evaluacionId)
    {
        $stmt = $this->db->prepare("DELETE FROM clasificacion_orientativa_ginecologica WHERE evaluacion_id = ?");
        return $stmt->execute([$evaluacionId]);
    }
}
