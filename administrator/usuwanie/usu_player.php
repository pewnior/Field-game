<?php
session_start();

require_once '../../helpers.php';
wymagajAdmina('../admin.php');

$identyfikator_gry = $_SESSION['game_id'];
$polaczenie = polaczZBaza();

$stmt = $polaczenie->prepare("SELECT id, name FROM players WHERE game_id = ?");
$stmt->bind_param('i', $identyfikator_gry);
$stmt->execute();
$wynik = $stmt->get_result();
$stmt->close();
$polaczenie->close();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Field game</title>
<link rel="stylesheet" type="text/css" href="../../style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
<div class="text">
<p class="exit" align="center"><a href="../admin_panel.php">&lt;<-- Powrót</a></p>
<b>Wybierz gracza którego chcesz usunąć:</b><br/><br/>
<?php if ($wynik->num_rows === 0): ?>
    <p align="center">Brak graczy</p>
<?php else: ?>
    <form action="old_player.php" method="get">
        <select class="logowanie" name="nazwa">
            <?php while ($wiersz = $wynik->fetch_assoc()): ?>
                <option value="<?= $wiersz['id'] ?>"><?= htmlspecialchars($wiersz['name']) ?></option>
            <?php endwhile; ?>
        </select>
        <br/><br/>
        <input type="submit" class="logowanie" value="Usuń"/>
    </form>
<?php endif; ?>
</div>
</div>
</body>
</html>
