<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 03-May-20
 * Time: 6:29 PM
 */
namespace Src;

require_once __DIR__ . '../../../vendor/autoload.php';
 session_start();
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('html_errors', true);
date_default_timezone_set('Africa/Nairobi');

$repository = new Repository();
$request_method = $_SERVER['REQUEST_METHOD'];
$result = array();

switch ($request_method) {
    case 'GET':
        $result =  $repository->find_user_with_id($_SESSION['login_ID']);

        break;
}




/**
 * Display the user profile page
 */
if (isset($_SESSION['is_authenticated']) && $_SESSION['is_authenticated']){   ?>
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
    <!-- reference your copy Font Awesome here (from our CDN or by hosting yourself) -->
    <link href="../res/vendor/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="../res/vendor/fontawesome/css/brands.css" rel="stylesheet">
    <link href="../res/vendor/fontawesome/css/solid.css" rel="stylesheet">
    <link href="../res/css/main.css" rel="stylesheet" media="screen,projection">
    <title>Dashboard</title>
    <style type="text/css">
        header, main, footer {
            padding-left: 300px;
        }
        @media only screen and (max-width : 992px) {
            header, main, footer {
                padding-left: 0;
            }
        }

        .fa-android {
            color: #12ff0b;
        }
    </style>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.dropdown-trigger');
            var instances = M.Dropdown.init(elems, options);
        });
    </script>
</head>
<body>
<header>
<!--        Main navigation-->
    <nav class="" role="navigation" id="">
        <div class="nav-wrapper container">
            <a href="#" data-target="nav-mobile" class="sidenav-trigger">
                <i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down" id="navList">
                <li class="" id="navProfileLink">
                    <a href="#!" data-target="dropdown1" class="dropdown-trigger waves-effect">
                        <i class="material-icons">notifications</i></a></li>
                <li id="navProfileLink">
                    <a href="#!" data-target="chat-dropdown" class="dropdown-trigger waves-effect" id="navProfileLink">
                        <i class="material-icons">settings</i></a></li>
                <li id="">
                    <a href="../logout.php" data-target="chat-dropdown"
                       class="dropdown-trigger waves-effect text-light text-capitalize" id="navProfileLink">Logout</a></li>
                <li id="">
                    <a href="<?php echo $_SERVER['SERVER_NAME']?>" data-target="chat-dropdown"
                       class="dropdown-trigger waves-effect text-light text-capitalize" id="navProfileLink">
                        View Site</a></li>
            </ul>
        </div>
    </nav>
<!--        side navbar impl-->
    <ul id="nav-mobile" class="sidenav sidenav-fixed">
        <li>
            <div class="user-view">
                <div class="background">
                    <img src="../res/img/wood.jpg" class="responsive-img img-fluid" alt="account-background">
                </div>
                <a href=""><img class="circle" src="<?php echo $result['body']['user']['url']; ?>" alt="user-avatar"></a>
                <a href=""><span class="name lime-text "><?php echo $result['body']['user']['username']; ?></span></a>
                <a href=""><span class="email lime-text "><?php echo $result['body']['user']['email']; ?></span></a>
            </div>
        </li>
        <li><a href=""><i class="fas fa-cogs fa-1x"></i>Settings</a></li>
        <li><a href="admin_profile.php"><i class="fas fa-user-astronaut"></i> Profile</a></li>

        <li><a class="subheader">Subheader</a></li>
        <li><a class="waves-effect" href="../auth/edit_user.php">Edit</a></li>
        <li><a class="waves-effect" href="../products/add_product.php">Add a product</a></li>
        <li><a class="waves-effect" href="../notifications.php"><i class="fas fa-rocket"></i> Manage notifications</a></li>
        <li><a class="waves-effect" href="manage_accounts.php"><i class="fa fa-user-friends"></i> Manage accounts</a></li>
        <li><a class="waves-effect text-black-50" href="manage_articles.php"><i class="fas fa-newspaper"></i> Manage Articles</a></li>
        <li><a href="manage_apps.php"><i class="fab fa-android"></i> Manage apps</a></li>
        <li><a href="../logout.php"><i class="fas fa-power-off red-text"></i>log out</a></li>
        <li><div class="divider"></div></li>
        <li><p class="grey-text"><?php echo date('l jS \of F Y h:i:s A'); ?></p></li>
    </ul>
    <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
</header>
<main>
    <!-- Dropdown Trigger -->
    <a class='dropdown-trigger btn' href='#' data-target='dropdown1'>Drop Me!</a>

    <!-- Dropdown Structure -->
    <ul id='dropdown1' class='dropdown-content'>
        <li><a href="#!">one</a></li>
        <li><a href="#!">two</a></li>
        <li class="divider" tabindex="-1"></li>
        <li><a href="#!">three</a></li>
        <li><a href="#!"><i class="material-icons">view_module</i>four</a></li>
        <li><a href="#!"><i class="material-icons">cloud</i>five</a></li>
    </ul>
</main>

<!--  Scripts-->
<script src="../res/vendor/jquery-3.4.1.js"></script>
<script src="../res/vendor/popper.min.js"></script>
<script src="../res/vendor/materialize/js/materialize.js"></script>
<script src="../res/vendor/jquery.mobile-1.4.5.js"></script>
<script src="../res/vendor/fontawesome/js/fontawesome.min.js"></script>
<script src="../res/js/init.js"></script>
</body>
</html>
<?php }  else {
    /* Redirect to a different page in the current directory that was requested */
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'login.php';
    header("Location: http://$host$uri/$extra");
    exit;
} ?>

