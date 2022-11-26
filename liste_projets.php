<?php
include('header.php');

echo '<div><p> Liste projet Ok </p></div>';

$stid ="";

if($_SESSION['TYPE_MEMBRE'] === 'administrateur' || $_SESSION['TYPE_MEMBRE'] === 'superviseur' )
{
    $type = "je suis admin";
    
    $stid = oci_parse($conn, "select NO_PROJET from (select NO_PROJET, DATE_DEBUT_PRO
                              from TP2_PROJET 
                                union select NO_PROJET, DATE_DEBUT_PRO
                                    from TP2_PROJET_ARCHIVE )
                                        order by DATE_DEBUT_PRO desc");    
}
else 
{
    $NO_MEMBRE = $_SESSION['NO_MEMBRE'];
    
    $type = "je suis simple membre";
    
    $stid = oci_parse($conn, "select E.NO_PROJET
                              from TP2_EQUIPE_PROJET E, TP2_PROJET P
                              where NO_MEMBRE = '$NO_MEMBRE' 
                              and E.NO_PROJET = P.NO_PROJET 
                              and not exists (select * from TP2_PROJET_ARCHIVE A where A.NO_PROJET = E.NO_PROJET)
                              order by P.DATE_DEBUT_PRO desc");  
}

oci_execute($stid);



// a supprimer juste pour tester nos conditions de membres ou admin
echo $type;
// a supprimer

echo "<form action='liste_projets.php' method='post' >\n";
//on affiche le début de notre liste de projet
echo "<select size='20' name='NO_PROJET'> \n";

while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    
    foreach ($row as $item) {
        //on affiche une cellule du tableau html i.e.: <td> ... </td>
        echo "  <option value='$item'>".($item !== null ? htmlspecialchars($item, ENT_QUOTES) : "&nbsp;")."</option>\n";
    }
    
}

echo "</select> \n";
echo "<br><br> \n";
echo "<input type='submit' name='projet' value='Voir projet'><br> \n";
echo "</form> \n";

if (isset($_POST['projet'])) {
    
    $NO_PROJET=$_POST['NO_PROJET'];
    $stid = oci_parse($conn, "select NO_PROJET, NOM_PRO, MNT_ALLOUE_PRO, STATUT_PRO, DATE_DEBUT_PRO
                              from TP2_PROJET
                              where NO_PROJET = '$NO_PROJET'");
    oci_execute($stid);
    
    
    echo "<br><br> \n";
   
    echo "<table>\n";
    
    while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
        
        echo "<tr>\n";
        echo "<th>NO PROJET</th>\n";
        echo "<th>NOM PROJET</th>\n";
        echo "<th>MONTANT ALLOUE</th>\n";
        echo "<th>STATUT PROJET</th>\n";
        echo "<th>DATE DEBUT PROJET</th>\n";
        echo "</tr>\n";
        
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
    
    
}




?>

<footer>
<?php

include 'footer.php';

?>
             
</footer>