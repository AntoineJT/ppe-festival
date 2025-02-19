<?php

include '_gestionSession.inc.php';

gererSession(function(){
    include("_debut.inc.php");
    include("_gestionBase.inc.php");
    include("_controlesEtGestionErreurs.inc.php");

    // CONNEXION AU SERVEUR MYSQL PUIS SÉLECTION DE LA BASE DE DONNÉES festival

    $connexion = connect();
    if (!$connexion) {
        ajouterErreur("Echec de la connexion au serveur MySql");
        afficherErreurs();
        exit();
    }

    // CONSULTER LES ATTRIBUTIONS DE TOUS LES ÉTABLISSEMENTS

    // IL FAUT QU'IL Y AIT AU MOINS UN ÉTABLISSEMENT OFFRANT DES CHAMBRES POUR
    // AFFICHER LE LIEN VERS LA MODIFICATION
    $nbEtab = obtenirNbEtabOffrantChambres($connexion);
    if ($nbEtab != 0) {
        echo "
       <a href='../modificationAttributions.php?action=demanderModifAttrib' class='mt-4 btn btn-outline-info active'>
       Effectuer ou modifier les attributions</a><br>";

        // POUR CHAQUE ÉTABLISSEMENT : AFFICHAGE D'UN TABLEAU COMPORTANT 2 LIGNES
        // D'EN-TÊTE ET LE DÉTAIL DES ATTRIBUTIONS
        $req = obtenirReqEtablissementsAyantChambresAttribuees();
        $rsEtab = $connexion->query($req);
        $lgEtab = $rsEtab->fetch(PDO::FETCH_ASSOC);
        // BOUCLE SUR LES ÉTABLISSEMENTS AYANT DÉJÀ DES CHAMBRES ATTRIBUÉES
        while ($lgEtab != FALSE) {
            $idEtab = $lgEtab['id'];
            $nomEtab = $lgEtab['nom'];

            echo "
            <table class='mt-4 table table-bordered table-dark'>
               <thead class='thead-light'>
                ";

            $nbOffre = $lgEtab["nombreChambresOffertes"];
            $nbOccup = obtenirNbOccup($connexion, $idEtab);
            // Calcul du nombre de chambres libres dans l'établissement
            $nbChLib = $nbOffre - $nbOccup;

            // AFFICHAGE DE LA 1ÈRE LIGNE D'EN-TÊTE
            echo "
          <tr>
             <th colspan='2' align='left'><strong>$nomEtab</strong>&nbsp;
             (Offre : $nbOffre&nbsp;&nbsp;Disponibilités : $nbChLib)
             </th>
          </tr>
          </thead>";

            // AFFICHAGE DE LA 2ÈME LIGNE D'EN-TÊTE
            echo "
          <tr>
             <td width='65%' align='left'><i><strong>Nom groupe</strong></i></td>
             <td width='35%' align='left'><i><strong>Chambres attribuées</strong></i>
             </td>
          </tr>";

            // AFFICHAGE DU DÉTAIL DES ATTRIBUTIONS : UNE LIGNE PAR GROUPE AFFECTÉ
            // DANS L'ÉTABLISSEMENT
            $req = obtenirReqGroupesEtab($idEtab);
            $rsGroupe = $connexion->query($req);
            $lgGroupe = $rsGroupe->fetch(PDO::FETCH_ASSOC);

            // BOUCLE SUR LES GROUPES (CHAQUE GROUPE EST AFFICHÉ EN LIGNE)
            while ($lgGroupe != FALSE) {
                $idGroupe = $lgGroupe['id'];
                $nomGroupe = $lgGroupe['nom'];
                echo "
             <tr>
                <td width='65%' align='left'>$nomGroupe</td>";
                // On recherche si des chambres ont déjà été attribuées à ce groupe
                // dans l'établissement
                $nbOccupGroupe = obtenirNbOccupGroupe($connexion, $idEtab, $idGroupe);
                echo "
                <td width='35%' align='left'>$nbOccupGroupe</td>
             </tr>";
                $lgGroupe = $rsGroupe->fetch(PDO::FETCH_ASSOC);
            } // Fin de la boucle sur les groupes

            echo "
          </table><br>";
            $lgEtab = $rsEtab->fetch(PDO::FETCH_ASSOC);
        } // Fin de la boucle sur les établissements
    }
});
