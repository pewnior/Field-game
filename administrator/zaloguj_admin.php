<?php
session_start();

require_once '../helpers.php';

$polaczenie = polaczZBaza();

$login = htmlentities($_POST['login'] ?? '', ENT_QUOTES, 'UTF-8');
$haslo = $_POST['haslo'] ?? '';

$stmt = $polaczenie->prepare("SELECT id, name, game_id, password FROM administrators WHERE name = ?");
$stmt->bind_param('s', $login);
$stmt->execute();
$wynik = $stmt->get_result();

if ($wynik->num_rows > 0) {
    $wiersz = $wynik->fetch_assoc();
    if (password_verify($haslo, $wiersz['password'])) {
        $_SESSION['zalogowany_admin'] = true;
        $_SESSION['game_id']          = $wiersz['game_id'];
        $_SESSION['uzytkownik']       = $wiersz;
        unset($_SESSION['blad']);
        $stmt->close();
        $polaczenie->close();
        header('Location: admin_panel.php');
        exit();
    }
}

$stmt->close();
$polaczenie->close();
$_SESSION['blad'] = '<span style="color:red">Nieprawidlowy login lub haslo!</span>';
header('Location: ../index.php');
exit();
