<?php
session_start();

require_once '../../helpers.php';
wymagajAdmina('../admin.php');

$identyfikator_gry = $_SESSION['game_id'];
$liczba_graczy     = (int)$_SESSION['ile_g'];
$polaczenie        = polaczZBaza();

for ($i = 0; $i < $liczba_graczy; $i++) {
    $nazwa_gracza = trim(str_replace(' ', '', $_POST[$i + 1] ?? ''));
    if (empty($nazwa_gracza)) continue;
    $haslo = generujHasloGracza($polaczenie);
    $stmt  = $polaczenie->prepare("INSERT INTO players (name, password, points, game_id) VALUES (?, ?, 0, ?)");
    $stmt->bind_param('ssi', $nazwa_gracza, $haslo, $identyfikator_gry);
    $stmt->execute();
    $stmt->close();
}

$polaczenie->close();
header('Location: new_game_names_checkpoints.php');
exit();
