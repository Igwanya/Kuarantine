<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 04-May-20
 * Time: 12:25 AM
 */
namespace Src;

use Src\models\User;

session_start();
require_once __DIR__ . '../../../vendor/autoload.php';
$repository = new Repository();
$current_user = null;

if (isset($_SESSION["login_ID"]) && !empty($_SESSION["login_ID"]) ){
    $id = filter_var($_SESSION["login_ID"], FILTER_VALIDATE_INT);
    $current_user = $repository->find_user_with_id($id);
}
$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == "POST") {
    $id = filter_var($_SESSION["login_ID"], FILTER_VALIDATE_INT);
    $username = filter_input(INPUT_POST, 'username');
    $email = filter_input(INPUT_POST, 'email');
    $first_name = filter_input(INPUT_POST, 'first_name');
    $last_name = filter_input(INPUT_POST, 'last_name');
    if (!empty($id) && !empty($username) && !empty($email)
        && !empty($first_name) && !empty($last_name)){
        $current_user = $repository->update_user_to_db($id, $username, $email, $first_name, $last_name);
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
    <link href="../res/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"  media="screen,projection">
    <link href="../res/vendor/materialize/css/materialize.css" rel="stylesheet"  media="screen,projection">
    <link href="../res/vendor/fontawesome/css/fontawesome.min.css" rel="stylesheet"  media="screen,projection">
    <link href="../res/css/main.css" rel="stylesheet"  media="screen,projection">
    <title>EDIT</title>
    <style>
        header, main, footer {
            padding-left: 300px;
        }
        @media only screen and (max-width : 992px) {
            header, main, footer {
                padding-left: 0;
            }
        }
    </style>
</head>
<body>

<header>
    <nav class="" role="navigation" id="profileNav">
        <div class="nav-wrapper container">
            <a href="#" data-target="nav-mobile" class="sidenav-trigger black-text"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down" id="navList">
                <li class="" id="navProfileLink">
                    <a href="#!" data-target="dropdown1" class="dropdown-trigger waves-effect">
                        <i class="material-icons">notifications</i></a></li>
                <li id="navProfileLink">
                    <a href="#!" data-target="chat-dropdown" class="dropdown-trigger waves-effect" id="navProfileLink">
                        <i class="material-icons">settings</i></a></li>
            </ul>
        </div>
    </nav>
    <ul id="nav-mobile" class="sidenav sidenav-fixed">
        <li><div class="user-view">
                <div class="background">
                    <img src="" alt="account-background">
                </div>
                <a href=""><img class="circle" src="" alt="user-avatar"></a>
                <a href=""><span class="text-black-50 name"><?php echo $current_user["body"]["username"] ?></span></a>
                <a href=""><span class="text-black-50 email"><?php echo $current_user["body"]["email"] ?></span></a>
            </div></li>
        <li><a href="#!"><i class="material-icons">cloud</i>First Link With Icon</a></li>
        <li><a href="#!">Second Link</a></li>
        <li><div class="divider"></div></li>
        <li><a class="subheader">Subheader</a></li>
        <li><a class="waves-effect" href="#!">Third Link With Waves</a></li>
    </ul>
    <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
</header>
<main>
<div class="container-fluid">
 <div class="row">
    <div class="col-2"></div>
    <div class="col-8">
        <div class="">
            <div class="card-content">
                <form action="edit-user.php" method="POST" class="card" id="editForm">
                        <fieldset>
                            <legend class="text-body text-accent-4">Edit</legend>
                            <div class="input-field">
                                <input id="inputUsername" type="text" name="username" class="validate" value="<?php echo $current_user["body"]["username"]?>">
                                <label for="inputUsername">Username</label>
                                <span class="helper-text" data-error="wrong" data-success="OK"></span>
                            </div>
                            <div class="input-field">
                                <input id="inputEmail" type="email" name="email" class="validate" value="<?php echo $current_user["body"]["email"] ?>">
                                <label for="inputEmail">Email</label>
                                <span class="helper-text" data-error="wrong" data-success="OK"></span>
                            </div>
                            <div class="input-field">
                                <input id="inputFirstName" type="text" name="first_name" class="validate" value="<?php echo $current_user["body"]["firstName"] ?>">
                                <label for="inputFirstName">First name</label>
                                <span class="helper-text" data-error="wrong" data-success="OK"></span>
                            </div>
                            <div class="input-field">
                                <input id="inputLastName" type="text" name="last_name" class="validate" value="<?php echo $current_user["body"]["lastName"] ?>">
                                <label for="inputLastName">Last name</label>
                                <span class="helper-text" data-error="wrong" data-success="OK"></span>
                            </div>
                            <div class="input-field right-align">
                                <input type="submit" id="inputSubmit" class="btn" />
                            </div>
                        </fieldset>
                        <input type="hidden" name="WEB_REGISTER" value="1" />
                    </form>
                </div>
            </div>

        </div>
        <div class="col-2"></div>
    </div>
</div>
</main>

<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($current_user["status"])) { ?>
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <img src="..." class="rounded mr-2" alt="...">
            <strong class="mr-auto">Bootstrap</strong>
            <small>11 mins ago</small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            Hello, world! This is a toast message.
        </div>
    </div>
<?php } ?>
<!--  Scripts-->
<script src="../res/vendor/jquery-3.4.1.js"></script>
<script src="../res/vendor/popper.min.js"></script>
<script src="../res/vendor/jquery.mobile-1.4.5.js"></script>
<script src="../res/vendor/materialize/js/materialize.js"></script>
<script src="../res/vendor/fontawesome/js/fontawesome.min.js"></script>
<script src="../res/js/init.js"></script>
</body>
</html>
