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

use Illuminate\Support\Facades\Route;

function embedInMainView(string $content) {
    return view('main', [
        'title' => 'Accueil',
        'content' => $content
    ]);
}

Route::get('/', function () {
    return view('welcome');
});

// TODO Mettre en tant que racine + gérer connexion
Route::get('/accueil', function(){
    $content = view('pages/index');
    return embedInMainView($content);
    // return embedInMainView('<p style="color:white;">test</p>');
});

