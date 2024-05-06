<?php
    session_start();
    require '../functions.php';

    $login = $_POST['login'];
    $pass = $_POST['pass'];

    user_login($login, $pass);

    error_log ("Exiting from API: USER-LOGIN");

    header("Location: ". DIR_INDEX ."index.php");