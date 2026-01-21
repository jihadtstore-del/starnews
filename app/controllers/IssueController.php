<?php

declare(strict_types=1);

class IssueController extends Controller
{
    public function createForm(): void
    {
        Auth::requireRole(['technician']);

        $user = Auth::user();
        $techModel = new Technician($this->db);
        $technician = $techModel->findByUserId($user['id']);

        if (!$technician) {
            $error = 'আপনার Technician প্রোফাইল তৈরি করা হয়নি। Admin এর সাথে যোগাযোগ করুন।';
        }

        $equipmentModel = new Equipment($this->db);
        $equipment = $equipmentModel->all();

        $this->view('technician/issues/create', compact('equipment', 'technician', 'error'));
    }

    public function store(): void
    {
        Auth::requireRole(['technician']);

        $data = [
            'issue_date' => $_POST['issue_date'] ?? '',
            'technician_id' => (int) ($_POST['technician_id'] ?? 0),
            'department' => trim($_POST['department'] ?? ''),
            'equipment_id' => (int) ($_POST['equipment_id'] ?? 0),
            'quantity' => (int) ($_POST['quantity'] ?? 0),
            'issue_purpose' => trim($_POST['issue_purpose'] ?? ''),
            'return_date' => $_POST['return_date'] ?: null,
            'status' => 'pending',
        ];

        if ($data['technician_id'] <= 0 || $data['issue_date'] === '' || $data['department'] === '' || $data['equipment_id'] === 0 || $data['quantity'] <= 0) {
            $error = 'সব তথ্য ঠিকভাবে পূরণ করুন।';
            $equipmentModel = new Equipment($this->db);
            $equipment = $equipmentModel->all();
            $this->view('technician/issues/create', compact('equipment', 'error'));
            return;
        }

        $issueModel = new DailyIssue($this->db);
        $issueModel->create($data);

        $this->redirect('issue-history');
    }

    public function history(): void
    {
        Auth::requireRole(['technician']);

        $user = Auth::user();
        $techModel = new Technician($this->db);
        $technician = $techModel->findByUserId($user['id']);

        $issues = [];
        if ($technician) {
            $issueModel = new DailyIssue($this->db);
            $issues = $issueModel->byTechnician((int) $technician['id']);
        }

        $this->view('technician/issues/history', compact('issues', 'technician'));
    }
}
