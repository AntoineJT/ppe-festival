<?php

use \Illuminate\Support\Facades\Session;

function gererSession($good_case)
{
    // TODO Rediriger vers page login quand sera fait
    if (is_null(Session::get('compte'))) {
        /*
        header('Location: login.php');
        return;
        */
    }
    $good_case();
}
