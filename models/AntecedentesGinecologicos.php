<?php
require_once __DIR__ . '/../core/Database.php';

class AntecedentesGinecologicos
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($evaluacionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM antecedentes_ginecologicos WHERE evaluacion_id = ?");
        $stmt->execute([$evaluacionId]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO antecedentes_ginecologicos (
                evaluacion_id, gesta, para, cesareas, abortos, paridad_satisfecha,
                legrado_cirugia_uterina, miomectomia, endometriosis_adenomiosis, otros
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['gesta'] ?? null,
            $data['para'] ?? null,
            $data['cesareas'] ?? null,
            $data['abortos'] ?? null,
            $data['paridad_satisfecha'] ?? null,
            $data['legrado_cirugia_uterina'] ?? 0,
            $data['miomectomia'] ?? 0,
            $data['endometriosis_adenomiosis'] ?? 0,
            $data['otros'] ?? null
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE antecedentes_ginecologicos SET
                gesta = ?, para = ?, cesareas = ?, abortos = ?, paridad_satisfecha = ?,
                legrado_cirugia_uterina = ?, miomectomia = ?, endometriosis_adenomiosis = ?, otros = ?
            WHERE evaluacion_id = ?
        ");
        return $stmt->execute([
            $data['gesta'] ?? null,
            $data['para'] ?? null,
            $data['cesareas'] ?? null,
            $data['abortos'] ?? null,
            $data['paridad_satisfecha'] ?? null,
            $data['legrado_cirugia_uterina'] ?? 0,
            $data['miomectomia'] ?? 0,
            $data['endometriosis_adenomiosis'] ?? 0,
            $data['otros'] ?? null,
            $data['evaluacion_id']
        ]);
    }

    public function delete($evaluacionId)
    {
        $stmt = $this->db->prepare("DELETE FROM antecedentes_ginecologicos WHERE evaluacion_id = ?");
        return $stmt->execute([$evaluacionId]);
    }
}
