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
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, apellido, email, telefono, password, rol_id, especialidad, activo, email_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['email'],
            $data['telefono'] ?? null,
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
        if (isset($data['telefono'])) {
            $setParts[] = "telefono = ?";
            $params[] = $data['telefono'];
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
        if (isset($data['ruta_firma'])) {
            $setParts[] = "ruta_firma = ?";
            $params[] = $data['ruta_firma'];
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

    public function getAllPaginated($search = '', $rolId = null, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $conditions = [];
        $params = [];

        if ($rolId !== null) {
            $conditions[] = 'u.rol_id = ?';
            $params[] = $rolId;
        }

        if (!empty($search)) {
            $conditions[] = '(u.nombre LIKE ? OR u.apellido LIKE ? OR u.email LIKE ? OR u.especialidad LIKE ?)';
            $s = '%' . $search . '%';
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
        }

        $where = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $params[] = $perPage;
        $params[] = $offset;

        $stmt = $this->db->prepare("
            SELECT u.*, r.nombre as rol_nombre
            FROM usuarios u
            JOIN roles r ON u.rol_id = r.id
            $where
            ORDER BY u.nombre ASC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function countAll($search = '', $rolId = null)
    {
        $conditions = [];
        $params = [];

        if ($rolId !== null) {
            $conditions[] = 'u.rol_id = ?';
            $params[] = $rolId;
        }

        if (!empty($search)) {
            $conditions[] = '(u.nombre LIKE ? OR u.apellido LIKE ? OR u.email LIKE ? OR u.especialidad LIKE ?)';
            $s = '%' . $search . '%';
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
        }

        $where = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total
            FROM usuarios u
            JOIN roles r ON u.rol_id = r.id
            $where
        ");
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ? (int) $result['total'] : 0;
    }

    public function getAllDoctors()
    {
        $stmt = $this->db->query("SELECT u.*, r.nombre as rol_nombre FROM usuarios u JOIN roles r ON u.rol_id = r.id WHERE u.rol_id = 4");
        return $stmt->fetchAll();
    }

    public function getMedicos()
    {
        $stmt = $this->db->query("SELECT id, nombre, apellido, especialidad FROM usuarios WHERE rol_id = 4 AND activo = 1 ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }

    public function updateFirma($id, $rutaFirma)
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET ruta_firma = ? WHERE id = ?");
        return $stmt->execute([$rutaFirma, $id]);
    }

    public function getFirma($id)
    {
        $stmt = $this->db->prepare("SELECT ruta_firma FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $row['ruta_firma'] : null;
    }
}
