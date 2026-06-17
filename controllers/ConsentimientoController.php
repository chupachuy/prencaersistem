<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/ConsentimientoAsignado.php';
require_once __DIR__ . '/../models/CatalogoConsentimiento.php';
require_once __DIR__ . '/../models/RegistroFirma.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/GeoLocator.php';

use Dompdf\Dompdf;

class ConsentimientoController extends Controller
{
    private $asignacionModel;
    private $mailer;
    private $catalogoModel;
    private $firmaModel;
    private $pacienteModel;
    private $userModel;

    public function __construct()
    {
        $this->asignacionModel = new ConsentimientoAsignado();
        $this->mailer = new Mailer();
        $this->catalogoModel = new CatalogoConsentimiento();
        $this->firmaModel = new RegistroFirma();
        $this->pacienteModel = new Paciente();
        $this->userModel = new User();
    }

    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $this->render('consentimientos/index');
    }

    public function search()
    {
        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }

        $search = trim($_GET['q'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 10;

        $roleId = Session::get('user_role_id');
        $medicoId = null;

        if (!in_array($roleId, [Auth::ROLE_SUPERADMIN, Auth::ROLE_JEFE, Auth::ROLE_ADMINISTRADOR])) {
            $medicoId = Auth::id();
        }

        $data = $this->asignacionModel->getAllPaginated($search, $page, $perPage, $medicoId);
        $total = $this->asignacionModel->countAll($search, $medicoId);
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
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $pacienteId = $_GET['paciente_id'] ?? null;
        $pacientes = $this->pacienteModel->getAll();
        $medicos = $this->userModel->getMedicos();
        $documentos = $this->catalogoModel->getAll();

        $this->render('consentimientos/create', [
            'pacientes' => $pacientes,
            'medicos' => $medicos,
            'documentos' => $documentos,
            'paciente_id' => $pacienteId
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/consentimientos');
        }

        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $pacienteId = (int) ($_POST['paciente_id'] ?? 0);
        $medicoId = (int) ($_POST['medico_id'] ?? 0);
        $documentoId = (int) ($_POST['documento_id'] ?? 0);

        if (!$pacienteId || !$medicoId || !$documentoId) {
            Session::set('error', 'Debe seleccionar paciente, médico y tipo de documento.');
            $this->redirect('/consentimientos/create');
            return;
        }

        $datosDinamicos = null;
        if (!empty($_POST['datos_dinamicos'])) {
            $datosDinamicos = $_POST['datos_dinamicos'];
        }

        $asignacionId = $this->asignacionModel->create($pacienteId, $medicoId, $documentoId, $datosDinamicos, Auth::id());

        if ($asignacionId) {
            Session::set('success', 'Consentimiento generado correctamente.');
            $this->redirect('/consentimientos/firmar?id=' . $asignacionId);
        } else {
            Session::set('error', 'Error al generar el consentimiento.');
            $this->redirect('/consentimientos/create');
        }
    }

    public function firmar()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/consentimientos');
        }

        $consentimiento = $this->asignacionModel->getById($id);
        if (!$consentimiento) {
            Session::set('error', 'Consentimiento no encontrado.');
            $this->redirect('/consentimientos');
        }

        $firmas = $this->firmaModel->getByAsignacion($id);

        $firmaMedico = null;
        if (!empty($consentimiento['medico_id'])) {
            $ruta = $this->userModel->getFirma($consentimiento['medico_id']);
            if ($ruta && file_exists(__DIR__ . '/..' . $ruta)) {
                $firmaMedico = Url::base() . $ruta;
            }
        }

        $this->render('consentimientos/firmar', [
            'consentimiento' => $consentimiento,
            'firmas' => $firmas,
            'firmaMedico' => $firmaMedico
        ]);
    }

    public function storeFirma()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/consentimientos');
        }

        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $asignacionId = (int) ($_POST['asignacion_id'] ?? 0);
        if (!$asignacionId) {
            Session::set('error', 'ID de asignación inválido.');
            $this->redirect('/consentimientos');
        }

        $consentimiento = $this->asignacionModel->getById($asignacionId);
        if (!$consentimiento) {
            Session::set('error', 'Consentimiento no encontrado.');
            $this->redirect('/consentimientos');
        }

        $ipOrigen = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $geo = GeoLocator::locate($ipOrigen);

        $storageDir = __DIR__ . '/../storage/firmas/';

        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0775, true);
        }

        $firmasGuardadas = 0;
        $firmantes = $_POST['firmantes'] ?? [];
        $roles = $_POST['roles'] ?? [];
        $nombres = $_POST['nombres'] ?? [];
        $acciones = $_POST['acciones'] ?? [];
        $firmasData = $_POST['firmas_data'] ?? [];

        foreach ($firmantes as $index => $rol) {
            if (empty($firmasData[$index])) {
                continue;
            }

            $nombreFirmante = $nombres[$index] ?? '';
            if (empty($nombreFirmante)) {
                continue;
            }

            $base64Data = $firmasData[$index];
            $base64Data = preg_replace('#^data:image/\w+;base64,#i', '', $base64Data);
            $imageData = base64_decode($base64Data);

            if ($imageData === false) {
                continue;
            }

            // BUG-06 FIX: Verificar que el contenido decodificado sea realmente una imagen válida
            // Esto previene que se suban archivos PHP u otros archivos maliciosos codificados en base64
            $imgInfo = @getimagesizefromstring($imageData);
            if ($imgInfo === false) {
                error_log("storeFirma: se intentó subir un archivo que no es imagen válida (asignacion_id={$asignacionId}, index={$index})");
                continue;
            }

            $nombreArchivo = 'firma_' . $asignacionId . '_' . time() . '_' . $index . '.png';
            $rutaCompleta = $storageDir . $nombreArchivo;

            if (file_put_contents($rutaCompleta, $imageData)) {
                $tipoAccion = $acciones[$index] ?? 'Aceptacion';
                $this->firmaModel->create(
                    $asignacionId,
                    $rol,
                    $nombreFirmante,
                    '/storage/firmas/' . $nombreArchivo,
                    $ipOrigen,
                    $tipoAccion,
                    $geo['pais'] ?? null,
                    $geo['region'] ?? null,
                    $geo['ciudad'] ?? null,
                    $geo['latitud'] ?? null,
                    $geo['longitud'] ?? null,
                    $geo['proveedor'] ?? null
                );
                $firmasGuardadas++;
            }
        }

        if ($firmasGuardadas > 0) {
            $documento = $this->catalogoModel->getById($consentimiento['documento_id']);
            $totalRequerido = 1; // paciente
            if ($documento['requiere_firma_medico']) {
                $totalRequerido++;
            }
            $totalRequerido += $documento['cantidad_testigos'];

            $firmasActuales = $this->firmaModel->getFirmasCompletadas($asignacionId);

            if (($acciones[0] ?? 'Aceptacion') === 'Denegacion') {
                $this->asignacionModel->updateEstado($asignacionId, 'Revocado');
            } elseif ($firmasActuales >= $totalRequerido) {
                $this->asignacionModel->updateEstado($asignacionId, 'Completado');
            } else {
                $this->asignacionModel->updateEstado($asignacionId, 'Parcialmente Firmado');
            }

            Session::set('success', "$firmasGuardadas firma(s) guardada(s) correctamente.");
        } else {
            Session::set('error', 'No se pudo guardar ninguna firma.');
        }

        $this->redirect('/consentimientos/firmar?id=' . $asignacionId);
    }

    public function show()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/consentimientos');
        }

        $consentimiento = $this->asignacionModel->getById($id);
        if (!$consentimiento) {
            Session::set('error', 'Consentimiento no encontrado.');
            $this->redirect('/consentimientos');
        }

        $firmas = $this->firmaModel->getByAsignacion($id);

        $this->render('consentimientos/show', [
            'consentimiento' => $consentimiento,
            'firmas' => $firmas
        ]);
    }

    public function print()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/consentimientos');
        }

        $consentimiento = $this->asignacionModel->getById($id);
        if (!$consentimiento) {
            Session::set('error', 'Consentimiento no encontrado.');
            $this->redirect('/consentimientos');
        }

        $firmas = $this->firmaModel->getByAsignacion($id);

        ob_start();
        $this->render('consentimientos/print', [
            'consentimiento' => $consentimiento,
            'firmas' => $firmas
        ]);
        $html = ob_get_clean();

        $dompdf = new Dompdf();
        $dompdf->setBasePath(__DIR__ . '/../');
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();
        $pdfDir = __DIR__ . '/../storage/pdfs/';

        if (!is_dir($pdfDir)) {
            mkdir($pdfDir, 0775, true);
        }

        $pdfNombre = 'consentimiento_' . $id . '.pdf';
        $pdfRuta = $pdfDir . $pdfNombre;
        file_put_contents($pdfRuta, $output);

        $this->asignacionModel->updateRutaPDF($id, '/storage/pdfs/' . $pdfNombre);

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $pdfNombre . '"');
        echo $output;
        exit;
    }

    public function catalogo()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $documentos = $this->catalogoModel->getAll();
        $this->render('consentimientos/catalogo/index', ['documentos' => $documentos]);
    }

    public function catalogoCreate()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $this->render('consentimientos/catalogo/create');
    }

    public function catalogoStore()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/consentimientos/catalogo');
        }

        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $nombre = $_POST['nombre_documento'] ?? '';
        $version = $_POST['version'] ?? null;
        $contenido = $_POST['contenido'] ?? null;
        $requiereFirma = isset($_POST['requiere_firma_medico']) ? 1 : 0;
        $cantidadTestigos = (int) ($_POST['cantidad_testigos'] ?? 0);

        if (empty($nombre)) {
            Session::set('error', 'El nombre del documento es obligatorio.');
            $this->redirect('/consentimientos/catalogo/create');
        }

        if ($this->catalogoModel->create($nombre, $version, $contenido, $requiereFirma, $cantidadTestigos)) {
            Session::set('success', 'Documento creado correctamente.');
        } else {
            Session::set('error', 'Error al crear el documento.');
        }

        $this->redirect('/consentimientos/catalogo');
    }

    public function catalogoEdit()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/consentimientos/catalogo');
        }

        $documento = $this->catalogoModel->getById($id);
        if (!$documento) {
            Session::set('error', 'Documento no encontrado.');
            $this->redirect('/consentimientos/catalogo');
        }

        $this->render('consentimientos/catalogo/edit', ['documento' => $documento]);
    }

    public function catalogoUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/consentimientos/catalogo');
        }

        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if (!$id) {
            $this->redirect('/consentimientos/catalogo');
        }

        $nombre = $_POST['nombre_documento'] ?? '';
        $version = $_POST['version'] ?? null;
        $contenido = $_POST['contenido'] ?? null;
        $requiereFirma = isset($_POST['requiere_firma_medico']) ? 1 : 0;
        $cantidadTestigos = (int) ($_POST['cantidad_testigos'] ?? 0);

        if (empty($nombre)) {
            Session::set('error', 'El nombre del documento es obligatorio.');
            $this->redirect('/consentimientos/catalogo/edit?id=' . $id);
        }

        if ($this->catalogoModel->update($id, $nombre, $version, $contenido, $requiereFirma, $cantidadTestigos)) {
            Session::set('success', 'Documento actualizado correctamente.');
        } else {
            Session::set('error', 'Error al actualizar el documento.');
        }

        $this->redirect('/consentimientos/catalogo');
    }

    public function catalogoDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/consentimientos/catalogo');
        }

        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if (!$id) {
            $this->redirect('/consentimientos/catalogo');
        }

        if ($this->catalogoModel->delete($id)) {
            Session::set('success', 'Documento eliminado correctamente.');
        } else {
            Session::set('error', 'Error al eliminar el documento.');
        }

        $this->redirect('/consentimientos/catalogo');
    }

    public function pdf()
    {
        if (!Auth::check()) { $this->redirect("/login"); }
        $id = $_GET["id"] ?? null;
        if (!$id) { $this->redirect("/consentimientos"); }
        $consentimiento = $this->asignacionModel->getById($id);
        if (!$consentimiento) { Session::set("error", "No encontrado."); $this->redirect("/consentimientos"); }
        $firmas = $this->firmaModel->getByAsignacion($id);
        $this->streamPdf("consentimientos/imprimir", [
            "consentimiento" => $consentimiento,
            "firmas" => $firmas
        ], "consentimientos" . "_" . $id . ".pdf");
    }
}