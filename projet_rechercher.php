<?php
$proteger = true;

include('header.php');

if(isset($_POST['annuler']))
{
    header('Location: liste_projets.php');
}

if(isset($_POST['NOM_PRO']) || isset($_POST['MNT_ALLOUE_PRO']) || isset($_POST['DATE']) )
{
    $NOM_PRO = $_POST['NOM_PRO'];
    $MNT_ALLOUE_PRO = $_POST['MNT_ALLOUE_PRO'];
    $DATE = $_POST['DATE'];
    
    if($NOM_PRO === "")
    {
        $NOM_PRO = "%";
    }
    else
    {
        $NOM_PRO = $_POST['NOM_PRO'];
        $NOM_PRO = "%".$NOM_PRO."%";
    }
    if($MNT_ALLOUE_PRO === "")
    {
        $MNT_ALLOUE_PRO = "%";
    }
    else 
    {
        $MNT_ALLOUE_PRO = $_POST['MNT_ALLOUE_PRO'];
    }
    if($DATE === "")
    {
        $DATE = "%";
        $stid = oci_parse($conn, "select NO_PROJET
                                    from TP2_PROJET
                                    where NOM_PRO like '$NOM_PRO' 
                                    and MNT_ALLOUE_PRO like '$MNT_ALLOUE_PRO' 
                                    order by DATE_DEBUT_PRO desc");
    }
    else 
    {
        $DATE = $_POST['DATE'];
        $stid = oci_parse($conn, "select NO_PROJET
                                    from TP2_PROJET
                                    where NOM_PRO like '$NOM_PRO' 
                                    and MNT_ALLOUE_PRO like '$MNT_ALLOUE_PRO' 
                                    and DATE_DEBUT_PRO <= to_date('$DATE','RR-MM-DD') 
                                    and DATE_FIN_PRO >= to_date('$DATE','RR-MM-DD') 
                                    order by DATE_DEBUT_PRO desc");
    }
    
    //on execute la réquête
    oci_execute($stid);
    
    while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false)
    {
        $NO_PROJET = $row[NO_PROJET];
        $resultat[] = $NO_PROJET;
    }
    $_SESSION['resultat'] = $resultat;
    
    header('Location: liste_projets.php?resultat_recherche='.$_SESSION['resultat']);
   
}


echo '<div><h2> Rechercher Projet</h2></div>';


echo "<form action='projet_rechercher.php' method='post' > \n";
echo "<label for='fprojectname'>Nom du projet : </label> \n";
echo "<input type='text' name='NOM_PRO'><br> \n";
echo "<label for='fmontantalloue'>Montant alloué : </label> \n";
echo "<input type='text' name='MNT_ALLOUE_PRO'><br> \n";
echo "<label for='fdateprojet'>Date du projet: </label> \n";
echo "<input type='text' placeholder='Format date AA-MM-JJ' name='DATE'> <br> \n";
echo "<input class='boutton' type='submit' name='ok' value=' OK '> \n";
echo "<input class='boutton' type = 'submit' name='annuler' value='Annuler'> \n";
echo "</form>\n";


?>

<footer>
<?php
//pied de page
include 'footer.php';

?>
             
</footer>