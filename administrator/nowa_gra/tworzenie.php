<?php
session_start();

require_once '../../helpers.php';
wymagajAdmina('../admin.php');

$identyfikator_gry = $_SESSION['game_id'];
$polaczenie = polaczZBaza();

$stmt = $polaczenie->prepare("DELETE FROM players WHERE game_id = ?");
$stmt->bind_param('i', $identyfikator_gry);
$stmt->execute();
$stmt->close();

$stmt = $polaczenie->prepare("DELETE FROM checkpoints WHERE game_id = ?");
$stmt->bind_param('i', $identyfikator_gry);
$stmt->execute();
$stmt->close();

$polaczenie->close();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Field game</title>
<link rel="stylesheet" type="text/css" href="../../style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
    <div class="text">
        <b>Tworzenie gry krok 1 z 4</b><br/><br/>
        <form action="new_game_names_players.php" method="post">
            Ile punktow:
            <input type="number" class="logowanie" name="ile_punktow" min="1" max="40" value="1"><br/><br/>
            Ile graczy:
            <input type="number" class="logowanie" name="ile_graczy" min="1" max="40" value="1"><br/><br/>
            <input type="submit" class="logowanie" value="Dalej">
        </form>
    </div></div>
</body>
</html>
