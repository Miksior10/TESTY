<?php
$mysqli = @new mysqli('db', 'root', 'password', 'kalendarz', 3306);
if ($mysqli->connect_errno) {
    die('Błąd połączenia z bazą.');
}
$mysqli->set_charset('utf8');

$dzisiaj = date('m-d');
$zapytanie1 = "Select imiona From imieniny Where data = '$dzisiaj'";
$wynik1 = mysqli_query($mysqli, $zapytanie1);
if ($wynik1 && mysqli_num_rows($wynik1) > 0) {
    $wiersz = mysqli_fetch_assoc($wynik1);
    $imieniny_dzisiaj = $wiersz['imiona'];
}

$dni_tygodnia = ['niedziela', 'poniedziałek', 'wtorek', 'środa', 'czwartek', 'piątek', 'sobota'];
$dzien_tygodnia = $dni_tygodnia[date('w')];
$data_dzisiaj = date('d-m-Y');


$imieniny_formularz = '';
$data_formularz = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'])) {
    $data_input = $_POST['data'];
    $data_formularz = $data_input;

    $czesci = explode('-', $data_input);
    $data_mmdd = $czesci[1] . '-' . $czesci[2];

    $zapytanie2 = "SELECT imiona FROM imieniny WHERE data = '$data_mmdd'";
    $wynik2 = mysqli_query($mysqli, $zapytanie2);
    if ($wynik2 && mysqli_num_rows($wynik2) > 0) {
        $wiersz2 = mysqli_fetch_assoc($wynik2);
        $imieniny_formularz = $wiersz2['imiona'];
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalendarz</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
    <header>
        <h1>Dni, miesiące, lata...</h1>
    </header>
    
    <section class="napis">
        <p>Dzisiaj jest <?php echo $dzien_tygodnia; ?>, <?php echo $data_dzisiaj; ?>, imieniny: <?php echo $imieniny_dzisiaj; ?></p>
    </section>
    
    <main class="tresci">
        <section class="blok-lewy">
            <table>
                <thead>
                    <tr>
                        <th>liczba dni</th>
                        <th>miesiąc</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="7">31</td>
                        <td>styczeń</td>
                    </tr>
                    <tr>
                        <td>marzec</td>
                    </tr>
                    <tr>
                        <td>maj</td>
                    </tr>
                    <tr>
                        <td>lipiec</td>
                    </tr>
                    <tr>
                        <td>sierpień</td>
                    </tr>
                    <tr>
                        <td>październik</td>
                    </tr>
                    <tr>
                        <td>grudzień</td>
                    </tr>
                    <tr>
                        <td rowspan="4">30</td>
                        <td>kwiecień</td>
                    </tr>
                    <tr>
                        <td>czerwiec</td>
                    </tr>
                    <tr>
                        <td>wrzesień</td>
                    </tr>
                    <tr>
                        <td>listopad</td>
                    </tr>
                    <tr>
                        <td>28 lub 29</td>
                        <td>luty</td>
                    </tr>
                </tbody>
            </table>
        </section>
        
        <section class="blok-srodkowy">
            <h2>Sprawdź kto ma urodziny</h2>
            <form method="POST" action="">
                <input type="date" name="data" min="2024-01-01" max="2024-12-31" required>
                <button type="submit">wyślij</button>
            </form>
            <?php if ($imieniny_formularz): ?>
                <p>Dnia <?php echo $data_formularz; ?> są imieniny: <?php echo $imieniny_formularz; ?></p>
            <?php endif; ?>
        </section>
        
        <section class="blok-prawy">
            <a href="https://pl.wikipedia.org/wiki/Kalendarz_Majów" target="_blank">
                <img src="kalendarz.gif" alt="Kalendarz Majów">
            </a>
            <h2>Rodzaje kalendarzy</h2>
            <ol>
                <li>słoneczny
                    <ul>
                        <li>kalendarz Majów</li>
                        <li>juliański</li>
                        <li>gregoriański</li>
                    </ul>
                </li>
                <li>księżycowy
                    <ul>
                        <li>starogrecki</li>
                        <li>babiloński</li>
                    </ul>
                </li>
            </ol>
        </section>
    </main>
    
    <footer>
        <p>Stronę opracował(a): 00000000000</p>
    </footer>
</body>
</html>
<?php
mysqli_close($mysqli);
?>