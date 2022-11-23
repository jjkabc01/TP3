<?php

if (isset($_GET['deconnecter'])) {
    session_destroy();
    header('Location: index.php?action=deconnecter');
} 

if (!isset($_SESSION['UTILISATEUR_MEM'])) {
    ?>
    <div ><a href="index.php">Connexion</a></div>
	
<?php } else { ?>
	<div­>Bienvenue <?php echo $_SESSION['NOM_MEM'] . " " . $_SESSION['PRENOM_MEM']; ?> <a href="etat_connexion.php?deconnecter=true">Deconnecter</a></div></br>
<?php } ?>

