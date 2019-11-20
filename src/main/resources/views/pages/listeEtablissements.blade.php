@php

// include('_gestionSession.inc.php');

// gererSession(function(){
//    include("_debut.inc.php");
    include(app_path() . '/includes/_gestionBase.inc.php');
//    include("_controlesEtGestionErreurs.inc.php");

// CONNEXION AU SERVEUR MYSQL PUIS SÉLECTION DE LA BASE DE DONNÉES festival

    $connexion = connect();
/*
    if (!$connexion)
    {
        ajouterErreur("Echec de la connexion au serveur MySql");
        afficherErreurs();
        exit();
    }
*/

// AFFICHER L'ENSEMBLE DES ÉTABLISSEMENTS
// CETTE PAGE CONTIENT UN TABLEAU CONSTITUÉ D'1 LIGNE D'EN-TÊTE ET D'1 LIGNE PAR
// ÉTABLISSEMENT

@endphp
    <table class='mt-4 table table-bordered table-dark'>
       <thead class='thead-light'>
        <tr>
        <th colspan='4'>Ligues</th>
        </tr>
       </thead>
       <tbody>

@php
    $req = obtenirReqEtablissements();
    $rsEtab = $connexion->query($req);
    $rsEtab->setFetchMode(PDO::FETCH_ASSOC);
    $lgEtab = $rsEtab->fetch();

    // BOUCLE SUR LES ÉTABLISSEMENTS
    while ($lgEtab != FALSE)
    {
        $id = $lgEtab['id'];
        $nom = $lgEtab['nom'];
@endphp
    <tr>
     <td width='52%'>{{ $nom }}</td>
     <td><a href='detailEtablissement.php?id=$id' class='btn btn-outline-info active'>Voir détail</a></td>

     <td><a href='modificationEtablissement.php?action=demanderModifEtab&amp;id=$id' class='btn btn-outline-info active'>Modifier</a></td>
@php

        // S'il existe déjà des attributions pour l'établissement, il faudra
        // d'abord les supprimer avant de pouvoir supprimer l'établissement
        if (!existeAttributionsEtab($connexion, $id))
        {
            echo "
        <td>
        <a href='suppressionEtablissement.php?action=demanderSupprEtab&amp;id=$id' class='btn btn-outline-info active'>Supprimer</a></td>
        ";
        }
        else
        {
            echo "
            <td>&nbsp; </td>
              </tr>";
            }
            echo "
      </tr>
      ";
            $lgEtab = $rsEtab->fetch();
        }
@endphp
        </tbody>
        </table>
      <a href='creationEtablissement.php?action=demanderCreEtab' class='btn btn-outline-info active'>
      Création d'un établissement</a>
// });
