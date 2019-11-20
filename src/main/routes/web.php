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

Route::get('/', function () {
    return view('welcome');
});

// TODO Mettre en tant que racine (?)
Route::get('/accueil', function () {
    $content = view('pages/index');
    return embedInMainView($content);
});

// https://stackoverflow.com/questions/19760585/laravel-throwing-methodnotallowedhttpexception
// https://scotch.io/tutorials/simple-and-easy-laravel-login-authentication
Route::get('/login', function () {
    return view('pages/login');
});
Route::post('/login', ['uses' => 'AuthController@doLogin']);

Route::get('/logout', function(){
    Request::session()->remove('compte');
    return Redirect::to('login');
});

Route::get('/consultationAttributions', function() {
    $content = view('pages/consultationAttributions');
    return embedInMainView($content);
});
