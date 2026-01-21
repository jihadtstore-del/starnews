<?php

declare(strict_types=1);

class ReportController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['admin']);

        $filters = [
            'date' => $_GET['date'] ?? '',
            'technician_id' => (int) ($_GET['technician_id'] ?? 0),
        ];

        $issueModel = new DailyIssue($this->db);
        $issues = $issueModel->report($filters);

        $techModel = new Technician($this->db);
        $technicians = $techModel->all();

        $this->view('admin/reports/index', compact('issues', 'technicians', 'filters'));
    }

    public function printView(): void
    {
        Auth::requireRole(['admin']);

        $filters = [
            'date' => $_GET['date'] ?? '',
            'technician_id' => (int) ($_GET['technician_id'] ?? 0),
        ];

        $issueModel = new DailyIssue($this->db);
        $issues = $issueModel->report($filters);

        require __DIR__ . '/../views/admin/reports/print.php';
    }
}
