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
    <div class="nav-title">Kontakt</div>
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

    <div class="kontakt">
        <h1>Kontakt</h1>
        <p>Imię i nazwisko: Mateusz Wiśniewski</p>
        <p>Numer indeksu: s24893</p>
        <p>Email: s24893@pjwstk.edu.pl</p>
    </div>
</main>
</body>
</html>