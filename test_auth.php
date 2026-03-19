<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/controllers/AuthController.php';

echo "Instantiating AuthController\n";
try {
    $auth = new AuthController();
    echo "Calling showLogin\n";
    $auth->showLogin();
    echo "Finished showLogin\n";
}
catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
