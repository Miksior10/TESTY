<?php declare(strict_types=1);
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/css/style.css" />
    <style>
        .admin-nav {
            background: #1e293b;
            color: white;
            padding: 16px 0;
            margin-bottom: 24px;
        }
        .admin-nav .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-nav a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
        }
        .admin-nav a:hover {
            color: #94a3b8;
        }
        .btn {
            background: #2563eb;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            font-size: 14px;
        }
        .btn:hover {
            background: #1d4ed8;
        }
        .btn-success {
            background: #16a34a;
        }
        .btn-success:hover {
            background: #15803d;
        }
        .btn-warning {
            background: #f59e0b;
        }
        .btn-warning:hover {
            background: #d97706;
        }
        .btn-danger {
            background: #dc2626;
        }
        .btn-danger:hover {
            background: #b91c1c;
        }
        .users-table {
            width: 100%;
            border-collapse: collapse;
        }
        .users-table th,
        .users-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .users-table th {
            background: #f8fafc;
            font-weight: 600;
        }
        .role-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
        }
        .role-user { background: #f1f5f9; color: #475569; }
        .role-premium { background: #dbeafe; color: #1e40af; }
        .role-vip { background: #fef3c7; color: #92400e; }
        .role-banned { background: #fee2e2; color: #991b1b; }
        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 16px;
        }
        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .form-inline {
            display: inline-flex;
            gap: 8px;
            align-items: center;
        }
        .form-inline select {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <nav class="admin-nav">
        <div class="container">
            <div>
                <a href="/admin">Dashboard</a>
                <a href="/admin/users">Użytkownicy</a>
                <a href="/admin/projects">Projekty</a>
                <a href="/admin/user-roles">Role</a>
            </div>
            <div>
                <span>Witaj, <?= htmlspecialchars($_SESSION['admin_username'], ENT_QUOTES, 'UTF-8') ?></span>
                <a href="/logout" style="margin-left: 16px;">Wyloguj</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card">
            <h2>Zarządzanie rolami użytkowników</h2>
            <?php if (!empty($users)): ?>
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imię i nazwisko</th>
                            <th>Email</th>
                            <th>Aktualna rola</th>
                            <th>Data przypisania</th>
                            <th>Przypisana przez</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <span class="role-badge role-<?= $user['role'] ?? 'user' ?>">
                                        <?= ucfirst($user['role'] ?? 'user') ?>
                                    </span>
                                </td>
                                <td>
                                    <?= $user['assigned_at'] ? date('Y-m-d H:i', strtotime($user['assigned_at'])) : '-' ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($user['assigned_by_name'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td>
                                    <form method="POST" action="/admin/user-roles/assign" class="form-inline">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <select name="role">
                                            <option value="user" <?= ($user['role'] ?? 'user') === 'user' ? 'selected' : '' ?>>User</option>
                                            <option value="premium" <?= ($user['role'] ?? 'user') === 'premium' ? 'selected' : '' ?>>Premium</option>
                                            <option value="vip" <?= ($user['role'] ?? 'user') === 'vip' ? 'selected' : '' ?>>VIP</option>
                                            <option value="banned" <?= ($user['role'] ?? 'user') === 'banned' ? 'selected' : '' ?>>Banned</option>
                                        </select>
                                        <button type="submit" class="btn btn-success">Zmień</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Brak użytkowników w systemie.</p>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>Opis ról</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                <div>
                    <h4><span class="role-badge role-user">User</span></h4>
                    <p>Podstawowy użytkownik z ograniczonymi uprawnieniami.</p>
                </div>
                <div>
                    <h4><span class="role-badge role-premium">Premium</span></h4>
                    <p>Użytkownik z rozszerzonymi funkcjami i priorytetowym wsparciem.</p>
                </div>
                <div>
                    <h4><span class="role-badge role-vip">VIP</span></h4>
                    <p>Użytkownik z pełnym dostępem do wszystkich funkcji.</p>
                </div>
                <div>
                    <h4><span class="role-badge role-banned">Banned</span></h4>
                    <p>Zablokowany użytkownik bez dostępu do systemu.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
