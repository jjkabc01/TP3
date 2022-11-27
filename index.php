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
    
    oci_execute($stid);
    
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
    header('Location: index.php');
    echo '<div> Vous êtes bien deconnecté</div>';
}
   

?>

<section class="presentation">
<h1>Bienvenue sur le portail de CIPRE</h1>
<p>CPRE est un centre de recherche internationnal qui compe de milliers<br>
de milliers de chercheurs a travers le monde.l'objectif de tous ses chercheurs est<br>
      centré sur des recherche pour repondre aux bésoin du monde.
</p>
</section>

<p>Merci de entrer votre nom d'utilisateur et votre mot de passe et cliquez sur le boutton Se connecter.</p>

<form action="index.php" method="post" >
    <div>
    	Nom d'utilisateur Membre : <input type="text" name="UTILISATEUR_MEM"><br>
    	Mot de passe Membre : <input type="text" name="MOT_DE_PASSE_MEM"><br>
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


