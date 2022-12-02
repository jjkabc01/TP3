<!doctype html>

<html>
<head>
<title>Aplication web de CIPRE</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<header>
<nav>
<a href="index.php" id="logo"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRrDKX0-LFLEI5iGqLqqVOlWWzOYdblsDW-VA&usqp=CAU" alt="" id="leaflogo"></a>
</nav>
</header>
    

</body>
             
</html>


<?php
//on demarre la session
session_start();

//si la page que le visiteur tente d'accéder est protégé et que celui ci n'est pas connecté on renvoi le visiteur à la page d'accueil avec le message d'erreur correspondant
if ($proteger == true && !isset($_SESSION['UTILISATEUR_MEM'])) {
    header('Location: index.php?connexion=requis');
}

include('init.php');
include('etat_connexion.php');

?>

