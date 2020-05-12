<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 12-Apr-20
 * Time: 2:12 PM
 */
namespace Src;

session_start();
error_reporting(E_ALL);

ini_set('display_errors', true);
ini_set('html_errors', true);

require_once __DIR__ . '../../vendor/autoload.php';

/**
 * Redirect the user to the home page.
 */
function redirect_to_index_page()
{
    /* Redirect to a different page in the current directory that was requested */
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'index.php';
    header("Location: http://$host$uri/$extra");
    exit;
}

$logout_page =<<<HTML
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
  <title>Logout</title>
</head>
<body>
  <div class="">
    <div class="row">
      <div class="col s4"></div>
      <div class="col s4">
        <form action="logout.php" method="POST" class="form" id="logoutForm">
            <fieldset id="logoutFormFieldset">
                <legend id="loginFormLegend">Restricted Access</legend>
                <div>
                  <p class="p-2">You are about to logout from the site.</p>
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
                    <a href="password_reset.html" id="loginFormPasswordReset" class="left left-align">Forgot password ?</a>
                    <a href="register.html" id="loginFormRegister" class="right right-align">Create an account.</a>
                </div>
            </fieldset>   
        </form>
      </div>
      <div class="col s4"></div>
    </div>
  </div>
<script type="text/javascript" src="res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="res/vendor/popper.min.js"></script>
<script type="text/javascript" src="res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="res/vendor/fontawesome/js/all.js"></script>
<script type="text/javascript" src="res/js/init.js"></script> 
</body>
</html>
HTML;

if ($_SESSION['is_authenticated'] == 1)
{
    $_SESSION['is_authenticated'] = false;
    unset($_SESSION['is_admin']);
    unset($_SESSION['login_ID']);
    echo $logout_page;
}
else {
    header("Location: http://" .$_SERVER['SERVER_NAME']);
}