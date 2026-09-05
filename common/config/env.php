<?php

$configuredEnvFile = trim((string) getenv('APP_ENV_FILE'));
$envFile = $configuredEnvFile !== '' ? $configuredEnvFile : dirname(__DIR__, 2) . '/.env';
if (!is_file($envFile) || !is_readable($envFile)) {
    return;
}

foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
        continue;
    }
    [$name, $value] = array_map('trim', explode('=', $line, 2));
    if (!preg_match('/^[A-Z_][A-Z0-9_]*$/', $name) || getenv($name) !== false) {
        continue;
    }
    if (
        strlen($value) >= 2 && (($value[0] === '"' && substr($value, -1) === '"')
        || ($value[0] === "'" && substr($value, -1) === "'"))
    ) {
        $value = substr($value, 1, -1);
    }
    putenv($name . '=' . $value);
    $_ENV[$name] = $value;
    $_SERVER[$name] = $value;
}
