<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

require_admin();

$fullName = trim($_POST['full_name'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$role = trim($_POST['role'] ?? 'user');

if ($fullName === '' || $username === '' || $password === '') {
    $_SESSION['dashboard_message'] = 'Please fill in all user account fields.';
    $_SESSION['dashboard_message_type'] = 'error';
    header('Location: dashboard.php');
    exit();
}

if (!in_array($role, ['admin', 'user'], true)) {
    $_SESSION['dashboard_message'] = 'Invalid role selected.';
    $_SESSION['dashboard_message_type'] = 'error';
    header('Location: dashboard.php');
    exit();
}

if (!$pdo) {
    $_SESSION['dashboard_message'] = 'Database connection is required to create accounts.';
    $_SESSION['dashboard_message_type'] = 'error';
    header('Location: dashboard.php');
    exit();
}

$exists = $pdo->prepare('SELECT id FROM users WHERE username = ?');
$exists->execute([$username]);

if ($exists->fetch()) {
    $_SESSION['dashboard_message'] = 'Username already exists. Please choose another.';
    $_SESSION['dashboard_message_type'] = 'error';
    header('Location: dashboard.php');
    exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$insert = $pdo->prepare('INSERT INTO users (username, password_hash, role, full_name) VALUES (?, ?, ?, ?)');
$insert->execute([$username, $hash, $role, $fullName]);

$_SESSION['dashboard_message'] = 'User account created successfully.';
$_SESSION['dashboard_message_type'] = 'success';

header('Location: dashboard.php');
exit();
