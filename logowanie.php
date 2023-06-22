<?php
session_start();


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
session_start();
$servername = "sql312.infinityfree.com";
$username = "if0_34481846";
$password = "TYL8Ktg5jfm";
$dbname = "if0_34481846_blog";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);


    $sql = "SELECT * FROM uzytkownik WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();
        if ($password == $row['password']) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['userId'] = $row['ID'];
            header("Location: index.php");
            exit;
        } else {
            echo "Niepoprawne hasło";
        }
    } else {
        echo "Nie ma takiego użytkownika";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<body>
<h2>Login</h2>
<form action="" method="post">
    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username" required><br>
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br>
    <input type="submit" value="Submit">
</form>
</body>
</html>
