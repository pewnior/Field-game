<?php
session_start();
if (!isset($_SESSION['zalogowany_admin']))
{
    header('Location: ../admin.php');
    exit();
}
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
<style type="text/css">
<?php 
include '../../style.css'; 
?>
</style>
</head> 
<body>
  <div class="container">
  <div class="text">
        <b>UWAGA!</b></br>
        Stworzenie nowej gry spowoduje usunięcie wszystkich danych z aktualnej!</br></br>
        <form action="tworzenie.php">
            <input type="submit" class="logowanie" value="Stwórz" />
        </form>
        </br></br><a href="../admin_panel.php">Anuluj</a>
    </div>
  </div>
</body>
</html>
