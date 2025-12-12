<?php declare(strict_types=1);
require __DIR__ . '/../includes/bootstrap.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../src/';
    if (str_starts_with($class, $prefix)) {
        $relative = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
        if (is_file($file)) require $file;
    }
});

use App\Controllers\Home\HomeController;
use App\Controllers\Auth\LoginController;
use App\Controllers\Admin\AdminController;
use App\Controllers\User\UserController;

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
switch ($uri) {
    case '/':
    case '/index.php':
        (new HomeController($config))->index();
        break;
    case '/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new LoginController($config))->login();
        } else {
            (new LoginController($config))->show();
        }
        break;
    case '/logout':
        (new LoginController($config))->logout();
        break;
    case '/admin':
        (new AdminController($config))->dashboard();
        break;
    case '/admin/users':
        (new AdminController($config))->users();
        break;
    case '/admin/users/add':
        (new AdminController($config))->addUser();
        break;
    case '/admin/users/delete':
        (new AdminController($config))->deleteUser();
        break;
    case '/admin/projects':
        (new AdminController($config))->projects();
        break;
    case '/admin/projects/add':
        (new AdminController($config))->addProject();
        break;
    case '/admin/projects/delete':
        (new AdminController($config))->deleteProject();
        break;
    case '/admin/user-roles':
        (new AdminController($config))->userRoles();
        break;
    case '/admin/user-roles/assign':
        (new AdminController($config))->assignRole();
        break;
    case '/admin/work-time':
        (new AdminController($config))->workTime();
        break;
    case '/admin/reports':
        (new AdminController($config))->reports();
        break;
    case '/admin/settings':
        (new AdminController($config))->settings();
        break;
    case '/user/login':
        (new UserController($config))->login();
        break;
    case '/user/dashboard':
        (new UserController($config))->dashboard();
        break;
    case '/user/clock-in':
        (new UserController($config))->clockIn();
        break;
    case '/user/clock-out':
        (new UserController($config))->clockOut();
        break;
    case '/user/profile':
        (new UserController($config))->profile();
        break;
    case '/user/update-profile':
        (new UserController($config))->updateProfile();
        break;
    case '/user/logout':
        (new UserController($config))->logout();
        break;
    case '/test/egzamin/upload':
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'error' => 'Method Not Allowed']);
            break;
        }

        header('Content-Type: application/json');

        // Bazowy katalog zapisu (stały): uploads/exam-inf03
        $targetBase = __DIR__ . '/uploads/exam-inf03';
        if (!is_dir($targetBase) && !mkdir($targetBase, 0777, true) && !is_dir($targetBase)) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => 'Nie można utworzyć katalogu bazowego']);
            break;
        }

        // Opcjonalny podkatalog w exam-inf03
        $subfolder = trim((string)($_POST['folder_name'] ?? ''));
        $subfolder = preg_replace('/[^a-zA-Z0-9-_\\/]+/', '-', $subfolder);
        $subfolder = str_replace('..', '', $subfolder);
        $subfolder = trim($subfolder, "/\\");

        $targetDir = $subfolder ? $targetBase . '/' . $subfolder : $targetBase;
        if (!is_dir($targetDir) && !mkdir($targetDir, 0777, true) && !is_dir($targetDir)) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => 'Nie można utworzyć katalogu docelowego']);
            break;
        }

        if (!isset($_FILES['files'])) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Brak plików do zapisu']);
            break;
        }

        $saved = [];
        foreach ($_FILES['files']['tmp_name'] as $i => $tmp) {
            if (!is_uploaded_file($tmp)) continue;

            $rel = $_POST['relative_paths'][$i] ?? $_FILES['files']['name'][$i];
            $rel = str_replace("\0", '', (string) $rel);
            $rel = str_replace('..', '', $rel);
            $rel = ltrim($rel, "/\\");
            if ($rel === '') $rel = $_FILES['files']['name'][$i];

            $destPath = $targetDir . '/' . $rel;
            $destDir = dirname($destPath);
            if (!is_dir($destDir) && !mkdir($destDir, 0777, true) && !is_dir($destDir)) {
                continue;
            }

            if (move_uploaded_file($tmp, $destPath)) {
                $publicPrefix = 'uploads/exam-inf03';
                $saved[] = $publicPrefix . ($subfolder ? '/' . $subfolder : '') . '/' . $rel;
            }
        }

        echo json_encode([
            'ok' => true,
            'dir' => 'uploads/exam-inf03' . ($subfolder ? '/' . $subfolder : ''),
            'saved' => $saved,
        ]);
        break;
    case '/test/egzamin/menu':
        // Prosty routing do strony egzaminu osadzonej w katalogu tests
        require __DIR__ . '/../tests/egzamin/manu.php';
        break;
    default:
        http_response_code(404);
        echo '404 Not Found';
}
