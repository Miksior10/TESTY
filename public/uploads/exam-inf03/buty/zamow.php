<?php
$mysqli = @new mysqli ('db', 'root', 'password', 'obuwie', 3306);
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
        <h2>Zamówienia</h2>
        <?php
           if(isset($_POST['model'])) {
                $model = $_POST['model'];
            	$rozmiar = $_POST['rozmiar'];
            	$liczba = $_POST['liczba'];
            	
            	$sql = "SELECT nazwa, cena, kolor, kod_produktu, material, nazwa_pliku FROM buty JOIN produkt USING(model) WHERE model = '$model';";
            	$result = $mysqli -> query($sql);
            	$row = $result -> fetch_assoc();
                 echo "<div class='buty'>";
            	        echo "<img src='" . $row['nazwa_pliku'] . "' alt='but męski'>";
            	        echo "<h2>" . $row['nazwa'] . "</h2>";
            	
            	        $cena = $row['cena'] * $liczba;
                         echo "<p>cena za $liczba par: $cena zł</p>";
                        echo "<p>Szczegóły produktu: ".$row['kolor'].", ".$row['material']."</p>";
                        echo "<p>Rozmiar: " . $rozmiar . "</p>";
            }               
        ?>
        <a href="index.php">Strona głowna</a>
    </main>

    <footer>

    </footer>
</body>
</html>