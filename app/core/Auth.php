<?php

declare(strict_types=1);

class Auth
{
    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function login(array $user): void
    {
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'role' => $user['role'],
        ];
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
        session_destroy();
    }

    public static function requireRole(array $roles): void
    {
        if (!self::check() || !in_array($_SESSION['user']['role'], $roles, true)) {
            header('Location: index.php?route=login');
            exit;
        }
    }
}
