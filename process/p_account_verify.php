<?php
require_once '../config.php';

if(isset($_GET['code'])){
    $code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);
    if(checkUserByCode($code)){
        verifyUserAccount($code);
        setMsg('msg_notify', 'Your account has been verified successfully, you can now login to your account.');
        redirect('login.php');
        exit();
    }else{
        setMsg('msg', 'Invalid activation code', 'warning');
    }
}else{
    setMsg('msg', 'Activation code does not exist', 'warning');
}

 redirect('register.php');
 exit();
