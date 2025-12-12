<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($title ?? 'Zarządzanie Czasem Pracy', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/css/style.css" />
    <style>
        .admin-nav { background: #1e293b; color: #e2e8f0; padding: 16px 0; margin-bottom: 24px; }
        .admin-nav .container { display: flex; justify-content: space-between; align-items: center; }
        .admin-nav a { color: #e2e8f0; text-decoration: none; margin-right: 20px; font-weight: 600; }
        .admin-nav a:hover { color: #93c5fd; }
        .filters { display: flex; gap: 12px; margin-bottom: 16px; }
        .card { margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        th { background: #f8fafc; color: #475569; }
        .form-inline { display: flex; gap: 12px; align-items: end; }
        .form-inline > div { display: flex; flex-direction: column; }
        .btn { background: #2563eb; color: #fff; border: none; padding: 10px 14px; border-radius: 8px; font-weight: 600; cursor: pointer; }
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
                <a href="/admin/work-time">Czas Pracy</a>
                <a href="/admin/reports">Raporty</a>
                <a href="/admin/settings">Ustawienia</a>
            </div>
            <div>
                <span>Witaj, <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></span>
                <a href="/logout" style="margin-left: 16px;">Wyloguj</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1><?= htmlspecialchars($title ?? 'Zarządzanie Czasem Pracy', ENT_QUOTES, 'UTF-8') ?></h1>

        <div class="card">
            <form class="form-inline" method="GET" action="/admin/work-time">
                <div>
                    <label for="user_id">Użytkownik</label>
                    <select name="user_id" id="user_id">
                        <option value="">Wszyscy</option>
                        <?php foreach (($users ?? []) as $u): ?>
                            <option value="<?= (int)$u['id'] ?>" <?= (isset($selectedUserId) && (int)$selectedUserId === (int)$u['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($u['name'] . ' (' . $u['email'] . ')', ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="month">Miesiąc</label>
                    <input type="month" id="month" name="month" value="<?= htmlspecialchars($selectedMonth ?? date('Y-m'), ENT_QUOTES, 'UTF-8') ?>" />
                </div>
                <div>
                    <button type="submit" class="btn">Filtruj</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Rejestr czasu</h2>
            <?php if (!empty($workTimeData)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Start</th>
                            <th>Koniec</th>
                            <th>Przerwa (min)</th>
                            <th>Godzin</th>
                            <th>Notatki</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($workTimeData as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row['start_time'] ?? '--', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row['end_time'] ?? '--', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= (int)($row['break_duration'] ?? 0) ?></td>
                                <td><?= number_format((float)($row['total_hours'] ?? 0), 2) ?></td>
                                <td><?= htmlspecialchars($row['notes'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Brak danych do wyświetlenia.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>


