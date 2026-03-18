<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="../style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
  <title>Field game</title>
  <style type="text/css">
  <?php 
    include '../style.css'; 
  ?>
  </style>
</head> 
<body>
  <div class="container">
    <div class="text">
      <b class="name">Field game - Administrator</b></br></br>
      <a href="../index.php"><--Powrót</a>
      </br></br>
      <form action="zaloguj_admin.php" method="post">
        <input type="text" class="logowanie" id="login-adm" name="login" placeHolder="Login"/></br>
        <br/> <input type="password" class="logowanie" id="pass-ad" name="haslo" placeHolder="Hasło"/><br/><br/>
        <input id="login" type="submit" class="logowanie" value="Zaloguj się"/></br>
      </form>
      
      <?php
       if (isset($_SESSION['blad'])) echo $_SESSION['blad'];
      ?>

      </br> </br>Autor: Maciej Pewniak 2026
    </div>
  </div>
</body>
</html>
