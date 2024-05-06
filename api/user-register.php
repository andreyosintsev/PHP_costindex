<?php
    session_start();
    require '../functions.php';

    $name = $_POST['name'];
    $login = $_POST['login'];
    $email = $_POST['email'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    $error = user_register($name, $login, $email, $password1, $password2);

    if ($error == "Сообщение отправлено") {
        error_log ("Exiting from API: USER-REGISTER message sent");

        header("Location: ". DIR_PAGES ."register-sent.php");
    } else {
        $_SESSION['error'] = $error;
        error_log ("Exiting from API: USER-REGISTER ERROR");

        header("Location: ". DIR_PAGES ."register.php");
    }
