<?php session_start();?>
<?php require '../functions.php'; ?>

<?php require DIR_TEMPLATE . 'header.php'; ?>
<!--good-add.php-->
<body>
    <?php include(DIR_TEMPLATE . 'ads-top.php'); ?>

    <?php
    if (!empty($_SESSION['login']) && !empty($_SESSION['id']))
        require (DIR_TEMPLATE . 'logout.php'); else require (DIR_TEMPLATE . 'login.php');
    ?>

    <div class="wrapper">
        <header>
            <h1>Добавить новый товар</h1>
        </header>
        <?php
            if (!empty($_SESSION['login']) && !empty($_SESSION['id'])) {
                require(DIR_FORMS . 'form-good-add.php');
            }
        ?>

<?php require DIR_TEMPLATE . 'footer.php'; ?>