<?php

declare(strict_types=1);

class IssueReturnLog
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function create(int $issueId, string $returnDate, int $updatedBy): void
    {
        $this->db->run(
            'INSERT INTO issue_return_log (daily_issue_id, return_date, updated_by)
             VALUES (:daily_issue_id, :return_date, :updated_by)',
            [
                'daily_issue_id' => $issueId,
                'return_date' => $returnDate,
                'updated_by' => $updatedBy,
            ]
        );
    }
}
