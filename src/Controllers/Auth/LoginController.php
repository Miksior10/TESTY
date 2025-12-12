<?php declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Models\Database;
use App\Models\Admin;

final class LoginController {
    public function __construct(private array $config) {}

    public function show(): void {
        if ($this->isLoggedIn()) {
            header('Location: /admin');
            exit;
        }
        require __DIR__ . '/../../Views/auth/login.php';
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $error = 'Wszystkie pola są wymagane';
            require __DIR__ . '/../../Views/auth/login.php';
            return;
        }

        $pdo = Database::getConnection($this->config['db']);
        $adminModel = new Admin($pdo);
        $admin = $adminModel->findByUsername($username);

        if (!$admin) {
            $error = 'Nieprawidłowy login lub hasło';
            require __DIR__ . '/../../Views/auth/login.php';
            return;
        }

        if (!$adminModel->verifyPassword($password, $admin['password_hash'])) {
            $error = 'Nieprawidłowy login lub hasło';
            require __DIR__ . '/../../Views/auth/login.php';
            return;
        }

        session_start();
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        
        $adminModel->updateLastLogin($admin['id']);
        
        // Debug: sprawdź czy sesja jest ustawiona
        error_log("Admin logged in: " . $admin['username'] . " ID: " . $admin['id']);
        
        header('Location: /admin');
        exit;
    }

    public function logout(): void {
        session_start();
        session_destroy();
        header('Location: /login');
        exit;
    }

    private function isLoggedIn(): bool {
        session_start();
        return isset($_SESSION['admin_id']);
    }
}
