<?php

declare(strict_types=1);

class EquipmentController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['admin']);

        $model = new Equipment($this->db);
        $equipments = $model->all();

        $this->view('admin/equipments/index', compact('equipments'));
    }

    public function store(): void
    {
        Auth::requireRole(['admin']);

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'category' => $_POST['category'] ?? 'Others',
            'stock' => (int) ($_POST['stock'] ?? 0),
            'status' => $_POST['status'] ?? 'active',
        ];

        if ($data['name'] === '' || $data['stock'] <= 0) {
            $error = 'নাম ও স্টক বাধ্যতামূলক।';
            $model = new Equipment($this->db);
            $equipments = $model->all();
            $this->view('admin/equipments/index', compact('equipments', 'error'));
            return;
        }

        $model = new Equipment($this->db);
        $model->create($data);

        $this->redirect('equipments');
    }

    public function delete(): void
    {
        Auth::requireRole(['admin']);

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $model = new Equipment($this->db);
            $model->delete($id);
        }

        $this->redirect('equipments');
    }
}
