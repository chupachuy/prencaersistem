<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private $mail;
    public $lastError = null;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->setup();
    }

    private function setup()
    {
        try {
            $this->mail->isSMTP();
            $this->mail->Host = MAIL_HOST;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = MAIL_USERNAME;
            $this->mail->Password = MAIL_PASSWORD;
            if (MAIL_PORT == 465) {
                $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $this->mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $this->mail->Port = MAIL_PORT;
            $this->mail->CharSet = 'UTF-8';

            $this->mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
            $this->lastError = null;
        }
        catch (Exception $e) {
            $this->lastError = 'SMTP Setup Error: ' . $this->mail->ErrorInfo;
            error_log($this->lastError);
        }
    }

    public function sendEmail($to, $subject, $body, $isHTML = true)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to);
            $this->mail->isHTML($isHTML);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->AltBody = strip_tags($body);

            $result = $this->mail->send();
            $this->lastError = null;
            return $result;
        }
        catch (Exception $e) {
            $this->lastError = 'SMTP Send Error (' . $to . '): ' . $this->mail->ErrorInfo;
            error_log($this->lastError);
            return false;
        }
    }

    public function addAttachment($path, $name = '', $encoding = PHPMailer::ENCODING_BASE64, $type = '')
    {
        $this->mail->addAttachment($path, $name, $encoding, $type);
    }

    public function addStringAttachment($string, $filename, $encoding = PHPMailer::ENCODING_BASE64, $type = '')
    {
        $this->mail->addStringAttachment($string, $filename, $encoding, $type);
    }

    public function clearAttachments()
    {
        $this->mail->clearAttachments();
    }
}
