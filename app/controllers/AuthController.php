<?php

declare(strict_types=1);

class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('dashboard');
        }

        $this->view('auth/login');
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $error = 'ইমেইল ও পাসওয়ার্ড দিন।';
            $this->view('auth/login', compact('error'));
            return;
        }

        $userModel = new User($this->db);
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $error = 'ভুল ইমেইল বা পাসওয়ার্ড।';
            $this->view('auth/login', compact('error'));
            return;
        }

        Auth::login($user);
        $this->redirect('dashboard');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('login');
    }
}
