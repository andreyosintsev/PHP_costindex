<?php

/*
    Параметры подключения к базе данных MySQL
*/

define ("DB_HOST", "localhost");
define ("DB_NAME", "u0650462_costindex");
define ("DB_USER", "u0650462_costuse");
define ("DB_PASS", "dG4rV8xG6xxI7jR3");


/*
    Параметры расположения каталогов для require и include
*/

define("DIR_TEMPLATE",  $_SERVER['DOCUMENT_ROOT'] . '/template/');
define("DIR_FORMS",     $_SERVER['DOCUMENT_ROOT'] . '/forms/');


/*
    Параметры расположения каталогов для вёрстки
*/

define("DIR_INDEX",     '/');
define("DIR_SCRIPTS",   '/scripts/');
define("DIR_PAGES",     '/pages/');
define("DIR_API",       '/api/');
define("DIR_IMAGES",    '/images/');
define("DIR_THUMBS",    '/thumbs/');


/*
    Вывод дебаг-сведений в error_log
*/

define("DEBUG", true);