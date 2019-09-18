<?php

if (isset($_POST)){
    $etablissement = isset($_POST['etablissement']) ? $_POST['etablissement'] : null;
    $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : null;

    if (!is_null($etablissement) && !is_null($mdp)){

    }
} else {
    echo "
<form method='POST'>
    <label>Id Etablissement
        <input type='text' name='etablissement'>
    </label><br>
    <label>Mot de passe
        <input type='password' name='mdp'>
    </label><br>
    <input type='submit' value='Valider'>
</form>";

}
