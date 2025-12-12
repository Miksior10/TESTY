<?php declare(strict_types=1); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egzaminy INF.03</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .card { max-width: 820px; margin: 0 auto; }
        .muted { color: var(--muted); }
        .picker { margin: 16px 0; }
        .result { margin-top: 16px; padding: 12px; border: 1px solid var(--border); border-radius: 12px; background: rgba(19,26,43,.65); }
        ul { padding-left: 20px; }
        li { margin: 4px 0; }
        .empty { color: var(--muted); }
        input[type="file"] { background: transparent; border: 0; padding: 0; color: var(--text); }
        .status { margin-top: 12px; font-size: 14px; }
        .status.ok { color: #22c55e; }
        .status.err { color: #ef4444; }
        .status.muted { color: var(--muted); }
    </style>
</head>
<body>
    <div class="wrap">
        <header>
            <h1>Egzaminy INF.03</h1>
            <p class="muted">Wybierz folder z materiałami do egzaminu (INF.03). Pliki nie są wysyłane na serwer – działamy tylko w przeglądarce.</p>
        </header>
        <div class="card">
            <div class="picker">
                <label for="folderInput">Folder z egzaminem:</label><br>
                <input id="folderInput" type="file" webkitdirectory directory multiple>
            </div>

            <div class="picker">
                <strong>Ścieżka serwerowa zapisu:</strong>
                <p class="muted" style="margin: 6px 0 0 0;">
                    Pliki zapisują się do <code>/public/uploads/exam-inf03/&lt;podkatalog&gt;</code>
                    (bazowy katalog jest stały: <code>exam-inf03</code>).
                    <br>Podgląd bazowego katalogu: <a href="/uploads/exam-inf03/" style="color: var(--accent);">/uploads/exam-inf03/</a>
                </p>
                <p class="muted" style="margin: 4px 0 0 0;">
                    Strona startowa aplikacji: <a href="/" class="muted" style="color: var(--accent);">/</a>
                    (router w <code>public/index.php</code>).
                </p>
            </div>

            <div class="picker">
                <label for="targetPath">Ścieżka folderu egzaminu (z nazwy wybranego katalogu):</label><br>
                <input id="targetPath" type="text" style="width: 100%; max-width: 480px;" readonly value="(nie wybrano)">
                <p class="muted" style="margin: 6px 0 0 0;">Przeglądarka nie udostępnia pełnej ścieżki dysku, pokazujemy nazwę wybranego katalogu.</p>
            </div>

            <div class="picker">
                <label for="folderName">Podkatalog w <code>exam-inf03</code> (opcjonalnie):</label><br>
                <input id="folderName" type="text" placeholder="np. grupa-a" style="max-width: 320px;">
                <p class="muted" style="margin: 6px 0 0 0;">Pliki zapiszą się w <code>public/uploads/exam-inf03/&lt;podkatalog&gt;</code>. Zostaw puste, by wrzucać bezpośrednio do <code>exam-inf03</code>.</p>
            </div>

            <div class="picker">
                <label for="extraFiles">Dodaj pliki do egzaminu (opcjonalnie):</label><br>
                <input id="extraFiles" type="file" multiple>
            </div>

            <div class="result" id="result">
                <strong>Nie wybrano folderu.</strong>
                <p class="muted">Po wyborze pokażemy listę plików.</p>
            </div>

            <div style="margin-top: 12px;">
                <button id="uploadBtn" class="btn">Zapisz pliki na serwerze</button>
                <div id="uploadStatus" class="status muted">Pliki nie są jeszcze wysłane.</div>
            </div>
        </div>
        <footer class="muted" style="margin-top:18px;">© <?= date('Y') ?> Egzaminy INF.03</footer>
    </div>

    <script>
        const folderInput = document.getElementById('folderInput');
        const extraFilesInput = document.getElementById('extraFiles');
        const targetPathInput = document.getElementById('targetPath');
        const folderNameInput = document.getElementById('folderName');
        const uploadBtn = document.getElementById('uploadBtn');
        const uploadStatus = document.getElementById('uploadStatus');
        const result = document.getElementById('result');

        let folderFiles = [];
        let extraFiles = [];
        let topFolder = '(nie wybrano)';

        const render = () => {
            if (!folderFiles.length) {
                result.innerHTML = '<strong>Nie wybrano folderu.</strong><p class="muted">Po wyborze pokażemy listę plików.</p>';
                targetPathInput.value = '(nie wybrano)';
                return;
            }

            const folderItems = folderFiles
                .filter(f => f.type || f.name)
                .map(f => f.webkitRelativePath || f.name);

            const extraItems = extraFiles
                .filter(f => f.name)
                .map(f => f.name);

            const totalCount = folderItems.length + extraItems.length;
            const listItems = [
                ...folderItems.map(name => `<li>${name}</li>`),
                ...extraItems.map(name => `<li>${topFolder}/${name} (dodany)</li>`)
            ];

            result.innerHTML = `
                <strong>Wybrano folder: ${topFolder}</strong>
                <p class="muted">Plików razem: ${totalCount}</p>
                <ul>${listItems.length ? listItems.join('') : '<li class="empty">Brak plików</li>'}</ul>
            `;
            targetPathInput.value = `/${topFolder}`;
        };

        folderInput.addEventListener('change', () => {
            folderFiles = Array.from(folderInput.files || []);
            if (!folderFiles.length) {
                render();
                return;
            }
            const firstPath = folderFiles[0].webkitRelativePath || folderFiles[0].name;
            topFolder = firstPath.split('/')[0] || 'Wybrany folder';
            render();
        });

        extraFilesInput.addEventListener('change', () => {
            extraFiles = Array.from(extraFilesInput.files || []);
            render();
        });

        const setStatus = (message, type = 'muted') => {
            uploadStatus.className = `status ${type}`;
            uploadStatus.innerHTML = message;
        };

        uploadBtn.addEventListener('click', async () => {
            const combined = [...folderFiles, ...extraFiles];
            if (!combined.length) {
                setStatus('Najpierw wybierz folder lub dodaj pliki.', 'err');
                return;
            }

            const folderName = (folderNameInput.value || '').trim();
            const fd = new FormData();
            fd.append('folder_name', folderName);

            combined.forEach((file) => {
                fd.append('files[]', file);
                const relPath = file.webkitRelativePath
                    ? file.webkitRelativePath.split('/').slice(1).join('/') || file.name
                    : file.name;
                fd.append('relative_paths[]', relPath);
            });

            setStatus('Wysyłanie plików...', 'muted');

            try {
                const res = await fetch('/test/egzamin/upload', { method: 'POST', body: fd });
                const data = await res.json().catch(() => null);

                if (!res.ok || !data || data.ok === false) {
                    throw new Error(data?.error || 'Błąd zapisu');
                }

                const savedList = (data.saved || []).map(p => `<li>${p}</li>`).join('') || '<li class="empty">Brak plików zapisanych</li>';
                setStatus(
                    `Zapisano pliki w katalogu <code>/${data.dir || ('uploads/' + folderName)}</code><ul>${savedList}</ul>`,
                    'ok'
                );
            } catch (e) {
                setStatus(`Nie udało się zapisać plików: ${e.message}`, 'err');
            }
        });
    </script>
</body>
</html>