<?php
$proteger = true;

include('header.php');


if(isset($_POST['bouton_annuler'])){
    header('Location: liste_projets.php');
}

if(isset($_POST['bouton_membre']) && isset($_POST['MEMBRE_EQUIPE'])){
    $NO_MEMBRE = $_POST['MEMBRE_EQUIPE'];
    $_SESSION['NO_PROJET'];
    header('Location: un_membre.php?NO_MEMBRE='.$NO_MEMBRE);   
}

if(isset($_POST['bouton_membre']) && !isset($_POST['MEMBRE_EQUIPE'])){
    $NO_PROJET = $_POST['NO_PROJET'];
    header('Location: un_projet.php?NO_PROJET='.$NO_PROJET);  
}

if(isset($_POST['bouton_ok']) && isset($_POST['NOM_PRO']) && isset($_POST['MNT_ALLOUE_PRO']) && isset($_POST['STATUT_PRO']) && isset($_POST['DATE_DEBUT_PRO']) && isset($_POST['DATE_FIN_PRO']) && $_SESSION['update'] == false){
    
    $NO_PROJET = $_POST['NO_PROJET'];
    $NOM_PRO = $_POST['NOM_PRO'];
    $MNT_ALLOUE_PRO = $_POST['MNT_ALLOUE_PRO'];
    $STATUT_PRO = $_POST['STATUT_PRO'];
    $DATE_DEBUT_PRO = $_POST['DATE_DEBUT_PRO'];
    $DATE_FIN_PRO = $_POST['DATE_FIN_PRO'];
    
    $stid = oci_parse($conn, "insert into TP2_PROJET (NO_PROJET, NOM_PRO, MNT_ALLOUE_PRO, STATUT_PRO, DATE_DEBUT_PRO, DATE_FIN_PRO)
                              values (NO_PROJET_SEQ.nextval, '$NOM_PRO', '$MNT_ALLOUE_PRO', '$STATUT_PRO', to_date('$DATE_DEBUT_PRO','RR-MM-DD'), to_date('$DATE_FIN_PRO','RR-MM-DD') ) ");
    
    oci_execute($stid);
    header('Location: liste_projets.php');    
}


if(isset($_POST['bouton_ok']) && isset($_POST['NOM_PRO']) && isset($_POST['MNT_ALLOUE_PRO']) && isset($_POST['STATUT_PRO']) && isset($_POST['DATE_DEBUT_PRO']) && isset($_POST['DATE_FIN_PRO']) && $_SESSION['update'] == true){
    
    $NO_PROJET = $_POST['NO_PROJET'];
    $NOM_PRO = $_POST['NOM_PRO'];
    $MNT_ALLOUE_PRO = $_POST['MNT_ALLOUE_PRO'];
    $STATUT_PRO = $_POST['STATUT_PRO'];
    $DATE_DEBUT_PRO = $_POST['DATE_DEBUT_PRO'];
    $DATE_FIN_PRO = $_POST['DATE_FIN_PRO'];
    
    $stid = oci_parse($conn, "update TP2_PROJET
                              set NOM_PRO ='$NOM_PRO', MNT_ALLOUE_PRO = '$MNT_ALLOUE_PRO', STATUT_PRO='$STATUT_PRO', DATE_DEBUT_PRO ='$DATE_DEBUT_PRO', DATE_FIN_PRO = '$DATE_FIN_PRO'
                              where NO_PROJET = '$NO_PROJET' ");
    oci_execute($stid);
    header('Location: liste_projets.php');
}


if(isset($_GET['NO_PROJET'])){
    echo '<div><h2> Modifier le Projet </h2></div>';
    
    $_SESSION['update'] = true;
    
    $NO_PROJET = $_GET['NO_PROJET'];
    
    $stid_projet = oci_parse($conn, "select NO_PROJET, NOM_PRO, MNT_ALLOUE_PRO, STATUT_PRO, DATE_DEBUT_PRO, DATE_FIN_PRO
                              from TP2_PROJET
                              where NO_PROJET = '$NO_PROJET' ");
    oci_execute($stid_projet);
    
    $PROJET = oci_fetch_array($stid_projet, OCI_ASSOC+OCI_RETURN_NULLS);
    
    $NOM_PRO = $PROJET ['NOM_PRO'];
    $MNT_ALLOUE_PRO = $PROJET ['MNT_ALLOUE_PRO'];
    $STATUT_PRO = $PROJET ['STATUT_PRO'];
    $DATE_DEBUT_PRO = $PROJET ['DATE_DEBUT_PRO'];
    $DATE_FIN_PRO = $PROJET ['DATE_FIN_PRO'];
    
    
    $stid_rapport = oci_parse($conn, "select R.NO_RAPPORT, R.NOM_FICHIER_RAP, E.NOM_ETAT_RAP
                              from TP2_RAPPORT R, TP2_RAPPORT_ETAT E
                              where R.CODE_ETAT_RAP = E.CODE_ETAT_RAP and R.NO_PROJET = '$NO_PROJET' ");
    oci_execute($stid_rapport);
    
    $stid_equipe= oci_parse($conn, "select E.NO_MEMBRE, M.PRENOM_MEM, M.NOM_MEM, M.COURRIEL_MEM
                              from TP2_EQUIPE_PROJET E, TP2_MEMBRE M
                              where E.NO_MEMBRE = M.NO_MEMBRE and E.NO_PROJET = '$NO_PROJET' ");
    oci_execute($stid_equipe);
      

    echo "<form action='un_projet.php' method='post' >\n";
    
    echo "<label for='NO_PROJET'> Numéro projet :</label> \n";
    echo "<input name='NO_PROJET' value='$NO_PROJET' readonly><br> \n";
    echo "<label for='NOM_PRO'> Nom: </label> \n";
    echo "<input type='text' name='NOM_PRO' value='$NOM_PRO'><br> \n";
    echo "<label for='MNT_ALLOUE_PRO'> Montant alloué: </label> \n";
    echo "<input type='text' name='MNT_ALLOUE_PRO' value='$MNT_ALLOUE_PRO'><br> \n";
    echo "<label for='DATE_DEBUT_PRO'> Début: </label> \n";
    echo "<input type='datetime-local' name='DATE_DEBUT_PRO' value='$DATE_DEBUT_PRO'><br> \n";
    echo "<label for='DATE_FIN_PRO'> Fin :</label> \n";
    echo "<input type='datetime-local' name='DATE_FIN_PRO' value='$DATE_FIN_PRO'><br> \n";
    
    echo "<label for='STATUT_PRO'> Statut :</label> \n";
    echo "<select name='STATUT_PRO' > \n";
    echo "  <option selected value='$STATUT_PRO'>$STATUT_PRO</option>\n";
    echo "  <option value='Accepté'>Accepté</option>\n";
    echo "  <option value='Préliminaire'>Préliminaire</option>\n";
    echo "  <option value='Intermédiaire'>Intermédiaire</option>\n";
    echo "  <option value='Final'>Final</option>\n";
    echo "  <option value='Terminé'>Terminé</option>\n";
    echo "</select> \n";
    
    echo "</br> \n";
    echo "<label for='STATUT_RAPPORT'> Rapport :</label> \n";
    echo "<select  size='5' name='STATUT_RAPPORT' > \n"; 
    while (($row = oci_fetch_array($stid_rapport, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
             $NO_RAPPORT = $row['NO_RAPPORT'];
             $NOM_FICHIER_RAP = $row['NOM_FICHIER_RAP'];
             $NOM_ETAT_RAP = $row  ['NOM_ETAT_RAP'];
             
            //on affiche une cellule du tableau html i.e.: <td> ... </td>
             echo "  <option value='$NO_RAPPORT'>".'Numero du rapport: '.$NO_RAPPORT.' | Nom du fichier: '.$NOM_FICHIER_RAP.' | Etat du rapport: '.$NOM_ETAT_RAP."</option>\n";       
    }
    echo "</select> \n";
    
    echo "</br> \n";
    echo "<label for='MEMBRE_EQUIPE'>  Membre Équipe:</label> \n";
    echo "<select  size='10' name='MEMBRE_EQUIPE' > \n";
    
    while (($row = oci_fetch_array($stid_equipe, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
        $NO_MEMBRE = $row['NO_MEMBRE'];
        $PRENOM_MEM = $row['PRENOM_MEM'];
        $NOM_MEM = $row['NOM_MEM'];
        $COURRIEL_MEM = $row  ['COURRIEL_MEM'];
        
        //on affiche une cellule du tableau html i.e.: <td> ... </td>
        echo "  <option value='$NO_MEMBRE'>".'Prenom: '.$PRENOM_MEM.' | Nom: '.$NOM_MEM.' | Courriel: '.$COURRIEL_MEM."</option>\n";
    }
    echo "</select> \n";
    
    echo "</br> \n";
    echo "<input type='submit' name='bouton_membre' value='Détails membre'><br> \n";
    echo "<input type='submit' name='bouton_ok' value=' OK '><br> \n";
    echo " <input type='submit' name='bouton_annuler' value='Annuler'> \n";  
    
    echo "</form> \n";
}
else
{
    echo '<div><h2> Créer un Projet </h2></div>';
    
    $_SESSION['update'] = false;
    
    echo "<form action='un_projet.php' method='post' >\n";
    
    echo "<label for='NO_PROJET'> Numéro projet: </label> \n";
    echo "<input name='NO_PROJET'  readonly><br> \n";
    echo "<label for='NOM_PRO'> Nom: </label> \n";
    echo "<input type='text' name='NOM_PRO' ><br> \n";
    
    if($_SESSION['TYPE_MEMBRE'] === 'administrateur' || $_SESSION['TYPE_MEMBRE'] === 'superviseur'){
        echo "<label for='MNT_ALLOUE_PRO'> Montant alloué:</label> \n";
        echo "<input type='text' name='MNT_ALLOUE_PRO' ><br> \n";
    }
    
    echo "<label for='DATE_DEBUT_PRO'> Début:</label> \n";
    echo "<input type='datetime-local' placeholder='Format date AA-MM-JJ'name='DATE_DEBUT_PRO' ><br> \n";
    echo "<label for='DATE_FIN_PRO'> Fin:</label> \n";
    echo "<input type='datetime-local' placeholder='Format date AA-MM-JJ' name='DATE_FIN_PRO' ><br> \n";
    
    echo "<label for='STATUT_PRO'> Statut:</label> \n";
    echo "<select name='STATUT_PRO' > \n";
    echo "  <option value='Accepté'>Accepté</option>\n";
    echo "  <option value='Préliminaire'>Préliminaire</option>\n";
    echo "  <option value='Intermédiaire'>Intermédiaire</option>\n";
    echo "  <option value='Final'>Final</option>\n";
    echo "  <option value='Terminé'>Terminé</option>\n";
    echo "</select> \n";
    
    echo "</br> \n";
    echo "<input type='submit' name='bouton_ok' value=' OK '><br> \n";
    echo " <input type='submit' name='bouton_annuler' value='Annuler'> \n"; 
    echo "</form> \n";
}


?>


<footer>
<?php
//pied de page
include 'footer.php';

?>
             
</footer>



