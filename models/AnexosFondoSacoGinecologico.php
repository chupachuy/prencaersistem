<?php
require_once __DIR__ . '/../core/Database.php';

class AnexosFondoSacoGinecologico
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM anexos_fondo_saco_ginecologico WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO anexos_fondo_saco_ginecologico (
                evaluacion_id,
                der_sin_alteraciones, der_lesion_anexial, der_hidrosalpinx, der_paraovarico, der_otro,
                izq_sin_alteraciones, izq_lesion_anexial, izq_hidrosalpinx, izq_paraovarico, izq_otro,
                fondo_saco_libre, fondo_saco_liquido_escaso, fondo_saco_liquido_moderado,
                fondo_saco_liquido_abundante, fondo_saco_liquido_ecos,
                fondo_saco_nodulo_implante, fondo_saco_dolor_presion, sliding_sign
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['der_sin_alteraciones'] ?? 0,
            $data['der_lesion_anexial'] ?? 0,
            $data['der_hidrosalpinx'] ?? 0,
            $data['der_paraovarico'] ?? 0,
            $data['der_otro'] ?? null,
            $data['izq_sin_alteraciones'] ?? 0,
            $data['izq_lesion_anexial'] ?? 0,
            $data['izq_hidrosalpinx'] ?? 0,
            $data['izq_paraovarico'] ?? 0,
            $data['izq_otro'] ?? null,
            $data['fondo_saco_libre'] ?? 0,
            $data['fondo_saco_liquido_escaso'] ?? 0,
            $data['fondo_saco_liquido_moderado'] ?? 0,
            $data['fondo_saco_liquido_abundante'] ?? 0,
            $data['fondo_saco_liquido_ecos'] ?? 0,
            $data['fondo_saco_nodulo_implante'] ?? 0,
            $data['fondo_saco_dolor_presion'] ?? 0,
            $data['sliding_sign'] ?? null
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE anexos_fondo_saco_ginecologico SET
                der_sin_alteraciones = ?, der_lesion_anexial = ?, der_hidrosalpinx = ?, der_paraovarico = ?, der_otro = ?,
                izq_sin_alteraciones = ?, izq_lesion_anexial = ?, izq_hidrosalpinx = ?, izq_paraovarico = ?, izq_otro = ?,
                fondo_saco_libre = ?, fondo_saco_liquido_escaso = ?, fondo_saco_liquido_moderado = ?,
                fondo_saco_liquido_abundante = ?, fondo_saco_liquido_ecos = ?,
                fondo_saco_nodulo_implante = ?, fondo_saco_dolor_presion = ?, sliding_sign = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['der_sin_alteraciones'] ?? 0,
            $data['der_lesion_anexial'] ?? 0,
            $data['der_hidrosalpinx'] ?? 0,
            $data['der_paraovarico'] ?? 0,
            $data['der_otro'] ?? null,
            $data['izq_sin_alteraciones'] ?? 0,
            $data['izq_lesion_anexial'] ?? 0,
            $data['izq_hidrosalpinx'] ?? 0,
            $data['izq_paraovarico'] ?? 0,
            $data['izq_otro'] ?? null,
            $data['fondo_saco_libre'] ?? 0,
            $data['fondo_saco_liquido_escaso'] ?? 0,
            $data['fondo_saco_liquido_moderado'] ?? 0,
            $data['fondo_saco_liquido_abundante'] ?? 0,
            $data['fondo_saco_liquido_ecos'] ?? 0,
            $data['fondo_saco_nodulo_implante'] ?? 0,
            $data['fondo_saco_dolor_presion'] ?? 0,
            $data['sliding_sign'] ?? null,
            $data['evaluacion_id']
        ]);
    }

    public function delete($evaluacionId)
    {
        $stmt = $this->db->prepare("DELETE FROM anexos_fondo_saco_ginecologico WHERE evaluacion_id = ?");
        return $stmt->execute([$evaluacionId]);
    }
}
