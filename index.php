<?php
	session_start();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
<meta charset="UTF-8">
  <title>Field game</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
  <style type="text/css">
    <?php 
      include 'style.css'; 
    ?>
  </style>
</head> 

<body>
  <div class="container">
    <div class="text">
      <b class="name">Field game </b></br>
      Aby zaliczyć punkt i zdobyć punkty </br>zeskanuj QR kod</br></br>
      <a id="admin" href="administrator/admin.php">Administrator</a>
      
      <?php
        if(@$_SESSION['blad']!=NULL){
        echo $_SESSION['blad'];
        }
        session_unset();
      ?>
      
    </br> </br> Autor: Maciej Pewniak 2026 </br> Kontakt: </br> field.game.app@gmail.com
    </div>
  </div>
</body>
</html>