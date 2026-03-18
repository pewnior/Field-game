<?php

function polaczZBaza(): mysqli {
    require __DIR__ . '/connect.php';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        $polaczenie->set_charset('utf8mb4');
        return $polaczenie;
    } catch (mysqli_sql_exception $e) {
        error_log('Błąd połączenia z bazą: ' . $e->getMessage());
        die('Błąd połączenia z bazą danych. Spróbuj ponownie później.');
    }
}


function wymagajAdmina(string $sciezka_powrotu = '../administrator/admin.php'): void {
    if (!isset($_SESSION['zalogowany_admin'])) {
        header('Location: ' . $sciezka_powrotu);
        exit();
    }
}


function wymagajGracza(string $sciezka_powrotu = 'index.php'): void {
    if (!isset($_SESSION['zalogowany'])) {
        header('Location: ' . $sciezka_powrotu);
        exit();
    }
}


function generujHasloGracza(mysqli $polaczenie): string {
    $zbior_slow = ['gruszka', 'sieradz', 'rynek', 'hufiec', 'harcerz', 'zamek', 'papier', 'stojak', 'list', 'kalosz'];
    do {
        $haslo = $zbior_slow[array_rand($zbior_slow)] . rand(100, 999);
        $stmt = $polaczenie->prepare("SELECT id FROM players WHERE password = ?");
        $stmt->bind_param('s', $haslo);
        $stmt->execute();
        $stmt->store_result();
        $liczba = $stmt->num_rows;
        $stmt->close();
    } while ($liczba > 0);
    return $haslo;
}


function generujHasloPunktu(mysqli $polaczenie): string {
    do {
        $haslo = (string)rand(1000000, 9999999) . (string)rand(1000000, 9999999);
        $stmt = $polaczenie->prepare("SELECT id FROM checkpoints WHERE password = ?");
        $stmt->bind_param('s', $haslo);
        $stmt->execute();
        $stmt->store_result();
        $liczba = $stmt->num_rows;
        $stmt->close();
    } while ($liczba > 0);
    return $haslo;
}


function pobierzIdZGet(string $klucz): int {
    $wartosc = filter_input(INPUT_GET, $klucz, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1]
    ]);
    if (!$wartosc) {
        header('Location: ../admin.php');
        exit();
    }
    return $wartosc;
}
?>
