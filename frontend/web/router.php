<?php

// Router for PHP's built-in development server. Existing static files must be
// served directly; all other requests are handled by Yii's front controller.
if (PHP_SAPI === 'cli-server') {
    $path = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
    $webroot = realpath(__DIR__);
    $file = realpath(__DIR__ . DIRECTORY_SEPARATOR . ltrim($path, '/\\'));
    $extension = strtolower(pathinfo($file ?: '', PATHINFO_EXTENSION));
    $types = [
        'css' => 'text/css; charset=UTF-8',
        'js' => 'application/javascript; charset=UTF-8',
        'txt' => 'text/plain; charset=UTF-8',
        'ico' => 'image/x-icon',
        'gif' => 'image/gif',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
    ];

    if ($path !== '/' && $file !== false && is_file($file)
        && strpos($file, $webroot . DIRECTORY_SEPARATOR) === 0
        && isset($types[$extension])
    ) {
        header('Content-Type: ' . $types[$extension]);
        header('Content-Length: ' . filesize($file));
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'HEAD') {
            readfile($file);
        }

        return true;
    }
}

require __DIR__ . '/index.php';
