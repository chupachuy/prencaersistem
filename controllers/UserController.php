<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Rol.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';

class UserController extends Controller
{
    private $userModel;
    private $rolModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->rolModel = new Rol();
    }

    public function index()
    {
        if (!Auth::check() || (!Auth::hasRole(Auth::ROLE_SUPERADMIN) && !Auth::hasRole(Auth::ROLE_ADMINISTRADOR))) {
            $this->redirect('/dashboard');
        }

        $usuarios = $this->userModel->getAll();
        $this->render('usuarios/index', ['medicos' => $usuarios]);
    }

    public function create()
    {
        if (!Auth::check() || (!Auth::hasRole(Auth::ROLE_SUPERADMIN) && !Auth::hasRole(Auth::ROLE_ADMINISTRADOR))) {
            $this->redirect('/dashboard');
        }

        $roles = $this->rolModel->getAll();
        $this->render('usuarios/create', ['roles' => $roles]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/usuarios');
        }

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'apellido' => trim($_POST['apellido'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'rol_id' => $_POST['rol_id'] ?? '',
            'especialidad' => trim($_POST['especialidad'] ?? ''),
            'activo' => isset($_POST['activo']) ? 1 : 0,
            'email_verified' => 1
        ];

        // Validations skipped for brevity, but could use Validator class

        try {
            if ($this->userModel->create($data)) {
                Session::set('success', 'Usuario creado exitosamente.');
                $this->redirect('/usuarios');
            } else {
                Session::set('error', 'Error al crear el usuario.');
                $this->redirect('/usuarios/create');
            }
        } catch (\PDOException $e) {
            Session::set('error', 'El correo ya está registrado u ocurrió un error: ' . $e->getMessage());
            $this->redirect('/usuarios/create');
        }
    }

    public function edit($id = null)
    {
        if ($id === null) {
            // Get ID from query string if not in route parameter
            $id = $_GET['id'] ?? null;
        }

        if (!$id) {
            Session::set('error', 'Usuario no especificado.');
            $this->redirect('/usuarios');
        }

        if (!Auth::check() || (!Auth::hasRole(Auth::ROLE_SUPERADMIN) && !Auth::hasRole(Auth::ROLE_ADMINISTRADOR))) {
            $this->redirect('/dashboard');
        }

        $usuario = $this->userModel->findById($id);
        if (!$usuario) {
            Session::set('error', 'Usuario no encontrado.');
            $this->redirect('/usuarios');
        }

        $roles = $this->rolModel->getAll();
        $this->render('usuarios/edit', ['usuario' => $usuario, 'roles' => $roles]);
    }

    public function update($id = null)
    {
        if ($id === null) {
            // Get ID from query string if not in route parameter
            $id = $_POST['id'] ?? $_GET['id'] ?? null;
        }

        if (!$id) {
            Session::set('error', 'Usuario no especificado.');
            $this->redirect('/usuarios');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/usuarios');
        }

        if (!Auth::check() || (!Auth::hasRole(Auth::ROLE_SUPERADMIN) && !Auth::hasRole(Auth::ROLE_ADMINISTRADOR))) {
            $this->redirect('/dashboard');
        }

        $usuario = $this->userModel->findById($id);
        if (!$usuario) {
            Session::set('error', 'Usuario no encontrado.');
            $this->redirect('/usuarios');
        }

        $data = [
            'id' => $id,
            'nombre' => trim($_POST['nombre'] ?? ''),
            'apellido' => trim($_POST['apellido'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'rol_id' => $_POST['rol_id'] ?? '',
            'especialidad' => trim($_POST['especialidad'] ?? ''),
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];

        // Si se proporciona una nueva contraseña, actualizarla
        if (!empty($_POST['password'] ?? '')) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        try {
            if ($this->userModel->update($data)) {
                Session::set('success', 'Usuario actualizado exitosamente.');
                $this->redirect('/usuarios');
            } else {
                Session::set('error', 'Error al actualizar el usuario.');
                $this->redirect('/usuarios/edit?id=' . $id);
            }
        } catch (\PDOException $e) {
            Session::set('error', 'Error al actualizar: ' . $e->getMessage());
            $this->redirect('/usuarios/edit?id=' . $id);
        }
    }
}
