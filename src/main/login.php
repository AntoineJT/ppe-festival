<?php
session_start();
include('_gestionBase.inc.php');

if (isset($_POST['etablissement'])){
    $etablissement = isset($_POST['etablissement']) ? $_POST['etablissement'] : null;
    $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : null;

    if (is_null($etablissement) || is_null($mdp)){
        die("Les champs obligatoires n'ont pas été remplis correctement");
    }
    $connexion = connect();
    // TODO Move that into gestionBase
    $request = $connexion->prepare('SELECT mdp FROM Administrateur WHERE nomAdmin = ?');
    /*
    // Temporary thing to generate hash easily
    $toHash = $request->execute([$etablissement]);
    $hash = password_hash($toHash, PASSWORD_ARGON2I);
    echo $hash;
    */
    if (!$request->execute([$etablissement])){
        die('Une erreur avec la base de données est survenue!');
    }
    $hash = $request->fetch()[0];
    if (!password_verify($mdp, $hash)){
        die('Le couple identifiant/mot de passe est invalide!');
    }
    $_SESSION['compte'] = $etablissement;
    header('Location: index.php');
} else
if (isset($_GET['disconnect'])){
    $_SESSION['compte'] = null;
    header('Location: login.php');
} else {
    echo
  '
  <head>
  <link href="css/connect.css" rel="stylesheet" type="text/css">
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
  </head>
  <body class="text-center">
    <form method="POST" class="form-signin">
      <img class="mb-4" src="images/mdl.png" class="rounded float-right" alt="Responive image"">
      <h1 class="h3 mb-3 font-weight-normal">Connexion</h1>
      <label for="etablissement" class="sr-only">ID</label>
      <input type="text" name="etablissement" class="form-control" placeholder="ID" required autofocus>
      <label for="mdp" class="sr-only">Mot de Passe</label>
      <input type="password" name="mdp" class="form-control" placeholder="Mot de Passe" required>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Se Connecter</button>
    </form>
  </body>
  ';
}
