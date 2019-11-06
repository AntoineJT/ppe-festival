<?php
session_start();

function gererSession($good_case){
    if (is_null($_SESSION['compte'])){
        header('Location: login.php');
    } else {
        $good_case();
    }
}
