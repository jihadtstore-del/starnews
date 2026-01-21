<?php

declare(strict_types=1);

class User
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->run('SELECT * FROM users WHERE email = :email LIMIT 1', [
            'email' => $email,
        ]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function allTechnicians(): array
    {
        return $this->db->run('SELECT * FROM users WHERE role = :role ORDER BY name ASC', [
            'role' => 'technician',
        ])->fetchAll();
    }
}
