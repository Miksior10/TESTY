<?php declare(strict_types=1);

namespace App\Controllers\User;

use App\Models\Database;
use App\Models\User;
use App\Models\WorkTime;

final class UserController {
    public function __construct(private array $config) {}

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../../Views/user/login.php';
            return;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = 'Wszystkie pola są wymagane';
            require __DIR__ . '/../../Views/user/login.php';
            return;
        }

        $pdo = Database::getConnection($this->config['db']);
        $userModel = new User($pdo);
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $error = 'Nieprawidłowy email lub hasło';
            require __DIR__ . '/../../Views/user/login.php';
            return;
        }

        if (!$user['is_active']) {
            $error = 'Konto zostało dezaktywowane';
            require __DIR__ . '/../../Views/user/login.php';
            return;
        }

        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        
        header('Location: /user/dashboard');
        exit;
    }

    public function dashboard(): void {
        $this->requireAuth();
        
        $pdo = Database::getConnection($this->config['db']);
        $workTimeModel = new WorkTime($pdo);
        
        $todayWork = $workTimeModel->getToday($_SESSION['user_id']);
        $recentWork = $workTimeModel->getByUser($_SESSION['user_id'], null);
        $monthlyStats = $workTimeModel->getMonthlyStats($_SESSION['user_id'], date('Y-m'));

        $title = 'Panel Użytkownika';
        require __DIR__ . '/../../Views/user/dashboard.php';
    }

    public function clockIn(): void {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /user/dashboard');
            exit;
        }

        $pdo = Database::getConnection($this->config['db']);
        $workTimeModel = new WorkTime($pdo);
        
        $currentTime = date('H:i:s');
        $workTimeModel->clockIn($_SESSION['user_id'], $currentTime);
        
        $_SESSION['success'] = 'Rozpoczęto pracę o ' . $currentTime;
        header('Location: /user/dashboard');
        exit;
    }

    public function clockOut(): void {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /user/dashboard');
            exit;
        }

        $breakDuration = (int) ($_POST['break_duration'] ?? 0);
        
        $pdo = Database::getConnection($this->config['db']);
        $workTimeModel = new WorkTime($pdo);
        
        $currentTime = date('H:i:s');
        $workTimeModel->clockOut($_SESSION['user_id'], $currentTime, $breakDuration);
        
        $_SESSION['success'] = 'Zakończono pracę o ' . $currentTime;
        header('Location: /user/dashboard');
        exit;
    }

    public function profile(): void {
        $this->requireAuth();
        
        $pdo = Database::getConnection($this->config['db']);
        $userModel = new User($pdo);
        $user = $userModel->getById($_SESSION['user_id']);

        $title = 'Mój Profil';
        require __DIR__ . '/../../Views/user/profile.php';
    }

    public function updateProfile(): void {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /user/profile');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';

        if (empty($name) || empty($email)) {
            $_SESSION['error'] = 'Imię i email są wymagane';
            header('Location: /user/profile');
            exit;
        }

        try {
            $pdo = Database::getConnection($this->config['db']);
            $userModel = new User($pdo);
            
            $updateData = ['name' => $name, 'email' => $email];
            
            if (!empty($newPassword)) {
                if (empty($currentPassword)) {
                    $_SESSION['error'] = 'Aby zmienić hasło, podaj obecne hasło';
                    header('Location: /user/profile');
                    exit;
                }
                
                $user = $userModel->getById($_SESSION['user_id']);
                if (!password_verify($currentPassword, $user['password_hash'])) {
                    $_SESSION['error'] = 'Nieprawidłowe obecne hasło';
                    header('Location: /user/profile');
                    exit;
                }
                
                $updateData['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
            }
            
            $userModel->update($_SESSION['user_id'], $updateData);
            $_SESSION['success'] = 'Profil został zaktualizowany';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Błąd podczas aktualizacji profilu';
        }

        header('Location: /user/profile');
        exit;
    }

    public function logout(): void {
        session_start();
        session_destroy();
        header('Location: /user/login');
        exit;
    }

    private function requireAuth(): void {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /user/login');
            exit;
        }
    }
}