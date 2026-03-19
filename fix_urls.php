<?php
$viewsDir = __DIR__ . '/views';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
$count = 0;

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $originalContent = $content;

        // Match href="/something" but ignore href="<?php..." or href="#"
        $content = preg_replace_callback('/href="\/([^"]*)"/', function ($matches) {
            return 'href="<?php echo Url::to(\'/' . $matches[1] . '\'); ?>"';
        }, $content);

        // Match action="/something" but ignore action="<?php..."
        $content = preg_replace_callback('/action="\/([^"]*)"/', function ($matches) {
            return 'action="<?php echo Url::to(\'/' . $matches[1] . '\'); ?>"';
        }, $content);

        if ($content !== $originalContent) {
            file_put_contents($file->getPathname(), $content);
            echo "Updated URLs in: " . $file->getPathname() . "\n";
            $count++;
        }
    }
}

echo "Total files updated: $count\n";
