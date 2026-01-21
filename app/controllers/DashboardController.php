<?php

declare(strict_types=1);

class DashboardController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) {
            $this->redirect('login');
        }

        $user = Auth::user();
        if ($user['role'] === 'admin') {
            $this->redirect('admin-dashboard');
        }

        $techModel = new Technician($this->db);
        $technician = $techModel->findByUserId($user['id']);

        $issues = [];
        if ($technician) {
            $issueModel = new DailyIssue($this->db);
            $issues = $issueModel->byTechnician((int) $technician['id']);
        }

        $this->view('technician/dashboard', compact('issues', 'technician'));
    }
}
