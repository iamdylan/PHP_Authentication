<?php
session_start();

//Database Credentials 
define('DB_HOST', 'us-cdbr-iron-east-01.cleardb.net');
define('DB_USER', 'bc905868d42042');
define('DB_PASSWORD', '5ca7fc15');
define('DB_NAME', 'heroku_59fc84637b68ba9');

//SMTP Credentials
define('SMTP_USER', 'emailfortesting103@gmail.com');
define('SMTP_PASSWORD', 'test@123456');

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

