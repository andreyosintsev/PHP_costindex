<?php
    session_start();
    require '../functions.php';

    $error = $_SESSION['error'];
?>

<?php require DIR_TEMPLATE . 'header.php'; ?>

<!--register.php-->
<body>
    <?php include(DIR_TEMPLATE . 'ads-top.php'); ?>

    <?php if (!(empty($_SESSION['login']) || empty($_SESSION['id'])))
        require (DIR_TEMPLATE . 'logout.php'); else require (DIR_TEMPLATE . 'login.php');
    ?>

    <div class="wrapper">
        <header>
            <h1>Регистрация пользователя</h1>
        </header>
        <div class="form_reg">
            <?php require(DIR_FORMS . 'form-register.php'); ?>
            <p style="width: 300px; text-align: center;"><?php echo $error; ?></p>
        </div>

<?php require DIR_TEMPLATE . 'footer.php'; ?>