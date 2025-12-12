<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($title ?? 'Raporty i Statystyki', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/css/style.css" />
    <style>
        .admin-nav { background: #1e293b; color: #e2e8f0; padding: 16px 0; margin-bottom: 24px; }
        .admin-nav .container { display: flex; justify-content: space-between; align-items: center; }
        .admin-nav a { color: #e2e8f0; text-decoration: none; margin-right: 20px; font-weight: 600; }
        .admin-nav a:hover { color: #93c5fd; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .card { margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        th { background: #f8fafc; color: #475569; }
        .stat-number { font-size: 28px; font-weight: 700; color: #2563eb; }
        .muted { color: #64748b; }
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
        <h1><?= htmlspecialchars($title ?? 'Raporty i Statystyki', ENT_QUOTES, 'UTF-8') ?></h1>

        <div class="grid">
            <div class="card">
                <h3>Użytkownicy</h3>
                <div class="stat-number"><?= (int)($userStats['total_users'] ?? 0) ?></div>
                <div class="muted">Łącznie użytkowników</div>
                <div style="margin-top:12px;">
                    <strong>Aktywni:</strong> <?= (int)($userStats['active_users'] ?? 0) ?>
                </div>
            </div>

            <div class="card">
                <h3>Projekty</h3>
                <div class="stat-number"><?= (int)($projectStats['total_projects'] ?? 0) ?></div>
                <div class="muted">Łącznie projektów</div>
                <div style="margin-top:12px;">
                    <strong>Budżet:</strong> <?= number_format((float)($projectStats['total_budget'] ?? 0), 0, ',', ' ') ?> zł
                </div>
            </div>

            <div class="card" style="grid-column: 1 / -1;">
                <h3>Czas pracy — ranking</h3>
                <?php if (!empty($workStats)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Użytkownik</th>
                                <th>Dział</th>
                                <th>Dni pracy</th>
                                <th>Godzin łącznie</th>
                                <th>Średnio / dzień</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($workStats as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($row['department'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= (int)($row['days_worked'] ?? 0) ?></td>
                                    <td><?= number_format((float)($row['total_hours'] ?? 0), 2) ?></td>
                                    <td><?= number_format((float)($row['avg_hours_per_day'] ?? 0), 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Brak danych do wyświetlenia.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>


