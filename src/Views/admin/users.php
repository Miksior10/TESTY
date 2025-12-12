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
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
        }
        .btn:hover {
            background: #1d4ed8;
        }
        .btn-danger {
            background: #dc2626;
        }
        .btn-danger:hover {
            background: #b91c1c;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 12px;
            align-items: end;
            margin-bottom: 24px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            margin-bottom: 4px;
            font-weight: 600;
        }
        .form-group input {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
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
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-badge.active {
            background: #dcfce7;
            color: #16a34a;
        }
        .status-badge.inactive {
            background: #fee2e2;
            color: #dc2626;
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
            <h2>Dodaj nowego użytkownika</h2>
            <form method="POST" action="/admin/users/add">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Imię i nazwisko:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="department">Dział:</label>
                        <input type="text" id="department" name="department">
                    </div>
                    <div class="form-group">
                        <label for="position">Stanowisko:</label>
                        <input type="text" id="position" name="position">
                    </div>
                    <div class="form-group">
                        <label for="hire_date">Data zatrudnienia:</label>
                        <input type="date" id="hire_date" name="hire_date">
                    </div>
                    <div class="form-group">
                        <label for="salary">Wynagrodzenie:</label>
                        <input type="number" id="salary" name="salary" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="is_active" name="is_active" checked>
                            Konto aktywne
                        </label>
                    </div>
                    <button type="submit" class="btn">Dodaj użytkownika</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Lista użytkowników</h2>
            <?php if (!empty($users)): ?>
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imię i nazwisko</th>
                            <th>Email</th>
                            <th>Dział</th>
                            <th>Stanowisko</th>
                            <th>Status</th>
                            <th>Data utworzenia</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($user['department'] ?? '--', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($user['position'] ?? '--', ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <span class="status-badge <?= $user['is_active'] ? 'active' : 'inactive' ?>">
                                        <?= $user['is_active'] ? 'Aktywny' : 'Nieaktywny' ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($user['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <a href="/admin/users/delete?id=<?= $user['id'] ?>" 
                                       class="btn btn-danger"
                                       onclick="return confirm('Czy na pewno chcesz usunąć tego użytkownika?')">
                                        Usuń
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Brak użytkowników w systemie.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
