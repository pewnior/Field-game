<?php
session_start();

require_once '../../helpers.php';
wymagajAdmina('../admin.php');

$identyfikator_gry    = $_SESSION['game_id'];
$identyfikator_punktu = pobierzIdZGet('nazwa');

$polaczenie = polaczZBaza();

$stmt = $polaczenie->prepare("DELETE FROM checkpoints WHERE id = ? AND game_id = ?");
$stmt->bind_param('ii', $identyfikator_punktu, $identyfikator_gry);
$stmt->execute();
$stmt->close();

$stmt = $polaczenie->prepare("DELETE FROM logs WHERE checkpoint_id = ? AND game_id = ?");
$stmt->bind_param('ii', $identyfikator_punktu, $identyfikator_gry);
$stmt->execute();
$stmt->close();

$polaczenie->close();
header('Location: zakonczenie.php');
exit();
