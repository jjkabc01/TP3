<?php

/* TP3- Équipe 6
 * Auteur
 * Kekeli
 * Check Sangaré
 * Aimé
 */

//Ajout de la page header 
include('header.php');

?>

<h1> Bienvenue sur le site du CIPRÉ</h1>


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