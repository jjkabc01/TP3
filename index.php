<?php

/* TP3- Équipe 6
 * Auteur
 * Kekeli
 * Check Sangaré
 * Aimé
 */

//Ajout de la page header 
include('header.php');


// Initialisation des variables
$UTILISATEUR_MEM = '';
$MOT_DE_PASSE_MEM= '';


// Si l'usager clique sur le boutton connecter
if (isset($_POST['connecter'])) {
    
    // On assigne les variable avec les données du champs login
    $UTILISATEUR_MEM = $_POST['UTILISATEUR_MEM'];
    $MOT_DE_PASSE_MEM = $_POST['MOT_DE_PASSE_MEM'];
    
    // Vérifie que l'usager existe et que le mot de passe est bon
    $stid = oci_parse($conn, "select *
                              from TP2_MEMBRE
                              where UTILISATEUR_MEM = '$UTILISATEUR_MEM' and MOT_DE_PASSE_MEM = '$MOT_DE_PASSE_MEM'");
    
    oci_execute($stid);
    
    $MEMBRE= oci_fetch_array($stid);
    
    // on sauvegarde les information dans la variable de session
    if ($MEMBRE  != false) {
        $_SESSION['UTILISATEUR_MEM'] = $MEMBRE['UTILISATEUR_MEM'];
        $_SESSION['NOM_MEM'] = $MEMBRE['NOM_MEM'];
        $_SESSION['PRENOM_MEM'] = $MEMBRE['PRENOM_MEM'];
        
        // On affiche la page liste_projet.php
        header('Location: liste_projets.php');
    } else {
           // On affiche le message d'erreur userid ou mot de passe incorrect
        $erreur = "Le nom d'utilisateur ou le mot de passe est incorrect, merci de vérifier les informations de connexion";
        echo $erreur;
    }
}

//Si l'usager est deconnecté
if (isset($_GET['action']) && $_GET['action'] === 'deconnecter' )  {
    session_destroy();
    header('Location: index.php');
    echo '<div> Vous êtes bien deconnecté</div>';
}
   

?>

<h1> Bienvenue sur le site du CIPRÉ</h1>

<h2>Connexion</h2>


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
<?php include('footer.php'); ?>



<?php

$stid = oci_parse($conn, 'select * from TP2_RAPPORT');

//on exécute le select
oci_execute($stid);

//on affiche le début d'un tableau html
echo "<table>\n";

//une boucle pour parcourir le "curseur"
while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    
    //on affiche le début d'une ligne d'un tableau html
    echo "<tr>\n";
    
    //une boucle pour parcourir les attributs de chaque ligne
    foreach ($row as $item) {
        //on affiche une cellule du tableau html i.e.: <td> ... </td>
        echo "  <td>".($item !== null ? htmlspecialchars($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
    }
    //on affiche la fin de la ligne d'un tableau html
    echo "</tr>\n";
}
//on affiche la fin du tableau html
echo "</table>\n";



?>