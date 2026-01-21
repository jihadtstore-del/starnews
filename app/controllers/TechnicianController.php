<?php

declare(strict_types=1);

class TechnicianController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['admin']);

        $model = new Technician($this->db);
        $technicians = $model->all();

        $this->view('admin/technicians/index', compact('technicians'));
    }

    public function store(): void
    {
        Auth::requireRole(['admin']);

        $data = [
            'user_id' => ($_POST['user_id'] ?? '') !== '' ? (int) $_POST['user_id'] : null,
            'name' => trim($_POST['name'] ?? ''),
            'department' => trim($_POST['department'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'status' => $_POST['status'] ?? 'active',
        ];

        if ($data['name'] === '' || $data['department'] === '') {
            $error = 'নাম ও বিভাগ বাধ্যতামূলক।';
            $model = new Technician($this->db);
            $technicians = $model->all();
            $this->view('admin/technicians/index', compact('technicians', 'error'));
            return;
        }

        $model = new Technician($this->db);
        $model->create($data);

        $this->redirect('technicians');
    }

    public function delete(): void
    {
        Auth::requireRole(['admin']);

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $model = new Technician($this->db);
            $model->delete($id);
        }

        $this->redirect('technicians');
    }
}
