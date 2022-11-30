<?php

$proteger = true;

include('header.php');


if (isset($_GET['resultat_recherche']))  {
    //si la page liste_projets est appélé avec le resultat d'une récherche (NO_projet)
    //faire quelque chose
}



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
    $_SESSION['NO_PROJET'] = $NO_PROJET;
    // on renvoie la page un projet en mode modification avec le numero du projet
    header('Location: un_projet.php?NO_PROJET='.$NO_PROJET);
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


echo '<div><p> Liste projet</p></div>';

$stid ="";
$type ="";

if($_SESSION['TYPE_MEMBRE'] === 'administrateur' || $_SESSION['TYPE_MEMBRE'] === 'superviseur' )
{
    
    $stid_projet = oci_parse($conn, "select NO_PROJET, NOM_PRO, MNT_ALLOUE_PRO, STATUT_PRO, DATE_DEBUT_PRO
                              from TP2_PROJET
                                order by DATE_DEBUT_PRO desc");

    oci_execute($stid_projet);
    
    $stid_projet_archive = oci_parse($conn, "select NO_PROJET, NOM_PRO, MNT_ALLOUE_PRO, STATUT_PRO, DATE_DEBUT_PRO
                                      from TP2_PROJET_ARCHIVE
                                        order by DATE_DEBUT_PRO desc");
            
    oci_execute($stid_projet_archive);
    
    
    //création d'une form pour avoir les valeur post des bouttons
    echo "<form action='liste_projets.php' method='post' >\n";
    //on affiche le début de notre liste de projet
    echo "<select size='20' name='NO_PROJET'> \n";
    
    while (($row = oci_fetch_array($stid_projet, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
        
            $NO_PROJET = $row['NO_PROJET'];
            $NOM_PRO = $row['NOM_PRO'];
            $MNT_ALLOUE_PRO = $row['MNT_ALLOUE_PRO'];
            $STATUT_PRO = $row['STATUT_PRO'];
            $DATE_DEBUT_PRO = $row['DATE_DEBUT_PRO'];
            
            //on affiche une cellule du tableau html i.e.: <td> ... </td>
            echo "  <option value='$NO_PROJET'>".'NO PROJET: '.($NO_PROJET!== null ? htmlspecialchars($NO_PROJET, ENT_QUOTES) : "&nbsp;")
                                                .' | NOM PROJET:  '.($NOM_PRO!== null ? htmlspecialchars($NOM_PRO, ENT_QUOTES) : "&nbsp;")
                                                .' | MONTANT PROJET: '.($MNT_ALLOUE_PRO!== null ? htmlspecialchars($MNT_ALLOUE_PRO, ENT_QUOTES) : "&nbsp;")
                                                .' | STATUT PROJET: '.($STATUT_PRO!== null ? htmlspecialchars($STATUT_PRO, ENT_QUOTES) : "&nbsp;")
                                                .' | DATE DEBUT PROJET: '.($DATE_DEBUT_PRO!== null ? htmlspecialchars($DATE_DEBUT_PRO, ENT_QUOTES) : "&nbsp;").
                "</option>\n";
        
    }
    echo "  <option value=''> #### Debut projet Archivé ####</option>\n";
    while (($row = oci_fetch_array($stid_projet_archive, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
                   
        $NO_PROJET = $row['NO_PROJET'];
        $NOM_PRO = $row['NOM_PRO'];
        $MNT_ALLOUE_PRO = $row['MNT_ALLOUE_PRO'];
        $STATUT_PRO = $row['STATUT_PRO'];
        $DATE_DEBUT_PRO = $row['DATE_DEBUT_PRO'];
        
        //on affiche une cellule du tableau html i.e.: <td> ... </td>
        echo "  <option value='$NO_PROJET'>".'NO PROJET: '.($NO_PROJET!== null ? htmlspecialchars($NO_PROJET, ENT_QUOTES) : "&nbsp;")
                                            .' | NOM PROJET:  '.($NOM_PRO!== null ? htmlspecialchars($NOM_PRO, ENT_QUOTES) : "&nbsp;")
                                            .' | MONTANT PROJET: '.($MNT_ALLOUE_PRO!== null ? htmlspecialchars($MNT_ALLOUE_PRO, ENT_QUOTES) : "&nbsp;")
                                            .' | STATUT PROJET: '.($STATUT_PRO!== null ? htmlspecialchars($STATUT_PRO, ENT_QUOTES) : "&nbsp;")
                                            .' | DATE DEBUT PROJET: '.($DATE_DEBUT_PRO!== null ? htmlspecialchars($DATE_DEBUT_PRO, ENT_QUOTES) : "&nbsp;").
        "</option>\n";
        
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
    

}
else
{  
    $NO_MEMBRE = $_SESSION['NO_MEMBRE'];
    $stid = oci_parse($conn, "select E.NO_PROJET, P.NOM_PRO, P.MNT_ALLOUE_PRO, P.STATUT_PRO, P.DATE_DEBUT_PRO
                              from TP2_EQUIPE_PROJET E, TP2_PROJET P
                              where NO_MEMBRE = '$NO_MEMBRE'
                              and E.NO_PROJET = P.NO_PROJET
                              and not exists (select * from TP2_PROJET_ARCHIVE A 
                                    where A.NO_PROJET = E.NO_PROJET)
                              order by P.DATE_DEBUT_PRO desc");
    
    oci_execute($stid);
    
    
    //création d'une form pour avoir les valeur post des bouttons
    echo "<form action='liste_projets.php' method='post' >\n";
    //on affiche le début de notre liste de projet
    echo "<select size='20' name='NO_PROJET'> \n";
    
    while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
        
        $NO_PROJET = $row['NO_PROJET'];
        $NOM_PRO = $row['NOM_PRO'];
        $MNT_ALLOUE_PRO = $row['MNT_ALLOUE_PRO'];
        $STATUT_PRO = $row['STATUT_PRO'];
        $DATE_DEBUT_PRO = $row['DATE_DEBUT_PRO'];
        
        //on affiche une cellule du tableau html i.e.: <td> ... </td>
        echo "  <option value='$NO_PROJET'>".'NO PROJET: '.($NO_PROJET!== null ? htmlspecialchars($NO_PROJET, ENT_QUOTES) : "&nbsp;")
        .' | NOM PROJET:  '.($NOM_PRO!== null ? htmlspecialchars($NOM_PRO, ENT_QUOTES) : "&nbsp;")
        .' | MONTANT PROJET: '.($MNT_ALLOUE_PRO!== null ? htmlspecialchars($MNT_ALLOUE_PRO, ENT_QUOTES) : "&nbsp;")
        .' | STATUT PROJET: '.($STATUT_PRO!== null ? htmlspecialchars($STATUT_PRO, ENT_QUOTES) : "&nbsp;")
        .' | DATE DEBUT PROJET: '.($DATE_DEBUT_PRO!== null ? htmlspecialchars($DATE_DEBUT_PRO, ENT_QUOTES) : "&nbsp;").
        "</option>\n";
        
    }
    echo "  <option value=''> #### Debut projet Archivé ####</option>\n";
    while (($row = oci_fetch_array($stid_projet_archive, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
        
        $NO_PROJET = $row['NO_PROJET'];
        $NOM_PRO = $row['NOM_PRO'];
        $MNT_ALLOUE_PRO = $row['MNT_ALLOUE_PRO'];
        $STATUT_PRO = $row['STATUT_PRO'];
        $DATE_DEBUT_PRO = $row['DATE_DEBUT_PRO'];
        
        //on affiche une cellule du tableau html i.e.: <td> ... </td>
        echo "  <option value='$NO_PROJET'>".'NO PROJET: '.($NO_PROJET!== null ? htmlspecialchars($NO_PROJET, ENT_QUOTES) : "&nbsp;")
        .' | NOM PROJET:  '.($NOM_PRO!== null ? htmlspecialchars($NOM_PRO, ENT_QUOTES) : "&nbsp;")
        .' | MONTANT PROJET: '.($MNT_ALLOUE_PRO!== null ? htmlspecialchars($MNT_ALLOUE_PRO, ENT_QUOTES) : "&nbsp;")
        .' | STATUT PROJET: '.($STATUT_PRO!== null ? htmlspecialchars($STATUT_PRO, ENT_QUOTES) : "&nbsp;")
        .' | DATE DEBUT PROJET: '.($DATE_DEBUT_PRO!== null ? htmlspecialchars($DATE_DEBUT_PRO, ENT_QUOTES) : "&nbsp;").
        "</option>\n";
        
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
    
    
    echo "</div> \n";
    echo "<br><br> \n";
    
    //Si l'usager connecté est un administrateur on affiche le boutton archiver
    if($_SESSION['TYPE_MEMBRE'] === 'administrateur' )
    {
        echo "<input type='date' name='date_archive' placeholder='Format date AA-MM-JJ' >";
        echo "<input class='boutton' type='submit' name='archiver' value='Archiver'><br> \n";
    }
    
    echo "</form> \n"; //fin de la form pour l'affichage du select et des bouttons en dessous.
    
}


//Debut affichage des details du projet selectionné dans la form select
//si le bouttin voir projet est cliqué et qu'un projet est selectionné dans le select
if (isset($_POST['projet']) && isset($_POST['NO_PROJET'])) {
    
    $NO_PROJET = $_POST['NO_PROJET'];
    
    //on cherche les infos de ce projet dans la base de donnée ( table projet)
    $stid = oci_parse($conn, "select NO_PROJET, NOM_PRO, MNT_ALLOUE_PRO, STATUT_PRO, DATE_DEBUT_PRO
                              from TP2_PROJET
                              where NO_PROJET = '$NO_PROJET'");
    oci_execute($stid);
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