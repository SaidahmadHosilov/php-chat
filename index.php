<?php

#    FRONT Controller

// 1. Umumiy sozlanish
ini_set('display_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Asia/Tashkent');

session_start();

// 2. Fayllarni ulash

define('ROOT', dirname(__FILE__));
require_once( ROOT . '/components/Autoload.php');
require_once(ROOT . '/vendor/autoload.php');

// 3. ROUTER ga murojaat

if(isset($_SESSION['user'])){
    User::isOnlineUser($_SESSION['user']);
}

$router = new Router();
$router->run();
