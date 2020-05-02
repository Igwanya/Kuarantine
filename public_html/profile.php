<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 12-Apr-20
 * Time: 1:12 PM
 */
namespace Src;

session_start();
error_reporting(E_ALL);

ini_set('display_errors', true);
ini_set('html_errors', true);

require_once __DIR__ . '../../vendor/autoload.php';

/**
 *
 * Fetch the logged in user details.
 */
$db = new database\DatabaseConnection();
$conn = $db->get_db_connection();
$stmt = $conn->prepare("SELECT * FROM users WHERE id LIKE ?");
if (!($stmt))
{
    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    trigger_error("Prepare failed: (" . $conn->errno . ") " . $conn->error, E_USER_ERROR);
}
if (!$stmt->bind_param('s', $_SESSION['login_ID'])){
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error, E_ERROR);
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error, E_CORE_ERROR);
}
$res = $stmt->get_result();
$row = $res->fetch_assoc();

$usr = new models\User(
    $row['id'],
    $row['username'],
    $row['email'],
    $row['first_name'],
    $row['last_name'],
    $row['is_admin'],
    $row['created'],
    $row['last_updated']
);
$usr->set_password_hash($row['password_hash']);
?>

<?php if ($_SESSION['is_authenticated'] == 1): ?>
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
    <link href="res/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"  media="screen,projection">
    <link href="res/vendor/materialize/css/materialize.css" rel="stylesheet"  media="screen,projection">
    <link href="res/vendor/fontawesome/css/fontawesome.min.css" rel="stylesheet"  media="screen,projection">
    <link href="res/css/main.css?lastModified=Sat, 04 Apr 2020 20:35:37 GMT" rel="stylesheet"  media="screen,projection">
    <title>Dashboard</title>
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

<!-- Navbar-->
<header>
     <div class="navbar-fixed">
        <nav class="navbar">
            <div class="nav-wrapper">
                <a href="#!" data-target="sidenav-left" class="sidenav-trigger left">
                    <i class="material-icons black-text">menu</i></a>
                <a href="" class="logo-container">Profile<i class="material-icons left">spa</i></a>
                <ul class="right">
                    <li class="">
                        <a href="#!" data-target="dropdown1" class="dropdown-trigger waves-effect">
                            <i class="material-icons">notifications</i></a></li>
                    <li>
                        <a href="#!" data-target="chat-dropdown" class="dropdown-trigger waves-effect">
                            <i class="material-icons">settings</i></a></li>
                </ul>

            </div>
        </nav>
    </div>
    <ul id="sidenav-left" class="sidenav sidenav-fixed">
        <li>
            <div class="user-view">
                <div class="background">
                    <img src="res/img/Trippy.jpg">
                </div>
                <a href="#"><img class="circle" src="res/img/free_spirit.jpg"></a>
                <a href="#"><span class="white-text name"><?php echo $usr->get_username(); ?></span></a>
                <a href="#"><span class="white-text email"><?php echo $usr->get_email(); ?></span></a>
            </div>
        </li>
        <li class="no-padding">
            <ul class="collapsible collapsible-accordion">
                
                <!-- User account navigation -->
                <li class="bold waves-effect">
                    <div class="collapsible-header">Account<i class="material-icons chevron">chevron_left</i></div>
                    <div class="collapsible-body">
                        <ul >
                            <li><a href="logout.php" class="waves-effect">Logout<i class="material-icons">person</i></a></li>
                            <li><a href="settings.php" class="waves-effect">Settings<i class="material-icons">settings</i></a></li>
                        </ul>
                    </div>
                </li>

            </ul>
        </li>
    </ul>

    <div id="dropdown1" class="dropdown-content notifications">
        <div class="notifications-title">notifications</div>
        <div class="card">
            <div class="card-content"><span class="card-title">Joe Smith made a purchase</span>
                <p>Content</p>
            </div>
            <div class="card-action"><a href="#!">view</a><a href="#!">dismiss</a></div>
        </div>
        <div class="card">
            <div class="card-content"><span class="card-title">Daily Traffic Update</span>
                <p>Content</p>
            </div>
            <div class="card-action"><a href="#!">view</a><a href="#!">dismiss</a></div>
        </div>
        <div class="card">
            <div class="card-content"><span class="card-title">New User Joined</span>
                <p>Content</p>
            </div>
            <div class="card-action"><a href="#!">view</a><a href="#!">dismiss</a></div>
        </div>
    </div>
    <div id="chat-dropdown" class="dropdown-content dropdown-tabbed">
        <ul class="tabs tabs-fixed-width">
            <li class="tab col s3"><a href="#settings">Settings</a></li>
            <li class="tab col s3"><a href="#friends" class="active">Friends</a></li>
        </ul>
        <div id="settings" class="col s12">
            <div class="settings-group">
                <div class="setting">Night Mode
                    <div class="switch right">
                        <label>
                            <input type="checkbox"><span class="lever"></span>
                        </label>
                    </div>
                </div>
                <div class="setting">Beta Testing
                    <label class="right">
                        <input type="checkbox"><span></span>
                    </label>
                </div>
            </div>
        </div>
        <div id="friends" class="col s12">
            <ul class="collection flush">
                <li class="collection-item avatar">
                    <div class="badged-circle online"><img src="//cdn.shopify.com/s/files/1/1775/8583/t/1/assets/portrait1.jpg?v=1218798423999129079" alt="avatar" class="circle"></div><span class="title">Jane Doe</span>
                    <p class="truncate">Lo-fi you probably haven't heard of them</p>
                </li>
                <li class="collection-item avatar">
                    <div class="badged-circle"><img src="//cdn.shopify.com/s/files/1/1775/8583/t/1/assets/portrait2.jpg?v=15342908036415923195" alt="avatar" class="circle"></div><span class="title">John Chang</span>
                    <p class="truncate">etsy leggings raclette kickstarter four dollar toast</p>
                </li>
                <li class="collection-item avatar">
                    <div class="badged-circle"><img src="//cdn.shopify.com/s/files/1/1775/8583/t/1/assets/portrait3.jpg?v=4679613373594475586" alt="avatar" class="circle"></div><span class="title">Lisa Simpson</span>
                    <p class="truncate">Raw denim fingerstache food truck chia health goth synth</p>
                </li>
            </ul>
        </div>
    </div>

</header>
<main>
    <div class="">
        <div class="row">
            <div class="col-sm-3" style="background-color: red;">
                One of three columns
            </div>
            <div class="col-sm-9" style="background-color: blue;">
                One of three columns
            </div>
        </div>
    </div>

   
<!--  Scripts-->
<script src="res/vendor/jquery-3.4.1.js"></script>
<script src="res/vendor/popper.min.js"></script>
<script src="res/vendor/jquery.mobile-1.4.5.js"></script>
<script src="res/vendor/materialize/js/materialize.js"></script>
<script src="res/vendor/fontawesome/js/fontawesome.min.js"></script>
<script src="res/js/init.js"></script>

</body>
</html>
<?php endif; ?>



<?php
//if ($_SESSION['is_authenticated'] != 1)
//{
//    /* Redirect to a different page in the current directory that was requested */
//    $host = $_SERVER['HTTP_HOST'];
//    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
//    $extra = 'login.php';
//    header("Location: http://$host$uri/$extra");
//    exit;
//}
//?>