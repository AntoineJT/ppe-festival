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

        $connexion = connect();
        // TODO Move that into gestionBase
        $request = $connexion->prepare('SELECT mdp FROM Administrateur WHERE nomAdmin = ?');

        if (!$request->execute([$etablissement])){
            die('Une erreur avec la base de données est survenue!');
        }

        $hash = $request->fetch()[0];
        if (!password_verify($mdp, $hash)){
            die('Le couple identifiant/mot de passe est invalide!');
        }

        Request::session()->put('compte', $etablissement);
        redirect()->route('accueil');
    }

    if (Request::has('disconnect')) {
        Request::session()->remove('compte');
        redirect()->route('login');
    }
@endphp
<head>
    <link href="css/connect.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
    <title>M2L - Connexion</title>
</head>
<body class="text-center">
    <form method="POST" class="form-signin">
        @csrf
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <img class="mb-4" src="images/mdl.png" class="rounded float-right" alt="Responsive image">
        <h1 class="h3 mb-3 font-weight-normal">Connexion</h1>
        <label for="etablissement" class="sr-only">ID</label>
        <input type="text" name="etablissement" class="form-control" placeholder="ID" required autofocus>
        <label for="mdp" class="sr-only">Mot de Passe</label>
        <input type="password" name="mdp" class="form-control" placeholder="Mot de Passe" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Se Connecter</button>
    </form>
</body>
