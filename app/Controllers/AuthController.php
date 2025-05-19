<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\DB;

class AuthController
{
    protected User $user;

    public function __construct()
    {
        $this->user = new User(DB::get());
    }

    public function showLogin()
    {
        require __DIR__ . '/../../app/Views/auth/login.php';
    }

    public function login()
    {
        $user = $this->user->authenticate(
            $_POST['email'],
            $_POST['password'],
            $_POST['role']
        );
        if ($user) {
            session_start();
            $_SESSION['user'] = $user;
            header("Location: /dashboard/" . $user['role']);
            exit;
        }
        echo "Login failed.";
    }

    public function showRegister()
    {
        require __DIR__ . '/../../app/Views/auth/register.php';
    }

    public function register()
    {
        $this->user->create(
            $_POST['name'],
            $_POST['email'],
            $_POST['password'],
            $_POST['role']
        );
        header("Location: /");
        exit;
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: /");
        exit;
    }
}