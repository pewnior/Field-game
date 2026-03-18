<?php
session_start();

if (!isset($_POST['haslo'])) {
    header('Location: index.php');
    exit();
}

require_once 'helpers.php';

$polaczenie = polaczZBaza();

$identyfikator_punktu_z_spacja = ' ' . $_SESSION['punkt'];

// Pobierz dane checkpointu
$stmt = $polaczenie->prepare("SELECT id, game_id, points FROM checkpoints WHERE password = ?");
$stmt->bind_param('s', $identyfikator_punktu_z_spacja);
$stmt->execute();
$wynik_punktu = $stmt->get_result();

if ($wynik_punktu->num_rows === 0) {
    $_SESSION['blad'] = '<span style="color:red">Nieprawidłowy adres. Zeskanuj ponownie kod QR</span>';
    $stmt->close();
    $polaczenie->close();
    header('Location: index.php');
    exit();
}

$dane_punktu = $wynik_punktu->fetch_assoc();
$id_checkpointu = $dane_punktu['id'];
$identyfikator_gry_checkpointu = $dane_punktu['game_id'];
$_SESSION['punkt'] = $id_checkpointu;
$stmt->close();

// Pobierz gracza po hasle
$haslo_gracza = ' ' . htmlentities($_POST['haslo'], ENT_QUOTES, 'UTF-8');

$stmt = $polaczenie->prepare("SELECT id, name, points, game_id FROM players WHERE password = ?");
$stmt->bind_param('s', $haslo_gracza);
$stmt->execute();
$wynik_gracza = $stmt->get_result();

if ($wynik_gracza->num_rows === 0) {
    $_SESSION['blad'] = '<span style="color:red">Nieprawidlowy login lub haslo!</span>';
    $czysty_identyfikator = str_replace(' ', '', $_SESSION['punkt']);
    $stmt->close();
    $polaczenie->close();
    header('Location: point.php?punkt=' . $czysty_identyfikator);
    exit();
}

$dane_gracza = $wynik_gracza->fetch_assoc();
$identyfikator_gracza = $dane_gracza['id'];
$identyfikator_gry_gracza = $dane_gracza['game_id'];
$stmt->close();

// Sprawdz czy gracz juz odwiedzil ten punkt
$stmt = $polaczenie->prepare("SELECT id FROM logs WHERE player_id = ? AND checkpoint_id = ?");
$stmt->bind_param('ii', $identyfikator_gracza, $id_checkpointu);
$stmt->execute();
$stmt->store_result();
$liczba_logow = $stmt->num_rows;
$stmt->close();

if ($liczba_logow > 0) {
    $_SESSION['blad'] = '<span style="color:red">Odwiedzony punkt</span>';
    $polaczenie->close();
    header('Location: index.php');
    exit();
}

if ($identyfikator_gry_checkpointu !== $identyfikator_gry_gracza) {
    $_SESSION['blad'] = '<span style="color:red">Nieprawidłowy adres. Zeskanuj ponownie kod QR</span>';
    $polaczenie->close();
    header('Location: index.php');
    exit();
}

$_SESSION['zalogowany']  = true;
$_SESSION['id']          = $dane_gracza['id'];
$_SESSION['nazwa']       = $dane_gracza['name'];
$_SESSION['punkty']      = $dane_gracza['points'];
$_SESSION['dod']         = 0;
$_SESSION['game_id']     = $identyfikator_gry_checkpointu;
unset($_SESSION['blad']);

$polaczenie->close();
header('Location: gra.php');
exit();
