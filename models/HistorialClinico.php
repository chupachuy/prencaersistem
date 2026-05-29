<?php
require_once __DIR__ . '/../core/Database.php';

class HistorialClinico
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByPaciente($pacienteId)
    {
        $stmt = $this->db->prepare("SELECT * FROM historial_clinico WHERE paciente_id = ?");
        $stmt->execute([$pacienteId]);
        $row = $stmt->fetch();
        return $row ?: [
            'hipertension_cronica' => false,
            'diabetes' => false,
            'lupus_les' => false,
            'sindrome_antifosfolipido_saf' => false,
            'antecedente_preeclampsia_rciu' => false,
            'fertilizacion_in_vitro' => false,
            'antecedente_parto_pretermino' => false
        ];
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO historial_clinico (paciente_id, hipertension_cronica, diabetes, lupus_les, sindrome_antifosfolipido_saf, antecedente_preeclampsia_rciu, fertilizacion_in_vitro, antecedente_parto_pretermino) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['paciente_id'],
            $data['hipertension_cronica'] ?? 0,
            $data['diabetes'] ?? 0,
            $data['lupus_les'] ?? 0,
            $data['sindrome_antifosfolipido_saf'] ?? 0,
            $data['antecedente_preeclampsia_rciu'] ?? 0,
            $data['fertilizacion_in_vitro'] ?? 0,
            $data['antecedente_parto_pretermino'] ?? 0
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("UPDATE historial_clinico SET hipertension_cronica = ?, diabetes = ?, lupus_les = ?, sindrome_antifosfolipido_saf = ?, antecedente_preeclampsia_rciu = ?, fertilizacion_in_vitro = ?, antecedente_parto_pretermino = ? WHERE paciente_id = ?");
        return $stmt->execute([
            $data['hipertension_cronica'] ?? 0,
            $data['diabetes'] ?? 0,
            $data['lupus_les'] ?? 0,
            $data['sindrome_antifosfolipido_saf'] ?? 0,
            $data['antecedente_preeclampsia_rciu'] ?? 0,
            $data['fertilizacion_in_vitro'] ?? 0,
            $data['antecedente_parto_pretermino'] ?? 0,
            $data['paciente_id']
        ]);
    }
}
