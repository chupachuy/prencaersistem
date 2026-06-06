<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/MedicoReferido.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Validator.php';

class MedicoReferidoController extends Controller
{
    private $medicoReferidoModel;

    public function __construct()
    {
        $this->medicoReferidoModel = new MedicoReferido();
    }

    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $roleId = Session::get('user_role_id');

        if ($roleId != Auth::ROLE_SUPERADMIN && $roleId != Auth::ROLE_ADMINISTRADOR && $roleId != Auth::ROLE_JEFE) {
            Session::set('error', 'No tiene permisos para acceder a esta seccion.');
            $this->redirect('/dashboard');
        }

        $medicos = $this->medicoReferidoModel->getAll();

        $this->render('medicos_referidos/index', ['medicos' => $medicos]);
    }

    public function create()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $roleId = Session::get('user_role_id');

        if ($roleId != Auth::ROLE_SUPERADMIN && $roleId != Auth::ROLE_ADMINISTRADOR && $roleId != Auth::ROLE_JEFE) {
            Session::set('error', 'No tiene permisos para acceder a esta seccion.');
            $this->redirect('/dashboard');
        }

        $this->render('medicos_referidos/create');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/medicos-referidos/create');
        }

        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $roleId = Session::get('user_role_id');

        if ($roleId != Auth::ROLE_SUPERADMIN && $roleId != Auth::ROLE_ADMINISTRADOR && $roleId != Auth::ROLE_JEFE) {
            $this->redirect('/dashboard');
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $especialidad = trim($_POST['especialidad'] ?? '');
        $institucion = trim($_POST['institucion'] ?? '');

        if (empty($nombre) || empty($apellido) || empty($email)) {
            Session::set('error', 'Nombre, apellido y email son obligatorios.');
            $this->redirect('/medicos-referidos/create');
        }

        if (!Validator::email($email)) {
            Session::set('error', 'El email ingresado no es valido.');
            $this->redirect('/medicos-referidos/create');
        }

        $userId = Auth::id();

        $id = $this->medicoReferidoModel->create([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'telefono' => $telefono ?: null,
            'especialidad' => $especialidad ?: null,
            'institucion' => $institucion ?: null,
            'created_by' => $userId,
            'updated_by' => $userId
        ]);

        if ($id) {
            Session::set('success', 'Medico referido registrado correctamente.');
            $this->redirect('/medicos-referidos');
        } else {
            Session::set('error', 'Error al registrar el medico referido.');
            $this->redirect('/medicos-referidos/create');
        }
    }

    public function edit()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $roleId = Session::get('user_role_id');

        if ($roleId != Auth::ROLE_SUPERADMIN && $roleId != Auth::ROLE_ADMINISTRADOR && $roleId != Auth::ROLE_JEFE) {
            Session::set('error', 'No tiene permisos para acceder a esta seccion.');
            $this->redirect('/dashboard');
        }

        $id = intval($_GET['id'] ?? 0);
        $medico = $this->medicoReferidoModel->getById($id);

        if (!$medico) {
            Session::set('error', 'Medico referido no encontrado.');
            $this->redirect('/medicos-referidos');
        }

        $this->render('medicos_referidos/edit', ['medico' => $medico]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/medicos-referidos');
        }

        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $roleId = Session::get('user_role_id');

        if ($roleId != Auth::ROLE_SUPERADMIN && $roleId != Auth::ROLE_ADMINISTRADOR && $roleId != Auth::ROLE_JEFE) {
            $this->redirect('/dashboard');
        }

        $id = intval($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $especialidad = trim($_POST['especialidad'] ?? '');
        $institucion = trim($_POST['institucion'] ?? '');

        if (empty($nombre) || empty($apellido) || empty($email)) {
            Session::set('error', 'Nombre, apellido y email son obligatorios.');
            $this->redirect('/medicos-referidos/edit?id=' . $id);
        }

        if (!Validator::email($email)) {
            Session::set('error', 'El email ingresado no es valido.');
            $this->redirect('/medicos-referidos/edit?id=' . $id);
        }

        $success = $this->medicoReferidoModel->update($id, [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'telefono' => $telefono ?: null,
            'especialidad' => $especialidad ?: null,
            'institucion' => $institucion ?: null
        ]);

        if ($success) {
            Session::set('success', 'Medico referido actualizado correctamente.');
            $this->redirect('/medicos-referidos');
        } else {
            Session::set('error', 'Error al actualizar el medico referido.');
            $this->redirect('/medicos-referidos/edit?id=' . $id);
        }
    }

    public function importar()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $roleId = Session::get('user_role_id');

        if ($roleId != Auth::ROLE_SUPERADMIN && $roleId != Auth::ROLE_ADMINISTRADOR && $roleId != Auth::ROLE_JEFE) {
            Session::set('error', 'No tiene permisos para acceder a esta seccion.');
            $this->redirect('/dashboard');
        }

        $this->render('medicos_referidos/importar');
    }

    public function procesarImportacion()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/medicos-referidos/importar');
        }

        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $roleId = Session::get('user_role_id');

        if ($roleId != Auth::ROLE_SUPERADMIN && $roleId != Auth::ROLE_ADMINISTRADOR && $roleId != Auth::ROLE_JEFE) {
            $this->redirect('/dashboard');
        }

        if (!isset($_FILES['archivo_csv']) || $_FILES['archivo_csv']['error'] !== UPLOAD_ERR_OK) {
            Session::set('error', 'Error al subir el archivo CSV.');
            $this->redirect('/medicos-referidos/importar');
        }

        $archivo = $_FILES['archivo_csv']['tmp_name'];
        $extension = strtolower(pathinfo($_FILES['archivo_csv']['name'], PATHINFO_EXTENSION));

        if ($extension !== 'csv') {
            Session::set('error', 'El archivo debe tener extension .csv');
            $this->redirect('/medicos-referidos/importar');
        }

        $handle = fopen($archivo, 'r');
        if (!$handle) {
            Session::set('error', 'No se pudo leer el archivo.');
            $this->redirect('/medicos-referidos/importar');
        }

        $userId = Auth::id();
        $importados = 0;
        $errores = [];
        $linea = 0;

        while (($fila = fgetcsv($handle, 0, ';')) !== false) {
            $linea++;

            if (count($fila) < 5) {
                $errores[] = "Linea {$linea}: formato incorrecto (columnas insuficientes)";
                continue;
            }

            if ($linea === 1) {
                $primerValor = strtolower(trim($fila[0]));
                if ($primerValor === 'nombre') continue;
            }

            $nombreCompleto = trim($fila[0]);
            $email = trim($fila[1]);
            $telefono = trim($fila[2]);
            $especialidad = trim($fila[3]);
            $institucion = trim($fila[4]);

            if (empty($nombreCompleto) || empty($email)) {
                $errores[] = "Linea {$linea}: nombre y email son obligatorios";
                continue;
            }

            if (!Validator::email($email)) {
                $errores[] = "Linea {$linea}: email '{$email}' no es valido";
                continue;
            }

            $partes = explode(' ', $nombreCompleto, 2);
            $nombre = $partes[0];
            $apellido = $partes[1] ?? '';

            $id = $this->medicoReferidoModel->create([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'email' => $email,
                'telefono' => $telefono ?: null,
                'especialidad' => $especialidad ?: null,
                'institucion' => $institucion ?: null,
                'created_by' => $userId,
                'updated_by' => $userId
            ]);

            if ($id) {
                $importados++;
            } else {
                $errores[] = "Linea {$linea}: error al insertar '{$nombreCompleto}'";
            }
        }

        fclose($handle);

        $mensaje = "Se importaron {$importados} medicos correctamente.";
        if (!empty($errores)) {
            $mensaje .= " " . count($errores) . " errores.";
            Session::set('import_errores', $errores);
        }

        Session::set('success', $mensaje);
        $this->redirect('/medicos-referidos');
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/medicos-referidos');
        }

        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $roleId = Session::get('user_role_id');

        if ($roleId != Auth::ROLE_SUPERADMIN && $roleId != Auth::ROLE_ADMINISTRADOR && $roleId != Auth::ROLE_JEFE) {
            Session::set('error', 'No tiene permisos para realizar esta accion.');
            $this->redirect('/dashboard');
        }

        $id = intval($_POST['id'] ?? 0);

        if ($this->medicoReferidoModel->eliminar($id)) {
            Session::set('success', 'Medico referido eliminado correctamente.');
        } else {
            Session::set('error', 'Error al eliminar el medico referido.');
        }

        $this->redirect('/medicos-referidos');
    }
}
