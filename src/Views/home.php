<?php
// src/Views/home.php
declare(strict_types=1);
?>
<!doctype html>
<html lang="pl">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/css/style.css" />
  </head>
  <body>
    <div id="app" class="container">
      <header>
        <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="muted">Prosta aplikacja PHP + JS</p>
      </header>

      <?php if (!empty($users)): ?>
        <section class="card">
          <h2>Użytkownicy</h2>
          <ul class="user-list">
            <?php foreach ($users as $u): ?>
              <li>
                <?= htmlspecialchars($u['name'], ENT_QUOTES, 'UTF-8') ?>
                (<?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?>)
                – <?= htmlspecialchars($u['created_at'], ENT_QUOTES, 'UTF-8') ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </section>
      <?php else: ?>
        <section class="card">
          <p>Brak użytkowników w bazie.</p>
        </section>
      <?php endif; ?>

      <section class="card">
        <h2>O nas</h2>
        <p>
          To przykładowa aplikacja PHP + JS uruchomiona w Dockerze.
          Kod strony znajduje się w katalogach <code>public</code> oraz <code>src</code>.
          Dane pochodzą z bazy <code>moja_strona</code> (tabela <code>users</code>).
        </p>
            <p style="margin-top: 16px;">
              <a href="/login" style="color: #2563eb; text-decoration: none; font-weight: 600; margin-right: 20px;">
                → Panel Administratora
              </a>
              <a href="/user/login" style="color: #16a34a; text-decoration: none; font-weight: 600;">
                → Panel Użytkownika
              </a>
              <a href="/test/egzamin/menu" style="color:rgb(255, 0, 0); text-decoration: none; font-weight: 600;">
              →  Egzaminy
              </a>
            </p>
      </section>

      <footer class="muted">© <?= date('Y') ?> Moja strona</footer>
    </div>

    <script src="/js/app.js" type="module"></script>
  </body>
</html>