<?php
session_start();

require_once '../helpers.php';
wymagajAdmina('admin.php');

$identyfikator_gry = $_SESSION['game_id'];
$polaczenie = polaczZBaza();

$stmt = $polaczenie->prepare("SELECT id, name, points FROM players WHERE game_id = ? ORDER BY points DESC");
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
<link rel="stylesheet" type="text/css" href="../style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
</head>
<body>
<div id="stats">
<p class="exit" align="center"><a href="admin_panel.php">&lt;-- Powrot</a></p>
<table width="900" align="center" border="1" bordercolor="#d5d5d5" cellpadding="0" cellspacing="0">
<tr>
    <td width="50"  align="center" bgcolor="#5EC9DB">Miejsce</td>
    <td width="50"  align="center" bgcolor="#5EC9DB">ID</td>
    <td width="100" align="center" bgcolor="#5EC9DB">Nazwa</td>
    <td width="100" align="center" bgcolor="#5EC9DB">Punkty</td>
    <td width="50"  align="center" bgcolor="#5EC9DB">Log</td>
</tr>
<?php if ($wynik->num_rows === 0): ?>
<tr><td colspan="5" align="center">Brak graczy</td></tr>
<?php else: $miejsce = 1; while ($wiersz = $wynik->fetch_assoc()): ?>
<tr>
    <td width="50"  align="center" bgcolor="#5EC9DB"><?= $miejsce++ ?></td>
    <td width="50"  align="center" bgcolor="#e5e5e5"><?= $wiersz['id'] ?></td>
    <td width="100" align="center" bgcolor="#e5e5e5"><?= htmlspecialchars($wiersz['name']) ?></td>
    <td width="100" align="center" bgcolor="#e5e5e5"><?= $wiersz['points'] ?></td>
    <td width="50"  align="center" bgcolor="#e5e5e5"><a href="log.php?id=<?= $wiersz['id'] ?>">Link</a></td>
</tr>
<?php endwhile; endif; ?>
</table>
</div>
</body>
</html>
