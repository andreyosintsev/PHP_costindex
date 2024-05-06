<?php
    session_start();
    require '../functions.php';

    $login = $_GET['login'];
    $number = $_GET['number'];

    $confirmed = user_confirm($login, $number);
?>

<?php require DIR_TEMPLATE . 'header.php'; ?>

<!--register-complete.php-->
<body>
    <?php include(DIR_TEMPLATE . 'ads-top.php'); ?>

    <?php if (!(empty($_SESSION['login']) || empty($_SESSION['id'])))
        require (DIR_TEMPLATE . 'logout.php'); else require (DIR_TEMPLATE . 'login.php');
    ?>

    <div class="wrapper">
        <header>
            <h1>Регистрация пользователя</h1>
        </header>
        <section>
            <?php if ($confirmed)
                echo '<p style="margin:100px 20px; text-align: center;">
                        Регистрация успешно завершена.<br><br>
                        Теперь вы можете воспользоваться своими <b>логином</b> и <b>паролем для доступа</b> на сайт</p>.';
                else
                echo '<p style="margin:100px 20px; text-align: center; color:#FF0000">
                        Ошибка подтверждения регистрации<br><br>
                        Пожалуйста, пройдите регистрацию заново.</p>';
            ?>
        </section>

<?php require DIR_TEMPLATE . 'footer.php'; ?>