<?php

$root = dirname(__DIR__, 2);
$host = getenv('TEST_DB_HOST') ?: '127.0.0.1';
$port = getenv('TEST_DB_PORT') ?: '3306';
$database = getenv('TEST_DB_NAME') ?: 'yii2_website_test';
$username = getenv('TEST_DB_USER') ?: 'root';
$password = getenv('TEST_DB_PASSWORD') !== false ? getenv('TEST_DB_PASSWORD') : '';
$keep = in_array('--keep', $argv, true);

if (!preg_match('/^[a-zA-Z0-9_]+$/', $database)) {
    fwrite(STDERR, "Unsafe test database name.\n");
    exit(2);
}

$pdo = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
$quotedDatabase = '`' . $database . '`';
$pdo->exec("DROP DATABASE IF EXISTS {$quotedDatabase}");
$pdo->exec("CREATE DATABASE {$quotedDatabase} CHARACTER SET latin1 COLLATE latin1_swedish_ci");

putenv("DB_HOST={$host}");
putenv("DB_PORT={$port}");
putenv("DB_NAME={$database}");
putenv("DB_USER={$username}");
putenv("DB_PASSWORD={$password}");
putenv('YII_ENV=test');
putenv('YII_DEBUG=0');

$command = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($root . '/yii')
    . ' migrate --interactive=0';
passthru($command, $exitCode);
if ($exitCode !== 0) {
    $pdo->exec("DROP DATABASE IF EXISTS {$quotedDatabase}");
    exit($exitCode);
}

$checks = [
    'tables' => (int) $pdo->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=" . $pdo->quote($database))->fetchColumn() >= 16,
    'charset' => (int) $pdo->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=" . $pdo->quote($database) . " AND table_collation NOT LIKE 'utf8mb4%'")->fetchColumn() === 0,
    'foreign keys' => (int) $pdo->query("SELECT COUNT(*) FROM information_schema.referential_constraints WHERE constraint_schema=" . $pdo->quote($database))->fetchColumn() >= 12,
    'RBAC roles' => (int) $pdo->query("SELECT COUNT(*) FROM {$quotedDatabase}.auth_item WHERE type=1 AND name IN ('editor','admin','superAdmin')")->fetchColumn() === 3,
];
foreach ($checks as $name => $passed) {
    if (!$passed) {
        fwrite(STDERR, "Installation check failed: {$name}\n");
        $pdo->exec("DROP DATABASE IF EXISTS {$quotedDatabase}");
        exit(1);
    }
}

if (!$keep) {
    $pdo->exec("DROP DATABASE IF EXISTS {$quotedDatabase}");
}
fwrite(STDOUT, "Fresh installation test passed.\n");
