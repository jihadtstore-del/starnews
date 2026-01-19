<?php
$settings = [
    'db_host' => getenv('DB_HOST') ?: '127.0.0.1',
    'db_name' => getenv('DB_NAME') ?: 'equipment_tracker',
    'db_user' => getenv('DB_USER') ?: 'root',
    'db_pass' => getenv('DB_PASS') ?: '',
];

$pdo = null;
$dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $settings['db_host'], $settings['db_name']);

try {
    $pdo = new PDO($dsn, $settings['db_user'], $settings['db_pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $exception) {
    $pdo = null;
}
