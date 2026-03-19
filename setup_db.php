<?php
require_once __DIR__ . '/config/database.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    // Create DB if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    $pdo->exec("USE " . DB_NAME);

    $sql = file_get_contents(__DIR__ . '/init_db.sql');
    $pdo->exec($sql);
    echo "Database initialized successfully.\n";
}
catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
