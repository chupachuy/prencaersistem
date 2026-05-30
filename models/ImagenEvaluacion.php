<?php
require_once __DIR__ . '/../core/Database.php';

class ImagenEvaluacion
{
    private $db;

    private static $maxAncho = 1920;
    private static $maxTamano = 5 * 1024 * 1024;
    private static $mimePermitidos = ['image/jpeg', 'image/png'];
    private static $maxImagenes = 10;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvaluacion($trimestre, $evaluacionId)
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM imagenes_evaluacion WHERE trimestre = ? AND evaluacion_id = ? ORDER BY orden ASC, id ASC'
        );
        $stmt->execute([$trimestre, $evaluacionId]);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM imagenes_evaluacion WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO imagenes_evaluacion (trimestre, evaluacion_id, ruta_imagen, nombre_original, mime_type, tamanio_bytes, orden)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['trimestre'],
            $data['evaluacion_id'],
            $data['ruta_imagen'],
            $data['nombre_original'] ?? null,
            $data['mime_type'] ?? null,
            $data['tamanio_bytes'] ?? null,
            $data['orden'] ?? 0
        ]);
        return $this->db->lastInsertId();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM imagenes_evaluacion WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function reorder($id, $orden)
    {
        $stmt = $this->db->prepare('UPDATE imagenes_evaluacion SET orden = ? WHERE id = ?');
        return $stmt->execute([$orden, $id]);
    }

    public static function procesarUpload($trimestre, $evaluacionId)
    {
        $storageBase = __DIR__ . '/../storage/evaluaciones/';
        $dir = $storageBase . $trimestre . '/' . $evaluacionId . '/';

        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        // Evitar PHP Notice si la variable $_FILES['imagenes'] no existe o está mal estructurada
        if (empty($_FILES['imagenes']) || empty($_FILES['imagenes']['tmp_name']) || empty($_FILES['imagenes']['tmp_name'][0])) {
            return;
        }

        $model = new self();
        $orden = count($model->getByEvaluacion($trimestre, $evaluacionId));
        $count = 0;

        foreach ($_FILES['imagenes']['tmp_name'] as $i => $tmpPath) {
            if ($_FILES['imagenes']['error'][$i] !== UPLOAD_ERR_OK) continue;
            if ($count >= self::$maxImagenes) break;

            $tamano = $_FILES['imagenes']['size'][$i];
            if ($tamano > self::$maxTamano) continue;

            $mime = mime_content_type($tmpPath);
            if (!in_array($mime, self::$mimePermitidos)) continue;

            $ext = ($mime === 'image/png') ? 'png' : 'jpg';
            $nombreArchivo = 'img_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

            // Soporte robusto en caso de que la extensión GD no esté instalada
            if (!extension_loaded('gd') || !function_exists('imagecreatefromjpeg')) {
                if (move_uploaded_file($tmpPath, $dir . $nombreArchivo)) {
                    $model->create([
                        'trimestre' => $trimestre,
                        'evaluacion_id' => $evaluacionId,
                        'ruta_imagen' => '/storage/evaluaciones/' . $trimestre . '/' . $evaluacionId . '/' . $nombreArchivo,
                        'nombre_original' => $_FILES['imagenes']['name'][$i] ?? null,
                        'mime_type' => $mime,
                        'tamanio_bytes' => $tamano,
                        'orden' => $orden + $count
                    ]);
                    $count++;
                }
                continue;
            }

            if ($ext === 'png') {
                $src = imagecreatefrompng($tmpPath);
            } else {
                $src = imagecreatefromjpeg($tmpPath);
            }

            if (!$src) continue;

            $w = imagesx($src);
            $h = imagesy($src);

            if ($w > self::$maxAncho) {
                $nuevoH = (int) ($h * (self::$maxAncho / $w));
                $nuevoW = self::$maxAncho;
            } else {
                $nuevoW = $w;
                $nuevoH = $h;
            }

            $destino = imagecreatetruecolor($nuevoW, $nuevoH);

            // Preservar la transparencia alfa para imágenes PNG
            if ($ext === 'png') {
                imagealphablending($destino, false);
                imagesavealpha($destino, true);
                $transparent = imagecolorallocatealpha($destino, 0, 0, 0, 127);
                imagefilledrectangle($destino, 0, 0, $nuevoW, $nuevoH, $transparent);
            }

            imagecopyresampled($destino, $src, 0, 0, 0, 0, $nuevoW, $nuevoH, $w, $h);

            if ($ext === 'png') {
                imagepng($destino, $dir . $nombreArchivo, 6);
            } else {
                imagejpeg($destino, $dir . $nombreArchivo, 85);
            }

            imagedestroy($src);
            imagedestroy($destino);

            $rutaRelativa = '/storage/evaluaciones/' . $trimestre . '/' . $evaluacionId . '/' . $nombreArchivo;

            $model->create([
                'trimestre' => $trimestre,
                'evaluacion_id' => $evaluacionId,
                'ruta_imagen' => $rutaRelativa,
                'nombre_original' => $_FILES['imagenes']['name'][$i] ?? null,
                'mime_type' => $mime,
                'tamanio_bytes' => $tamano,
                'orden' => $orden + $count
            ]);

            $count++;
        }
    }

    public static function eliminarMarcadas($imagenesEliminar)
    {
        if (empty($imagenesEliminar)) return;
        $ids = array_map('intval', explode(',', $imagenesEliminar));
        $model = new self();
        foreach ($ids as $imgId) {
            if ($imgId <= 0) continue;
            $img = $model->getById($imgId);
            if ($img) {
                $path = __DIR__ . '/..' . $img['ruta_imagen'];
                if (file_exists($path)) unlink($path);
                $model->delete($imgId);
            }
        }
    }
}
