<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
$loggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>My Blog</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<nav>
    <div class="nav-title">My Blog</div>
    <ul>
        <li><button onclick="location.href='index.php'">Strona Główna</button></li>
        <?php if (!$loggedIn) { ?>
            <li><button onclick="location.href='rejestracja.php'">Rejestracja</button></li>
            <li><button onclick="location.href='logowanie.php'">Logowanie</button></li>
        <?php } else { ?>
            <li><button onclick="location.href='dodaj_post.php'">Dodaj post</button></li>
            <li><button onclick="location.href='moje_wpisy.php'">Moje posty</button></li>
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

    $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : 0;

    $queryCondition = $userId ? "AND id_uzytkownika = $userId" : "";

    $min_id_query = "SELECT MIN(id) AS min_id FROM wpisy WHERE 1=1 $queryCondition";
    $result_min_id = $mysqli->query($min_id_query);
    $min_id_row = $result_min_id->fetch_assoc();
    $min_id = isset($min_id_row['min_id']) ? $min_id_row['min_id'] : null;

    $id = isset($_GET['id']) ? (int)$_GET['id'] : $min_id;

    $result = $mysqli->query("SELECT * FROM wpisy WHERE id = $id $queryCondition");
    $row = $result->fetch_assoc();

    echo "<article>";
    echo "<h2>" . $row['tytul'] . "</h2>";
    echo "<p>" . $row['tresc'] . "</p>";
    if (!empty($row['obraz'])) {
        echo "<img src=\"" . $row['obraz'] . "\" alt=\"Image for " . $row['tytul'] . "\" style=\"width: 10vw; height: 10vh;\">";
    }
    echo "<p class=\"date\">Data dodania: " . $row['data_publikacji'] . "</p>";
        echo "<button onclick=\"location.href='edycja_posta.php?id=$id'\">Edytuj post</button>";
    echo "<button onclick=\"location.href='komentarze.php?id=$id'\">Komentarze</button>";
    echo "</article>";

    $max_id_query = "SELECT MAX(id) AS max_id FROM wpisy WHERE 1=1 $queryCondition";
    $result_max_id = $mysqli->query($max_id_query);
    $max_id_row = $result_max_id->fetch_assoc();
    $max_id = isset($max_id_row['max_id']) ? $max_id_row['max_id'] : null;

    $prev_id = $id - 1;
    $next_id = $id + 1;

    $prev_available = $id > $min_id;
    $next_available = $id < $max_id;

    if ($prev_available) {
        echo "<button onclick=\"location.href='moje_wpisy.php?id=$prev_id'\">Poprzedni wpis</button>";
    }
    if ($next_available) {
        echo "<button onclick=\"location.href='moje_wpisy.php?id=$next_id'\">Następny wpis</button>";
    }
    echo "</div>";
    ?>
</main>
</body>
</html>
