<?php
$db = @new mysqli('db', 'root', 'password', 'szachy', 3306);
if ($db->connect_errno) {
    die("Błąd Połączenia");
}
$db->set_charset('utf8');

//zapytanie 1
$zapytanie1 =  "SELECT pseudonim, tytul, ranking, klasa 
FROM zawodnicy 
WHERE ranking > 2787 
ORDER BY ranking DESC";
$wynik1 = $db->query($zapytanie1);

//zapytanie2
$paraGraczy = '';
if(isset($_POST['losuj'])) {
    $zapytanie2 = "SELECT pseudonim, klasa 
                   FROM zawodnicy 
                   ORDER BY RAND() 
                   LIMIT 2";
    $wynik2 = $db->query($zapytanie2);
    if ($wynik2 && $wynik2->num_rows == 2) {
        $gracz1 = $wynik2->fetch_assoc();
        $gracz2 = $wynik2->fetch_assoc();
        $paraGraczy = $gracz1['pseudonim'] . ' ' . $gracz1['klasa'] . ' ' . $gracz2['pseudonim'] . ' ' . $gracz2['klasa'];
    }               
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>KOŁO SZACHOWE</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h2><em>Koło szachowe gambit piona</em></h2>
    </header>
    
    <main>
        <aside>
            <h4>Polecane linki</h4>
            <ul>
            <li><a href="kwerenda1.png">kwerenda1</a></li>
                <li><a href="kwerenda2.png">kwerenda2</a></li>
                <li><a href="kwerenda3.png">kwerenda3</a></li>
                <li><a href="kwerenda4.png">kwerenda4</a></li>
            </ul>
            <img src="logo.png" alt="Logo koła">
        </aside>

        <section>
            <h3>Najlepsi gracze naszego koła</h3>
            <table>
                <thead>
                    <tr>
                        <th>Pozycja</th>
                        <th>Pseudonim</th>
                        <th>Tytuł</th>
                        <th>Ranking</th>
                        <th>Klasa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($wynik1 && $wynik1->num_rows > 0) {
                        $pozycja = 1;
                        while ($wiersz = $wynik1->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $pozycja . '</td>';
                            echo '<td>' . htmlspecialchars($wiersz['pseudonim']) . '</td>';
                            echo '<td>' . htmlspecialchars($wiersz['tytul']) . '</td>';
                            echo '<td>' . htmlspecialchars($wiersz['ranking']) . '</td>';
                            echo '<td>' . htmlspecialchars($wiersz['klasa']) . '</td>';
                            echo '</tr>';
                            $pozycja++;
                        }
                    }
                    ?>
                </tbody>
            </table>

            <form method="POST" action="">
                    <button type = "submit" name="losuj">Losuj nową pare graczy</button>
            </form>

            <?php if ($paraGraczy): ?>
                <h4><?php echo htmlspecialchars($paraGraczy); ?></h4>
            <?php endif; ?>
            
            <p>Legenda: AM - Absolutny Mistrz, SM - Szkolny Mistrz, PM - Mistrz Poziomu, KM - Mistrz Klasowy</p>
        </section>
    </main>

    <footer>
        <p>Stronę wykonał : 00000000000</p>
    </footer>

    <?php
    $db->close();
    ?>
</body>
</html>
