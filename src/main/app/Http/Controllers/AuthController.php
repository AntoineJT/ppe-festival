<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function doLogin()
    {
        $rules = [
            'etablissement' => 'required',
            'mdp' => 'required'
        ];
        $validator = Validator::make(Request::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('login')
                ->withErrors($validator) // send back all errors to the login form
                ->withInput(Request::except('password')); // send back the input (not the password) so that we can repopulate the form
        }

        $data = [
            'etablissement' => Request::input('etablissement'),
            'mdp' => Request::input('mdp')
        ];

        return self::verifyCredentials($data);
    }

    private static function verifyCredentials(array $data){
        include app_path() . '/includes/_gestionBase.inc.php';

        $connexion = connect();
        // TODO Move that into gestionBase
        $request = $connexion->prepare('SELECT mdp FROM Administrateur WHERE nomAdmin = ?');
        $etablissement = $data['etablissement'];

        // TODO Gérer erreurs pour qu'elles s'affichent sur la page login
        if (!$request->execute([$etablissement])){
            echo('Une erreur avec la base de données est survenue!');
            return Redirect::to('login');
        }

        $hash = $request->fetch()[0];
        if (!password_verify($data['mdp'], $hash)){
            echo('Le couple identifiant/mot de passe est invalide!');
            return Redirect::to('login');
        }

        Request::session()->put('compte', $etablissement);
        return Redirect::to('accueil');
    }
}
