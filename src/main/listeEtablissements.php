<?php

include('_gestionSession.inc.php');

gererSession(function(){
    include("_debut.inc.php");
    include("_gestionBase.inc.php");
    include("_controlesEtGestionErreurs.inc.php");

// CONNEXION AU SERVEUR MYSQL PUIS SÉLECTION DE LA BASE DE DONNÉES festival

    $connexion = connect();
    if (!$connexion)
    {
        ajouterErreur("Echec de la connexion au serveur MySql");
        afficherErreurs();
        exit();
    }

// AFFICHER L'ENSEMBLE DES ÉTABLISSEMENTS
// CETTE PAGE CONTIENT UN TABLEAU CONSTITUÉ D'1 LIGNE D'EN-TÊTE ET D'1 LIGNE PAR
// ÉTABLISSEMENT

    echo "
<table width='70%' cellspacing='0' cellpadding='0' align='center' 
class='tabNonQuadrille'>
   <tr class='enTeteTabNonQuad'>
      <td colspan='4'>Etablissements</td>
   </tr>";

    $req = obtenirReqEtablissements();
    $rsEtab = $connexion->query($req);
    $rsEtab->setFetchMode(PDO::FETCH_ASSOC);
    $lgEtab = $rsEtab->fetch();

    // BOUCLE SUR LES ÉTABLISSEMENTS
    while ($lgEtab != FALSE)
    {
        $id = $lgEtab['id'];
        $nom = $lgEtab['nom'];
        echo "
    <tr class='ligneTabNonQuad'>
     <td width='52%'>$nom</td>
     
     <td width='16%' align='center'> 
     <a href='detailEtablissement.php?id=$id'>
     Voir détail</a></td>
     
     <td width='16%' align='center'> 
     <a href='modificationEtablissement.php?action=demanderModifEtab&amp;id=$id'>
     Modifier</a></td>";

        // S'il existe déjà des attributions pour l'établissement, il faudra
        // d'abord les supprimer avant de pouvoir supprimer l'établissement
        if (!existeAttributionsEtab($connexion, $id))
        {
            echo "
        <td width='16%' align='center'> 
        <a href='suppressionEtablissement.php?action=demanderSupprEtab&amp;id=$id'>
        Supprimer</a></td>";
        }
        else
        {
            echo "
            <td width='16%'>&nbsp; </td>";
            }
            echo "
      </tr>";
            $lgEtab = $rsEtab->fetch();
        }
        echo "
   <tr class='ligneTabNonQuad'>
      <td colspan='4'><a href='creationEtablissement.php?action=demanderCreEtab'>
      Création d'un établissement</a ></td>
  </tr>
</table>";
});
