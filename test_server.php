<?php
echo "✓ Servidor activo - " . date('Y-m-d H:i:s') . "<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Base Dir: " . __DIR__ . "<br>";

// Verifica si los archivos existen
echo "<h3>Archivos del proyecto:</h3>";
$files = [
    '/controllers/UserController.php',
    '/models/User.php',
    '/views/usuarios/index.php',
    '/views/usuarios/edit.php'
];

foreach ($files as $file) {
    $path = __DIR__ . $file;
    echo "<br>$file: " . (file_exists($path) ? "✓ Existe" : "✗ NO EXISTE");
}

// Verifica OPcache
echo "<h3>Cache:</h3>";
echo (function_exists('opcache_get_status') ? "OPcache HABILITADO" : "OPcache deshabilitado");
