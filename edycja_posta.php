<?php
session_start();

$loggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'];

if (!$loggedIn) {
    header("Location: logowanie.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $servername = "sql312.infinityfree.com";
    $username = "if0_34481846";
    $password = "TYL8Ktg5jfm";
    $dbname = "if0_34481846_blog";

    $mysqli = new mysqli($servername, $username, $password, $dbname);

    if ($mysqli->connect_error) {
        die("Błąd połączenia: " . $mysqli->connect_error);
    }

    // Pobranie posta o podanym ID
    $result = $mysqli->query("SELECT * FROM wpisy WHERE id = $id");
    $row = $result->fetch_assoc();

    if ($result->num_rows === 0) {
        echo "Wpis nie istnieje.";
    } else {

        $userId = $_SESSION['userId'];
        if ($userId != $row['id_uzytkownika']) {
            echo "Nie masz uprawnień do edycji tego posta.";
        } else {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $tytul = $_POST['tytul'];
                $tresc = $_POST['tresc'];


                $updateQuery = "UPDATE wpisy SET tytul = '$tytul', tresc = '$tresc' WHERE id = $id";
                $updateResult = $mysqli->query($updateQuery);

                if ($updateResult) {
                    echo "Post został zaktualizowany.";
                    header("Location: index.php");
                    exit;
                } else {
                    echo "Wystąpił błąd podczas aktualizacji posta.";
                }
            } else {

                echo "<h2>Edytuj post</h2>";
                echo "<form method=\"POST\">";
                echo "<label>Tytuł:</label><br>";
                echo "<input type=\"text\" name=\"tytul\" value=\"" . $row['tytul'] . "\"><br>";
                echo "<label>Treść:</label><br>";
                echo "<textarea name=\"tresc\">" . $row['tresc'] . "</textarea><br>";
                echo "<input type=\"submit\" value=\"Zapisz zmiany\">";
                echo "</form>";
            }
        }
    }
} else {
    echo "Nieprawidłowe ID posta.";
}
?>
