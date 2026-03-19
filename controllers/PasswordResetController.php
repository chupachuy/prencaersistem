<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/PasswordReset.php';
require_once __DIR__ . '/../core/Mailer.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../helpers/Url.php';

class PasswordResetController extends Controller
{
    private $userModel;
    private $resetModel;
    private $mailer;

    public function __construct()
    {
        $this->userModel = new User();
        $this->resetModel = new PasswordReset();
        $this->mailer = new Mailer();
    }

    public function showForgotForm()
    {
        $this->render('auth/forgot-password');
    }

    public function sendResetLink()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/forgot-password');
        }

        $email = $_POST['email'] ?? '';

        if (!Validator::email($email)) {
            Session::set('error', 'Por favor, ingrese un correo válido.');
            $this->redirect('/forgot-password');
        }

        $user = $this->userModel->findByEmail($email);

        if ($user) {
            $token = $this->resetModel->createToken($email);
            $base = Url::base();
            
            // Build absolute URL
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
            $domain = $_SERVER['HTTP_HOST'];
            $link = "{$protocol}://{$domain}{$base}/reset-password?token={$token}";
            
            error_log("Password reset link generated: {$link}");

            $body = "<h2>Recuperación de Contraseña</h2>
                     <p>Hola {$user['nombre']},</p>
                     <p>Recibimos una solicitud para restablecer su contraseña.</p>
                     <p>Haga clic en el siguiente enlace para crear una nueva:</p>
                     <a href='{$link}' style='display: inline-block; background: #367d84; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px;'>Restablecer Contraseña</a>
                     <p style='margin-top: 20px;'>Si no solicitó este cambio, ignore este correo.</p>
                     <p style='font-size: 12px; color: #86868b;'>Enlace válido por 1 hora.</p>";

            // NOTE: This might fail if SMTP config in mail.php is not setup correctly by the user.
            $this->mailer->sendEmail($email, "Restablecimiento de Contraseña - PRENACER", $body);
        }

        // We always show success to prevent email enumeration
        Session::set('success', 'Si el correo existe en nuestro sistema, recibirá un enlace para restablecer su contraseña.');
        $this->redirect('/forgot-password');
    }

    public function showResetForm()
    {
        $token = $_GET['token'] ?? '';
        
        error_log("Token received: " . $token);
        
        if (empty($token)) {
            Session::set('error', 'Token no proporcionado.');
            $this->redirect('/forgot-password');
        }
        
        $resetData = $this->resetModel->verifyToken($token);
        error_log("Token verification result: " . ($resetData ? "valid" : "invalid"));

        if (!$resetData) {
            Session::set('error', 'El enlace es inválido o ha expirado. Por favor, solicita un nuevo enlace.');
            $this->redirect('/forgot-password');
        }

        $this->render('auth/reset-password', ['token' => $token]);
    }

    public function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/forgot-password');
        }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        $resetData = $this->resetModel->verifyToken($token);

        if (!$resetData) {
            Session::set('error', 'El enlace es inválido o ha expirado.');
            $this->redirect('/forgot-password');
        }

        if (strlen($password) < 8 || $password !== $password_confirm) {
            Session::set('error', 'Las contraseñas no coinciden o tienen menos de 8 caracteres.');
            $this->redirect('/reset-password?token=' . $token);
        }

        $user = $this->userModel->findByEmail($resetData['email']);

        if ($user) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $this->userModel->updatePassword($user['id'], $hashed);
            $this->resetModel->markAsUsed($resetData['id']);

            Session::set('success', 'Contraseña restablecida exitosamente. Ahora puede iniciar sesión.');
            $this->redirect('/login');
        }
        else {
            Session::set('error', 'Ha ocurrido un error inesperado.');
            $this->redirect('/forgot-password');
        }
    }
}
