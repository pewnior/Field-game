<?php
session_start();

require_once '../../helpers.php';
wymagajAdmina('../admin.php');

$identyfikator_gry    = $_SESSION['game_id'];
$identyfikator_gracza = pobierzIdZGet('nazwa');

$polaczenie = polaczZBaza();

$stmt = $polaczenie->prepare("DELETE FROM players WHERE id = ? AND game_id = ?");
$stmt->bind_param('ii', $identyfikator_gracza, $identyfikator_gry);
$stmt->execute();
$stmt->close();

$stmt = $polaczenie->prepare("DELETE FROM logs WHERE player_id = ? AND game_id = ?");
$stmt->bind_param('ii', $identyfikator_gracza, $identyfikator_gry);
$stmt->execute();
$stmt->close();

$polaczenie->close();
header('Location: zakonczenie.php');
exit();
