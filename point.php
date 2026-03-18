<?php
session_start();

require_once 'helpers.php';

$polaczenie = polaczZBaza();

$haslo_punktu = ' ' . $_GET['punkt'];
$stmt = $polaczenie->prepare("SELECT id FROM checkpoints WHERE password = ?");
$stmt->bind_param('s', $haslo_punktu);
$stmt->execute();
$stmt->store_result();
$liczba_wynikow = $stmt->num_rows;
$stmt->close();
$polaczenie->close();

if ($liczba_wynikow === 0) {
    $_SESSION['blad'] = '<span style="color:red">Nieprawidlowy adres. Zeskanuj ponownie kod QR</span>';
    header('Location: index.php');
    exit();
}

$_SESSION['punkt'] = $_GET['punkt'];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
    <title>Field game</title>
</head>
<body>
    <div class="container">
        <div class="text" id="point">
            <b class="name">Field game</b>
            <form action="zaloguj.php" method="post">
                <br/>
                <input class="logowanie" type="password" id="pass" name="haslo" placeholder="Hasło"/><br/><br/>
                <input class="logowanie" id="login" type="submit" value="Zaloguj się"/><br/>
            </form>
            <?php if (isset($_SESSION['blad'])) echo $_SESSION['blad']; ?>
            <br/><br/>Autor: Maciej Pewniak 2026
        </div>
    </div>
</body>
</html>
