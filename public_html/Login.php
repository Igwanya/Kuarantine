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

$request_method = $_SERVER['REQUEST_METHOD'];
$username_or_email_error = null;
$password_error          = null;

/**
 * Handle the form and log in the user
 */
function redirect_to_profile_page()
{
    /* Redirect to a different page in the current directory that was requested */
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'auth/profile.php';
    header("Location: http://$host$uri/$extra");
    exit;
}

if ($request_method == 'POST')
{
    $login_frm_credentials   = filter_input(INPUT_POST, 'inputCredentials');
    $login_frm_password      = filter_input(INPUT_POST, 'inputLoginPassword');
    $login = new Login();
    if (!empty($login_frm_credentials) && !empty($login_frm_password) ) {
        $login->setUsernameOrEmail($login_frm_credentials);
        $login->setPassword($login_frm_password);
        $username_or_email_error = $login->perform_username_or_email_check();
        $password_error = $login->perform_password_check();
        header("Location: profile.php");
//        header("Location: http://".$_SERVER['HTTP_HOST']."/public_html/auth/profile.php");
//        if ($login->authenticate() == 1){
//           redirect_to_profile_page();
//        }
    }
}
?>
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
    <link href="res/vendor/materialize/css/materialize.css" rel="stylesheet"  media="screen,projection">
  <link href="res/vendor/bootstrap/css/bootstrap.css" rel="stylesheet"  media="screen,projection">
   <!-- reference your copy Font Awesome here (from our CDN or by hosting yourself) -->
  <link href="res/vendor/fontawesome/css/fontawesome.css" rel="stylesheet">
  <link href="res/vendor/fontawesome/css/brands.css" rel="stylesheet">
  <link href="res/vendor/fontawesome/css/solid.css" rel="stylesheet">
  <link href="res/css/main.css" rel="stylesheet"  media="screen,projection">
  <title>Login</title>
  <style type="text/css">
    .fa-facebook {
    color: rgb(59, 91, 152);
  }
  
    </style>
    </head>
<body>
  <div class="">
    <div class="row">
      <div class="col-md-2"></div>
      <div class="col-md-5">
        <form action="login.php" method="POST" class="form" id="loginForm">
            <fieldset id="loginFormFieldset">
                <legend id="loginFormLegend" class="text-danger">Restricted Access</legend>
                <div class="input-field">
                    <input id="inputCredentials" type="text" class="validate" name="inputCredentials">
                    <label for="inputCredentials" id="labelInputCredentials"><i class="fas fa-user"></i> Username or Email</label>
                    <span class="helper-text" data-error="wrong" data-success="">
                         <p id="loginFormCredentialsHelper"><?php echo $username_or_email_error; ?></p>
                    </span>
                </div>
                <div class="input-field">
                  <input id="inputPassword" type="password" class="validate" name="inputLoginPassword">
                  <label for="inputPassword" id="labelInputPassword" ><i class="fas fa-key"></i> Password</label>
                  <span class="helper-text" data-error="wrong" data-success="">
                      <p id="loginFormPasswordHelper"><?php echo $password_error; ?></p>
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
                      <i class="fab fa-facebook fa-2x"></i>  Facebook
                    </a>
                    <a href="#" class="right-align" id="loginFormGoogleLink">
                        <i class="fab fa-google fa-2x"></i> oogle
                    </a>
                  </p>
                </div>
                </div>
                <div id="loginFormRedirect">
                    <a href="auth/password_reset.php" id="loginFormPasswordReset" class="left left-align">Forgot password ?</a>
                    <a href="auth/register.php" id="loginFormRegister" class="right right-align">Create an account.</a>
                </div>
            </fieldset>
        </form>
      </div>
      <div class="col-md-2"></div>
    </div>
  </div>
<script type="text/javascript" src="res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="res/vendor/popper.min.js"></script>
<script type="text/javascript" src="res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="res/js/init.js"></script> 
</body>
</html>