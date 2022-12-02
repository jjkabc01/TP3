<?php

if (isset($_GET['deconnecter'])) {
    session_destroy();
    header('Location: index.php?action=deconnecter');
} 

if (!isset($_SESSION['UTILISATEUR_MEM'])) {
    ?>
    <p id="etat_connexion"><a href="index.php">Connexion</a></p>
	
<?php } else { ?>
	<p id="etat_connexion" >Bienvenue <?php echo $_SESSION['NOM_MEM'] . " " . $_SESSION['PRENOM_MEM']; ?> <a href="etat_connexion.php?deconnecter=true">Deconnecter</a></p></br>
<?php } ?>

