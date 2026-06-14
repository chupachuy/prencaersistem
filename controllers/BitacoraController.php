<?php
require_once __DIR__ . '/../models/Bitacora.php';

class BitacoraController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        if (!Auth::hasRole(Auth::ROLE_SUPERADMIN) && !Auth::hasRole(Auth::ROLE_ADMINISTRADOR)) {
            Session::set('error', 'No tienes permiso para acceder a la bitácora.');
            $this->redirect('/dashboard');
        }

        $bitacora = new Bitacora();
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = 50;
        $offset = ($page - 1) * $limit;

        $total = $bitacora->count();
        $registros = $bitacora->getAll($limit, $offset);
        $totalPages = ceil($total / $limit);

        $this->render('bitacora/index', [
            'registros' => $registros,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total
        ]);
    }
}
