<?php
session_start();

require_once '../../helpers.php';
wymagajAdmina('../admin.php');

$identyfikator_gry = $_SESSION['game_id'];
$nazwa_punktu = trim(str_replace(' ', '', $_POST['nazwa'] ?? ''));

if (empty($nazwa_punktu)) {
    header('Location: dod_checkpoint.php');
    exit();
}

$polaczenie = polaczZBaza();
$haslo = generujHasloPunktu($polaczenie);

$stmt = $polaczenie->prepare("INSERT INTO checkpoints (name, password, points, game_id) VALUES (?, ?, 2, ?)");
$stmt->bind_param('ssi', $nazwa_punktu, $haslo, $identyfikator_gry);
$stmt->execute();
$stmt->close();
$polaczenie->close();

header('Location: zakonczenie.php');
exit();
