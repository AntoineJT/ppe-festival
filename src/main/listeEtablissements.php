<?php

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
<div class='table-container bg-info text-white'>
  <div class='flex-table header'>
  <div class='flex-row first center'>Etablissement</div>
</div>
";

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
<div class='flex-table row'>
   <div class='flex-row bg-white text-dark'>$nom</div>
   <div class='flex-row bg-white text-dark'><a href='detailEtablissement.php?id=$id'>Voir détail</a></div>
   <div class='flex-row bg-white text-dark'><a href='modificationEtablissement.php?action=demanderModifEtab&amp;id=$id'>Modifier</a></div
";

         // S'il existe déjà des attributions pour l'établissement, il faudra
         // d'abord les supprimer avant de pouvoir supprimer l'établissement
			if (!existeAttributionsEtab($connexion, $id))
			{
            echo "
            <div class='flex-row bg-white text-dark'><a href='suppressionEtablissement.php?action=demanderSupprEtab&amp;id=$id'>Supprimer</a></div>";
         }
         else
         {
            echo "
            <div class='flex-row bg-white text-dark'>&nbsp; </div>";
			}
			echo "
      </div>";
      $lgEtab = $rsEtab->fetch();
   }
   echo "
   <div class='container bg-light text-dark'><a href='creationEtablissement.php?action=demanderCreEtab'>Création d'un établissement</a ></div>

";
