<?php
require_once __DIR__ . '/../core/Database.php';

class PasswordReset
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createToken($email)
    {
        $token = bin2hex(random_bytes(32));
        // Token válido por 2 horas
        $expires = date('Y-m-d H:i:s', time() + 7200);

        // Invalidate older tokens
        $stmt = $this->db->prepare("UPDATE password_resets SET used = 1 WHERE email = ?");
        $stmt->execute([$email]);

        $stmt = $this->db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $expires]);

        return $token;
    }

    public function verifyToken($token)
    {
        $stmt = $this->db->prepare("SELECT * FROM password_resets WHERE token = ? AND used = 0 AND expires_at > NOW() ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    public function markAsUsed($id)
    {
        $stmt = $this->db->prepare("UPDATE password_resets SET used = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
