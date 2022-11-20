<?php
echo "bello!";

//en débogage, pratique d'affiche toutes les erreurs
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//On créé la connexion vers le serveur
//le VPN doit être activé dans votre ordinateur hôte sinon ce code n'aura pas accès au serveur SGBD Oracle
//Ceci n'est qu'un compte de test, ne pas utiliser pour vos travaux ou laboratoires, remplacer par votre compte à vous
$conn = oci_connect('C##SERVEUR', 'd098UIKJ33', 'fsg-p-ora01.fsg.ulaval.ca:1521/ora19c.fsg.ulaval.ca', 'AL32UTF8');

//on prépare un select dans la BD
$stid = oci_parse($conn, 'select * from LAB06_BIDON');

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

