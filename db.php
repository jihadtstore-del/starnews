<?php
$driver = getenv('DB_DRIVER') ?: 'mysql';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    if ($driver === 'sqlite') {
        $dbPath = getenv('DB_PATH') ?: __DIR__ . '/data/news.db';
        $dbDir = dirname($dbPath);
        if (!is_dir($dbDir)) {
            mkdir($dbDir, 0755, true);
        }
        $dsn = "sqlite:{$dbPath}";
        $pdo = new PDO($dsn, null, null, $options);
        $pdo->exec('CREATE TABLE IF NOT EXISTS news (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            body TEXT NOT NULL,
            author TEXT NOT NULL,
            category TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )');
    } else {
        $host = getenv('DB_HOST') ?: 'localhost';
        $dbname = getenv('DB_NAME') ?: 'starnews';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";
        $pdo = new PDO($dsn, $user, $pass, $options);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Database connection failed.';
    exit;
}
