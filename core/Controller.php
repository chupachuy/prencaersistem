<?php

use Mpdf\Mpdf;

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

    private function getHeaderBase64Html()
    {
        $logoPath = __DIR__ . '/../logos/logoblanco.png';
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $imgTag = '<img src="data:image/png;base64,' . $logoData . '" height="40" style="display:block;border:none;">';
            return '<div style="background-color:#1B4F5A; padding:10px 30px; width:100%;">'
                . $imgTag
                . '</div>';
        }
        return '';
    }

    private function getFooterHtml()
    {
        return '<div style="border-top:3px solid #81BABB; background-color:#1B4F5A; color:#ffffff; text-align:center; padding:10px 0 8px 0; font-family:Helvetica,Arial,sans-serif; font-size:7.5pt; line-height:1.5; width:100%;">'
            . '&#x1F4CD; Circuito Comercial Sat&eacute;lite N&uacute;m 20, San &Aacute;ngel Inn, Samara Satelite Torre B Consultorios 629-630<br>'
            . '&#x2709;&#xFE0F; contacto@prenacer.mx &nbsp;|&nbsp; &#x1F4DE; 55 2726 8794 / 55 8376 3086'
            . '</div>';
    }

    public function streamPdf($view, $data = [], $filename = 'reporte.pdf')
    {
        ob_start();
        $this->render($view, $data);
        $html = ob_get_clean();

        try {
            $mpdf = new Mpdf([
                'tempDir' => __DIR__ . '/../storage/tmp',
                'margin_top' => 30,
                'margin_header' => 5,
                'margin_bottom' => 16,
                'margin_footer' => 5,
            ]);
            $headerImg = $this->getHeaderBase64Html();
            if ($headerImg) {
                $mpdf->SetHTMLHeader($headerImg);
            }
            $mpdf->SetHTMLFooter($this->getFooterHtml());
            $mpdf->WriteHTML($html);
            $mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
            exit;
        } catch (\Throwable $e) {
            error_log("mPDF error: " . $e->getMessage());
            Session::set('error', 'Error al generar el PDF.');
            $this->redirect('/');
        }
    }

    protected function generatePdfAttachment($view, $data, $outputPath)
    {
        ob_start();
        $this->render($view, $data);
        $html = ob_get_clean();

        try {
            $mpdf = new Mpdf([
                'tempDir' => __DIR__ . '/../storage/tmp',
                'margin_top' => 30,
                'margin_header' => 5,
                'margin_bottom' => 16,
                'margin_footer' => 5,
            ]);
            $headerImg = $this->getHeaderBase64Html();
            if ($headerImg) {
                $mpdf->SetHTMLHeader($headerImg);
            }
            $mpdf->SetHTMLFooter($this->getFooterHtml());
            $mpdf->WriteHTML($html);
            $mpdf->Output($outputPath, \Mpdf\Output\Destination::FILE);
            return file_exists($outputPath) && filesize($outputPath) > 0;
        } catch (\Throwable $e) {
            error_log("mPDF error: " . $e->getMessage());
            return false;
        }
    }

    public function redirect($url)
    {
        $base = dirname($_SERVER['SCRIPT_NAME']);
        if ($base === '/' || $base === '\\') {
            $base = '';
        }
        $schema = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        header("Location: $schema://$host$base$url");
        exit;
    }
}
