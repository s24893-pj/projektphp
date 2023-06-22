<?php
session_start();

$loggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'];

?>


<!DOCTYPE html>
<html>
<head>
    <title>Blog</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<nav>
    <div class="nav-title">Strona Główna</div>
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

    $result = $mysqli->query("SELECT MIN(id) AS min_id, MAX(id) AS max_id FROM wpisy");
    $row = $result->fetch_assoc();
    $minId = $row['min_id'];
    $maxId = $row['max_id'];

    $id = isset($_GET['id']) ? (int)$_GET['id'] : $minId;

    $id = max($minId, min($id, $maxId));

    $result = $mysqli->query("SELECT * FROM wpisy WHERE id = {$id}");
    $row = $result->fetch_assoc();

    if (!$row && $id < $maxId) {
        $next_id = $id + 1;
        header("Location: index.php?id=$next_id");
        exit();
    }
    if (!$row && $id > $minId) {
        $prev_id = $id - 1;
        header("Location: index.php?id=$prev_id");
        exit();
    }

    echo "<article>";
    echo "<h2>" . $row['tytul'] . "</h2>";
    echo "<p>" . $row['tresc'] . "</p>";
    if (!empty($row['obraz'])) {
        echo "<img src=\"" . $row['obraz'] . "\" alt=\"Image for " . $row['tytul'] . "\" style=\"width: 10vw; height: 10vh;\">";
    }
    echo "<p class=\"date\">Data dodania: " . $row['data_publikacji'] . "</p>";
    echo "<button onclick=\"location.href='komentarze.php?id=$id'\">Komentarze</button>";
    echo "</article>";

    $prev_id = $id - 1;
    $next_id = $id + 1;

    if ($prev_id >= $minId) {
        echo "<button onclick=\"location.href='index.php?id=$prev_id'\">Poprzedni wpis</button>";
    }
    if ($next_id <= $maxId) {
        echo "<button onclick=\"location.href='index.php?id=$next_id'\">Następny wpis</button>";
    }
    echo "</div>";
    ?>
</main>
</body>
</html>



