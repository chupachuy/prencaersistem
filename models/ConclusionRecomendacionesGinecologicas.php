<?php
require_once __DIR__ . '/../core/Database.php';

class ConclusionRecomendacionesGinecologicas
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM conclusion_recomendaciones_ginecologicas WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO conclusion_recomendaciones_ginecologicas (
                evaluacion_id,
                estudio_limites_esperados, miomatosis_uterina,
                conclusion_mioma_dominante_mm, conclusion_figo,
                engrosamiento_endometrial, conclusion_medida_endometrio_mm,
                imagen_focal_polipo, datos_sugestivos_adenomiosis,
                quiste_simple_der, quiste_simple_izq,
                quiste_hemorragico_der, quiste_hemorragico_izq,
                endometrioma_der, endometrioma_izq,
                conclusion_quiste_medida_mm, masa_anexial_indeterminada, conclusion_otro,
                rec_correlacion_edad_fum, rec_correlacion_hb_hormonal,
                rec_estudio_histologico, rec_histeroscopia_endometrio,
                rec_sonohisterografia_histeroscopia, rec_valorar_manejo_miomatosis,
                rec_iorads_marcadores_oncologia, rec_control_ultrasonografico,
                rec_control_tiempo, rec_control_unidad, rec_otro
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['estudio_limites_esperados'] ?? 0,
            $data['miomatosis_uterina'] ?? 0,
            $data['conclusion_mioma_dominante_mm'] ?? null,
            $data['conclusion_figo'] ?? null,
            $data['engrosamiento_endometrial'] ?? 0,
            $data['conclusion_medida_endometrio_mm'] ?? null,
            $data['imagen_focal_polipo'] ?? 0,
            $data['datos_sugestivos_adenomiosis'] ?? 0,
            $data['quiste_simple_der'] ?? 0,
            $data['quiste_simple_izq'] ?? 0,
            $data['quiste_hemorragico_der'] ?? 0,
            $data['quiste_hemorragico_izq'] ?? 0,
            $data['endometrioma_der'] ?? 0,
            $data['endometrioma_izq'] ?? 0,
            $data['conclusion_quiste_medida_mm'] ?? null,
            $data['masa_anexial_indeterminada'] ?? 0,
            $data['conclusion_otro'] ?? null,
            $data['rec_correlacion_edad_fum'] ?? 0,
            $data['rec_correlacion_hb_hormonal'] ?? 0,
            $data['rec_estudio_histologico'] ?? 0,
            $data['rec_histeroscopia_endometrio'] ?? 0,
            $data['rec_sonohisterografia_histeroscopia'] ?? 0,
            $data['rec_valorar_manejo_miomatosis'] ?? 0,
            $data['rec_iorads_marcadores_oncologia'] ?? 0,
            $data['rec_control_ultrasonografico'] ?? 0,
            $data['rec_control_tiempo'] ?? null,
            $data['rec_control_unidad'] ?? null,
            $data['rec_otro'] ?? null
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE conclusion_recomendaciones_ginecologicas SET
                estudio_limites_esperados = ?, miomatosis_uterina = ?,
                conclusion_mioma_dominante_mm = ?, conclusion_figo = ?,
                engrosamiento_endometrial = ?, conclusion_medida_endometrio_mm = ?,
                imagen_focal_polipo = ?, datos_sugestivos_adenomiosis = ?,
                quiste_simple_der = ?, quiste_simple_izq = ?,
                quiste_hemorragico_der = ?, quiste_hemorragico_izq = ?,
                endometrioma_der = ?, endometrioma_izq = ?,
                conclusion_quiste_medida_mm = ?, masa_anexial_indeterminada = ?, conclusion_otro = ?,
                rec_correlacion_edad_fum = ?, rec_correlacion_hb_hormonal = ?,
                rec_estudio_histologico = ?, rec_histeroscopia_endometrio = ?,
                rec_sonohisterografia_histeroscopia = ?, rec_valorar_manejo_miomatosis = ?,
                rec_iorads_marcadores_oncologia = ?, rec_control_ultrasonografico = ?,
                rec_control_tiempo = ?, rec_control_unidad = ?, rec_otro = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['estudio_limites_esperados'] ?? 0,
            $data['miomatosis_uterina'] ?? 0,
            $data['conclusion_mioma_dominante_mm'] ?? null,
            $data['conclusion_figo'] ?? null,
            $data['engrosamiento_endometrial'] ?? 0,
            $data['conclusion_medida_endometrio_mm'] ?? null,
            $data['imagen_focal_polipo'] ?? 0,
            $data['datos_sugestivos_adenomiosis'] ?? 0,
            $data['quiste_simple_der'] ?? 0,
            $data['quiste_simple_izq'] ?? 0,
            $data['quiste_hemorragico_der'] ?? 0,
            $data['quiste_hemorragico_izq'] ?? 0,
            $data['endometrioma_der'] ?? 0,
            $data['endometrioma_izq'] ?? 0,
            $data['conclusion_quiste_medida_mm'] ?? null,
            $data['masa_anexial_indeterminada'] ?? 0,
            $data['conclusion_otro'] ?? null,
            $data['rec_correlacion_edad_fum'] ?? 0,
            $data['rec_correlacion_hb_hormonal'] ?? 0,
            $data['rec_estudio_histologico'] ?? 0,
            $data['rec_histeroscopia_endometrio'] ?? 0,
            $data['rec_sonohisterografia_histeroscopia'] ?? 0,
            $data['rec_valorar_manejo_miomatosis'] ?? 0,
            $data['rec_iorads_marcadores_oncologia'] ?? 0,
            $data['rec_control_ultrasonografico'] ?? 0,
            $data['rec_control_tiempo'] ?? null,
            $data['rec_control_unidad'] ?? null,
            $data['rec_otro'] ?? null,
            $data['evaluacion_id']
        ]);
    }

    public function delete($evaluacionId)
    {
        $stmt = $this->db->prepare("DELETE FROM conclusion_recomendaciones_ginecologicas WHERE evaluacion_id = ?");
        return $stmt->execute([$evaluacionId]);
    }
}
