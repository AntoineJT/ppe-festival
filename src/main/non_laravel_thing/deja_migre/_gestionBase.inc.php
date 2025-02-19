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
   $req = "SELECT id, nom FROM Etablissement ORDER BY id";
   return $req;
}

function obtenirReqEtablissementsOffrantChambres()
{
   $req="SELECT id, nom, nombreChambresOffertes FROM Etablissement WHERE 
         nombreChambresOffertes!=0 ORDER BY id";
   return $req;
}

function obtenirReqEtablissementsAyantChambresAttribuees()
{
   $req="SELECT DISTINCT id, nom, nombreChambresOffertes FROM Etablissement, 
         Attribution WHERE id = idEtab ORDER BY id";
   return $req;
}

function obtenirDetailEtablissement($connexion, $id)
{
   $req = "SELECT * FROM Etablissement WHERE id='$id'";
   $rsEtab = $connexion->query($req);
   return $rsEtab->fetch(PDO::FETCH_ASSOC);
}

function supprimerEtablissement($connexion, $id)
{
   $req = "DELETE FROM Etablissement WHERE id='$id'";
   $connexion->query($req);
}
 
function modifierEtablissement($connexion, $id, $nom, $adresseRue, $codePostal, 
                               $ville, $tel, $adresseElectronique, $type, 
                               $civiliteResponsable, $nomResponsable, 
                               $prenomResponsable, $nombreChambresOffertes)
{  
   $nom=str_replace("'", "''", $nom);
   $adresseRue=str_replace("'","''", $adresseRue);
   $ville=str_replace("'","''", $ville);
   $adresseElectronique=str_replace("'","''", $adresseElectronique);
   $nomResponsable=str_replace("'","''", $nomResponsable);
   $prenomResponsable=str_replace("'","''", $prenomResponsable);
  
   $req="UPDATE Etablissement set nom='$nom',adresseRue='$adresseRue',
         codePostal='$codePostal',ville='$ville',tel='$tel',
         adresseElectronique='$adresseElectronique',type='$type',
         civiliteResponsable='$civiliteResponsable',nomResponsable=
         '$nomResponsable',prenomResponsable='$prenomResponsable',
         nombreChambresOffertes='$nombreChambresOffertes' WHERE id='$id'";
   
   $connexion->query($req);
}

function creerEtablissement($connexion, $id, $nom, $adresseRue, $codePostal, 
                            $ville, $tel, $adresseElectronique, $type, 
                            $civiliteResponsable, $nomResponsable, 
                            $prenomResponsable, $nombreChambresOffertes)
{ 
   $nom=str_replace("'", "''", $nom);
   $adresseRue=str_replace("'","''", $adresseRue);
   $ville=str_replace("'","''", $ville);
   $adresseElectronique=str_replace("'","''", $adresseElectronique);
   $nomResponsable=str_replace("'","''", $nomResponsable);
   $prenomResponsable=str_replace("'","''", $prenomResponsable);
   
   $req="insert into Etablissement values ('$id', '$nom', '$adresseRue', 
         '$codePostal', '$ville', '$tel', '$adresseElectronique', '$type', 
         '$civiliteResponsable', '$nomResponsable', '$prenomResponsable',
         '$nombreChambresOffertes')";
   
   $connexion->query($req);
}

function estUnIdEtablissement($connexion, $id)
{
   $req="SELECT * FROM Etablissement WHERE id='$id'";
   $rsEtab = $connexion->query($req);
   return $rsEtab->fetch(PDO::FETCH_ASSOC);
}

function estUnNomEtablissement($connexion, $mode, $id, $nom)
{
   $nom=str_replace("'", "''", $nom);
   // S'il s'agit d'une création, on vérifie juste la non existence du nom sinon
   // on vérifie la non existence d'un autre établissement (id!='$id') portant 
   // le même nom
   if ($mode=='C')
   {
      $req="SELECT * FROM Etablissement WHERE nom='$nom'";
   }
   else
   {
      $req="SELECT * FROM Etablissement WHERE nom='$nom' AND id!='$id'";
   }
   $rsEtab = $connexion->query($req);
   return $rsEtab->fetch(PDO::FETCH_ASSOC);
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
   $req="SELECT COUNT(*) AS nombreEtabOffrantChambres FROM Etablissement WHERE 
         nombreChambresOffertes!=0";
   $rsEtabOffrantChambres = $connexion->query($req);
   $lgEtabOffrantChambres = $rsEtabOffrantChambres->fetch(PDO::FETCH_ASSOC);
   return $lgEtabOffrantChambres["nombreEtabOffrantChambres"];
}

// Retourne false si le nombre de chambres transmis est inférieur au nombre de 
// chambres occupées pour l'établissement transmis 
// Retourne true dans le cas contraire
function estModifOffreCorrecte($connexion, $idEtab, $nombreChambres)
{
   $nbOccup=obtenirNbOccup($connexion, $idEtab);
   return ($nombreChambres>=$nbOccup);
}

// FONCTIONS RELATIVES AUX GROUPES

function obtenirReqIdNomGroupesAHeberger()
{
   $req="SELECT id, nom FROM Groupe WHERE hebergement='O' ORDER BY id";
   return $req;
}

function obtenirNomGroupe($connexion, $id)
{
   $req="SELECT nom FROM Groupe WHERE id='$id'";
   $rsGroupe = $connexion->query($req);
   $lgGroupe = $rsGroupe->fetch(PDO::FETCH_ASSOC);
   return $lgGroupe["nom"];
}

// FONCTIONS RELATIVES AUX ATTRIBUTIONS

// Teste la présence d'attributions pour l'établissement transmis    
function existeAttributionsEtab($connexion, $id)
{
   $req="SELECT * FROM Attribution WHERE idEtab='$id'";
   $rsAttrib = $connexion->query($req);
   return $rsAttrib->fetch(PDO::FETCH_ASSOC);
}

// Retourne le nombre de chambres occupées pour l'id étab transmis
function obtenirNbOccup($connexion, $idEtab)
{
   $req="SELECT IFNULL(SUM(nombreChambres), 0) AS totalChambresOccup FROM
        Attribution WHERE idEtab='$idEtab'";
   $rsOccup = $connexion->query($req);
   $lgOccup = $rsOccup->fetch(PDO::FETCH_ASSOC);
   return $lgOccup["totalChambresOccup"];
}

// Met à jour (suppression, modification ou ajout) l'attribution correspondant à
// l'id étab et à l'id groupe transmis
function modifierAttribChamb($connexion, $idEtab, $idGroupe, $nbChambres)
{
   $req="SELECT COUNT(*) AS nombreAttribGroupe FROM Attribution WHERE idEtab=
        '$idEtab' AND idGroupe='$idGroupe'";
   $rsAttrib = $connexion->query($req);
   $lgAttrib = $rsAttrib->fetch(PDO::FETCH_ASSOC);
   if ($nbChambres==0)
      $req="DELETE FROM Attribution WHERE idEtab='$idEtab' AND idGroupe='$idGroupe'";
   else
   {
      if ($lgAttrib["nombreAttribGroupe"]!=0)
         $req="UPDATE Attribution set nombreChambres=$nbChambres WHERE idEtab=
              '$idEtab' AND idGroupe='$idGroupe'";
      else
         $req="insert into Attribution values('$idEtab','$idGroupe', $nbChambres)";
   }
   $connexion->query($req);
}

// Retourne la requête permettant d'obtenir les id et noms des groupes affectés
// dans l'établissement transmis
function obtenirReqGroupesEtab($id)
{
   $req="SELECT DISTINCT id, nom FROM Groupe, Attribution WHERE 
        Attribution.idGroupe=Groupe.id AND idEtab='$id'";
   return $req;
}
            
// Retourne le nombre de chambres occupées par le groupe transmis pour l'id étab
// et l'id groupe transmis
function obtenirNbOccupGroupe($connexion, $idEtab, $idGroupe)
{
   $req="SELECT nombreChambres FROM Attribution WHERE idEtab='$idEtab'
        AND idGroupe='$idGroupe'";
   $rsAttribGroupe = $connexion->query($req);
   if ($lgAttribGroupe = $rsAttribGroupe->fetch(PDO::FETCH_ASSOC))
      return $lgAttribGroupe["nombreChambres"];
   return 0;
}
/*
function obtenirHashMdp($connexion, $idEtab){
    $req = "SELECT "
}
*/
