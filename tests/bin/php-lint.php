<?php

$root = dirname(__DIR__, 2);
$directories = ['common', 'frontend', 'console', 'tests'];
$failed = [];
foreach ($directories as $directory) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/' . $directory));
    foreach ($iterator as $file) {
        if (!$file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }
        $command = escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($file->getPathname());
        exec($command, $output, $code);
        if ($code !== 0) {
            $failed[] = $file->getPathname();
        }
        $output = [];
    }
}
if ($failed) {
    fwrite(STDERR, "PHP syntax errors:\n" . implode("\n", $failed) . "\n");
    exit(1);
}
fwrite(STDOUT, "PHP syntax check passed.\n");
