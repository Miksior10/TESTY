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
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }
        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #2563eb;
        }
        .stat-label {
            color: #64748b;
            margin-top: 8px;
        }
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }
        @media (max-width: 768px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }
        .card h3 {
            margin: 0 0 16px;
            font-size: 18px;
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
                      <a href="/admin/work-time">Czas Pracy</a>
                      <a href="/admin/reports">Raporty</a>
                      <a href="/admin/settings">Ustawienia</a>
                      <a href="/test/egzamin"></a>
                  </div>
            <div>
                <span>Witaj, <?= htmlspecialchars($_SESSION['admin_username'], ENT_QUOTES, 'UTF-8') ?></span>
                <a href="/logout" style="margin-left: 16px;">Wyloguj</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= count($users) ?></div>
                <div class="stat-label">Użytkownicy</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count($projects) ?></div>
                <div class="stat-label">Projekty</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= number_format((float)$projectStats['total_budget'], 0, ',', ' ') ?> zł</div>
                <div class="stat-label">Całkowity budżet</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $projectStats['this_month'] ?></div>
                <div class="stat-label">Projekty w tym miesiącu</div>
            </div>
        </div>

        <div class="charts-grid">
            <div class="card">
                <h3>Statusy projektów</h3>
                <canvas id="projectStatusChart" width="400" height="200"></canvas>
            </div>
            <div class="card">
                <h3>Role użytkowników</h3>
                <canvas id="userRolesChart" width="400" height="200"></canvas>
            </div>
        </div>

        <div class="card">
            <h2>Ostatni użytkownicy</h2>
            <?php if (!empty($users)): ?>
                <ul>
                    <?php foreach (array_slice($users, 0, 5) as $user): ?>
                        <li>
                            <?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>
                            (<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>)
                            – <?= htmlspecialchars($user['created_at'], ENT_QUOTES, 'UTF-8') ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php if (count($users) > 5): ?>
                    <p><a href="/admin/users">Zobacz wszystkich użytkowników →</a></p>
                <?php endif; ?>
            <?php else: ?>
                <p>Brak użytkowników w systemie.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Dane dla wykresów
        const projectStatusData = <?= json_encode($projectStats['by_status']) ?>;
        const userRolesData = <?= json_encode($roleStats) ?>;

        // Wykres statusów projektów
        const projectStatusCtx = document.getElementById('projectStatusChart').getContext('2d');
        new Chart(projectStatusCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(projectStatusData),
                datasets: [{
                    data: Object.values(projectStatusData),
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Wykres ról użytkowników
        const userRolesCtx = document.getElementById('userRolesChart').getContext('2d');
        new Chart(userRolesCtx, {
            type: 'bar',
            data: {
                labels: userRolesData.map(item => item.role),
                datasets: [{
                    label: 'Liczba użytkowników',
                    data: userRolesData.map(item => item.count),
                    backgroundColor: '#3b82f6',
                    borderColor: '#1d4ed8',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
