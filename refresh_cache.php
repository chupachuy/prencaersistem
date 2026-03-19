<?php
// Limpia OPcache
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "✓ OPcache limpio<br>";
}

// Regenera autoload
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
    echo "✓ Autoload recargado<br>";
}

echo "✓ Cache limpiado. Ahora recarga tu navegador con Ctrl+F5";
