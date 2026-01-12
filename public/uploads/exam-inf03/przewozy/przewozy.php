<?php
$mysqli = @new mysqli('db', 'root', 'password', 'przewozy', 3306);
if ($mysqli->connect_errno) {
    die('Błąd połączenia z bazą.');
}

// Skrypt 1 - obsługa usuwania (przed jakimkolwiek outputem)
if (isset($_GET['usun'])) {
    $stmt = $mysqli->prepare('DELETE FROM zadania WHERE id_zadania = ?');
    $stmt->bind_param('i', $_GET['usun']);
    $stmt->execute();
    $stmt->close();
    header('Location: przewozy.php');
    exit;
}

// Skrypt 2 - obsługa dodawania (przed jakimkolwiek outputem)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $zadanie = trim($_POST['zadanie'] ?? '');
    $data = $_POST['data'] ?? '';
    if ($zadanie !== '' && $data !== '') {
        if (!$mysqli->connect_errno) {
            $stmt = $mysqli->prepare('INSERT INTO zadania VALUES (NULL, ?, ?, 1)');
            $stmt->bind_param('ss', $zadanie, $data);
            $stmt->execute();
            $stmt->close();
            header('Location: przewozy.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Firma Przewozowa</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <header>
            <h1>Firma przewozowa Półdarmo</h1>
        </header>

        <nav>
            <a href="kw1.png">kwerenda1</a>
            <a href="kw2.png">kwerenda2</a>
            <a href="kw3.png">kwerenda3</a>
            <a href="kw4.png">kwerenda4</a>
        </nav>

        <main>
            <section id="lewy">
                <h2>Zadania do wykonania</h2>
                <table>
                    <tr>
                        <th>Zadanie do wykonania</th>
                        <th>Data realizacji</th>
                        <th>Akcja</th>
                    </tr>
                    <?php
                        $result = $mysqli->query('SELECT id_zadania, zadanie, data FROM zadania');
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['zadanie'], ENT_QUOTES, 'UTF-8') . '</td>';
                                echo '<td>' . htmlspecialchars($row['data'], ENT_QUOTES, 'UTF-8') . '</td>';
                                echo '<td><a href="?usun=' . (int)$row['id_zadania'] . '">Usuń</a></td>';
                                echo '</tr>';
                            }
                            $result->free();
                        }
                    ?>
                </table>
                <form action="przewozy.php" method="post">
                    <label for="zadanie">Zadanie do wykonania: </label>
                    <input type="text" name="zadanie" id="zadanie"><br>
                    <label for="data">Data realizacji: </label>
                    <input type="date" name="data" id="data">
                    <input type="submit" value="Dodaj">
                </form>
            </section>

            <section id="prawy">
                <img src="auto.png" alt="auto firmowe">
                <h3>Nasza specjalność</h3>
                <ul>
                    <li>Przeprowadzki</li>
                    <li>Przewóz mebli</li>
                    <li>Przesyłki gabarytowe</li>
                    <li>Wynajem pojazdów</li>
                    <li>Zakupy towarów</li>
                </ul>
            </section>
        </main>

        <footer>
            <p>Stronę wykonał: <a href="https://ee-informatyk.pl/" target="_blank" style="text-decoration: none; color: unset;">EE-Informatyk.pl</a></p>
        </footer>
    </body>
</html>
<?php
$mysqli->close();
?>
