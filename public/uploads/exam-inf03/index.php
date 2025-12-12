<?php declare(strict_types=1);

// Prosta lista zawartości katalogu exam-inf03 (podkatalogi + pliki)
$base = __DIR__;

// Obsługa wejścia do podkatalogów: ?dir=sub/folder
$requested = trim((string)($_GET['dir'] ?? ''), "/\\");
$requested = str_replace('..', '', $requested); // prosta sanitacja
$currentPath = $requested ? $base . '/' . $requested : $base;

if (!is_dir($currentPath)) {
    http_response_code(404);
    echo 'Katalog nie istnieje';
    exit;
}

$items = array_values(array_filter(scandir($currentPath) ?: [], function ($name) {
    return $name !== '.' && $name !== '..';
}));

// Sortuj katalogi przed plikami
usort($items, function ($a, $b) use ($currentPath) {
    $isDirA = is_dir($currentPath . '/' . $a);
    $isDirB = is_dir($currentPath . '/' . $b);
    if ($isDirA === $isDirB) return strcasecmp($a, $b);
    return $isDirA ? -1 : 1;
});

// Breadcrumbs
$crumbs = [];
if ($requested !== '') {
    $parts = explode('/', $requested);
    $acc = [];
    foreach ($parts as $part) {
        $acc[] = $part;
        $crumbs[] = [
            'name' => $part,
            'path' => implode('/', $acc)
        ];
    }
}
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Pliki egzaminu - exam-inf03</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .card { max-width: 960px; margin: 24px auto; }
        .list { list-style: none; padding: 0; margin: 0; }
        .list li { padding: 8px 0; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; gap: 12px; }
        .muted { color: var(--muted); }
        .badge { padding: 2px 8px; border-radius: 999px; font-size: 12px; background: #334155; color: #e8eeff; }
        a { color: var(--accent); text-decoration: none; }
        .crumbs { margin: 8px 0 12px 0; font-size: 14px; }
        .crumbs a { color: var(--accent); }
    </style>
</head>
<body>
    <div class="wrap">
        <header>
            <h1>Pliki egzaminu (exam-inf03)</h1>
            <p class="muted">Lista podkatalogów i plików zapisanych w <code>public/uploads/exam-inf03</code>.</p>
            <?php if ($requested !== ''): ?>
                <div class="crumbs">
                    <a href="/uploads/exam-inf03/">exam-inf03</a>
                    <?php foreach ($crumbs as $crumb): ?>
                        / <a href="/uploads/exam-inf03/?dir=<?= htmlspecialchars($crumb['path'], ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($crumb['name'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </header>
        <div class="card">
            <?php if (empty($items)): ?>
                <p class="muted">Brak plików ani podkatalogów.</p>
            <?php else: ?>
                <ul class="list">
                    <?php foreach ($items as $name): ?>
                        <?php
                            $path = $currentPath . '/' . $name;
                            $isDir = is_dir($path);
                            $relPath = ($requested ? $requested . '/' : '') . $name;
                            $href = $isDir
                                ? '/uploads/exam-inf03/?dir=' . rawurlencode($relPath)
                                : '/uploads/exam-inf03/' . $relPath;
                        ?>
                        <li>
                            <div>
                                <a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>
                                </a>
                                <?php if (!$isDir): ?>
                                    <span class="muted" style="margin-left: 8px; font-size: 12px;">
                                        (<?= number_format(filesize($path) ?: 0) ?> B)
                                    </span>
                                <?php endif; ?>
                            </div>
                            <span class="badge"><?= $isDir ? 'folder' : 'plik' ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

