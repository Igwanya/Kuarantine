<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 12-Apr-20
 * Time: 10:52 PM
 */

namespace Src;
use Src\auth\Register;

require_once __DIR__ . '../../vendor/autoload.php';

$username_error   = null;
$email_error      = null;
$web_register = filter_input(INPUT_POST, 'WEB_REGISTER');
if ($web_register == 1) {
    $register = new Register();
    $username = filter_input(INPUT_POST, 'username');
    $register->setUsername($username);
    $username_error = $register->perform_username_check();
    $email = filter_input(INPUT_POST, 'email');
    $register->setEmail($email);
    $email_error = $register->perform_email_check();
    $first_name = filter_input(INPUT_POST, 'first_name');
    $register->setFirstName($first_name);
    $last_name = filter_input(INPUT_POST, 'last_name');
    $register->setLastName($last_name);
    $password = filter_input(INPUT_POST, 'password');
    $register->setPassword($password);
    if ($username_error == null && $email_error == null && !empty($username) && !empty($email)){
        $register->register();
    }
}

$register_page =<<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="felixmuthui32@gmail.com">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <link href="res/vendor/bootstrap/css/bootstrap.css" rel="stylesheet"  media="screen,projection">
    <link href="res/vendor/materialize/css/materialize.css" rel="stylesheet"  media="screen,projection">
    <link href="res/css/main.css" rel="stylesheet"  media="screen,projection">
    <title>sign up</title>
</head>
<body>
<section id="registerPage">
<div class="row">
        <div class="col-4"></div>
        <div class="col-4">
          <form action="register.php" method="POST" class="card" id="registerForm">
            <fieldset>
                <legend>Register</legend>
                <div class="input-field">
                    <input id="inputUsername" type="text" name="username" class="validate">
                    <label for="inputUsername">Username</label>
                    <span class="helper-text" data-error="wrong" data-success="OK">{$username_error}</span>
                </div>
                <div class="input-field">
                    <input id="inputEmail" type="email" name="email" class="validate">
                    <label for="inputEmail">Email</label>
                    <span class="helper-text" data-error="wrong" data-success="OK">{$email_error}</span>
                </div>
                <div class="input-field">
                    <input id="inputFirstName" type="text" name="first_name" class="validate">
                    <label for="inputFirstName">First name</label>
                    <span class="helper-text" data-error="wrong" data-success="OK"></span>
                </div>
                <div class="input-field">
                    <input id="inputLastName" type="text" name="last_name" class="validate">
                    <label for="inputLastName">Last name</label>
                    <span class="helper-text" data-error="wrong" data-success="OK"></span>
                </div>
                <div class="input-field">
                    <input id="inputNewPassword" type="password" class="validate">
                    <label for="inputNewPassword" id="labelInputNewPassword">Password</label>
                    <span class="helper-text" data-error="wrong" data-success="OK"></span>
                </div>
                <div class="input-field">
                    <input id="inputPassword" type="password" name="password" class="validate">
                    <label for="inputPassword">Confirm Password</label>
                    <span class="helper-text" data-error="wrong" data-success="OK"></span>
                </div>
                <div>
                    <label>
                        <input type="checkbox" class="filled-in" checked="" id="inputTermsAndConditions"/>
                        <span>Accept terms and Conditions</span>
                    </label>
                </div>
                <div class="input-field right-align">
                    <input type="submit" id="inputSubmit" class="btn" />
                </div>
                <div class="center">
                    <p>
                        Read our<a href="#"> Terms and Conditions </a> and <a href="#"> Privacy Agreement </a>.
                    </p>
                </div>
            </fieldset>
            <input type="hidden" name="WEB_REGISTER" value="1" />
            </form>
        </div>
        <div class="col-4"></div>
    </div>
</section>
<script type="text/javascript" src="res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="res/vendor/popper.min.js"></script>
<script type="text/javascript" src="res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="res/js/init.js"></script> 
</body>
</html>
HTML;
echo $register_page;