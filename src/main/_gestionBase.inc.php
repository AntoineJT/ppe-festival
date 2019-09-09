<?php

// FONCTIONS DE CONNEXION

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


   // return mysql_connect($hote, $login, $mdp);
}

/*
function selectBase($connexion)
{
   // $bd = 'festival';
   $query = 'SET CHARACTER SET utf8';
   // Modification du jeu de caractères de la connexion
   $connexion->query($query);
   //$res = mysql_query($query, $connexion); 
   // $ok = mysql_select_db($bd, $connexion);
   return $ok;
}
*/

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

function obtenirReqEtablissementsAyantChambresAttribuées()
{
   $req="SELECT DISTINCT id, nom, nombreChambresOffertes FROM Etablissement, 
         Attribution WHERE id = idEtab ORDER BY id";
   return $req;
}

function obtenirDetailEtablissement($connexion, $id)
{
   $req = "SELECT * FROM Etablissement WHERE id='$id'";
   $rsEtab = $connexion->query($req);
   //$rsEtab=mysql_query($req, $connexion);
   return $rsEtab->fetch(PDO::FETCH_ASSOC);
   //return mysql_fetch_array($rsEtab);
}

function supprimerEtablissement($connexion, $id)
{
   $req = "DELETE FROM Etablissement WHERE id='$id'";
   $connexion->query($req);
   //mysql_query($req, $connexion);
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
   //mysql_query($req, $connexion);
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
   //mysql_query($req, $connexion);
}


function estUnIdEtablissement($connexion, $id)
{
   $req="SELECT * FROM Etablissement WHERE id='$id'";
   $rsEtab = $connexion->query($req);
   // $rsEtab=mysql_query($req, $connexion);
   return $rsEtab->fetch(PDO::FETCH_ASSOC);
   // return mysql_fetch_array($rsEtab);
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
   // $rsEtab=mysql_query($req, $connexion);
   return $rsEtab->fetch(PDO::FETCH_ASSOC);
   // return mysql_fetch_array($rsEtab);
}

function obtenirNbEtab($connexion)
{
   $req="SELECT COUNT(*) AS nombreEtab FROM Etablissement";
   $rsEtab = $connexion->query($req);
   // $rsEtab=mysql_query($req, $connexion);
   $lgEtab = $rsEtab->fetch(PDO::FETCH_ASSOC);
   //$lgEtab=mysql_fetch_array($rsEtab);
   return $lgEtab["nombreEtab"];
}

function obtenirNbEtabOffrantChambres($connexion)
{
   $req="SELECT COUNT(*) AS nombreEtabOffrantChambres FROM Etablissement WHERE 
         nombreChambresOffertes!=0";
   $rsEtabOffrantChambres = $connexion->query($req);
   // $rsEtabOffrantChambres=mysql_query($req, $connexion);
   $lgEtabOffrantChambres = $rsEtabOffrantChambres->fetch(PDO::FETCH_ASSOC);
   // $lgEtabOffrantChambres=mysql_fetch_array($rsEtabOffrantChambres);
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
   // $rsGroupe=mysql_query($req, $connexion);
   $lgGroupe = $rsGroupe->fetch(PDO::FETCH_ASSOC);
   // $lgGroupe=mysql_fetch_array($rsGroupe);
   return $lgGroupe["nom"];
}

// FONCTIONS RELATIVES AUX ATTRIBUTIONS

// Teste la présence d'attributions pour l'établissement transmis    
function existeAttributionsEtab($connexion, $id)
{
   $req="SELECT * FROM Attribution WHERE idEtab='$id'";
   $rsAttrib = $connexion->query($req);
   // $rsAttrib=mysql_query($req, $connexion);
   return $rsAttrib->fetch(PDO::FETCH_ASSOC);
   // return mysql_fetch_array($rsAttrib);
}

// Retourne le nombre de chambres occupées pour l'id étab transmis
function obtenirNbOccup($connexion, $idEtab)
{
   $req="SELECT IFNULL(SUM(nombreChambres), 0) AS totalChambresOccup FROM
        Attribution WHERE idEtab='$idEtab'";
   $rsOccup = $connexion->query($req);
   // $rsOccup=mysql_query($req, $connexion);
   $lgOccup = $rsOccup->fetch(PDO::FETCH_ASSOC);
   //$lgOccup=mysql_fetch_array($rsOccup);
   return $lgOccup["totalChambresOccup"];
}

// Met à jour (suppression, modification ou ajout) l'attribution correspondant à
// l'id étab et à l'id groupe transmis
function modifierAttribChamb($connexion, $idEtab, $idGroupe, $nbChambres)
{
   $req="SELECT COUNT(*) AS nombreAttribGroupe FROM Attribution WHERE idEtab=
        '$idEtab' AND idGroupe='$idGroupe'";
   $rsAttrib = $connexion->query($req);
   // $rsAttrib=mysql_query($req, $connexion);
   $lgAttrib = $rsAttrib->fetch(PDO::FETCH_ASSOC);
   // $lgAttrib=mysql_fetch_array($rsAttrib);
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
   // mysql_query($req, $connexion);
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
   // $rsAttribGroupe=mysql_query($req, $connexion);
   if ($lgAttribGroupe = $rsAttribGroupe->fetch(PDO::FETCH_ASSOC))
   // if ($lgAttribGroupe=mysql_fetch_array($rsAttribGroupe))
      return $lgAttribGroupe["nombreChambres"];
   return 0;
}

?>

