<?php
// Połączenie z bazą danych (Docker: host serwisu db, user root, hasło password)
$mysqli = @new mysqli('db', 'root', 'password', 'konkurs', 3306);
if ($mysqli->connect_errno) {
    die('Błąd połączenia z bazą.');
}
$mysqli->set_charset('utf8');

// Zapytanie 1: losowo wybierz 5 nagród
$wynik = $mysqli->query('SELECT nazwa, opis, cena FROM nagrody ORDER BY RAND() LIMIT 5');
if (!$wynik) {
    $mysqli->close();
    die('Błąd zapytania.');
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>WOLONTARIAT SZKOLNY</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>KONKURS - WOLONTARIAT SZKOLNY</h1>
    </header>
    <main>
        <section class="lewy">
            <h3>Konkursowe nagrody</h3>
            <form method="get">
                <button type="submit">Losuj nowe nagrody</button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>Nr</th>
                        <th>Nazwa</th>
                        <th>Opis</th>
                        <th>Wartość</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $licznik = 1;
                    while ($rekord = $wynik->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $licznik . '</td>';
                        echo '<td>' . htmlspecialchars($rekord['nazwa']) . '</td>';
                        echo '<td>' . htmlspecialchars($rekord['opis']) . '</td>';
                        echo '<td>' . number_format((float)$rekord['cena'], 2, '.', '') . '</td>';
                        echo '</tr>';
                        $licznik++;
                    }
                    $wynik->free();
                    $mysqli->close();
                    ?>
                </tbody>
            </table>
        </section>
        <aside class="prawy">
            <img src="puchar.png" alt="Puchar dla wolontariusza">
            <h4>Polecane linki</h4>
            <ul>
                <li><a href="kwerenda1.png">Kwerenda1</a></li>
                <li><a href="kwerenda2.png">Kwerenda2</a></li>
                <li><a href="kwerenda3.png">Kwerenda3</a></li>
                <li><a href="kwerenda4.png">Kwerenda4</a></li>
            </ul>
        </aside>
    </main>
    <footer>
        <p>Numer zdającego: 00000000000</p>
    </footer>
</body>
</html>
