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
        die('Le mot de passe ne correspond pas!');
    }
    $_SESSION['compte'] = $etablissement;
    header('Location: index.php');
} else
if (isset($_GET['disconnect'])){
    $_SESSION['compte'] = null;
    header('Location: login.php');
} else {
    echo "
<form method='POST'>
    <label>Compte admin
        <input type='text' name='etablissement'>
    </label><br>
    <label>Mot de passe
        <input type='password' name='mdp'>
    </label><br>
    <input type='submit' value='Valider'>
</form>";

}
