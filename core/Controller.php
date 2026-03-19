<?php

class Controller
{
    public function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../views/$view.php";

        if (file_exists($viewFile)) {
            try {
                require $viewFile;
            }
            catch (\Throwable $e) {
                echo "Error rendering view: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine();
            }
        }
        else {
            die("View $view not found.");
        }
    }

    public function redirect($url)
    {
        $base = dirname($_SERVER['SCRIPT_NAME']);
        if ($base === '/' || $base === '\\') {
            $base = '';
        }
        header("Location: $base$url");
        exit;
    }
}
