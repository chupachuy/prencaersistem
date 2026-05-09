<?php
require_once __DIR__ . '/../core/Database.php';

class CatalogoConsentimiento
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM catalogo_consentimientos WHERE activo = 1 ORDER BY nombre_documento");
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM catalogo_consentimientos WHERE id = ? AND activo = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($nombreDocumento, $version = null, $requiereFirmaMedico = true, $cantidadTestigos = 0)
    {
        $stmt = $this->db->prepare("INSERT INTO catalogo_consentimientos (nombre_documento, version, requiere_firma_medico, cantidad_testigos) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombreDocumento, $version, $requiereFirmaMedico, $cantidadTestigos]);
        return $this->db->lastInsertId();
    }

    public function update($id, $nombreDocumento, $version = null, $requiereFirmaMedico = true, $cantidadTestigos = 0)
    {
        $stmt = $this->db->prepare("UPDATE catalogo_consentimientos SET nombre_documento = ?, version = ?, requiere_firma_medico = ?, cantidad_testigos = ? WHERE id = ?");
        return $stmt->execute([$nombreDocumento, $version, $requiereFirmaMedico, $cantidadTestigos, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("UPDATE catalogo_consentimientos SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
