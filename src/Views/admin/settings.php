<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($title ?? 'Ustawienia Systemu', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/css/style.css" />
    <style>
        .admin-nav { background: #1e293b; color: #e2e8f0; padding: 16px 0; margin-bottom: 24px; }
        .admin-nav .container { display: flex; justify-content: space-between; align-items: center; }
        .admin-nav a { color: #e2e8f0; text-decoration: none; margin-right: 20px; font-weight: 600; }
        .admin-nav a:hover { color: #93c5fd; }
        .card { margin-bottom: 24px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { margin-bottom: 8px; color: #334155; font-weight: 600; }
        .form-group input, .form-group select { padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 16px; }
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
        <h1><?= htmlspecialchars($title ?? 'Ustawienia Systemu', ENT_QUOTES, 'UTF-8') ?></h1>

        <div class="card">
            <h2>Ustawienia ogólne</h2>
            <form method="POST" action="#">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="company_name">Nazwa firmy</label>
                        <input id="company_name" name="company_name" type="text" placeholder="Moja Firma" />
                    </div>
                    <div class="form-group">
                        <label for="work_day_hours">Godziny pracy (domyślnie)</label>
                        <input id="work_day_hours" name="work_day_hours" type="number" step="0.25" min="0" value="8" />
                    </div>
                    <div class="form-group">
                        <label for="timezone">Strefa czasowa</label>
                        <select id="timezone" name="timezone">
                            <option value="Europe/Warsaw" selected>Europe/Warsaw</option>
                            <option value="UTC">UTC</option>
                        </select>
                    </div>
                </div>
                <div style="margin-top: 16px;">
                    <button class="btn" type="submit" disabled>Zapisz (demo)</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Integracje (demo)</h2>
            <p class="muted">Sekcja poglądowa — implementacja w kolejnych iteracjach.</p>
        </div>
    </div>
</body>
</html>


