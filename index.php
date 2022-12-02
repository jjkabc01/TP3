<?php

/* TP3- Équipe 6
 * Auteur
 * Kekeli
 * Check Sangaré
 * Aimé
 */
//cette page n'est pas protégée
$proteger = false;

if (isset($_GET['connexion']) && $_GET['connexion'] === 'requis' )  {
    $erreur = "Vous devez être connecté pour accéder à cette page";
    echo '<div class="erreur">'. $erreur . '</div>';
}

//Ajout de la page header 
include('header.php');


// Initialisation des variables
$UTILISATEUR_MEM = '';
$MOT_DE_PASSE_MEM = '';


// Si l'usager clique sur le boutton connecter
if (isset($_POST['connecter'])) {
    
    // On assigne les variable avec les données du champs login
    $UTILISATEUR_MEM = strtolower($_POST['UTILISATEUR_MEM']);
    $MOT_DE_PASSE_MEM = $_POST['MOT_DE_PASSE_MEM'];
    
    // Vérifie que l'usager existe et que le mot de passe est bon
    $stid = oci_parse($conn, "select *
                              from TP2_MEMBRE
                              where LOWER(UTILISATEUR_MEM) = '$UTILISATEUR_MEM' and MOT_DE_PASSE_MEM = '$MOT_DE_PASSE_MEM'");
    
    //oci_execute($stid);
    execute_commande($stid);
    $MEMBRE = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
    
    // on sauvegarde les information dans la variable de session
    if ($MEMBRE  != false) {
        $_SESSION['UTILISATEUR_MEM'] = $MEMBRE['UTILISATEUR_MEM'];
        $_SESSION['NO_MEMBRE'] = $MEMBRE['NO_MEMBRE'];
        $_SESSION['NOM_MEM'] = $MEMBRE['NOM_MEM'];
        $_SESSION['PRENOM_MEM'] = $MEMBRE['PRENOM_MEM'];
        
        if($MEMBRE['EST_ADMINISTRATEUR_MEM'] == 1 )
        {
            $_SESSION['TYPE_MEMBRE'] = 'administrateur';
        }
        elseif ($MEMBRE['EST_SUPERVISEUR_MEM'] == 1)
        {
            $_SESSION['TYPE_MEMBRE'] = 'superviseur';
        }
        else
        {
            $_SESSION['TYPE_MEMBRE'] = 'membre';
        }
            
        
        // On affiche la page liste_projet.php
        header('Location: liste_projets.php');
    } else {
           // On affiche le message d'erreur userid ou mot de passe incorrect
        $erreur = "Le nom d'utilisateur ou le mot de passe est incorrect, merci de vérifier les informations de connexion";
        echo '<div class="erreur">'. $erreur . '</div>';
    }
}

//Si l'usager est deconnecté
if (isset($_GET['action']) && $_GET['action'] === 'deconnecter' )  {
    session_destroy();
    oci_close($conn);
    header('Location: index.php');
    echo '<div> Vous êtes bien deconnecté</div>';
}
   

?>

<section class="presentation">
<h1>Bienvenue sur le portail de CIPRE</h1>
<p>CIPRE est un centre de recherche international qui compte des milliers
de chercheurs à travers le monde.
<br>L'objectif de tous ces chercheurs est centré sur des recherches pour repondre aux bésoins du monde.
</p>
</section>

<p>Merci d'entrer votre nom d'utilisateur et votre mot de passe et cliquez sur le bouton Se connecter.</p>

<form action="index.php" method="post" >
    <div>
    	<label for="UTILISATEUR_MEM">Nom d'utilisateur Membre :</label>
    	 <input type="text" name="UTILISATEUR_MEM"><br>
    	 <label for="MOT_DE_PASSE_MEM">Mot de passe Membre :</label>
    	<input type="password" name="MOT_DE_PASSE_MEM"><br>
    </div>
	<div >
		<input class="boutton" type="submit" name="connecter" value="Se connecter">
	</div>
</form>

</br>


<footer>
<?php
       
         include 'footer.php';
             
      ?>
             
</footer>


