<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login(): void
{
    if (empty($_SESSION['user'])) {
        header('Location: index.php');
        exit();
    }
}

function require_admin(): void
{
    require_login();

    if (($_SESSION['user']['role'] ?? '') !== 'admin') {
        header('Location: dashboard.php');
        exit();
    }
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}
