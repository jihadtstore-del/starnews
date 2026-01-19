<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    $_SESSION['login_error'] = 'Please enter both username and password.';
    header('Location: index.php');
    exit();
}

$user = null;

if ($pdo) {
    $statement = $pdo->prepare('SELECT id, username, password_hash, role, full_name FROM users WHERE username = ?');
    $statement->execute([$username]);
    $record = $statement->fetch();

    if ($record && password_verify($password, $record['password_hash'])) {
        $user = [
            'id' => $record['id'],
            'username' => $record['username'],
            'role' => $record['role'],
            'full_name' => $record['full_name'],
        ];
    }
} else {
    $demoUsers = [
        'admin' => ['password' => 'admin123', 'role' => 'admin', 'full_name' => 'Admin Manager'],
        'user' => ['password' => 'user123', 'role' => 'user', 'full_name' => 'Viewer Account'],
    ];

    if (isset($demoUsers[$username]) && $demoUsers[$username]['password'] === $password) {
        $user = [
            'id' => 0,
            'username' => $username,
            'role' => $demoUsers[$username]['role'],
            'full_name' => $demoUsers[$username]['full_name'],
        ];
    }
}

if (!$user) {
    $_SESSION['login_error'] = 'Invalid credentials. Try the demo accounts or connect a database.';
    header('Location: index.php');
    exit();
}

$_SESSION['user'] = $user;

header('Location: dashboard.php');
exit();
