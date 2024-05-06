<?php
    session_start();
    require '../functions.php';

    $group   = $_GET['group'];
    $filter  = $_GET['filter'];
    $sortby  = $_GET['sortby'];
    $sortdir = $_GET['sortdir'];
?>

<?php require DIR_TEMPLATE . 'header.php'; ?>

<!--goods.php-->
<body>
    <?php include(DIR_TEMPLATE . 'ads-top.php'); ?>

    <?php if (!(empty($_SESSION['login']) || empty($_SESSION['id'])))
        require (DIR_TEMPLATE . 'logout.php'); else require (DIR_TEMPLATE . 'login.php');
    ?>

    <div class="wrapper">
        <?php require DIR_TEMPLATE . 'abccontents.php'; ?>

        <header>
            <h1>Таблица товаров на</h1>
            <h2><time datetime="<?php date_get_newest('Y-m-d');?>"><?php date_get_newest('d.m.Y');?></time></h2>
        </header>

        <?php groups_get_contents(); ?>

        <?php
            if (!empty($filter))
                echo '<h2 style="margin: 10px 0 0 10px">'. $filter .'</h2>';
            else
                echo '<h2 style="margin: 10px 0 0 10px">'. group_get_name($group) .'</h2>';
        ?>

        <?php goods_get_popular($group); ?>

        <article>
            <div class="goods">
                <?php goods_get_table($group, $filter, $sortby, $sortdir) ?>
            </div>
        </article>

<?php require DIR_TEMPLATE . 'footer.php'; ?>