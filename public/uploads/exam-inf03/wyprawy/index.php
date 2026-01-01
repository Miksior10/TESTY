<?php
// Połączenie z bazą danych - używane przez wszystkie skrypty
$polaczenie = mysqli_connect('db', 'root' , 'password', 'wyprawy', 3306);
if (!$polaczenie) {
    die('Błąd połączenia: ' . mysqli_connect_error());
}
mysqli_set_charset($polaczenie, 'utf8');
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biuro turystyczne</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="wczasy.html">Wczasy</a></li>
            <li><a href="wycieczki.html">Wycieczki</a></li>
            <li><a href="allinclusive.html">All inclusive</a></li>
        </ul>
    </nav>
    
    <main>
        <aside>
            <h3>Twój cel wyprawy</h3>
            <form method="POST" action="">
                <label for="miejsce">Miejsce wycieczki</label>
                <select name="miejsce" id="miejsce">
                    <?php
                    // Skrypt 1 - zapytanie 1: nazwy miejscowości posortowane rosnąco
                    $zapytanie1 = "SELECT nazwa FROM miejsca ORDER BY nazwa ASC";
                    $wynik1 = mysqli_query($polaczenie, $zapytanie1);
                    
                    if ($wynik1) {
                        while ($wiersz = mysqli_fetch_assoc($wynik1)) {
                            $selected = (isset($_POST['miejsce']) && $_POST['miejsce'] == $wiersz['nazwa']) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($wiersz['nazwa']) . '" ' . $selected . '>' . htmlspecialchars($wiersz['nazwa']) . '</option>';
                        }
                    }
                    ?>
                </select>
                
                <label for="dorosli">Ile dorosłych?</label>
                <input type="number" name="dorosli" id="dorosli" min="0" value="<?php echo isset($_POST['dorosli']) ? htmlspecialchars($_POST['dorosli']) : '0'; ?>">
                
                <label for="dzieci">Ile dzieci?</label>
                <input type="number" name="dzieci" id="dzieci" min="0" value="<?php echo isset($_POST['dzieci']) ? htmlspecialchars($_POST['dzieci']) : '0'; ?>">
                
                <label for="termin">Termin</label>
                <input type="date" name="termin" id="termin" value="<?php echo isset($_POST['termin']) ? htmlspecialchars($_POST['termin']) : ''; ?>">
                
                <button type="submit" name="symulacja">Symulacja ceny</button>
            </form>
            
            <h4>Koszt wycieczki</h4>
            <?php
            // Skrypt 2 - symulacja ceny
            if (isset($_POST['symulacja']) && isset($_POST['miejsce']) && isset($_POST['dorosli']) && isset($_POST['dzieci']) && isset($_POST['termin'])) {
                $miejsce = $_POST['miejsce'];
                $dorosli = intval($_POST['dorosli']);
                $dzieci = intval($_POST['dzieci']);
                $termin = $_POST['termin'];
                
                // Zapytanie 2 zmodyfikowane - cena dla wybranego miejsca
                $zapytanie2 = "SELECT cena FROM miejsca WHERE nazwa = '" . mysqli_real_escape_string($polaczenie, $miejsce) . "'";
                $wynik2 = mysqli_query($polaczenie, $zapytanie2);
                
                if ($wynik2 && $wiersz2 = mysqli_fetch_assoc($wynik2)) {
                    $cena = floatval($wiersz2['cena']);
                    // Dzieci płacą połowę ceny
                    $wartosc = ($dorosli * $cena) + ($dzieci * $cena * 0.5);
                    
                    echo '<p>W dniu: ' . htmlspecialchars($termin) . '</p>';
                    echo '<p>' . number_format($wartosc, 2, '.', '') . ' złotych</p>';
                }
            }
            ?>
        </aside>
        
        <section>
            <h3>Wycieczki</h3>
            <?php
            // Skrypt 3 - wyświetlanie wycieczek
            // Zapytanie 3: nazwa, cena, link obrazu gdzie link zaczyna się od 0
            $zapytanie3 = "SELECT nazwa, cena, link_obraz FROM miejsca WHERE link_obraz LIKE '0%'";
            $wynik3 = mysqli_query($polaczenie, $zapytanie3);
            
            if ($wynik3) {
                while ($wiersz3 = mysqli_fetch_assoc($wynik3)) {
                    echo '<div class="wycieczka">';
                    echo '<img src="' . htmlspecialchars($wiersz3['link_obraz']) . '" alt="zdjęcie z wycieczki">';
                    echo '<h2>' . htmlspecialchars($wiersz3['nazwa']) . '</h2>';
                    echo '<p>' . number_format(floatval($wiersz3['cena']), 2, '.', '') . '</p>';
                    echo '</div>';
                }
            }
            ?>
        </section>
    </main>
    
    <footer>
        <p>Autor: 000000000</p>
    </footer>
</body>
</html>
<?php
// Zamknięcie połączenia z bazą
mysqli_close($polaczenie);
?>
