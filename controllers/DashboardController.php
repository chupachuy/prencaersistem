<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../helpers/Auth.php';

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $roleId = Session::get('user_role_id');

        switch ($roleId) {
            case Auth::ROLE_SUPERADMIN:
                $this->render('dashboard/superadmin');
                break;
            case Auth::ROLE_ADMINISTRADOR:
                $this->render('dashboard/admin');
                break;
            case Auth::ROLE_JEFE:
                $this->render('dashboard/jefe');
                break;
            case Auth::ROLE_MEDICO:
                $this->render('dashboard/medico');
                break;
            default:
                Session::set('error', 'Rol no autorizado.');
                $this->redirect('/login');
        }
    }
}
