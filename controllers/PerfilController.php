<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Auth.php';

class PerfilController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $userModel = new User();
        $user = $userModel->findById(Auth::id());

        $this->render('perfil/index', ['user' => $user]);
    }
}
