<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 12-Apr-20
 * Time: 10:52 PM
 */

namespace Src;
use Src\auth\Register;
use Src\models\User;

require_once __DIR__ . '../../../vendor/autoload.php';
include_once "../utils.php";

error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('html_errors', true);
$request_method = $_SERVER["REQUEST_METHOD"];

$username_error   = null;
$email_error      = null;

switch ($request_method) {
    case 'POST':
        $result = array(
                "status"  => "",
                "body"    => array(),
                "error"   => array()
        );
        $register = new Register();
        $username = filter_input(INPUT_POST, 'username');
        $email = filter_input(INPUT_POST, 'email');
        $first_name = filter_input(INPUT_POST, 'first_name');
        $last_name = filter_input(INPUT_POST, 'last_name');
        $full_name = filter_input(INPUT_POST, "full_name");
        $password = filter_input(INPUT_POST, 'password');
        if (!empty($username) && !empty($email) &&
            !empty($full_name) && !empty($password) ) {
            $register->setUsername($username);
            $username_error = $register->perform_username_check();
            $email  = filter_var($email, FILTER_SANITIZE_EMAIL);
            $register->setEmail($email);
            $email_error = $register->perform_email_check();
            $register->set_first_name($first_name);
            $register->set_last_name($last_name);
            $register->set_full_name($full_name);
            $register->setPassword($password);
            // if no errors in the form
            if ($username_error == null && $email_error == null){
                $uploaddir = $_SERVER["DOCUMENT_ROOT"]."/public/uploads/";
                if (mkdir($uploaddir.$username)){
                    $uploadfile = $uploaddir.basename($_FILES['userfile']['name']);
                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                        if (file_exists($uploadfile)){
                            if (rename($uploadfile,
                                $uploaddir.$username."/".basename($_FILES['userfile']['name']))) {
                                //Register and  login user who uploaded a profile photo
                                $files = scandir($uploaddir.$username);
                                $url =  $uploaddir.$username.'/'.$files[2];
                                $var = preg_split("#/#", $url);
//                                $result_path = $var[3].'/'.$var[4].'/'.$var[5].'/'.$var[6];
                                $result_path = $var[4].'/'.$var[5].'/'.$var[6];
                                $register->setUrl($result_path);
                                $register->register_user();
                            } else {
                                $result["error"] = "Failed to move the file";
                            }
                        }
                    } else {
                        // No profile photo uploaded
                        $register->register_user();
                    }
                }
            }
        }

        break;
}
?>
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
    <link href="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/materialize/css/materialize.css" rel="stylesheet" media="screen,projection">
    <link href="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen,projection">
    <link href="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/fontawesome/css/brands.css" rel="stylesheet">
    <link href="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/fontawesome/css/solid.css" rel="stylesheet">
    <link href="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/css/main.css" rel="stylesheet" media="screen,projection">
    <title>SIGN UP</title>
</head>
<body>
<div class="container">
    <section id="registerPage">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="card mt-2">
                    <div class="card-title p-2"><h5 class="">Create an account</h5></div>
                    <div class="card-content">
                        <form action="<?php echo \Src\get_server_url_domain_name(); ?>/public/auth/register.php" method="POST" enctype="multipart/form-data" class="" id="registerForm">
                            <fieldset>
                                <div class="input-field">
                                    <input id="inputUsername" type="text" name="username" class="validate">
                                    <label for="inputUsername">Username</label>
                                    <span class="helper-text text-danger" data-error="This field is empty." data-success="">
                                <?php echo $username_error ?></span>
                                </div>
                                <div class="input-field">
                                    <input id="inputEmail" type="email" name="email" class="validate">
                                    <label for="inputEmail">Email</label>
                                    <span class="helper-text text-danger" data-error="Incorrect format for email" data-success="">
                                <?php echo $email_error ?></span>
                                </div>
                                <div class="input-field file-field" id="inputPhoto">
                                    <div class="btn-small">
                                        <label for="profilePhoto" class="">
                                            <i class="fas fa-file-upload fa-1x"></i> Upload a picture</label>
                                        <input id="profilePhoto" type="file" name="userfile" class="">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text" />
                                    </div>
                                    <span class="helper-text text-info" data-error="This field is empty." data-success="">
                    Only jpg, jpeg, .gif, .png formats are allowed. Max file size 2MB</span>
                                </div>
                                <div class="input-field ">
                                    <input id="inputFirstName" type="text" name="first_name" class="validate">
                                    <label for="inputFirstName">First name</label>
                                    <span class="helper-text text-danger" data-error="This field is empty." data-success=""></span>
                                </div>
                                <div class="input-field">
                                    <input id="inputLastName" type="text" name="last_name" class="validate">
                                    <label for="inputLastName">Last name</label>
                                    <span class="helper-text" data-error="This field is empty." data-success=""></span>
                                </div>
                                <div class="input-field">
                                    <input id="inputFullName" type="text" name="full_name" class="validate">
                                    <label for="inputFullName">Full name</label>
                                    <span class="helper-text" data-error="This field is empty." data-success=""></span>
                                </div>
                                <div class="input-field">
                                    <input id="inputNewPassword" type="password" class="validate">
                                    <label for="inputNewPassword" id="labelInputNewPassword">Password</label>
                                    <span class="helper-text" data-error="This field is empty." data-success=""></span>
                                </div>
                                <div class="input-field">
                                    <input id="inputPassword" type="password" name="password" class="validate">
                                    <label for="inputPassword">Confirm Password</label>
                                    <span class="helper-text" data-error="This field is empty." data-success=""></span>
                                </div>
                                <div>
                                    <label>
                                        <input type="checkbox" class="filled-in"  id="inputTermsAndConditions"/>
                                        <span>Accept terms and Conditions</span>
                                    </label>
                                </div>
                                <div class="input-field right-align">
                                    <input type="submit" id="inputSubmit" class="btn-small" />
                                </div>
                                <div class="center">
                                    <p>
                                        Read our<a href="#"> Terms and Conditions </a> and <a href="#"> Privacy Agreement </a>.
                                    </p>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/popper.min.js"></script>
<script type="text/javascript" src="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/js/init.js"></script>
</body>
</html>