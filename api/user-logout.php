<?php
    session_start();
    require '../functions.php';

    $login = $_POST['login'];

    user_logout($login);

    error_log ("Exiting from API: USER-LOGOUT");

    header("Location: ". DIR_INDEX ."index.php");