<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Rol.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Validator.php';

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
        if (!Auth::check() || (!Auth::hasRole(Auth::ROLE_SUPERADMIN) && !Auth::hasRole(Auth::ROLE_ADMINISTRADOR) && !Auth::hasRole(Auth::ROLE_JEFE))) {
            $this->redirect('/dashboard');
        }

        $roles = $this->rolModel->getAll();
        $this->render('usuarios/index', ['roles' => $roles]);
    }

    public function search()
    {
        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }

        $search = trim($_GET['q'] ?? '');
        $rolId = ($_GET['rol_id'] ?? '') !== '' ? (int) $_GET['rol_id'] : null;
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 10;

        $data = $this->userModel->getAllPaginated($search, $rolId, $page, $perPage);
        $total = $this->userModel->countAll($search, $rolId);
        $totalPages = (int) ceil($total / $perPage);

        header('Content-Type: application/json');
        echo json_encode([
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages
        ]);
        exit;
    }

    public function create()
    {
        if (!Auth::check() || (!Auth::hasRole(Auth::ROLE_SUPERADMIN) && !Auth::hasRole(Auth::ROLE_ADMINISTRADOR) && !Auth::hasRole(Auth::ROLE_JEFE))) {
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

        if (!Auth::check() || (!Auth::hasRole(Auth::ROLE_SUPERADMIN) && !Auth::hasRole(Auth::ROLE_ADMINISTRADOR) && !Auth::hasRole(Auth::ROLE_JEFE))) {
            $this->redirect('/dashboard');
        }

        $data = [
            'nombre'      => trim($_POST['nombre'] ?? ''),
            'apellido'    => trim($_POST['apellido'] ?? ''),
            'email'       => trim($_POST['email'] ?? ''),
            'telefono'    => trim($_POST['telefono'] ?? ''),
            'password'    => $_POST['password'] ?? '',
            'rol_id'      => $_POST['rol_id'] ?? '',
            'especialidad'=> trim($_POST['especialidad'] ?? ''),
            'activo'      => isset($_POST['activo']) ? 1 : 0,
            'email_verified' => 1,
            'ruta_firma'  => null
        ];

        // BUG-05: Validar campos obligatorios y contraseña mínima
        if (empty($data['nombre']) || empty($data['apellido']) || empty($data['email'])) {
            Session::set('error', 'Nombre, apellido y correo electrónico son obligatorios.');
            $this->redirect('/usuarios/create');
            return;
        }

        if (strlen($data['password']) < 6) {
            Session::set('error', 'La contraseña debe tener al menos 6 caracteres.');
            $this->redirect('/usuarios/create');
            return;
        }

        if (!Validator::email($data['email'])) {
            Session::set('error', 'El correo electrónico no tiene un formato válido.');
            $this->redirect('/usuarios/create');
            return;
        }

        if (!empty($_FILES['firma']['tmp_name']) && $_FILES['firma']['error'] === UPLOAD_ERR_OK) {
            $rolId = (int) $data['rol_id'];
            if (in_array($rolId, [3, 4]) && $_FILES['firma']['size'] <= 2 * 1024 * 1024) {
                $tmpPath = $_FILES['firma']['tmp_name'];
                $mime = mime_content_type($tmpPath);
                $allowed = ['image/jpeg', 'image/png'];

                if (in_array($mime, $allowed)) {
                    $img = null;
                    switch ($mime) {
                        case 'image/jpeg': $img = imagecreatefromjpeg($tmpPath); break;
                        case 'image/png':  $img = imagecreatefrompng($tmpPath); break;
                    }

                    if ($img) {
                        $dir = __DIR__ . '/../storage/firmas/medicos/';
                        if (!is_dir($dir)) mkdir($dir, 0775, true);
                        $nombre = 'firma_medico_' . time() . '_' . bin2hex(random_bytes(4)) . '.png';
                        imagepng($img, $dir . $nombre);
                        imagedestroy($img);
                        $data['ruta_firma'] = '/storage/firmas/medicos/' . $nombre;
                    }
                }
            }
        }

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

        if (!Auth::check() || (!Auth::hasRole(Auth::ROLE_SUPERADMIN) && !Auth::hasRole(Auth::ROLE_ADMINISTRADOR) && !Auth::hasRole(Auth::ROLE_JEFE))) {
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
            // BUG-04: Solo leer el ID del POST para evitar manipulación vía URL
            $id = $_POST['id'] ?? null;
        }

        if (!$id) {
            Session::set('error', 'Usuario no especificado.');
            $this->redirect('/usuarios');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/usuarios');
        }

        if (!Auth::check() || (!Auth::hasRole(Auth::ROLE_SUPERADMIN) && !Auth::hasRole(Auth::ROLE_ADMINISTRADOR) && !Auth::hasRole(Auth::ROLE_JEFE))) {
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
            'telefono' => trim($_POST['telefono'] ?? ''),
            'rol_id' => $_POST['rol_id'] ?? '',
            'especialidad' => trim($_POST['especialidad'] ?? ''),
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];

        // Si se proporciona una nueva contraseña, actualizarla
        if (!empty($_POST['password'] ?? '')) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        $firmaDir = __DIR__ . '/../storage/firmas/medicos/';

        if (!empty($_POST['eliminar_firma'])) {
            if (!empty($usuario['ruta_firma'])) {
                $rutaAbsoluta = __DIR__ . '/..' . $usuario['ruta_firma'];
                if (file_exists($rutaAbsoluta)) unlink($rutaAbsoluta);
            }
            $data['ruta_firma'] = null;
        } elseif (!empty($_FILES['firma']['tmp_name']) && $_FILES['firma']['error'] === UPLOAD_ERR_OK) {
            $rolId = $usuario['rol_id'];
            if (in_array($rolId, [3, 4]) && $_FILES['firma']['size'] <= 2 * 1024 * 1024) {
                $tmpPath = $_FILES['firma']['tmp_name'];
                $mime = mime_content_type($tmpPath);
                $allowed = ['image/jpeg', 'image/png'];

                if (in_array($mime, $allowed)) {
                    $img = null;
                    switch ($mime) {
                        case 'image/jpeg': $img = imagecreatefromjpeg($tmpPath); break;
                        case 'image/png':  $img = imagecreatefrompng($tmpPath); break;
                    }

                    if ($img) {
                        if (!is_dir($firmaDir)) mkdir($firmaDir, 0775, true);
                        if (!empty($usuario['ruta_firma'])) {
                            $rutaAbsoluta = __DIR__ . '/..' . $usuario['ruta_firma'];
                            if (file_exists($rutaAbsoluta)) unlink($rutaAbsoluta);
                        }
                        $nombre = 'firma_medico_' . time() . '_' . bin2hex(random_bytes(4)) . '.png';
                        imagepng($img, $firmaDir . $nombre);
                        imagedestroy($img);
                        $data['ruta_firma'] = '/storage/firmas/medicos/' . $nombre;
                    }
                }
            }
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
