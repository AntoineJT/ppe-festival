<?php

include '_gestionSession.inc.php';

gererSession(function(){
    include("_debut.inc.php");
    include("_gestionBase.inc.php");
    include("_controlesEtGestionErreurs.inc.php");

// CONNEXION AU SERVEUR MYSQL PUIS SÉLECTION DE LA BASE DE DONNÉES festival

    $connexion=connect();
    if (!$connexion)
    {
        ajouterErreur("Echec de la connexion au serveur MySql");
        afficherErreurs();
        exit();
    }

// SUPPRIMER UN ÉTABLISSEMENT

    $id=$_REQUEST['id'];

    $lgEtab=obtenirDetailEtablissement($connexion, $id);
    $nom=$lgEtab['nom'];

// Cas 1ère étape (on vient de listeEtablissements.php)

    if ($_REQUEST['action']=='demanderSupprEtab')
    {
        echo "
   <br><h5 class='center'>Souhaitez-vous vraiment supprimer l'établissement $nom ? 
   <br><br>
   <a href='suppressionEtablissement.php?action=validerSupprEtab&amp;id=$id'>
   Oui</a>&nbsp; &nbsp; &nbsp; &nbsp;
   <a href='listeEtablissements.php?'>Non</a></h5>";
    }

// Cas 2ème étape (on vient de suppressionEtablissement.php)

    else
    {
        supprimerEtablissement($connexion, $id);
        echo "
   <br><br><div class='center'><h5>L'établissement $nom a été supprimé</h5>
   <a href='listeEtablissements.php?'>Retour</a></div>";
    }
});
