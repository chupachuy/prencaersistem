<?php
require_once __DIR__ . '/Session.php';

class Auth
{
    public static function login($user)
    {
        Session::set('user_id', $user['id']);
        Session::set('user_role_id', $user['rol_id']);
        Session::set('user_name', $user['nombre']);
    }

    public static function check()
    {
        return Session::has('user_id');
    }

    public static function user()
    {
        // Here we could query DB, but for now just return basic info from session
        return [
            'id' => Session::get('user_id'),
            'rol_id' => Session::get('user_role_id'),
            'nombre' => Session::get('user_name'),
        ];
    }

    public static function id()
    {
        return Session::get('user_id');
    }

    public static function logout()
    {
        Session::destroy();
    }

    public static function hasRole($roleId)
    {
        return self::check() && Session::get('user_role_id') == $roleId;
    }

    // Role Constants
    const ROLE_SUPERADMIN = 1;
    const ROLE_ADMINISTRADOR = 2;
    const ROLE_JEFE = 3;
    const ROLE_MEDICO = 4;
}
