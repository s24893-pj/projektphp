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
    <div class="nav-title">Dodaj Post</div>
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


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $image = $_POST['image'];


        $stmt = $mysqli->prepare("INSERT INTO wpisy (tytul, tresc, obraz, id_uzytkownika) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $content, $image, $userId);


        if ($stmt->execute()) {
            echo "Nowy wpis został dodany!";
            header("Location: index.php");
            exit;
        } else {
            echo "Błąd: " . $stmt->error;
        }


        $stmt->close();
    }
    ?>

    <h2>Dodaj nowy wpis</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Tytuł: <input type="text" name="title">
        <br><br>
        Treść: <textarea name="content" rows="5" cols="40"></textarea>
        <br><br>
        Obraz (URL): <input type="text" name="image">
        <br><br>
        <input type="submit" name="submit" value="Dodaj">
    </form>

</main>
</body>
</html>
