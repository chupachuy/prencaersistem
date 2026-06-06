<?php
require_once __DIR__ . '/../core/Database.php';

class AdenomiosisGinecologica
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM adenomiosis_ginecologica WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO adenomiosis_ginecologica (
                evaluacion_id, hallazgos,
                utero_globoso, asimetria_paredes, miometrio_heterogeneo,
                estriaciones_lineales, quistes_miometriales, islas_hiperecogenicas,
                sombra_abanico, zona_union_irregular, vascularidad_translesional, datos_otro,
                distribucion, predominio_anterior, predominio_posterior, predominio_fundico
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['hallazgos'] ?? null,
            $data['utero_globoso'] ?? 0,
            $data['asimetria_paredes'] ?? 0,
            $data['miometrio_heterogeneo'] ?? 0,
            $data['estriaciones_lineales'] ?? 0,
            $data['quistes_miometriales'] ?? 0,
            $data['islas_hiperecogenicas'] ?? 0,
            $data['sombra_abanico'] ?? 0,
            $data['zona_union_irregular'] ?? 0,
            $data['vascularidad_translesional'] ?? 0,
            $data['datos_otro'] ?? null,
            $data['distribucion'] ?? null,
            $data['predominio_anterior'] ?? 0,
            $data['predominio_posterior'] ?? 0,
            $data['predominio_fundico'] ?? 0
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE adenomiosis_ginecologica SET
                hallazgos = ?,
                utero_globoso = ?, asimetria_paredes = ?, miometrio_heterogeneo = ?,
                estriaciones_lineales = ?, quistes_miometriales = ?, islas_hiperecogenicas = ?,
                sombra_abanico = ?, zona_union_irregular = ?, vascularidad_translesional = ?, datos_otro = ?,
                distribucion = ?, predominio_anterior = ?, predominio_posterior = ?, predominio_fundico = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['hallazgos'] ?? null,
            $data['utero_globoso'] ?? 0,
            $data['asimetria_paredes'] ?? 0,
            $data['miometrio_heterogeneo'] ?? 0,
            $data['estriaciones_lineales'] ?? 0,
            $data['quistes_miometriales'] ?? 0,
            $data['islas_hiperecogenicas'] ?? 0,
            $data['sombra_abanico'] ?? 0,
            $data['zona_union_irregular'] ?? 0,
            $data['vascularidad_translesional'] ?? 0,
            $data['datos_otro'] ?? null,
            $data['distribucion'] ?? null,
            $data['predominio_anterior'] ?? 0,
            $data['predominio_posterior'] ?? 0,
            $data['predominio_fundico'] ?? 0,
            $data['evaluacion_id']
        ]);
    }

    public function delete($evaluacionId)
    {
        $stmt = $this->db->prepare("DELETE FROM adenomiosis_ginecologica WHERE evaluacion_id = ?");
        return $stmt->execute([$evaluacionId]);
    }
}
