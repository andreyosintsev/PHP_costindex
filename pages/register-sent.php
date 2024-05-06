<?php
    require '../functions.php';
?>

<?php require DIR_TEMPLATE . 'header.php'; ?>

<!--register-sent.php-->
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
            <p style="margin:100px 20px; text-align: center;">
                На указанный при регистрации <b>e-mail</b> был выслан код подтверждения.<br><br>
                Следуйте указаниям в письме для окончания регистрации.
            </p>
        </section>

<?php require DIR_TEMPLATE . 'footer.php'; ?>