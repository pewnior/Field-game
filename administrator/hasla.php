<?php
session_start();

require_once '../helpers.php';
wymagajAdmina('admin.php');

$identyfikator_gry = $_SESSION['game_id'];
$polaczenie = polaczZBaza();

$stmt = $polaczenie->prepare("SELECT name, password FROM players WHERE game_id = ?");
$stmt->bind_param('i', $identyfikator_gry);
$stmt->execute();
$wynik = $stmt->get_result();
$stmt->close();
$polaczenie->close();

$nazwa_pliku  = "hasla/hasla-graczy{$identyfikator_gry}.txt";
$nazwa_zip    = "hasla/hasla{$identyfikator_gry}.zip";

$plik = fopen($nazwa_pliku, 'w');
while ($wiersz = $wynik->fetch_assoc()) {
    fputs($plik, $wiersz['name'] . ' ' . $wiersz['password'] . "\r\n");
}
fclose($plik);

$katalog_hasel    = realpath('hasla');
$archiwum         = new ZipArchive();
$archiwum->open($nazwa_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE);
$sciezka_relatywna = substr($nazwa_pliku, strlen($katalog_hasel) + 1);
$archiwum->addFile($nazwa_pliku, $sciezka_relatywna);
$archiwum->close();

header('Content-type: application/zip');
header('Content-Length: ' . filesize($nazwa_zip));
header('Content-Disposition: attachment; filename="hasla-graczy' . $identyfikator_gry . '.zip"');
readfile($nazwa_zip);

unlink($nazwa_pliku);
unlink($nazwa_zip);
exit();
