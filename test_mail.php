<?php
// Email test script - Prenacer
// Access via: http://localhost/prencaersistem/test_mail.php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "<h2>Test de Email - PreNacer</h2>";
echo "<pre>";

$to = $_GET['to'] ?? 'chupachuy@gmail.com';

echo "Enviando a: $to\n";
echo "SMTP: " . MAIL_HOST . ":" . MAIL_PORT . "\n";
echo "Usuario: " . MAIL_USERNAME . "\n\n";

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = MAIL_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = MAIL_USERNAME;
    $mail->Password = MAIL_PASSWORD;
    $mail->SMTPSecure = (MAIL_PORT == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = MAIL_PORT;
    $mail->CharSet = 'UTF-8';
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];
    
    // SMTPDebug: 0=off, 1=client, 2=client+server, 3=connection
    $mail->SMTPDebug = 3;
    
    $mail->setFrom(MAIL_FROM_ADDRESS, 'PreNacer Test');
    $mail->addAddress($to, 'Test Recipient');
    $mail->Subject = 'Test PreNacer - ' . date('Y-m-d H:i:s');
    $mail->isHTML(true);
    $mail->Body = '<p>Este es un correo de prueba del sistema PreNacer.</p><p>Fecha: ' . date('Y-m-d H:i:s') . '</p>';
    $mail->AltBody = 'Test PreNacer - ' . date('Y-m-d H:i:s');

    echo "Conectando a SMTP...\n";
    echo "---\n";
    
    if ($mail->send()) {
        echo "---\n";
        echo "\n✅ CORREO ENVIADO EXITOSAMENTE\n";
        echo "Si no ves este email en tu bandeja de entrada:\n";
        echo "1. Revisa SPAM / No deseados\n";
        echo "2. Revisa la pestaña 'Promociones' en Gmail\n";
        echo "3. Busca por 'PreNacer' en el buscador de Gmail\n";
    }
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $mail->ErrorInfo . "\n";
    echo "Exception: " . $e->getMessage() . "\n";
}

echo "</pre>";
