<?php
function usunKatalog($sciezka_katalogu) {
    if (!file_exists($sciezka_katalogu))
        return false;

    if (is_dir($sciezka_katalogu))
        $uchwyt_katalogu = opendir($sciezka_katalogu);

    if (!$uchwyt_katalogu)
        return false;

    while ($nazwa_pliku = readdir($uchwyt_katalogu)) {
        if ($nazwa_pliku != '.' && $nazwa_pliku != '..') {
            if (!is_dir($sciezka_katalogu . "/" . $nazwa_pliku))
                unlink($sciezka_katalogu . "/" . $nazwa_pliku);
            else
                usunKatalog($sciezka_katalogu . '/' . $nazwa_pliku);
        }
    }

    closedir($uchwyt_katalogu);
    return true;
}

usunKatalog('codes');

header('Location: generator.php');
?>
