<?php
class Session
{
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function remove($key)
    {
        if (self::has($key)) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy()
    {
        session_destroy();
        $_SESSION = [];
    }

    public static function getFlash($key)
    {
        if (self::has($key)) {
            $val = self::get($key);
            self::remove($key);
            return $val;
        }
        return null;
    }
}
