<?php
session_start();

require_once '../helpers.php';
wymagajAdmina('../administrator/admin.php');

$dane_admina      = $_SESSION['uzytkownik'];
$identyfikator_gry = $_SESSION['game_id'];

$polaczenie = polaczZBaza();

$stmt = $polaczenie->prepare("SELECT COUNT(*) AS liczba FROM checkpoints WHERE game_id = ?");
$stmt->bind_param('i', $identyfikator_gry);
$stmt->execute();
$liczba_punktow = $stmt->get_result()->fetch_assoc()['liczba'];
$stmt->close();

$stmt = $polaczenie->prepare("SELECT COUNT(*) AS liczba FROM players WHERE game_id = ?");
$stmt->bind_param('i', $identyfikator_gry);
$stmt->execute();
$liczba_graczy = $stmt->get_result()->fetch_assoc()['liczba'];
$stmt->close();

$polaczenie->close();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Field game</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
    <div class="text">
        <b class="name">Field game - panel administratora</b><br/>
        <p>Witaj <?= htmlspecialchars($dane_admina['name']) ?>! [ <a href="../logout.php">Wyloguj się!</a> ]</p>
        <br/>Aktualna gra:<br/>
        Ilość graczy: <?= $liczba_graczy ?><br/>
        Ilość punktów: <?= $liczba_punktow ?><br/>

        <br/><a href="../qrcodes/sprzatanie.php">Pobierz kody QR</a>
        <br/><a href="hasla.php">Pobierz hasła graczy</a>
        <br/><a href="statystyki.php">Pokaż statystki</a>
        <br/><a href="logi.php">Pokaż logi graczy</a>
        <br/><a href="linki.php">Linki do logowania na punkty</a>
        <br/><br/><a href="dodawanie/dod_players.php">Dodaj gracza</a>
        <br/><a href="dodawanie/dod_checkpoint.php">Dodaj punkt</a>
        <br/><a href="usuwanie/usu_checkpoint.php">Usuń punkt</a>
        <br/><a href="usuwanie/usu_player.php">Usuń gracza</a>
        <br/><br/><a href="nowa_gra/nowa.php">Stwórz nowa grę</a>
        <br/><br/>Kontakt:<br/>field.game.app@gmail.com
    </div>
    </div>
</body>
</html>
