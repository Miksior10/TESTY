<?php declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Database;
use App\Models\User;
use App\Models\Project;
use App\Models\UserRole;

final class AdminController {
    public function __construct(private array $config) {}

    public function dashboard(): void {
        $this->requireAuth();
        
        $pdo = Database::getConnection($this->config['db']);
        $userModel = new User($pdo);
        $projectModel = new Project($pdo);
        $userRoleModel = new UserRole($pdo);
        
        $users = $userModel->getAll();
        $projects = $projectModel->getAll();
        $projectStats = $projectModel->getStats();
        $roleStats = $userRoleModel->getRoleStats();

        $title = 'Panel Administratora';
        require __DIR__ . '/../../Views/admin/dashboard.php';
    }

    public function users(): void {
        $this->requireAuth();
        
        $pdo = Database::getConnection($this->config['db']);
        $userModel = new User($pdo);
        $users = $userModel->getAll();

        $title = 'Zarządzanie Użytkownikami';
        require __DIR__ . '/../../Views/admin/users.php';
    }

    public function addUser(): void {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($name) || empty($email)) {
            $_SESSION['error'] = 'Wszystkie pola są wymagane';
            header('Location: /admin/users');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Nieprawidłowy format email';
            header('Location: /admin/users');
            exit;
        }

        $password = $_POST['password'] ?? '';
        $department = trim($_POST['department'] ?? '');
        $position = trim($_POST['position'] ?? '');
        $hireDate = $_POST['hire_date'] ?: null;
        $salary = $_POST['salary'] ? (float) $_POST['salary'] : null;
        $isActive = isset($_POST['is_active']);

        if (empty($password)) {
            $_SESSION['error'] = 'Hasło jest wymagane';
            header('Location: /admin/users');
            exit;
        }

        try {
            $pdo = Database::getConnection($this->config['db']);
            $userModel = new User($pdo);
            
            $userData = [
                'name' => $name,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'department' => $department ?: null,
                'position' => $position ?: null,
                'hire_date' => $hireDate,
                'salary' => $salary,
                'is_active' => $isActive
            ];
            
            $userModel->create($userData);
            $_SESSION['success'] = 'Użytkownik został dodany';
        } catch (\PDOException $e) {
            $_SESSION['error'] = 'Błąd podczas dodawania użytkownika: ' . $e->getMessage();
        }

        header('Location: /admin/users');
        exit;
    }

    public function deleteUser(): void {
        $this->requireAuth();
        
        $userId = (int) ($_GET['id'] ?? 0);
        
        if ($userId <= 0) {
            $_SESSION['error'] = 'Nieprawidłowy ID użytkownika';
            header('Location: /admin/users');
            exit;
        }

        try {
            $pdo = Database::getConnection($this->config['db']);
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$userId]);
            $_SESSION['success'] = 'Użytkownik został usunięty';
        } catch (\PDOException $e) {
            $_SESSION['error'] = 'Błąd podczas usuwania użytkownika';
        }

        header('Location: /admin/users');
        exit;
    }

    public function projects(): void {
        $this->requireAuth();
        
        $pdo = Database::getConnection($this->config['db']);
        $projectModel = new Project($pdo);
        $projects = $projectModel->getAll();

        $title = 'Zarządzanie Projektami';
        require __DIR__ . '/../../Views/admin/projects.php';
    }

    public function addProject(): void {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/projects');
            exit;
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'status' => $_POST['status'] ?? 'planning',
            'budget' => $_POST['budget'] ? (float) $_POST['budget'] : null,
            'start_date' => $_POST['start_date'] ?: null,
            'end_date' => $_POST['end_date'] ?: null,
            'created_by' => $_SESSION['admin_id']
        ];

        if (empty($data['name'])) {
            $_SESSION['error'] = 'Nazwa projektu jest wymagana';
            header('Location: /admin/projects');
            exit;
        }

        try {
            $pdo = Database::getConnection($this->config['db']);
            $projectModel = new Project($pdo);
            $projectModel->create($data);
            $_SESSION['success'] = 'Projekt został dodany';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Błąd podczas dodawania projektu';
        }

        header('Location: /admin/projects');
        exit;
    }

    public function deleteProject(): void {
        $this->requireAuth();
        
        $projectId = (int) ($_GET['id'] ?? 0);
        
        if ($projectId <= 0) {
            $_SESSION['error'] = 'Nieprawidłowy ID projektu';
            header('Location: /admin/projects');
            exit;
        }

        try {
            $pdo = Database::getConnection($this->config['db']);
            $projectModel = new Project($pdo);
            $projectModel->delete($projectId);
            $_SESSION['success'] = 'Projekt został usunięty';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Błąd podczas usuwania projektu';
        }

        header('Location: /admin/projects');
        exit;
    }

    public function userRoles(): void {
        $this->requireAuth();
        
        $pdo = Database::getConnection($this->config['db']);
        $userRoleModel = new UserRole($pdo);
        $users = $userRoleModel->getAllUsersWithRoles();

        $title = 'Zarządzanie Rolami Użytkowników';
        require __DIR__ . '/../../Views/admin/user-roles.php';
    }

    public function assignRole(): void {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/user-roles');
            exit;
        }

        $userId = (int) ($_POST['user_id'] ?? 0);
        $role = $_POST['role'] ?? '';
        $assignedBy = $_SESSION['admin_id'];

        if ($userId <= 0 || empty($role)) {
            $_SESSION['error'] = 'Nieprawidłowe dane';
            header('Location: /admin/user-roles');
            exit;
        }

        try {
            $pdo = Database::getConnection($this->config['db']);
            $userRoleModel = new UserRole($pdo);
            $userRoleModel->assignRole($userId, $role, $assignedBy);
            $_SESSION['success'] = 'Rola została przypisana';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Błąd podczas przypisywania roli';
        }

        header('Location: /admin/user-roles');
        exit;
    }

        public function workTime(): void {
            $this->requireAuth();
            
            $pdo = Database::getConnection($this->config['db']);
            $workTimeModel = new \App\Models\WorkTime($pdo);
            $userModel = new User($pdo);
            
            $users = $userModel->getAll();
            $selectedUserId = $_GET['user_id'] ?? null;
            $selectedMonth = $_GET['month'] ?? date('Y-m');
            
            $workTimeData = [];
            if ($selectedUserId) {
                $workTimeData = $workTimeModel->getByUser((int)$selectedUserId);
            }

            $title = 'Zarządzanie Czasem Pracy';
            require __DIR__ . '/../../Views/admin/work-time.php';
        }

        public function reports(): void {
            $this->requireAuth();
            
            $pdo = Database::getConnection($this->config['db']);
            $userModel = new User($pdo);
            $projectModel = new Project($pdo);
            $workTimeModel = new \App\Models\WorkTime($pdo);
            
            $userStats = $userModel->getStats();
            $projectStats = $projectModel->getStats();
            
            // Statystyki czasu pracy
            $workStats = $pdo->query('
                SELECT 
                    u.name,
                    u.department,
                    COUNT(wt.id) as days_worked,
                    SUM(wt.total_hours) as total_hours,
                    AVG(wt.total_hours) as avg_hours_per_day
                FROM users u 
                LEFT JOIN work_time wt ON u.id = wt.user_id 
                WHERE u.is_active = 1
                GROUP BY u.id, u.name, u.department
                ORDER BY total_hours DESC
            ')->fetchAll();

            $title = 'Raporty i Statystyki';
            require __DIR__ . '/../../Views/admin/reports.php';
        }

        public function settings(): void {
            $this->requireAuth();
            
            $title = 'Ustawienia Systemu';
            require __DIR__ . '/../../Views/admin/settings.php';
        }

        private function requireAuth(): void {
            session_start();
            if (!isset($_SESSION['admin_id'])) {
                header('Location: /login');
                exit;
            }
        }
}
