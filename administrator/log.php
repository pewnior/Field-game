<?php
session_start();

require_once '../helpers.php';
wymagajAdmina('admin.php');

$identyfikator_gracza = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$identyfikator_gracza) {
    header('Location: logi.php');
    exit();
}

$identyfikator_gry = $_SESSION['game_id'];
$polaczenie = polaczZBaza();

$stmt = $polaczenie->prepare(
    "SELECT l.checkpoint_id, l.timestamp, c.name
     FROM logs l
     LEFT JOIN checkpoints c ON c.id = l.checkpoint_id AND c.game_id = l.game_id
     WHERE l.player_id = ? AND l.game_id = ?
     ORDER BY l.timestamp ASC"
);
$stmt->bind_param('ii', $identyfikator_gracza, $identyfikator_gry);
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
<link rel="stylesheet" type="text/css" href="../style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
</head>
<body>
<div id="stats">
<p class="exit" align="center"><a href="logi.php">&lt;-- Powrot</a></p>
<table width="900" align="center" border="1" bordercolor="#d5d5d5" cellpadding="0" cellspacing="0">
<tr>
    <td width="50"  align="center" bgcolor="#5EC9DB">Lp</td>
    <td width="100" align="center" bgcolor="#5EC9DB">Nazwa punktu</td>
    <td width="100" align="center" bgcolor="#5EC9DB">Czas</td>
</tr>
<?php if ($wynik->num_rows === 0): ?>
<tr><td colspan="3" align="center">Ten gracz nie odwiedził żadnych punktów</td></tr>
<?php else: $i = 1; while ($wiersz = $wynik->fetch_assoc()): ?>
<tr>
    <td width="50"  align="center" bgcolor="#5EC9DB"><?= $i++ ?></td>
    <td width="100" align="center" bgcolor="#e5e5e5"><?= htmlspecialchars($wiersz['name'] ?? '—') ?></td>
    <td width="100" align="center" bgcolor="#e5e5e5"><?= htmlspecialchars($wiersz['timestamp']) ?></td>
</tr>
<?php endwhile; endif; ?>
</table>
</div>
</body>
</html>
