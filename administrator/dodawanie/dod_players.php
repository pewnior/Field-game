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
    <b>Dodawanie gracza</b></br></br>
    <a href="../admin_panel.php"><--Powrót</a></br></br>
    <form action="new_player.php" method="post">
        <input type="text" class="logowanie" required placeHolder="Nazwa" name="nazwa"/></br>
        </br>
        <input type="submit" class="logowanie" value="Dodaj"/>
    </form>
</div></div>
</body>
</html>
