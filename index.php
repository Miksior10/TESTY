<?php
// odp nr 1
$klasa = "4D"; 
echo "Odpowiedź 1: " . $klasa . "<br>";

// odp nr 2
$a = 6/3;
$b = 2;
echo "Odpowiedź 2: ";
echo $a%$b;
echo "<br>";

// odp nr 3
$k = 5; // przykładowa wartość początkowa
echo "Odpowiedź 3: ";
echo ++$k; // inkrementacja przed wykonaniem obliczeń
echo "<br>";

// odp nr 4
echo "Odpowiedź 4: <br>";
$liczba = 15;   //przykładowa liczba do sprawdzenia 
echo "Sprawdzana liczba: " . $liczba . "<br>";
if ($liczba % 3 == 0) {
    echo "podana liczba jest iloczynem liczby 3.";
} else{
    echo "Ta liczba nie jest podzielna przez 3.";
}
echo "<br>";

// odp nr 5 
echo "Odpowiedź 5: <br>";
for ($i = 25; $i <= 50; $i++) {
    echo $i . " ";
}
echo "<br>";

//odp nr 6
echo "Odpowiedź 6: <br>";
$imie = "Maks";
$długosc = strlen($imie);
$i = $długosc - 1;

while ($i >= 0) {
    echo $imie[$i];
    $i--;
}
echo "<br>";
?>
