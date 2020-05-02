<?php
/**
 *
 * User: MUTHUI
 * Date: 09-Apr-20
 * Time: 12:03 PM
 *
 * File : login.php
 *
 * Desc :
 *       Handles authentication, registering, resetting passwords, forgot password,
 *       and other user handling.
 *
 */

use Src\auth\Login;

session_start();
error_reporting(E_ALL);

ini_set('display_errors', true);
ini_set('html_errors', true);

require_once __DIR__ . '../../vendor/autoload.php';

$web_login               = filter_input(INPUT_POST, 'WEB-LOGIN');
$username_or_email_error = null;
$password_error          = null;
if ($web_login == 1)
{
    $login_frm_credentials   = filter_input(INPUT_POST, 'inputCredentials');
    $login_frm_password      = filter_input(INPUT_POST, 'inputLoginPassword');
    $login = new Login();
    $login->setUsernameOrEmail($login_frm_credentials);
    $login->setPassword($login_frm_password);
    $username_or_email_error = $login->perform_username_or_email_check();
    $password_error = $login->perform_password_check();
    if ($username_or_email_error == null && $password_error == null
        && !empty($login_frm_credentials) && !empty($password_error)){
        echo "login in... ...";
        $login->perform_login();
    }

}

$login_page = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="felixmuthui32@gmail.com">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="msapplication-tap-highlight" content="no">
  <link href="res/vendor/bootstrap/css/bootstrap.css" rel="stylesheet"  media="screen,projection">
  <link href="res/vendor/materialize/css/materialize.css" rel="stylesheet"  media="screen,projection">
  <link href="res/css/main.css" rel="stylesheet"  media="screen,projection">
  <link href="res/vendor/fontawesome/css/all.css" rel="stylesheet"/>
  <title>Login</title>
</head>
<body>
  <div class="">
    <div class="row">
      <div class="col s4"></div>
      <div class="col s4">
        <form action="login.php" method="POST" class="form" id="loginForm">
            <fieldset id="loginFormFieldset">
                <legend id="loginFormLegend">Restricted Access</legend>
                <div class="input-field">
                    <input id="inputCredentials" type="text" class="validate" name="inputCredentials">
                    <label for="inputCredentials" id="labelInputCredentials">Username or Email</label>
                    <span class="helper-text" data-error="wrong" data-success="OK">
                         <p id="loginFormCredentialsHelper">{$username_or_email_error}</p>
                    </span>
                </div>
                <div class="input-field">
                  <input id="inputPassword" type="password" class="validate" name="inputLoginPassword">
                  <label for="inputPassword" id="labelInputPassword" >Password</label>
                  <span class="helper-text" data-error="wrong" data-success="OK">
                      <p id="loginFormPasswordHelper">{$password_error}</p> 
                  </span>
                </div>
                <div class="input-field right-align">
                  <input type="submit" id="inputSubmit" class="btn" />
                </div>
                <div>
                <span class="" id="loginFormSocialsText">
                    <p class="center">
                      or sign in with socials
                    </p>
                </span>
                <div class="" id="loginFormSocials">
                  <p class="center">
                    <a href="#" class="left-align" id="loginFormFacebooklink">
                      <i class="fa fa-facebook-square fa-2x"></i>  Facebook
                    </a>
                    <a href="#" class="right-align" id="loginFormGoogleLink">
                        <i class="fa fa-google fa-2x"></i>  Google
                    </a>
                  </p>
                </div>
                </div>
                <div id="loginFormRedirect">
                    <a href="password_reset.php" id="loginFormPasswordReset" class="left left-align">Forgot password ?</a>
                    <a href="register.php" id="loginFormRegister" class="right right-align">Create an account.</a>
                </div>
            </fieldset>  
            <input type="hidden" name="WEB-LOGIN"  value="1" />
        </form>
      </div>
      <div class="col s4"></div>
    </div>
  </div>
<script type="text/javascript" src="res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="res/vendor/popper.min.js"></script>
<script type="text/javascript" src="res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="res/js/init.js"></script> 
</body>
</html>
HTML;
echo $login_page;