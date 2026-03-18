<?php
session_start();

require_once 'helpers.php';
wymagajGracza();

$identyfikator_punktu = $_SESSION['punkt'];
$identyfikator_gry    = $_SESSION['game_id'];
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <title>Field game</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="text" id="point">
        <?php
            $polaczenie = polaczZBaza();

            // Pobierz dane punktu
            $stmt = $polaczenie->prepare("SELECT id, points FROM checkpoints WHERE id = ?");
            $stmt->bind_param('i', $identyfikator_punktu);
            $stmt->execute();
            $wynik_punktu = $stmt->get_result();
            $dane_punktu  = $wynik_punktu->fetch_assoc();
            $stmt->close();

            $dodatkowe_punkty   = $dane_punktu['points'];
            $suma_punktow       = $_SESSION['punkty'] + $dodatkowe_punkty;
            $identyfikator_gracza = $_SESSION['id'];

            echo '<p>Witaj ' . htmlspecialchars($_SESSION['nazwa']) . '!</p>';
            echo '<p>Punkt został zaliczony</p>';
            echo '<p><b>Punkty</b>: ' . $_SESSION['punkty'] . '+' . $dodatkowe_punkty . '</p>';

            // Zaktualizuj punkty gracza
            $stmt = $polaczenie->prepare("UPDATE players SET points = ? WHERE id = ?");
            $stmt->bind_param('ii', $suma_punktow, $identyfikator_gracza);
            $stmt->execute();
            $stmt->close();

            // Zapisz log odwiedzenia
            date_default_timezone_set('Europe/Warsaw');
            $aktualny_czas = date('Y-m-d H:i:s');
            $stmt = $polaczenie->prepare("INSERT INTO logs (player_id, checkpoint_id, timestamp, game_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('iisi', $identyfikator_gracza, $identyfikator_punktu, $aktualny_czas, $identyfikator_gry);
            $stmt->execute();
            $stmt->close();

            $polaczenie->close();
            session_unset();
        ?>
        </div>
    </div>
</body>
</html>
