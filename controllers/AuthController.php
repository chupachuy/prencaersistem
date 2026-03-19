<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Validator.php';

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function showLogin()
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
        $this->render('auth/login');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!Validator::email($email) || !Validator::required($password)) {
            Session::set('error', 'Por favor, ingrese un correo y contraseña válidos.');
            $this->redirect('/login');
        }

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['activo'] == 0) {
                Session::set('error', 'Esta cuenta está desactivada. Contacte al administrador.');
                $this->redirect('/login');
            }

            Auth::login($user);
            $this->userModel->updateLoginTime($user['id']);

            Session::set('success', 'Sesión iniciada exitosamente.');
            $this->redirect('/dashboard');
        }
        else {
            Session::set('error', 'Credenciales incorrectas.');
            $this->redirect('/login');
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::set('success', 'Ha cerrado sesión correctamente.');
        $this->redirect('/login');
    }
}
