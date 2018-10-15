<?php
session_start();

//Database Credentials 
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '123456');
define('DB_NAME', 'phpauth');

//URL
define('URLROOT', 'http://localhost/phpAuthentication');

//APP URL
define('APPROOT', dirname(__FILE__));

require_once 'functions.php';

//create database connection
$objDB = objDB();

$restricted_pages = [
    '/phpAuthentication/profile.php',
    '/phpAuthentication/change_password.php',
    '/phpAuthentication/edit_profile.php',
    '/phpAuthentication/logout.php', 
];

$public_pages = [
    '/phpAuthentication/login.php',
    '/phpAuthentication/register.php',
    '/phpAuthentication/forget_password.php',
];

if(!isUserLoggedIn() && in_array($_SERVER['REQUEST_URI'], $restricted_pages, true)){
    setMsg('msg_notify', 'You need to login before accessing this page', 'warning');
    redirect('login.php');
    exit();
}

if(isUserLoggedIn() && in_array($_SERVER['REQUEST_URI'], $public_pages, true)){
    setMsg('msg_notify', 'You need to logout before accessing this page', 'warning');
    redirect('profile.php');
    exit();
}

if(isset($_SESSION['user']) || isset($_COOKIE['user'])){
    $user = isset($_COOKIE['user']) ? unserialize($_COOKIE['user']) : $_SESSION['user'];
}else{
    $user = '';
}

