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
        <p>
            {{ $errors->first('etablissement') }}
            {{ $errors->first('mdp') }}
        </p>
        <label for="etablissement" class="sr-only">ID</label>
        <input type="text" name="etablissement" class="form-control" placeholder="ID" required autofocus>
        <label for="mdp" class="sr-only">Mot de Passe</label>
        <input type="password" name="mdp" class="form-control" placeholder="Mot de Passe" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Se Connecter</button>
    </form>
</body>
