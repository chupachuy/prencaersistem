<?php
require_once __DIR__ . '/../core/Database.php';

class EndometrioGinecologico
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM endometrio_ginecologico WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO endometrio_ginecologico (
                evaluacion_id, grosor_mm, patron, correlacion_ciclo,
                cavidad_regular, cavidad_distorsionada, cavidad_liquido_intracavitario,
                cavidad_imagen_focal_polipo, cavidad_imagen_mioma_submucoso,
                cavidad_sinequias, cavidad_diu_intrauterino, cavidad_otro,
                doppler, diu_posicion, diu_distancia_fondo_mm
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['grosor_mm'] ?? null,
            $data['patron'] ?? null,
            $data['correlacion_ciclo'] ?? null,
            $data['cavidad_regular'] ?? 0,
            $data['cavidad_distorsionada'] ?? 0,
            $data['cavidad_liquido_intracavitario'] ?? 0,
            $data['cavidad_imagen_focal_polipo'] ?? 0,
            $data['cavidad_imagen_mioma_submucoso'] ?? 0,
            $data['cavidad_sinequias'] ?? 0,
            $data['cavidad_diu_intrauterino'] ?? 0,
            $data['cavidad_otro'] ?? null,
            $data['doppler'] ?? null,
            $data['diu_posicion'] ?? null,
            $data['diu_distancia_fondo_mm'] ?? null
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE endometrio_ginecologico SET
                grosor_mm = ?, patron = ?, correlacion_ciclo = ?,
                cavidad_regular = ?, cavidad_distorsionada = ?, cavidad_liquido_intracavitario = ?,
                cavidad_imagen_focal_polipo = ?, cavidad_imagen_mioma_submucoso = ?,
                cavidad_sinequias = ?, cavidad_diu_intrauterino = ?, cavidad_otro = ?,
                doppler = ?, diu_posicion = ?, diu_distancia_fondo_mm = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['grosor_mm'] ?? null,
            $data['patron'] ?? null,
            $data['correlacion_ciclo'] ?? null,
            $data['cavidad_regular'] ?? 0,
            $data['cavidad_distorsionada'] ?? 0,
            $data['cavidad_liquido_intracavitario'] ?? 0,
            $data['cavidad_imagen_focal_polipo'] ?? 0,
            $data['cavidad_imagen_mioma_submucoso'] ?? 0,
            $data['cavidad_sinequias'] ?? 0,
            $data['cavidad_diu_intrauterino'] ?? 0,
            $data['cavidad_otro'] ?? null,
            $data['doppler'] ?? null,
            $data['diu_posicion'] ?? null,
            $data['diu_distancia_fondo_mm'] ?? null,
            $data['evaluacion_id']
        ]);
    }

    public function delete($evaluacionId)
    {
        $stmt = $this->db->prepare("DELETE FROM endometrio_ginecologico WHERE evaluacion_id = ?");
        return $stmt->execute([$evaluacionId]);
    }
}
