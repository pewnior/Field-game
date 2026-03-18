<?php
session_start();

require_once '../helpers.php';
wymagajAdmina('../administrator/admin.php');

$identyfikator_gry = $_SESSION['game_id'];
$polaczenie        = polaczZBaza();


$stmt = $polaczenie->prepare("SELECT id, password FROM checkpoints WHERE game_id = ?");
$stmt->bind_param('i', $identyfikator_gry);
$stmt->execute();
$wynik = $stmt->get_result();
$stmt->close();
$polaczenie->close();

if (!is_dir('codes')) {
    mkdir('codes', 0755, true);
}

require_once 'zapis.php';
$generator_qr  = new QrCode();
$numer_kolejny = 1;

while ($wiersz = $wynik->fetch_assoc()) {
    $haslo_punktu = trim($wiersz['password']);
    $odnosnik     = 'http://field-game.pl/point.php?punkt=' . rawurlencode($haslo_punktu);
    $generator_qr->url($odnosnik);
    $sciezka = 'codes/' . $identyfikator_gry . 'iqr' . $numer_kolejny . '.png';
    $generator_qr->QRCODE(400, $sciezka);
    $numer_kolejny++;
}

$_SESSION['ile_qr'] = $numer_kolejny - 1;
header('Location: pakowanie.php');
exit();
