<?php
session_start();

$loggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'];

?>


<!DOCTYPE html>
<html>
<head>
    <title>My Blog - Komentarze</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<nav>
    <div class="nav-title">Komentarze</div>
    <ul>
        <li><button onclick="location.href='index.php'">Strona Główna</button></li>
        <?php if (!$loggedIn) { ?>
            <li><button onclick="location.href='rejestracja.php'">Rejestracja</button></li>
            <li><button onclick="location.href='logowanie.php'">Logowanie</button></li>
        <?php } else { ?>
            <li><button onclick="location.href='dodaj_post.php'">Dodaj post</button></li>
            <li><button onclick="location.href='moje_wpisy.php'">Moje wpisy</button></li>
            <li><button onclick="location.href='wyloguj.php'">Wyloguj</button></li>
        <?php } ?>
        <li><button onclick="location.href='kontakt.php'">Kontakt</button></li>
    </ul>
</nav>
<main>
    <?php
    $servername = "sql312.infinityfree.com";
    $username = "if0_34481846";
    $password = "TYL8Ktg5jfm";
    $dbname = "if0_34481846_blog";

    $mysqli = new mysqli($servername, $username, $password, $dbname);

    if ($mysqli->connect_error) {
        die("Błąd połączenia: " . $mysqli->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
        $postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $comment = $_POST['comment'];

        $stmt = $mysqli->prepare("INSERT INTO komentarze (id_wpisu, tresc, nazwa_uzytkownika) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $postId, $comment, $_SESSION['username']);
        $stmt->execute();
        $stmt->close();
    }

    $postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    $result = $mysqli->query("SELECT * FROM wpisy WHERE id = {$postId}");
    $row = $result->fetch_assoc();

    echo "<article>";
    echo "<h2>" . $row['tytul'] . "</h2>";
    echo "<p>" . $row['tresc'] . "</p>";
    echo "<p class=\"date\">Data dodania: " . $row['data_publikacji'] . "</p>";
    echo "</article>";

    if ($loggedIn) {
        echo "<div class=\"komentarze\">";
        echo "<form method=\"post\">";
        echo "<textarea name=\"comment\" rows=\"4\" cols=\"50\" placeholder=\"Dodaj komentarz\"></textarea>";
        echo "<br>";
        echo "<button type=\"submit\">Dodaj komentarz</button>";
        echo "</form>";
        echo "</div>";
    } else {
        echo "<p>Zaloguj się, aby dodać komentarz.</p>";
    }

    $commentsResult = $mysqli->query("SELECT * FROM komentarze WHERE id_wpisu = {$postId}");
    $comments = $commentsResult->fetch_all(MYSQLI_ASSOC);

    echo "<h3>Komentarze:</h3>";
    echo "<div class=\"komentarze\">";
    foreach ($comments as $comment) {
        echo "<div class=\"comment\">";
        echo "<p><strong>Autor: </strong>" . $comment['nazwa_uzytkownika'] . "</p>";
        echo "<p>" . $comment['tresc'] . "</p>";
        echo "</div>";
    }
    echo "</div>";

    $mysqli->close();
    ?>
</main>
</body>
</html>
