<?php
require_once __DIR__ . '/Session.php';

class Csrf
{
    public static function token()
    {
        if (!Session::has('csrf_token')) {
            return self::generateToken();
        }

        return Session::get('csrf_token');
    }

    public static function generateToken()
    {
        $token = bin2hex(random_bytes(32));
        Session::set('csrf_token', $token);
        return $token;
    }

    public static function validateToken($token)
    {
        $sessionToken = Session::get('csrf_token');
        if (empty($sessionToken) || empty($token)) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    public static function validateRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return true;
        }

        $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!self::validateToken($token)) {
            http_response_code(419);
            echo 'CSRF token inválido. Por favor recargue la página e intente de nuevo.';
            exit;
        }

        return true;
    }
}
