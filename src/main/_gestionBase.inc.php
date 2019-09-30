<?php

// FONCTIONS DE CONNEXION

// TODO Sécuriser ça en faisant des requêtes préparées
function connect()
{
   $bd = 'festival';
   $hote = 'localhost';
   $login = 'festival';
   $mdp = 'secret';
   try {
      $dbh = new PDO("mysql:dbname=$bd;host=$hote;charset=utf8", $login, $mdp);
   } catch(PDOException $e){
      echo 'Exception reçue : ', $e->getMessage(), '\n';
      die();
   }
   return $dbh;
}

// FONCTIONS DE GESTION DES ÉTABLISSEMENTS

function obtenirReqEtablissements()
{
   return "SELECT id, nom FROM Etablissement ORDER BY id";
}

function obtenirReqEtablissementsOffrantChambres()
{
   return "SELECT id, nom, nombreChambresOffertes FROM Etablissement WHERE 
         nombreChambresOffertes!=0 ORDER BY id";
}

function obtenirReqEtablissementsAyantChambresAttribuees()
{
   return "SELECT DISTINCT id, nom, nombreChambresOffertes FROM Etablissement, 
         Attribution WHERE id = idEtab ORDER BY id";
}

function effectuerRequetePreparee($connexion, $request, $array) { // TODO refactor rename to effectuerRequetePreparee
    $response = $connexion->prepare($request);
    $response->execute($array);
    return $response;
}

function obtenirDetailEtablissement($connexion, $id)
{
    return effectuerRequetePreparee($connexion,"SELECT * FROM Etablissement WHERE id='?'", [$id])->fetch(PDO::FETCH_ASSOC);
}

function supprimerEtablissement($connexion, $id)
{
    effectuerRequetePreparee($connexion, "DELETE FROM Etablissement WHERE id='?'", [$id]);
}

function modifierEtablissement($connexion, $id, $nom, $adresseRue, $codePostal,
                               $ville, $tel, $adresseElectronique, $type,
                               $civiliteResponsable, $nomResponsable,
                               $prenomResponsable, $nombreChambresOffertes)
{
    list($nom, $adresseRue, $ville, $adresseElectronique, $nomResponsable, $prenomResponsable) = formatInfos($nom, $adresseRue, $ville, $adresseElectronique, $nomResponsable, $prenomResponsable);

    $req = "UPDATE Etablissement SET nom=':nom',adresseRue=':adRue',
         codePostal=':cp',ville=':ville',tel=':tel',
         adresseElectronique=':mail',type=':type',
         civiliteResponsable=':civilResp',nomResponsable=
         ':nomResp',prenomResponsable=':pnomResp',
         nombreChambresOffertes=':nbChambOff' WHERE id=':id'";

   $array = [
       ':nom' => $nom,
       ':adRue' => $adresseRue,
       ':cp' => $codePostal,
       ':ville' => $ville,
       ':tel' => $tel,
       ':mail' => $adresseElectronique,
       ':type' => $type,
       ':civilResp' => $civiliteResponsable,
       ':nomResp' => $nomResponsable,
       ':pnomResp' => $prenomResponsable,
       ':nbChambOff' => $nombreChambresOffertes,
       ':id' => $id
   ];

   effectuerRequetePreparee($connexion, $req, $array);
}

function formatInfos($nom, $adresseRue, $ville, $adresseElectronique, $nomResponsable, $prenomResponsable)
{
    $nom = str_replace("'", "''", $nom);
    $adresseRue = str_replace("'", "''", $adresseRue);
    $ville = str_replace("'", "''", $ville);
    $adresseElectronique = str_replace("'", "''", $adresseElectronique);
    $nomResponsable = str_replace("'", "''", $nomResponsable);
    $prenomResponsable = str_replace("'", "''", $prenomResponsable);
    return array($nom, $adresseRue, $ville, $adresseElectronique, $nomResponsable, $prenomResponsable);
}

function creerEtablissement($connexion, $id, $nom, $adresseRue, $codePostal,
                            $ville, $tel, $adresseElectronique, $type,
                            $civiliteResponsable, $nomResponsable,
                            $prenomResponsable, $nombreChambresOffertes)
{
    list($nom, $adresseRue, $ville, $adresseElectronique, $nomResponsable, $prenomResponsable) = formatInfos($nom, $adresseRue, $ville, $adresseElectronique, $nomResponsable, $prenomResponsable);

    $req = "INSERT INTO Etablissement VALUES (':id', ':nom', ':adRue', 
         ':cp', ':ville', ':tel', ':mail', ':type', 
         ':civilResp', ':nomResp', ':pnomResp',
         ':nbChambOff')";

    $array = [
        ':nom' => $nom,
        ':adRue' => $adresseRue,
        ':cp' => $codePostal,
        ':ville' => $ville,
        ':tel' => $tel,
        ':mail' => $adresseElectronique,
        ':type' => $type,
        ':civilResp' => $civiliteResponsable,
        ':nomResp' => $nomResponsable,
        ':pnomResp' => $prenomResponsable,
        ':nbChambOff' => $nombreChambresOffertes,
        ':id' => $id
    ];

    effectuerRequetePreparee($connexion, $req, $array);
}

function estUnIdEtablissement($connexion, $id)
{
    return effectuerRequetePreparee($connexion, "SELECT * FROM Etablissement WHERE id='?'", [$id])->fetch(PDO::FETCH_ASSOC);
}

function estUnNomEtablissement($connexion, $mode, $id, $nom)
{
    $nom = str_replace("'", "''", $nom);
    // S'il s'agit d'une création, on vérifie juste la non existence du nom sinon
    // on vérifie la non existence d'un autre établissement (id!='$id') portant
    // le même nom
    if ($mode == 'C')
    {
       return effectuerRequetePreparee($connexion, "SELECT * FROM Etablissement WHERE nom='?'", [$nom])->fetch(PDO::FETCH_ASSOC);
    }
    return effectuerRequetePreparee($connexion, "SELECT * FROM Etablissement WHERE nom='?' AND id!='?'", [$nom, $id])->fetch(PDO::FETCH_ASSOC);
}

/*
function obtenirNbEtab($connexion)
{
   $req="SELECT COUNT(*) AS nombreEtab FROM Etablissement";
   $rsEtab = $connexion->query($req);
   $lgEtab = $rsEtab->fetch(PDO::FETCH_ASSOC);
   return $lgEtab["nombreEtab"];
}
*/

function obtenirNbEtabOffrantChambres($connexion)
{
    $response = $connexion->query("SELECT COUNT(*) AS nombreEtabOffrantChambres FROM Etablissement WHERE 
         nombreChambresOffertes!=0")->fetch(PDO::FETCH_ASSOC);
    return $response["nombreEtabOffrantChambres"];
}

// Retourne false si le nombre de chambres transmis est inférieur au nombre de 
// chambres occupées pour l'établissement transmis 
// Retourne true dans le cas contraire
function estModifOffreCorrecte($connexion, $idEtab, $nombreChambres)
{
   $nbOccup = obtenirNbOccup($connexion, $idEtab);
   return $nombreChambres >= $nbOccup;
}

// FONCTIONS RELATIVES AUX GROUPES

function obtenirReqIdNomGroupesAHeberger()
{
   return "SELECT id, nom FROM Groupe WHERE hebergement='O' ORDER BY id";
}

function obtenirNomGroupe($connexion, $id)
{
    $response = effectuerRequetePreparee($connexion, "SELECT nom FROM Groupe WHERE id='?'", [$id])->fetch(PDO::FETCH_ASSOC);
    return $response["nom"];
}

// FONCTIONS RELATIVES AUX ATTRIBUTIONS

// Teste la présence d'attributions pour l'établissement transmis    
function existeAttributionsEtab($connexion, $id)
{
    return effectuerRequetePreparee($connexion, "SELECT * FROM Attribution WHERE idEtab='?'", [$id])->fetch(PDO::FETCH_ASSOC);
}

// Retourne le nombre de chambres occupées pour l'id étab transmis
function obtenirNbOccup($connexion, $idEtab)
{
    $response = effectuerRequetePreparee($connexion, "SELECT IFNULL(SUM(nombreChambres), 0) AS totalChambresOccup FROM
        Attribution WHERE idEtab='?'", [$idEtab])->fetch(PDO::FETCH_ASSOC);
   return $response["totalChambresOccup"];
}

// Met à jour (suppression, modification ou ajout) l'attribution correspondant à
// l'id étab et à l'id groupe transmis
function modifierAttribChamb($connexion, $idEtab, $idGroupe, $nbChambres)
{
    $array = [$idEtab, $idGroupe];

    $response = effectuerRequetePreparee($connexion, "SELECT COUNT(*) AS nombreAttribGroupe FROM Attribution WHERE idEtab=
        '?' AND idGroupe='?'", $array)->fetch(PDO::FETCH_ASSOC);

    if ($nbChambres == 0) {
        effectuerRequetePreparee($connexion, "DELETE FROM Attribution WHERE idEtab='?' AND idGroupe='?'", $array);
    } else {
      if ($response["nombreAttribGroupe"] != 0) {
          effectuerRequetePreparee($connexion, "UPDATE Attribution SET nombreChambres=$nbChambres WHERE idEtab=
              '?' AND idGroupe='?'", $array);
      } else {
          effectuerRequetePreparee($connexion, "INSERT INTO Attribution VALUES('?', '?', ?)", [$idEtab, $idGroupe, $nbChambres]);
      }
   }
}

// Retourne la requête permettant d'obtenir les id et noms des groupes affectés
// dans l'établissement transmis
function obtenirReqGroupesEtab($connexion, $id)
{
    return effectuerRequetePreparee($connexion, "SELECT DISTINCT id, nom FROM Groupe, Attribution WHERE
        Attribution.idGroupe=Groupe.id AND idEtab='?'", [$id]);
}

// Retourne le nombre de chambres occupées par le groupe transmis pour l'id étab
// et l'id groupe transmis
function obtenirNbOccupGroupe($connexion, $idEtab, $idGroupe)
{
    $rsAttribGroupe = effectuerRequetePreparee($connexion, "SELECT nombreChambres FROM Attribution WHERE idEtab='$idEtab'
        AND idGroupe='?'", [$idGroupe]);
    if ($lgAttribGroupe = $rsAttribGroupe->fetch(PDO::FETCH_ASSOC))
        return $lgAttribGroupe["nombreChambres"];
    return 0;
}
