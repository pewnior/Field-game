<?php
session_start();

require_once '../../helpers.php';
wymagajAdmina('../admin.php');

$identyfikator_gry = $_SESSION['game_id'];
$nazwa_gracza = trim($_POST['nazwa'] ?? '');

if (empty($nazwa_gracza)) {
    header('Location: dod_players.php');
    exit();
}

$polaczenie = polaczZBaza();
$haslo = generujHasloGracza($polaczenie);

$stmt = $polaczenie->prepare("INSERT INTO players (name, password, points, game_id) VALUES (?, ?, 0, ?)");
$stmt->bind_param('ssi', $nazwa_gracza, $haslo, $identyfikator_gry);
$stmt->execute();
$stmt->close();
$polaczenie->close();

header('Location: zakonczenie.php');
exit();
