<?php
include('header.php');
//Initialisation des variables
$NOM_PRO = '';
$MNT_ALLOUE_PRO = '';
//$DATE = '';

// Si l'usager clique sur le boutton ok
if (isset($_POST['ok'])) 

{
    // On assigne les variables avec les données des champs respectifs
    $NOM_PRO = $_POST['NOM_PRO'];
    $MNT_ALLOUE_PRO = $_POST['MNT_ALLOUE_PRO'];
    //$DATE = $_POST['DATE_FIN_PRO'];

    // Vérifie si les informations sont bonnes
    $stid = oci_parse($conn, "select *
                              from TP2_PROJET
                              where NOM_PRO = '$NOM_PRO' or MNT_ALLOUE_PRO ='$MNT_ALLOUE_PRO'");
                              


oci_execute($stid);

//on affiche le debut du tableau html
echo "<table>\n";

// une boucle pour parcourir le curseur
while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false)

{
    echo "<tr>\n";
    
    echo "<td>".($row["NOM_PRO"] !== null ? htmlspecialchars($row["NOM_PRO"], ENT_QUOTES) : "&nbsp;")."</td>\n";
}

//on affiche la fin de la ligne d'un tableau html
echo "</tr>\n";
}
//on affiche la fin du tableau html
echo "</table>\n";



/*
$PROJET= oci_fetch_array($stid);



// on sauvegarde les information dans la variable de session
if ($PROJET  != false)
{
    $_SESSION['NOM_PRO'] = $PROJET['NOM_PRO'];
    $_SESSION['MNT_ALLOUE_PRO'] = $PROJET['MNT_ALLOUE_PRO'];
    $_SESSION['DATE_FIN_PRO'];
    
    // On affiche la page liste_projet.php
    header('Location: index.php');
    echo "<table>­\n";
}


else
{
    // On affiche un message d'erreur
    $erreur = "Aucun projet ne correspond < votre recherche";
    echo '<div class="erreur">'. $erreur . '</div>';
}

}

*/

// Si l'usager clique sur le boutton connecter
if (isset($_POST['annuler'])) 
{
    header('Location: liste_projets.php');
}

?>

<form action="projet_rechercher.php" method="post" >
    <div>
    	Nom du projet : <input type="text" name="NOM_PRO"><br>
    	Montant alloué : <input type="text" name="MNT_ALLOUE_PRO"><br>
    	Date du projet : <input type="text" name= "'$DATE'"> <br> 
    	 </div>
    
	<div >
		<input class="boutton" type="submit" name="ok" value="OK">
		<input class="boutton" type = "submit" name="annuler" value="Annuler"
	
	</div>
</form>

</br>


<footer>
<?php
       
         include 'footer.php';
             
      ?>
             
</footer>
