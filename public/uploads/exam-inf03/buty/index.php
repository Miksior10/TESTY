<?php
$mysqli = @new mysqli('db', 'root', 'password', 'obuwie', 3306);
if ($mysqli->connect_errno) {
    die('Błąd połączenia z baza danych');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obuwie</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
   <header>
        <h1>Obuwie męskie</h1>
   </header> 

   <main>
        <form action="zamow.php" method="post">
            <label for="model">Model: </label>
            <select name="model" id="model" class="kontrolki">
                <?php
                $sql = "SELECT model FROM produkt;";
                $result = $mysqli->query($sql);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['model']) . "'>" . htmlspecialchars($row['model']) . "</option>"; 
                    }
                    $result->free();
                }
                ?>
            </select>
            <label for="rozmiar">Rozmiar: </label>
            <select name="rozmiar" id="rozmiar" class="kontrolki">
                <option value="40">40</option>
                <option value="41">41</option>
                <option value="42">42</option>
                <option value="43">43</option>
            </select>
            <label for="liczba">Liczba par: </label>
            <input type="number" name="liczba" id="liczba" class="kontrolki">
            <input type="submit" value="Zamów" class="kontrolki">
        </form>
        <?php
        $sql = "SELECT model, nazwa, cena, nazwa_pliku FROM buty JOIN produkt USING(model);";
        $result = $mysqli->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='buty'>";
                echo "<img src='" . htmlspecialchars($row['nazwa_pliku']) . "' alt='but męski'>";
                echo "<h2>" . htmlspecialchars($row['nazwa']) . "</h2>";
                echo "<h5>Model: " . htmlspecialchars($row['model']) . "</h5>";
                echo "<h4>Cena: " . number_format($row['cena'], 2, '.', '') . " zł</h4>";
                echo "</div>";
            }
            $result->free();
        } else {
            echo "<p>Brak produktów do wyświetlenia.</p>";
            if ($mysqli->error) {
                echo "<p>Błąd: " . htmlspecialchars($mysqli->error) . "</p>";
            }
        }
        ?>
   </main>

   <footer>
    <p>Autor Strony: JA</p>
   </footer>
</body>
</html>

<?php
    $mysqli->close();
?>