<?php

declare(strict_types=1);

class DailyIssue
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function create(array $data): void
    {
        $this->db->run(
            'INSERT INTO daily_issues (issue_date, technician_id, department, equipment_id, quantity, issue_purpose, return_date, status)
             VALUES (:issue_date, :technician_id, :department, :equipment_id, :quantity, :issue_purpose, :return_date, :status)',
            [
                'issue_date' => $data['issue_date'],
                'technician_id' => $data['technician_id'],
                'department' => $data['department'],
                'equipment_id' => $data['equipment_id'],
                'quantity' => $data['quantity'],
                'issue_purpose' => $data['issue_purpose'],
                'return_date' => $data['return_date'],
                'status' => $data['status'],
            ]
        );
    }

    public function byTechnician(int $technicianId): array
    {
        return $this->db->run(
            'SELECT di.*, e.name AS equipment_name
             FROM daily_issues di
             JOIN equipments e ON di.equipment_id = e.id
             WHERE di.technician_id = :technician_id
             ORDER BY di.issue_date DESC',
            ['technician_id' => $technicianId]
        )->fetchAll();
    }

    public function all(): array
    {
        return $this->db->run(
            'SELECT di.*, t.name AS technician_name, e.name AS equipment_name
             FROM daily_issues di
             JOIN technicians t ON di.technician_id = t.id
             JOIN equipments e ON di.equipment_id = e.id
             ORDER BY di.issue_date DESC'
        )->fetchAll();
    }

    public function updateStatus(int $id, string $status): void
    {
        $this->db->run(
            'UPDATE daily_issues SET status = :status WHERE id = :id',
            ['status' => $status, 'id' => $id]
        );
    }

    public function updateReturn(int $id, string $returnDate): void
    {
        $this->db->run(
            'UPDATE daily_issues SET return_date = :return_date, status = :status WHERE id = :id',
            ['return_date' => $returnDate, 'status' => 'returned', 'id' => $id]
        );
    }

    public function report(array $filters): array
    {
        $sql = 'SELECT di.*, t.name AS technician_name, e.name AS equipment_name
                FROM daily_issues di
                JOIN technicians t ON di.technician_id = t.id
                JOIN equipments e ON di.equipment_id = e.id
                WHERE 1=1';

        $params = [];

        if (!empty($filters['date'])) {
            $sql .= ' AND di.issue_date = :issue_date';
            $params['issue_date'] = $filters['date'];
        }

        if (!empty($filters['technician_id'])) {
            $sql .= ' AND di.technician_id = :technician_id';
            $params['technician_id'] = $filters['technician_id'];
        }

        $sql .= ' ORDER BY di.issue_date DESC';

        return $this->db->run($sql, $params)->fetchAll();
    }
}
