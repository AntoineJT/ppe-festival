<?php
echo '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<!-- TITRE ET MENUS -->
<html lang="fr">
    <head>
        <title>Maison des Ligues</title>
        <meta http-equiv="Content-Language" content="fr">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
        <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="css/cssGeneral.css" rel="stylesheet" type="text/css">
    </head>
    <body class="ml-5 mr-5 p-3 mb-2 bg-dark text-dark">
        <div class="p-3 mb-2 bg-info text-white">
          Maison des Ligues <br>
          Hébergement des groupes</span>
          <img src="images/mdl.png" class="rounded float-right" alt="Responive image"">
        </div>
        <nav class="m-2 nav nav_pills nav-fill">
              <a class="nav-link nav-item" href="index.php"><i class="fas fa-home"></i>&nbsp&nbspAccueil</a>
              <a class="nav-link nav-item" href="listeEtablissements.php">Gestion des ligues&nbsp&nbsp<i class="fas fa-tasks"></i></a>
              <a class="nav-link nav-item" href="consultationAttributions.php">Attributions chambres&nbsp&nbsp<i class="fas fa-bed"></i></a>
              <a class="nav-link nav-item" href="login.php?disconnect">Déconnexion  &nbsp  &nbsp<i class="fas fa-sign-out-alt"></i></a>
        </nav>
    </body>
';
