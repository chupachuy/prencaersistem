<?php
require_once __DIR__ . '/../core/Database.php';

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, apellido, email, password, rol_id, especialidad, activo, email_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['rol_id'],
            $data['especialidad'] ?? null,
            $data['activo'] ?? 1,
            $data['email_verified'] ?? 0
        ]);
    }

    public function updateLoginTime($id)
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET last_login = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updatePassword($id, $hashedPassword)
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $id]);
    }

    public function update($data)
    {
        $setParts = [];
        $params = [];

        if (isset($data['nombre'])) {
            $setParts[] = "nombre = ?";
            $params[] = $data['nombre'];
        }
        if (isset($data['apellido'])) {
            $setParts[] = "apellido = ?";
            $params[] = $data['apellido'];
        }
        if (isset($data['email'])) {
            $setParts[] = "email = ?";
            $params[] = $data['email'];
        }
        if (isset($data['password'])) {
            $setParts[] = "password = ?";
            $params[] = $data['password'];
        }
        if (isset($data['rol_id'])) {
            $setParts[] = "rol_id = ?";
            $params[] = $data['rol_id'];
        }
        if (isset($data['especialidad'])) {
            $setParts[] = "especialidad = ?";
            $params[] = $data['especialidad'];
        }
        if (isset($data['activo'])) {
            $setParts[] = "activo = ?";
            $params[] = $data['activo'];
        }

        if (empty($setParts)) {
            return false;
        }

        $params[] = $data['id'];
        $sql = "UPDATE usuarios SET " . implode(", ", $setParts) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function getAll()
    {
        // Get all users with their role information
        $stmt = $this->db->query("SELECT u.*, r.nombre as rol_nombre FROM usuarios u JOIN roles r ON u.rol_id = r.id ORDER BY u.nombre ASC");
        return $stmt->fetchAll();
    }

    public function getAllDoctors()
    {
        // Query to get users with ROLE_MEDICO (Role ID 4)
        $stmt = $this->db->query("SELECT u.*, r.nombre as rol_nombre FROM usuarios u JOIN roles r ON u.rol_id = r.id WHERE u.rol_id = 4");
        return $stmt->fetchAll();
    }
}
