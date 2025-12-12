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
        .btn-success {
            background: #16a34a;
        }
        .btn-success:hover {
            background: #15803d;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
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
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .project-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
        }
        .project-card h3 {
            margin: 0 0 8px;
            font-size: 18px;
        }
        .project-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
        }
        .status-planning { background: #fef3c7; color: #92400e; }
        .status-active { background: #d1fae5; color: #065f46; }
        .status-completed { background: #dbeafe; color: #1e40af; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .project-budget {
            font-weight: 700;
            color: #16a34a;
            margin: 8px 0;
        }
        .project-dates {
            color: #64748b;
            font-size: 14px;
            margin: 8px 0;
        }
        .project-actions {
            margin-top: 16px;
            display: flex;
            gap: 8px;
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
            <h2>Dodaj nowy projekt</h2>
            <form method="POST" action="/admin/projects/add">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Nazwa projektu:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select id="status" name="status">
                            <option value="planning">Planowanie</option>
                            <option value="active">Aktywny</option>
                            <option value="completed">Zakończony</option>
                            <option value="cancelled">Anulowany</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="budget">Budżet (zł):</label>
                        <input type="number" id="budget" name="budget" step="0.01">
                    </div>
                    <button type="submit" class="btn">Dodaj projekt</button>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="description">Opis:</label>
                        <textarea id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="start_date">Data rozpoczęcia:</label>
                        <input type="date" id="start_date" name="start_date">
                    </div>
                    <div class="form-group">
                        <label for="end_date">Data zakończenia:</label>
                        <input type="date" id="end_date" name="end_date">
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Lista projektów</h2>
            <?php if (!empty($projects)): ?>
                <div class="projects-grid">
                    <?php foreach ($projects as $project): ?>
                        <div class="project-card">
                            <div class="project-status status-<?= $project['status'] ?>">
                                <?= ucfirst($project['status']) ?>
                            </div>
                            <h3><?= htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <?php if ($project['description']): ?>
                                <p><?= htmlspecialchars($project['description'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                            <?php if ($project['budget']): ?>
                                <div class="project-budget"><?= number_format((float)$project['budget'], 0, ',', ' ') ?> zł</div>
                            <?php endif; ?>
                            <div class="project-dates">
                                <?php if ($project['start_date']): ?>
                                    Rozpoczęcie: <?= $project['start_date'] ?><br>
                                <?php endif; ?>
                                <?php if ($project['end_date']): ?>
                                    Zakończenie: <?= $project['end_date'] ?>
                                <?php endif; ?>
                            </div>
                            <div class="project-actions">
                                <a href="/admin/projects/delete?id=<?= $project['id'] ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirm('Czy na pewno chcesz usunąć ten projekt?')">
                                    Usuń
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Brak projektów w systemie.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
