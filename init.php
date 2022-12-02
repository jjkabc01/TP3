<?php

/* TP3 - Équipe 6
 * Auteur
 * Kekeli   
 * Check Sangaré
 * Aimé
 */



// connexion à la base de donnée oracle
$conn = oci_connect('C##CHSAN29', 'IFT-2004!', 'fsg-p-ora01.fsg.ulaval.ca:1521/ora19c.fsg.ulaval.ca', 'AL32UTF8');


if(!$conn)
{
    die("Merci de vérifier les informations de connexion à la base de donnée "); 
}

                                                                                                                                                                                                                                                                                                                                                                                                                                                         
// fonction pour la gestion des erreur suite à l'éxécution d'une réquête
function execute_commande($commande) {
    if($commande)
    {
        oci_execute($commande);
    }
    else 
    {
        echo("Un problème est survenu dans l'éxécution de la réquête ");

    }
}


