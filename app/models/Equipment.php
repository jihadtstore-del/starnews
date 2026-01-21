<?php

declare(strict_types=1);

class Equipment
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        return $this->db->run('SELECT * FROM equipments ORDER BY name ASC')->fetchAll();
    }

    public function create(array $data): void
    {
        $this->db->run(
            'INSERT INTO equipments (name, category, stock, status) VALUES (:name, :category, :stock, :status)',
            [
                'name' => $data['name'],
                'category' => $data['category'],
                'stock' => $data['stock'],
                'status' => $data['status'],
            ]
        );
    }

    public function update(int $id, array $data): void
    {
        $this->db->run(
            'UPDATE equipments SET name = :name, category = :category, stock = :stock, status = :status WHERE id = :id',
            [
                'id' => $id,
                'name' => $data['name'],
                'category' => $data['category'],
                'stock' => $data['stock'],
                'status' => $data['status'],
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->db->run('DELETE FROM equipments WHERE id = :id', ['id' => $id]);
    }
}
