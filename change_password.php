<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

require_login();

$currentPassword = trim($_POST['current_password'] ?? '');
$newPassword = trim($_POST['new_password'] ?? '');
$confirmPassword = trim($_POST['confirm_password'] ?? '');

if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
    $_SESSION['dashboard_message'] = 'Please fill in all password fields.';
    $_SESSION['dashboard_message_type'] = 'error';
    header('Location: dashboard.php');
    exit();
}

if ($newPassword !== $confirmPassword) {
    $_SESSION['dashboard_message'] = 'New password and confirmation do not match.';
    $_SESSION['dashboard_message_type'] = 'error';
    header('Location: dashboard.php');
    exit();
}

$user = current_user();

if (!$pdo) {
    $_SESSION['dashboard_message'] = 'Database connection is required to change passwords.';
    $_SESSION['dashboard_message_type'] = 'error';
    header('Location: dashboard.php');
    exit();
}

$statement = $pdo->prepare('SELECT password_hash FROM users WHERE id = ?');
$statement->execute([$user['id']]);
$record = $statement->fetch();

if (!$record || !password_verify($currentPassword, $record['password_hash'])) {
    $_SESSION['dashboard_message'] = 'Current password is incorrect.';
    $_SESSION['dashboard_message_type'] = 'error';
    header('Location: dashboard.php');
    exit();
}

$newHash = password_hash($newPassword, PASSWORD_DEFAULT);
$update = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
$update->execute([$newHash, $user['id']]);

$_SESSION['dashboard_message'] = 'Password updated successfully.';
$_SESSION['dashboard_message_type'] = 'success';

header('Location: dashboard.php');
exit();
