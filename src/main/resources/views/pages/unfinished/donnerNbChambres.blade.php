@php

include( app_path() . 'includes/_gestionBase.inc.php');
    // include("_controlesEtGestionErreurs.inc.php");

// CONNEXION AU SERVEUR MYSQL PUIS SÉLECTION DE LA BASE DE DONNÉES festival

    $connexion=connect();
    /*
    if (!$connexion)
    {
        ajouterErreur("Echec de la connexion au serveur MySql");
        afficherErreurs();
        exit();
    }
    */

// SÉLECTIONNER LE NOMBRE DE CHAMBRES SOUHAITÉES


    $idEtab=$_REQUEST['idEtab'];
    $idGroupe=$_REQUEST['idGroupe'];
    $nbChambres=$_REQUEST['nbChambres'];
    $nomGroupe=obtenirNomGroupe($connexion, $idGroupe);

@endphp
    <table class='mt-4 table table-bordered table-dark'>
       <thead class='thead-light'>
        <tr>
        <th colspan='2'>Modification</th>
        </tr>
       </thead>
       <tbody>

   <form method='POST' action='modificationAttributions.php'>
	 <input type='hidden' value='validerModifAttrib' name='action'>
   <input type='hidden' value='{{ $idEtab }}' name='idEtab'>
   <input type='hidden' value='{{ $idGroupe }}' name='idGroupe'>

   <tr><td>Combien de chambres souhaitez-vous pour le
   groupe {{ $nomGroupe }} dans cet établissement ?</td>

    <td>&nbsp;<select name='nbChambres'>
@php
    for ($i=0; $i<=$nbChambres; $i++)
    {
        echo "<option>$i</option>";
    }
@endphp
   </td></tr>
   </select></h5>
   <td>
   <input type='submit' value='Valider' class='btn btn-outline-info active' name='valider'>&nbsp&nbsp&nbsp&nbsp
   <input type='reset' value='Annuler' class='btn btn-outline-info active'  name='Annuler'><br><br>
   </td>
   </form>
   </tbody>
   </table><a href='modificationAttributions.php?action=demanderModifAttrib' class='btn btn-outline-info active'>Retour</a>
