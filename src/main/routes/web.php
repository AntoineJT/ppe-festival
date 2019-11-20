<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

function embedInMainView(string $content)
{
    if (session('compte') == NULL) {
        return Redirect::to('login');
    }
    return view('main', [
        'title' => 'Accueil',
        'content' => $content
    ]);
}

function embedPageInMainView(string $path) {
    $content = view('pages/' . $path);
    return embedInMainView($content);
}

function declareSubpage(string $path) {
    Route::get($path, function() use ($path) {
        return embedPageInMainView($path);
    });
}

function declareView(string $uri, string $path) {
    Route::get($uri, function() use ($path) {
        return view($path);
    });
}

function declareEmbedView(string $uri, string $path) {
    Route::get($uri, function() use ($path) {
        $content = view($path);
        return embedInMainView($content);
    });
}

// default thing
declareView('/', 'welcome');

// TODO Mettre en tant que racine (?)
declareEmbedView('/accueil', 'pages/index');

// https://stackoverflow.com/questions/19760585/laravel-throwing-methodnotallowedhttpexception
// https://scotch.io/tutorials/simple-and-easy-laravel-login-authentication
declareView('/login', 'pages/login');
Route::post('/login', ['uses' => 'AuthController@doLogin']);

Route::get('/logout', function(){
    Request::session()->remove('compte');
    return Redirect::to('login');
});

declareSubpage('consultationAttributions');
// declareSubpage('donnerNbChambres');
declareSubpage('detailEtablissement');
declareSubpage('listeEtablissements');
