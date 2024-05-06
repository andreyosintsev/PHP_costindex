<?php
    require '../functions.php';

    $group = $_POST['group'];
    $good_name = $_POST['good_name'];
    $cost = $_POST['cost'];
    $units = $_POST['units'];

    good_add($group, $good_name, $cost, $units);
    goods_update();

    error_log ("Exiting from API: GOOD-ADD");

    header("Location: ". DIR_PAGES ."receipt-add.php");