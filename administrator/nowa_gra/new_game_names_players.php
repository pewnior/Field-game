<?php
session_start();
if (!isset($_SESSION['zalogowany_admin']))
{
    header('Location: ../admin.php');
    exit();
}
if (isset($_POST['ile_punktow']) && isset($_POST['ile_graczy'])) {
    $_SESSION['ile_g'] = $_POST['ile_graczy'];
    $_SESSION['ile_p'] = $_POST['ile_punktow'];
    $liczba_graczy = $_SESSION['ile_g'];
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
    <b>Tworzenie gry krok 2 z 4</b></br></br>
    Proszę uzupełnić luki nazwami graczy</br></br>
    <form action="input_players.php" method="post">
        <?php
            for ($i = 1; $i < $liczba_graczy + 1; $i++) {
                $podpowiedz = 'Gracz nr ' . $i;
                $pole_formularza = "'<td><input required class=\"logowanie\" type=\"text\" name=" . $i . " placeHolder=\"" . $podpowiedz . "\"></td></br>";
                echo $pole_formularza;
            }
        ?>
        </br>
        <input type="submit" class="logowanie" value="Dalej"/>
    </form>
</div></div>
</body>
</html>
