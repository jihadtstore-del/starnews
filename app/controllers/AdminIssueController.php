<?php

declare(strict_types=1);

class AdminIssueController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['admin']);

        $issueModel = new DailyIssue($this->db);
        $issues = $issueModel->all();

        $this->view('admin/issues/index', compact('issues'));
    }

    public function approve(): void
    {
        Auth::requireRole(['admin']);

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $issueModel = new DailyIssue($this->db);
            $issueModel->updateStatus($id, 'approved');
        }

        $this->redirect('admin-issues');
    }

    public function reject(): void
    {
        Auth::requireRole(['admin']);

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $issueModel = new DailyIssue($this->db);
            $issueModel->updateStatus($id, 'rejected');
        }

        $this->redirect('admin-issues');
    }

    public function markReturned(): void
    {
        Auth::requireRole(['admin']);

        $id = (int) ($_POST['id'] ?? 0);
        $returnDate = $_POST['return_date'] ?? '';

        if ($id > 0 && $returnDate !== '') {
            $issueModel = new DailyIssue($this->db);
            $issueModel->updateReturn($id, $returnDate);

            $log = new IssueReturnLog($this->db);
            $log->create($id, $returnDate, Auth::user()['id']);
        }

        $this->redirect('admin-issues');
    }
}
