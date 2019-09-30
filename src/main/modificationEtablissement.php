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

// MODIFIER UN ÉTABLISSEMENT

// Déclaration du tableau des civilités
    $tabCivilite=array("M.","Mme","Melle");

    $action=$_REQUEST['action'];
    $id=$_REQUEST['id'];

// Si on ne "vient" pas de ce formulaire, il faut récupérer les données à partir
// de la base (en appelant la fonction obtenirDetailEtablissement) sinon on
// affiche les valeurs précédemment contenues dans le formulaire
    if ($action=='demanderModifEtab')
    {
        $lgEtab=obtenirDetailEtablissement($connexion, $id);

        $nom=$lgEtab['nom'];
        $adresseRue=$lgEtab['adresseRue'];
        $codePostal=$lgEtab['codePostal'];
        $ville=$lgEtab['ville'];
        $tel=$lgEtab['tel'];
        $adresseElectronique=$lgEtab['adresseElectronique'];
        $type=$lgEtab['type'];
        $civiliteResponsable=$lgEtab['civiliteResponsable'];
        $nomResponsable=$lgEtab['nomResponsable'];
        $prenomResponsable=$lgEtab['prenomResponsable'];
        $nombreChambresOffertes=$lgEtab['nombreChambresOffertes'];
    }
    else
    {
        $nom=$_REQUEST['nom'];
        $adresseRue=$_REQUEST['adresseRue'];
        $codePostal=$_REQUEST['codePostal'];
        $ville=$_REQUEST['ville'];
        $tel=$_REQUEST['tel'];
        $adresseElectronique=$_REQUEST['adresseElectronique'];
        $type=$_REQUEST['type'];
        $civiliteResponsable=$_REQUEST['civiliteResponsable'];
        $nomResponsable=$_REQUEST['nomResponsable'];
        $prenomResponsable=$_REQUEST['prenomResponsable'];
        $nombreChambresOffertes=$_REQUEST['nombreChambresOffertes'];

        verifierDonneesEtabM($connexion, $id, $nom, $adresseRue, $codePostal, $ville,
            $tel, $nomResponsable, $nombreChambresOffertes);
        if (nbErreurs()==0)
        {
            modifierEtablissement($connexion, $id, $nom, $adresseRue, $codePostal, $ville,
                $tel, $adresseElectronique, $type, $civiliteResponsable,
                $nomResponsable, $prenomResponsable, $nombreChambresOffertes);
        }
    }

    echo "
<form method='POST' action='modificationEtablissement.php?'>
   <input type='hidden' value='validerModifEtab' name='action'>
   <table class='mt-4 table table-bordered table-dark'>
      <thead class='thead-light'>
       <tr>
       <th colspan='3'>$nom ($id)</th>
       </tr>
      </thead>
      <tbody>
      ";

    echo '
      <tr>
         <td> Nom :</td>
         <td><input type="text" value="'.$nom.'" name="nom" size="50"
         maxlength="45"></td>
      </tr>
      <tr>
         <td> Adresse :</td>
         <td><input type="text" value="'.$adresseRue.'" name="adresseRue"
         size="50" maxlength="45"></td>
      </tr>
      <tr>
         <td> Code postal :</td>
         <td><input type="text" value="'.$codePostal.'" name="codePostal"
         size="4" maxlength="5"></td>
      </tr>
      <tr>
         <td> Ville :</td>
         <td><input type="text" value="'.$ville.'" name="ville" size="40"
         maxlength="35"></td>
      </tr>
      <tr>
         <td> Téléphone :</td>
         <td><input type="text" value="'.$tel.'" name="tel" size ="20"
         maxlength="10"></td>
      </tr>
      <tr>
         <td> E-mail : </td>
         <td><input type="text" value="'.$adresseElectronique.'" name=
         "adresseElectronique" size ="75" maxlength="70"></td>
      </tr>
      <tr>
         <td> Type :</td>
         <td>';
    if ($type==1)
    {
        echo "
               <input type='radio' name='type' value='1' checked>
               Etablissement Scolaire
               <input type='radio' name='type' value='0'>  Autre";
    }
    else
    {
        echo "
                <input type='radio' name='type' value='1'>
                Etablissement Scolaire
                <input type='radio' name='type' value='0' checked> Autre";
    }
    echo "
           </td>
         </tr>
         <tr>
            <td colspan='2' ><strong>Responsable:</strong></td>
         </tr>
         <tr>
            <td> Civilité </td>
            <td> <select name='civiliteResponsable'>";
    for ($i=0; $i<3; $i=$i+1)
        if ($tabCivilite[$i]==$civiliteResponsable)
        {
            echo "<option selected>$tabCivilite[$i]</option>";
        }
        else
        {
            echo "<option>$tabCivilite[$i]</option>";
        }
    echo '
               </select>&nbsp; &nbsp; &nbsp; Nom*:
               <input type="text" value="'.$nomResponsable.'" name=
               "nomResponsable" size="26" maxlength="25">
               &nbsp; &nbsp; &nbsp; Prénom:
               <input type="text"  value="'.$prenomResponsable.'" name=
               "prenomResponsable" size="26" maxlength="25">
            </td>
         </tr>
         <tr>
            <td> Nombre chambres offertes </td>
            <td><input type="text" value="'.$nombreChambresOffertes.'" name=
            "nombreChambresOffertes" size ="2" maxlength="3"></td>
         </tr>
         <tr>
         <td colspan="4"><input type="submit" class="btn btn-outline-info left active" value="Valider" name="valider">
         <input type="reset" class="btn btn-outline-info right active" value="Annuler" name="annuler"></td>
         </tr>
   </table>';

    echo "

   <a href='listeEtablissements.php' class='btn btn-outline-info active'>Retour</a>

</form>";

// En cas de validation du formulaire : affichage des erreurs ou du message de
// confirmation
    if ($action=='validerModifEtab')
    {
        if (nbErreurs()!=0)
        {
            afficherErreurs();
        }
        else
        {
            echo "
      <h5 class='center'>La modification de l'établissement a été effectuée</h5>";
        }
    }
});
