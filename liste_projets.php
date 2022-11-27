<?php

$proteger = true;

include('header.php');

//si le boutton Archiver est cliqué et qu'une date d'archivage est entrée ex:15-10-01
if(isset($_POST['archiver']) && isset($_POST['date_archive'])){
    //on reccupe la date d'archivage et le nom d'utilisateur pour exécuter la procédure d'archivage
    $DATE_ARCHIVE = $_POST['date_archive'];
    $UTILISATEUR_MEM = $_SESSION['UTILISATEUR_MEM'];
    //Commande d'archivage et exécution de celle-ci
    $stid = oci_parse($conn, "begin TP3_SP_ARCHIVER_PROJET(to_date('$DATE_ARCHIVE','RR-MM-DD'),'$UTILISATEUR_MEM'); end;"); 
    oci_execute($stid);
    
}
   
//si le boutton mise à jour projet est cliqué et que un projet est selectionné dans le select
if(isset($_POST['update']) && isset($_POST['NO_PROJET'])){
    // on reccupère le numéro du projet à modifier
    $NO_PROJET = $_POST['NO_PROJET'];
    // on renvoie la page un projet en mode modification avec le numero du projet
    header('Location: un_projet.php?noprojet='.$NO_PROJET); 
}

//si le boutton creer projet est cliqué
if(isset($_POST['creer'])){
    //On renvoie la page un_projet en mode création
    header('Location: un_projet.php');
}
//si le boutton rechercher projet est cliqué
if(isset($_POST['rechercher'])){
    //on renvoie la page rechercher
    header('Location: projet_rechercher.php');
}

//À supprimmer pour tester la page un_membre.php 
if(isset($_POST['membre'])){
    //on renvoie la page un_membre.php avec le membre 
    header('Location: un_membre.php?NO_MEMBRE=20');
}
//À supprimer   


echo '<div><p> Liste projet</p></div>';

$stid ="";
$type ="";

if($_SESSION['TYPE_MEMBRE'] === 'administrateur' || $_SESSION['TYPE_MEMBRE'] === 'superviseur' )
{
    // a supprimer juste pour tester nos conditions de membres ou admin
    $type = "je suis admin ou superviseur";
    // a supprimer
    
    $stid = oci_parse($conn, "select NO_PROJET from (select NO_PROJET, DATE_DEBUT_PRO
                              from TP2_PROJET 
                                union select NO_PROJET, DATE_DEBUT_PRO
                                    from TP2_PROJET_ARCHIVE )
                                        order by DATE_DEBUT_PRO desc");    
}
else 
{ 
    // a supprimer juste pour tester nos conditions de membres ou admin
    $type = "je suis simple membre";
    // a supprimer
      
    $NO_MEMBRE = $_SESSION['NO_MEMBRE'];
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

//création d'une form pour avoir les valeur post des bouttons
echo "<form action='liste_projets.php' method='post' >\n";
//on affiche le début de notre liste de projet
echo "<select size='20' name='NO_PROJET'> \n";

while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    
    foreach ($row as $item) {
        //on affiche une cellule du tableau html i.e.: <td> ... </td>
        echo "  <option value='$item'>".($item !== null ? htmlspecialchars($item, ENT_QUOTES) : "&nbsp;")."</option>\n";
    }
    
}
//Création d'un select pour l'affichage des numeros projets
echo "</select> \n";
echo "<br><br> \n"; 
echo "<div class='boutton' style='width:500px;'> \n"; 
echo "<input class='boutton' type='submit' name='projet' value='Voir le projet'><br> \n";

//Affichage des boutton Mettre à jour, creer et rechercher projet
echo "<input class='boutton' type='submit' name='update' value='Mettre à jour le projet'><br> \n";
echo "<input class='boutton' type='submit' name='creer' value='Creer un projet'><br> \n";
echo "<input class='boutton' type='submit' name='rechercher' value='Rechercher'><br> \n";

//À supprimmer pour tester la page un_membre.php 
echo "<input class='boutton' type='submit' name='membre' value='Afficher membre NO 20'><br> \n";
//À supprimmer 

echo "</div> \n"; 
echo "<br><br> \n"; 

//Si l'usager connecté est un administrateur on affiche le boutton archiver
if($_SESSION['TYPE_MEMBRE'] === 'administrateur' )
{
    echo "<input type='date' name='date_archive' placeholder='Format date AA-MM-JJ' >";
    echo "<input class='boutton' type='submit' name='archiver' value='Archiver'><br> \n";
}

echo "</form> \n"; //fin de la form pour l'affichage du select et des bouttons en dessous.


//Debut affichage des details du projet selectionné dans la form select
//si le bouttin voir projet est cliqué et qu'un projet est selectionné dans le select
if (isset($_POST['projet']) && isset($_POST['NO_PROJET'])) {
    
    $NO_PROJET = $_POST['NO_PROJET'];
    
    //on cherche les infos de ce projet dans la base de donnée ( table projet)
    $stid = oci_parse($conn, "select NO_PROJET, NOM_PRO, MNT_ALLOUE_PRO, STATUT_PRO, DATE_DEBUT_PRO
                              from TP2_PROJET
                              where NO_PROJET = '$NO_PROJET'");
    // Si le resultat de la commande cherchant dans la table projet est vide alors le projet est surement archivé on cherche alors dans la table archive
    if(($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) == false){
        
        $stid = oci_parse($conn, "select NO_PROJET, NOM_PRO, MNT_ALLOUE_PRO, STATUT_PRO, DATE_DEBUT_PRO
                              from TP2_PROJET_ARCHIVE
                              where NO_PROJET = '$NO_PROJET'");
    }
    
    oci_execute($stid);
    
    //debut de l'affichage des informations du projet dans un tableau html
    echo "<br><br> \n";
   
    echo "<table>\n";
    
    while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
        
        //Affichage des entêtes du tableau
        echo "<tr>\n";
        echo "<th>NO PROJET</th>\n";
        echo "<th>NOM PROJET</th>\n";
        echo "<th>MONTANT ALLOUE</th>\n";
        echo "<th>STATUT PROJET</th>\n";
        echo "<th>DATE DEBUT PROJET</th>\n";
        echo "</tr>\n";
        
        //on affiche le début d'une ligne d'un tableau html
        echo "<tr>\n";
        
        //une boucle pour parcourir les attributs de chaque ligne et afficher les élements du resultat
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
//pied de page
include 'footer.php';

?>
             
</footer>