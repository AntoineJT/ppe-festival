@php
    use \Illuminate\Support\Facades\Request;
    include app_path() . '/includes/_gestionBase.inc.php';

    function getIfSet(string $str) {
        if (Request::has($str)) {
            return Request::query($str);
        }
        return null;
    }

    $etablissement = getIfSet('etablissement');
    if ($etablissement != NULL) {
        $mdp = getIfSet('mdp');
        if ($etablissement == NULL || $mdp == NULL) {
            die("Les champs obligatoires n'ont pas été remplis correctement");
        }
    /*
    if (isset($_POST['etablissement'])){
        $etablissement = isset($_POST['etablissement']) ? $_POST['etablissement'] : null;
        $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : null;

        if (is_null($etablissement) || is_null($mdp)){
            die("Les champs obligatoires n'ont pas été remplis correctement");
        }
        */
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
        // $_SESSION['compte'] = $etablissement;
        Request::session()->put('compte', $etablissement);
        // header('Location: index.php');
        redirect()->route('accueil');
    }

    // if (isset($_GET['disconnect'])){
    if (Request::has('disconnect')) {
        // $_SESSION['compte'] = null;
        Request::session()->remove('compte');
        // header('Location: login.php');
        redirect()->route('login');
    }
@endphp
<head>
    <link href="css/connect.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
</head>
<body class="text-center">
<form method="POST" class="form-signin">
    <img class="mb-4" src="images/mdl.png" class="rounded float-right" alt="Responive image">
    <h1 class="h3 mb-3 font-weight-normal">Connexion</h1>
    <label for="etablissement" class="sr-only">ID</label>
    <input type="text" name="etablissement" class="form-control" placeholder="ID" required autofocus>
    <label for="mdp" class="sr-only">Mot de Passe</label>
    <input type="password" name="mdp" class="form-control" placeholder="Mot de Passe" required>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Se Connecter</button>
</form>
</body>
