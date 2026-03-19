<?php
class Url
{
    public static function base()
    {
        if (defined('BASE_URL')) {
            return BASE_URL;
        }
        
        $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptPath === '/' || $scriptPath === '\\') {
            return '';
        }
        return $scriptPath;
    }

    public static function to($path)
    {
        $path = ltrim($path, '/');
        // If the path is empty, just return base
        if (empty($path)) {
            return self::base();
        }
        // Base may already have a leading slash, so we join smartly
        return rtrim(self::base(), '/') . '/' . ltrim($path, '/');
    }
}
