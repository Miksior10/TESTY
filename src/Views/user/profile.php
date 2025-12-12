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
        .profile-form {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-weight: 600;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .btn-primary {
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
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
        .message.error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .profile-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: 600;
            color: #475569;
        }
        .info-value {
            color: #0f172a;
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
        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error"><?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="profile-info">
            <h3>Informacje o koncie</h3>
            <div class="info-row">
                <span class="info-label">ID:</span>
                <span class="info-value"><?= htmlspecialchars((string)$user['id'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Dział:</span>
                <span class="info-value"><?= htmlspecialchars($user['department'] ?? 'Nie określono', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Stanowisko:</span>
                <span class="info-value"><?= htmlspecialchars($user['position'] ?? 'Nie określono', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Data zatrudnienia:</span>
                <span class="info-value"><?= htmlspecialchars($user['hire_date'] ?? 'Nie określono', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value"><?= $user['is_active'] ? 'Aktywny' : 'Nieaktywny' ?></span>
            </div>
        </div>

        <div class="profile-form">
            <h2>Edytuj Profil</h2>
            <form method="POST" action="/user/update-profile">
                <div class="form-group">
                    <label for="name">Imię i nazwisko:</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="current_password">Obecne hasło (wymagane do zmiany hasła):</label>
                    <input type="password" id="current_password" name="current_password">
                </div>
                
                <div class="form-group">
                    <label for="new_password">Nowe hasło (opcjonalnie):</label>
                    <input type="password" id="new_password" name="new_password">
                </div>
                
                <button type="submit" class="btn-primary">Zaktualizuj Profil</button>
            </form>
        </div>
    </div>
</body>
</html>
