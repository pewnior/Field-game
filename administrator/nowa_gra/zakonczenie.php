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
        <b>Tworzenie gry krok 4 z 4</b></br>
        Gra została stworzona</br>Dziękujemy za korzystanie z aplikacji Field Game</br></br>
        <form action="../admin_panel.php">
            <input class="logowanie" type="submit" value="Zakończ" />
        </form>
    </div>
  </div>
</body>
</html>
