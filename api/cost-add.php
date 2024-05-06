<?php
    require '../functions.php';

    $good = $_POST['good'];
    $cost = $_POST['cost'];

    cost_add($good, $cost);
    goods_update();

    error_log ("Exiting from API: COST-ADD");

    header("Location: ". DIR_PAGES ."receipt-add.php");