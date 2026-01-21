<?php

declare(strict_types=1);

class AdminController extends Controller
{
    public function dashboard(): void
    {
        Auth::requireRole(['admin']);

        $stats = [
            'technicians' => (int) $this->db->run('SELECT COUNT(*) AS total FROM technicians')->fetch()['total'],
            'equipments' => (int) $this->db->run('SELECT COUNT(*) AS total FROM equipments')->fetch()['total'],
            'pending' => (int) $this->db->run("SELECT COUNT(*) AS total FROM daily_issues WHERE status = 'pending'")->fetch()['total'],
            'returned' => (int) $this->db->run("SELECT COUNT(*) AS total FROM daily_issues WHERE status = 'returned'")->fetch()['total'],
        ];

        $this->view('admin/dashboard', compact('stats'));
    }
}
