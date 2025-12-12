<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/css/style.css" />
    <style>
        .user-nav {
            background: #1e40af;
            color: #e2e8f0;
            padding: 16px 0;
            margin-bottom: 24px;
        }
        .user-nav .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user-nav a {
            color: #e2e8f0;
            text-decoration: none;
            margin-right: 20px;
            font-weight: 600;
        }
        .user-nav a:hover {
            color: #93c5fd;
        }
        .clock-section {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
            margin-bottom: 24px;
            text-align: center;
        }
        .clock-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            margin-top: 20px;
        }
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-size: 16px;
        }
        .btn-success {
            background: #16a34a;
            color: #ffffff;
        }
        .btn-success:hover {
            background: #15803d;
        }
        .btn-danger {
            background: #dc2626;
            color: #ffffff;
        }
        .btn-danger:hover {
            background: #b91c1c;
        }
        .btn-secondary {
            background: #64748b;
            color: #ffffff;
        }
        .btn-secondary:hover {
            background: #475569;
        }
        .work-status {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .work-status.working {
            color: #16a34a;
        }
        .work-status.not-working {
            color: #dc2626;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
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
        .recent-work {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
        }
        .work-entry {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .work-entry:last-child {
            border-bottom: none;
        }
        .work-date {
            font-weight: 600;
            color: #0f172a;
        }
        .work-hours {
            color: #64748b;
        }
        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        .message.success {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }
    </style>
</head>
<body>
    <nav class="user-nav">
        <div class="container">
            <div>
                <a href="/user/dashboard">Dashboard</a>
                <a href="/user/profile">Mój Profil</a>
            </div>
            <div>
                <span>Witaj, <?= htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8') ?></span>
                <a href="/user/logout" style="margin-left: 16px;">Wyloguj</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="message success"><?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="clock-section">
            <div class="work-status <?= $todayWork && $todayWork['end_time'] ? 'not-working' : 'working' ?>">
                <?php if ($todayWork && $todayWork['end_time']): ?>
                    Praca zakończona o <?= htmlspecialchars($todayWork['end_time'], ENT_QUOTES, 'UTF-8') ?>
                <?php elseif ($todayWork && $todayWork['start_time']): ?>
                    Praca rozpoczęta o <?= htmlspecialchars($todayWork['start_time'], ENT_QUOTES, 'UTF-8') ?>
                <?php else: ?>
                    Nie rozpoczęto pracy dzisiaj
                <?php endif; ?>
            </div>

            <div class="clock-buttons">
                <?php if (!$todayWork || !$todayWork['start_time']): ?>
                    <form method="POST" action="/user/clock-in" style="display: inline;">
                        <button type="submit" class="btn btn-success">Rozpocznij Pracę</button>
                    </form>
                <?php elseif (!$todayWork['end_time']): ?>
                    <form method="POST" action="/user/clock-out" style="display: inline;">
                        <div style="margin-bottom: 10px;">
                            <label for="break_duration">Przerwa (minuty):</label>
                            <input type="number" id="break_duration" name="break_duration" value="0" min="0" max="480" style="width: 80px; margin-left: 8px;">
                        </div>
                        <button type="submit" class="btn btn-danger">Zakończ Pracę</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $monthlyStats['days_worked'] ?? 0 ?></div>
                <div class="stat-label">Dni pracy w tym miesiącu</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= number_format((float)($monthlyStats['total_hours'] ?? 0), 1) ?></div>
                <div class="stat-label">Godziny w tym miesiącu</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= number_format((float)($monthlyStats['avg_hours_per_day'] ?? 0), 1) ?></div>
                <div class="stat-label">Średnio godzin dziennie</div>
            </div>
        </div>

        <div class="recent-work">
            <h2>Ostatnie dni pracy</h2>
            <?php if (!empty($recentWork)): ?>
                <?php foreach (array_slice($recentWork, 0, 10) as $work): ?>
                    <div class="work-entry">
                        <div class="work-date"><?= htmlspecialchars($work['date'], ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="work-hours">
                            <?= htmlspecialchars($work['start_time'] ?? '--', ENT_QUOTES, 'UTF-8') ?> - 
                            <?= htmlspecialchars($work['end_time'] ?? '--', ENT_QUOTES, 'UTF-8') ?>
                            (<?= number_format((float)($work['total_hours'] ?? 0), 1) ?>h)
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Brak danych o czasie pracy.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
