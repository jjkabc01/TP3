<?php
$proteger = true;

include('header.php');

if (isset($_GET['NO_MEMBRE']))  {
    
    $NO_MEMBRE = $_GET['NO_MEMBRE'];
    
    $stid = oci_parse($conn, "select NO_MEMBRE, UTILISATEUR_MEM, NOM_MEM, PRENOM_MEM, ADRESSE_MEM, CODE_POSTAL_MEM, TEL_MEM, LANGUE_CORRESPONDANCE_MEM, NOM_FICHIER_PHOTO_MEM
                              from TP2_MEMBRE 
                              where NO_MEMBRE = '$NO_MEMBRE' ");
    
    oci_execute($stid);
    
    $MEMBRE = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
    
    $Utilisateur = $MEMBRE['UTILISATEUR_MEM'];
    $Nom = $MEMBRE['NOM_MEM'];
    $Prenom = $MEMBRE['PRENOM_MEM'];
    $Adresse = $MEMBRE['ADRESSE_MEM'].", ". $MEMBRE['CODE_POSTAL_MEM'] .", Tél: ". $MEMBRE['TEL_MEM'];
    $Langue = $MEMBRE['LANGUE_CORRESPONDANCE_MEM'];
    $Photo = $MEMBRE['NOM_FICHIER_PHOTO_MEM'];
    $ExemplePhoto = "https://i.ibb.co/wBMCG13/download.png";
    
    
    //on affiche le début d'un tableau html
    echo "<table>\n";

    //on affiche le début d'une ligne d'un tableau html
    echo "<tr>\n";
    //On affiche le heading 
    echo "<th> Numero Membre </th>\n";
    //On affiche la valeur correspondant au heading
    echo " <td>".$NO_MEMBRE."</td>\n";
    //on affiche la fin de la ligne d'un tableau html
    echo "</tr>\n";
    
    //on affiche le début d'une ligne d'un tableau html
    echo "<tr>\n";
    //On affiche le heading
    echo "<th> Utilisateur </th>\n";
    //On affiche la valeur correspondant au heading
    echo "<td>".$Utilisateur."</td>\n";
    //on affiche la fin de la ligne d'un tableau html
    echo "</tr>\n";
    
    //on affiche le début d'une ligne d'un tableau html
    echo "<tr>\n";
    //On affiche le heading
    echo "<th>Nom</th>\n";
    //On affiche la valeur correspondant au heading
    echo "<td>".$Nom."</td>\n";
    //on affiche la fin de la ligne d'un tableau html
    echo "</tr>\n";
    
    //on affiche le début d'une ligne d'un tableau html
    echo "<tr>\n";
    //On affiche le heading
    echo "<th>Prénom</th>\n";
    //On affiche la valeur correspondant au heading
    echo "<td>".$Prenom."</td>\n";
    //on affiche la fin de la ligne d'un tableau html
    echo "</tr>\n";
    
    //on affiche le début d'une ligne d'un tableau html
    echo "<tr>\n";
    //On affiche le heading
    echo "<th>Adresse</th>\n";
    //On affiche la valeur correspondant au heading
    echo "<td>".$Adresse."</td>\n";
    //on affiche la fin de la ligne d'un tableau html
    echo "</tr>\n";
    
    //on affiche le début d'une ligne d'un tableau html
    echo "<tr>\n";
    //On affiche le heading
    echo "<th>Langue</th>\n";
    //On affiche la valeur correspondant au heading
    echo " <td>".$Langue."</td>\n";
    //on affiche la fin de la ligne d'un tableau html
    echo "</tr>\n";
    
    //on affiche le début d'une ligne d'un tableau html
    echo "<tr>\n";
    //On affiche le heading
    echo "<th>Photo</th>\n";
    //On affiche la valeur correspondant au heading
    echo "<td><img src='".$Photo."' /></td> \n";
    //on affiche la fin de la ligne d'un tableau html
    echo "</tr>\n";
    
    echo "<tr>\n";
    //On affiche le heading
    echo "<th>Exemple Photo</th>\n";
    //On affiche la valeur correspondant au heading
    echo "<td><img src='".$ExemplePhoto."' /></td> \n";
    //on affiche la fin de la ligne d'un tableau html
    echo "</tr>\n";
    
    //on affiche la fin du tableau html
    echo "</table>\n";    

}

?>

<footer>
<?php
//pied de page
include 'footer.php';

?>
             
</footer>