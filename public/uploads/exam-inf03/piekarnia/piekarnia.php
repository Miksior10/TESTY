<?php
$db = @new mysqli('db', 'root', 'password', 'piekarnia', 3306);
if ($db->connect_errno) {
    die('Błąd połączenia z bazą danych.');
}
$db->set_charset('utf8');

// Zapytanie 2: unikalne rodzaje malejąco
$rodzaje = $db->query("SELECT DISTINCT Rodzaj FROM wyroby ORDER BY Rodzaj DESC;");

$wybranyRodzaj = $_POST['rodzaj'] ?? '';
$wyniki = null;
if ($wybranyRodzaj !== '') {
    // Zapytanie 1 z filtrem Rodzaj
    $stmt = $db->prepare("SELECT Rodzaj, Nazwa, Gramatura, Cena FROM wyroby WHERE Rodzaj = ? ORDER BY Nazwa");
    $stmt->bind_param('s', $wybranyRodzaj);
    $stmt->execute();
    $wyniki = $stmt->get_result();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>PIEKARNIA</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <img src="wypieki.png" alt="Produkty naszej piekarni" class="tlo">

    <nav>
        <a href="kwerenda1.png">KWERENDA1</a>
        <a href="kwerenda2.png">KWERENDA2</a>
        <a href="kwerenda3.png">KWERENDA3</a>
        <a href="kwerenda4.png">KWERENDA4</a>
    </nav>

    <header>
        <h1>WITAMY</h1>
        <h4>NA STRONIE PIEKARNI</h4>
        <p>Zapraszamy do zapoznania się z ofertą naszych wypieków, przygotowanych z najwyższą starannością i według tradycyjnych receptur.</p>
    </header>

    <main>
        <h4>Wybierz rodzaj wypieków:</h4>
        <form method="post">
            <select name="rodzaj">
                <option value="">-- wybierz rodzaj --</option>
                <?php
                if ($rodzaje) {
                    while ($r = $rodzaje->fetch_assoc()) {
                        $sel = ($r['Rodzaj'] === $wybranyRodzaj) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($r['Rodzaj']) . '" ' . $sel . '>' . htmlspecialchars($r['Rodzaj']) . '</option>';
                    }
                }
                ?>
            </select>
            <button type="submit">Wybierz</button>
        </form>

        <table>
            <tr>
                <th>Rodzaj</th>
                <th>Nazwa</th>
                <th>Gramatura</th>
                <th>Cena</th>
            </tr>
            <?php
            if ($wyniki && $wyniki->num_rows > 0) {
                while ($w = $wyniki->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($w['Rodzaj']) . '</td>';
                    echo '<td>' . htmlspecialchars($w['Nazwa']) . '</td>';
                    echo '<td>' . htmlspecialchars($w['Gramatura']) . '</td>';
                    echo '<td>' . htmlspecialchars(number_format((float)$w['Cena'], 2, '.', '')) . '</td>';
                    echo '</tr>';
                }
            }
            ?>
        </table>
    </main>
    
    <footer>
        <p>AUTOR 00000000000</p>
        <p>Data: 2025-12-11</p>
    </footer>
</body>
</html>
<?php
if ($rodzaje) {
    $rodzaje->free();
}
if ($wyniki) {
    $wyniki->free();
}
$db->close();
?>
