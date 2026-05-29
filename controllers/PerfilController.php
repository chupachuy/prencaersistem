<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';

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

    public function edit()
    {
        if (!Auth::check()) { $this->redirect('/login'); }

        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');

        $userModel = new User();
        $user = $userModel->findById(Auth::id());

        $this->render('perfil/edit', ['user' => $user]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('/perfil'); }
        if (!Auth::check()) { $this->redirect('/login'); }

        $uid = Auth::id();
        $userModel = new User();

        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'apellido' => $_POST['apellido'] ?? '',
            'email' => $_POST['email'] ?? '',
            'telefono' => $_POST['telefono'] ?? null,
        ];

        $accionFirma = $_POST['accion_firma'] ?? null;
        if ($accionFirma === 'limpiar') {
            $user = $userModel->findById($uid);
            if (!empty($user['ruta_firma'])) {
                $path = __DIR__ . '/..' . $user['ruta_firma'];
                if (file_exists($path)) unlink($path);
            }
            $data['ruta_firma'] = null;
        } elseif (!empty($_POST['firma_data'])) {
            $firmaData = $_POST['firma_data'];
            $firmaData = preg_replace('#^data:image/\w+;base64,#i', '', $firmaData);
            $img = base64_decode($firmaData);
            if ($img !== false) {
                $dir = __DIR__ . '/../storage/firmas/medicos/';
                if (!is_dir($dir)) mkdir($dir, 0775, true);
                $nombre = 'firma_medico_' . $uid . '.png';
                file_put_contents($dir . $nombre, $img);
                $data['ruta_firma'] = '/storage/firmas/medicos/' . $nombre;
            }
        }

        $data['id'] = $uid;

        try {
            $result = $userModel->update($data);

            if ($result === false) {
                Session::set('error', 'No se pudo actualizar el perfil. Verifica los datos e intenta de nuevo.');
                $this->redirect('/perfil/edit');
                return;
            }

            Session::set('success', 'Perfil actualizado correctamente.');
        } catch (\PDOException $e) {
            error_log('Error al actualizar perfil: ' . $e->getMessage());
            if (str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'email')) {
                Session::set('error', 'El correo electrónico ya está registrado por otro usuario.');
            } else {
                Session::set('error', 'Error al actualizar el perfil: ' . $e->getMessage());
            }
        }

        session_write_close();
        $this->redirect('/perfil/edit');
    }
}
