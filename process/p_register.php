<?php
require_once '../config.php';
require_once '../libraries/PHPMailer-master/PHPMailerAutoload.php';

if(isset($_POST['register'])){

    $errors = array();

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING); 
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $website = filter_input(INPUT_POST, 'website', FILTER_SANITIZE_URL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);

    if(strlen($name)>50 || strlen($name)<6){
        $errors['name_err'] = 'Name min limit is 6 & max limit is 50 characters';
    }

    if(strlen($username)>15 || strlen($username)<5){
        $errors['username_err'] = 'Username min limit is 5 & max limit is 15 characters';
    }elseif(checkUserByUsername($username)){
        $errors['username_err'] = 'Username already exists';
    }

    $reg_email = '/^[a-z0-9]+(\.|_)?[a-z0-9]+@[a-z0-9]+(.com|.net|.org|.me)$/i'; 
    if(!preg_match($reg_email, $email)){
        $errors['email_err'] = 'Invalid Email-ID';
    }elseif(checkUserByEmail($email)){
         $errors['email_err'] = 'Email already exists';
    }

    if(empty($website)){
        $errors['website_err'] = 'Invalid entry';
    }

    if(strlen($password)>20 || strlen($password)<5){
        $errors['password_err'] = 'Password min limit is 5 & max limit is 20 characters';
    }

    if($password!=$confirm_password || empty($confirm_password)){
        $errors['confirm_password_err'] = 'Passwords do not match';
    }

    if(!count($errors)){
        
        $password = password_hash($password, PASSWORD_DEFAULT);
        $code = md5(crypt(rand(), 'aa'));

        $stmt = $objDB->prepare(
            'INSERT INTO users(name, email, username, password, website, created_at, reset_code) 
            VALUES(?, ?, ?, ?, ?, ?, ?)'
        );
        
        $time = time();
        $stmt->bind_param('sssssis', ucwords($name), $email, $username, $password, $website, $time, $code);

        if($stmt->execute()){
            setMsg('msg', 'Your account has been created successfully. Please check your email to verify your account.', 'warning');

            $message = "Hi! You have registered account on our website. In order to use your account, you need to click on the verification link to activate it.<br><br><a href='".URLROOT."/process/p_account_verify.php?code=$code'>Click here to verify.</a><br><br>Thank you<br>PHP Authentication";
            
            send_mail([
                'to' => $email,
                'message' => $message,
                'subject' => 'Account Verficiation',
                'from' => 'PHP Authentication',
            ]);
        }
    } else{
        $data = [
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'website' => $website,
            'password' => $password,
            'confirm_password' => $confirm_password,
        ];
        
        setMsg('form_data', $data);
        setMsg('errors', $errors); 
    } 
   
     redirect('register.php');
}