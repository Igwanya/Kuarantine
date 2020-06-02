<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 04-May-20
 * Time: 12:25 AM
 */
namespace Src;

use Src\auth\Register;
use Src\models\User;

session_start();
require_once __DIR__ . '../../../vendor/autoload.php';
$repository = new Repository();
$result = array();
$user = array();
$username_error = "";
$email_error    = "";

$requestMethod = $_SERVER["REQUEST_METHOD"];
switch ($requestMethod)   {
    case 'GET':
        if (isset($_SESSION["login_ID"])){
            $id = filter_var($_SESSION["login_ID"], FILTER_VALIDATE_INT);
            $result = $repository->find_user_with_id($id);
            $user = $result['body']['user'];
        }
        break;

    case 'POST':
        $id = $_SESSION["login_ID"];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (isset($_SESSION["login_ID"])){
            $result = $repository->find_user_with_id($id);
            $user = $result['body']['user'];
        }
        $result_path = ""; // new user url
        $first_name = filter_input(INPUT_POST, 'first_name');
        $last_name  = filter_input(INPUT_POST, 'last_name');
        $full_name  = filter_input(INPUT_POST, 'full_name');
        $username   = filter_input(INPUT_POST, 'username');
        if (empty($username)){
            $username_error = "Username field cannot be empty ";
        }
        $email      =   filter_input(INPUT_POST, 'email');
        if (empty($email)) {
            $email_error = "The email field cannot be empty";
        }
        if (empty($username) && empty($email)) {
            $username_error = "Username field cannot be empty ";
            $email_error = "The email field cannot be empty";
        } else {
            $upload_dir  = $_SERVER["DOCUMENT_ROOT"]."/public/uploads/";
            if (is_uploaded_file($_FILES['userfile']['tmp_name'])) { // There is a new file
                if (is_dir($upload_dir.$user['username'])){
                    $path = preg_split("#/#", $user['url']);
                    $file = $path[2];
                    unlink($upload_dir.$user['username'].'/'.$file);
                }
                rmdir($upload_dir.$user['username']);
            }
            if (is_uploaded_file($_FILES['userfile']['tmp_name']) &&  mkdir($upload_dir.$username) ){
                $upload_file = $upload_dir.basename($_FILES['userfile']['name']);
                if (move_uploaded_file($_FILES['userfile']['tmp_name'], $upload_file)) {
                    if (file_exists($upload_file)){
                        if (rename($upload_file,
                            $upload_dir.$username."/".basename($_FILES['userfile']['name']))) {
                            //Register and  login user who uploaded a profile photo
                            $files = scandir($upload_dir.$username);
                            $url =  $upload_dir.$username.'/'.$files[2];
                            $var = preg_split("#/#", $url);
//                                $result_path = $var[3].'/'.$var[4].'/'.$var[5].'/'.$var[6];
                            $result_path = $var[4].'/'.$var[5].'/'.$var[6];
                            $repository->update_user_to_db($id, $result_path,
                                $username, $email, $first_name, $last_name, $full_name);
                        } else {
                            echo "Failed to move the file";
                        }
                    }
                }
            }
            if ($username != $user['username']) {   // no file uploaded but the username changed
                if (is_dir($upload_dir.$user['username'])){
                    rename($upload_dir.$user['username'], $upload_dir.$username);
                    $path = $user['url'];
                    $arr  = preg_split("#/#", $path);
                    $repository->update_user_to_db($id, $arr[0].'/'.$username.'/'.$arr[2],
                        $username, $email, $first_name, $last_name, $full_name);
                }
            }
        }
        
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
    <link href="../res/vendor/materialize/css/materialize.css" rel="stylesheet" media="screen,projection">
    <link href="../res/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen,projection">
    <!-- reference your copy Font Awesome here (from our CDN or by hosting yourself) -->
    <link href="../res/vendor/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="../res/vendor/fontawesome/css/brands.css" rel="stylesheet">
    <link href="../res/vendor/fontawesome/css/solid.css" rel="stylesheet">
    <link href="../res/css/main.css" rel="stylesheet" media="screen,projection">
    <title>EDIT</title>
    <style type="text/css">

    </style>
    <script type="text/javascript">
        // Or with jQuery

        $(document).ready(function(){
            $('.modal').modal();
        });
    </script>
</head>
<body>
<?php include_once '../partials/navbar.php'; ?>
<div class="container">
<main>
 <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-7">
            <div class="card">
                <div class="card-image">
                    <img src="../<?php echo $user['url']; ?>">
                    <span class="card-title">Edit profile</span>
                    <a class="btn-floating halfway-fab waves-effect waves-light red modal-trigger"
                       href="#modalDelete"><i class="fas fa-trash"></i></a>
                    <!-- Modal Structure -->
                    <div id="modalDelete" class="col-md-4 modal" style="height: 15rem; width: 32rem;">
                        <div class="modal-content">
                            <h4>Delete profile<i class="fas fa-question"></i></h4>
                            <p>Are you sure you want to delete <?php echo $user['email']; ?> profile.
                                All apps <i class="fab fa-android"></i> data will also be removed
                                , the <i class="fas fa-newspaper"></i> articles too</p>
                        </div>
                        <div class="modal-footer">
                            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Not sure ?</a>
                            <a href="delete_profile.php" class="modal-close waves-effect waves-green btn-flat">
                                <i class="fa fa-trash-alt"></i> delete everything</a>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <form action="edit_user.php" enctype="multipart/form-data"  method="POST" id="editForm">
                        <div class="input-field file-field" id="inputPhoto">
                            <div class="btn-flat btn">
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
                        <div class="input-field">
                            <input id="inputUsername" type="text" name="username" class="validate"
                                   value="<?php echo $user["username"]?>">
                            <label for="inputUsername">Username</label>
                            <span class="helper-text red" data-error="" data-success=""><?php echo $username_error; ?></span>
                        </div>
                        <div class="input-field">
                            <input id="inputEmail" type="email" name="email" class="validate" value="<?php echo $user["email"] ?>">
                            <label for="inputEmail">Email</label>
                            <span class="helper-text red" data-error="" data-success=""><?php echo $email_error; ?></span>
                        </div>
                        <div class="input-field">
                            <input id="inputFirstName" type="text" name="first_name" class="validate"
                                   value="<?php echo $user["first_name"] ?>">
                            <label for="inputFirstName">First name</label>
                            <span class="helper-text" data-error="wrong" data-success=""></span>
                        </div>
                        <div class="input-field">
                            <input id="inputLastName" type="text" name="last_name"
                                   class="validate" value="<?php echo $user["last_name"] ?>">
                            <label for="inputLastName">Last name</label>
                            <span class="helper-text" data-error="wrong" data-success=""></span>
                        </div>
                        <div class="input-field">
                            <input id="inputFullName" type="text" name="full_name"
                                   class="validate" value="<?php echo $user["full_name"] ?>">
                            <label for="inputLastName">Full name</label>
                            <span class="helper-text" data-error="wrong" data-success=""></span>
                        </div>
                        <div class="input-field right-align">
                            <input type="submit" id="inputSubmit" class="btn" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-2"></div>
    </div>
</main>


</div>
<!--  Scripts-->
<script src="../res/vendor/jquery-3.4.1.js"></script>
<script src="../res/vendor/popper.min.js"></script>
<script src="../res/vendor/materialize/js/materialize.js"></script>
<script src="../res/vendor/jquery.mobile-1.4.5.js"></script>
<script src="../res/vendor/fontawesome/js/fontawesome.min.js"></script>
<script src="../res/js/init.js"></script>
</body>
</html>
