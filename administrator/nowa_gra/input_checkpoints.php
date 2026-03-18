<?php
session_start();

require_once '../../helpers.php';
wymagajAdmina('../admin.php');

$identyfikator_gry = $_SESSION['game_id'];
$liczba_punktow    = (int)$_SESSION['ile_p'];
$polaczenie        = polaczZBaza();

for ($i = 0; $i < $liczba_punktow; $i++) {
    $nazwa_punktu = trim(str_replace(' ', '', $_POST[$i + 1] ?? ''));
    if (empty($nazwa_punktu)) continue;
    $haslo = generujHasloPunktu($polaczenie);
    $stmt  = $polaczenie->prepare("INSERT INTO checkpoints (name, password, points, game_id) VALUES (?, ?, 2, ?)");
    $stmt->bind_param('ssi', $nazwa_punktu, $haslo, $identyfikator_gry);
    $stmt->execute();
    $stmt->close();
}

// Wyczysc logi starej gry
$stmt = $polaczenie->prepare("DELETE FROM logs WHERE game_id = ?");
$stmt->bind_param('i', $identyfikator_gry);
$stmt->execute();
$stmt->close();

$polaczenie->close();
header('Location: zakonczenie.php');
exit();
