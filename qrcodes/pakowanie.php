<?php
session_start();
if (!isset($_SESSION['zalogowany_admin']))
{
    header('Location: ../administrator/admin.php');
    exit();
}

$identyfikator_gry = $_SESSION['game_id'];
$liczba_kodow = $_SESSION['ile_qr'];
$katalog_kodow = realpath("codes");

$archiwum = new ZipArchive();
$nazwa_archiwum = 'codes/kody' . $identyfikator_gry . '.zip';
$archiwum->open($nazwa_archiwum, ZipArchive::CREATE | ZipArchive::OVERWRITE);

$pliki = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($katalog_kodow),
    RecursiveIteratorIterator::LEAVES_ONLY
);

for ($i = 1; $i < $liczba_kodow + 1; $i++) {
    $sciezka_pliku = 'codes/' . $identyfikator_gry . 'iqr' . $i . '.png';
    $sciezka_relatywna = substr($sciezka_pliku, strlen($katalog_kodow) + 1);
    $archiwum->addFile($sciezka_pliku, $sciezka_relatywna);
}

$archiwum->close();

$sciezka_archiwum = "codes/kody" . $identyfikator_gry . ".zip";
$nazwa_do_pobrania = "pobieranie" . $identyfikator_gry . ".zip";

header("Content-type:application/zip");
header("Content-Length: " . filesize($sciezka_archiwum));
header('Content-Disposition: attachment; filename="' . $nazwa_do_pobrania . '"');
readfile($sciezka_archiwum);
exit;
?>
