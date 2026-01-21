<?php

declare(strict_types=1);

class Technician
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        return $this->db->run('SELECT * FROM technicians ORDER BY name ASC')->fetchAll();
    }

    public function findByUserId(int $userId): ?array
    {
        $stmt = $this->db->run('SELECT * FROM technicians WHERE user_id = :user_id LIMIT 1', [
            'user_id' => $userId,
        ]);

        $tech = $stmt->fetch();

        return $tech ?: null;
    }

    public function create(array $data): void
    {
        $this->db->run(
            'INSERT INTO technicians (user_id, name, department, phone, status) VALUES (:user_id, :name, :department, :phone, :status)',
            [
                'user_id' => $data['user_id'],
                'name' => $data['name'],
                'department' => $data['department'],
                'phone' => $data['phone'],
                'status' => $data['status'],
            ]
        );
    }

    public function update(int $id, array $data): void
    {
        $this->db->run(
            'UPDATE technicians SET name = :name, department = :department, phone = :phone, status = :status WHERE id = :id',
            [
                'id' => $id,
                'name' => $data['name'],
                'department' => $data['department'],
                'phone' => $data['phone'],
                'status' => $data['status'],
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->db->run('DELETE FROM technicians WHERE id = :id', ['id' => $id]);
    }
}
