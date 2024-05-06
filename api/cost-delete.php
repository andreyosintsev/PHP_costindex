<?php
    require '../functions.php';

    $good = $_GET['good'];

    cost_del($good);
    goods_update();

    error_log ("Exiting from API: COST-DELETE");

    header("Location: ". DIR_PAGES ."receipt-add.php");