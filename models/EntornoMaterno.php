<?php
require_once __DIR__ . '/../core/Database.php';

class EntornoMaterno
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM entorno_materno WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO entorno_materno (
                evaluacion_id, liquido_amniotico, placenta_posicion, placenta_insercion,
                longitud_cervical_mm, indice_consistencia_cervical_pct,
                morfologia_uterina_eshre, miomas_visibles, miomas_figo_tipo
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['liquido_amniotico'] ?? 'Normal',
            $data['placenta_posicion'] ?? null,
            $data['placenta_insercion'] ?? null,
            $data['longitud_cervical_mm'] ?? null,
            $data['indice_consistencia_cervical_pct'] ?? null,
            $data['morfologia_uterina_eshre'] ?? null,
            $data['miomas_visibles'] ?? 0,
            $data['miomas_figo_tipo'] ?? null
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE entorno_materno SET
                liquido_amniotico = ?, placenta_posicion = ?, placenta_insercion = ?,
                longitud_cervical_mm = ?, indice_consistencia_cervical_pct = ?,
                morfologia_uterina_eshre = ?, miomas_visibles = ?, miomas_figo_tipo = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['liquido_amniotico'] ?? 'Normal',
            $data['placenta_posicion'] ?? null,
            $data['placenta_insercion'] ?? null,
            $data['longitud_cervical_mm'] ?? null,
            $data['indice_consistencia_cervical_pct'] ?? null,
            $data['morfologia_uterina_eshre'] ?? null,
            $data['miomas_visibles'] ?? 0,
            $data['miomas_figo_tipo'] ?? null,
            $data['evaluacion_id']
        ]);
    }
}
