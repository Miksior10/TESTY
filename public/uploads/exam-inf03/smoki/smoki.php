<?php
// Połączenie z bazą danych
$mysqli = @new mysqli('db', 'root', 'password', 'smoki', 3306);

if ($mysqli->connect_errno) {
    die('Błąd połączenia z bazą danych.');
}
$mysqli->set_charset('utf8');

// Skrypt 1 – zapytanie 2: unikalne kraje pochodzenia smoków rosnąco
// (w bazie kolumna nazywa się 'pochodzenie')
$krajeSql = "SELECT DISTINCT pochodzenie FROM smok ORDER BY pochodzenie ASC";
$krajeWynik = $mysqli->query($krajeSql);

$wybranyKraj = $_POST['kraj'] ?? '';
$smokiWynik = null;

// Skrypt 2 – wykonywany tylko po wysłaniu formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $wybranyKraj !== '') {
    // Zapytanie 1 z warunkiem na kraj (kolumna 'pochodzenie')
    $stmt = $mysqli->prepare("SELECT nazwa, dlugosc, szerokosc FROM smok WHERE pochodzenie = ?");
    $stmt->bind_param('s', $wybranyKraj);
    $stmt->execute();
    $smokiWynik = $stmt->get_result();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Smoki</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
    <header>
        <h2>Poznaj smoki!</h2>
    </header>

    <nav>
        <div id="blok1" class="blok-nav" onclick="pokazSekcje(1)">Baza</div>
        <div id="blok2" class="blok-nav" onclick="pokazSekcje(2)">Opisy</div>
        <div id="blok3" class="blok-nav" onclick="pokazSekcje(3)">Galeria</div>
    </nav>

    <main>
        <!-- Sekcja 1: baza smoków -->
        <section id="sekcja1">
            <h3>Baza Smoków</h3>
            <form method="post">
                <label for="kraj" class="ukryj-napis">Wybierz kraj</label>
                <select name="kraj" id="kraj">
                    <?php
                    if ($krajeWynik) {
                        while ($kraj = $krajeWynik->fetch_assoc()) {
                            $wartosc = htmlspecialchars($kraj['pochodzenie']);
                            $selected = ($wartosc === $wybranyKraj) ? 'selected' : '';
                            echo "<option value=\"{$wartosc}\" {$selected}>{$wartosc}</option>";
                        }
                    }
                    ?>
                </select>
                <button type="submit">Szukaj</button>
            </form>

            <table>
                <tr>
                    <th>Nazwa</th>
                    <th>Długość</th>
                    <th>Szerokość</th>
                </tr>
                <?php
                if ($smokiWynik && $smokiWynik->num_rows > 0) {
                    while ($smok = $smokiWynik->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($smok['nazwa']) . '</td>';
                        echo '<td>' . htmlspecialchars($smok['dlugosc']) . '</td>';
                        echo '<td>' . htmlspecialchars($smok['szerokosc']) . '</td>';
                        echo '</tr>';
                    }
                }
                ?>
            </table>
        </section>

        <!-- Sekcja 2: opisy smoków -->
        <section id="sekcja2">
            <h3>Opisy smoków</h3>
            <dl>
                <dt>Smok czerwony</dt>
                <dd>Pochodzi z Chin. Ma 1000 lat. Żywi się mniejszymi zwierzętami. Posiada łuskę cenną na rynkach wschodnich do wyrabiania lekarstw. Jest dziki i groźny.</dd>

                <dt>Smok zielony</dt>
                <dd>Pochodzi z Bułgarii. Ma 10000 lat. Żywi się owocami egzotycznymi. Jest kosmaty. Z sierści spłaszczonej grubej przez niego, a z niej tworzy się najdroższe materiały.</dd>

                <dt>Smok niebieski</dt>
                <dd>Pochodzi z Francji. Ma 100 lat. Żywi się owocami morza. Jest natchnieniem dla najlepszych malarzy. Często im pozuje. Smok ten jest przyjacielem ludzi i czasami im pomaga. Jest jednak próżny i nie lubi się przepracowywać.</dd>
            </dl>
        </section>

        <!-- Sekcja 3: galeria -->
        <section id="sekcja3">
            <h3>Galeria</h3>
            <img src="smok1.jpg" alt="Smok czerwony">
            <img src="smok2.jpg" alt="Smok wielki">
            <img src="smok3.jpg" alt="Skrzydlaty łaciaty">
        </section>
    </main>

    <footer>
        <p>Stronę opracował: 00000000000</p>
    </footer>

    <script>
        function pokazSekcje(nr) {
            const sekcje = [
                document.getElementById('sekcja1'),
                document.getElementById('sekcja2'),
                document.getElementById('sekcja3')
            ];
            const bloki = [
                document.getElementById('blok1'),
                document.getElementById('blok2'),
                document.getElementById('blok3')
            ];

            sekcje.forEach(function (sekcja, index) {
                if (index === nr - 1) {
                    sekcja.style.display = 'block';
                } else {
                    sekcja.style.display = 'none';
                }
            });

            bloki.forEach(function (blok, index) {
                if (index === nr - 1) {
                    blok.style.backgroundColor = 'MistyRose';
                } else {
                    blok.style.backgroundColor = '#FFAEA5';
                }
            });
        }

        // Stan początkowy – widoczna sekcja 1
        pokazSekcje(1);
    </script>
</body>
</html>
<?php
if ($krajeWynik) {
    $krajeWynik->free();
}
if ($smokiWynik) {
    $smokiWynik->free();
}
$mysqli->close();
?>