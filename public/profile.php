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

$request_method = $_SERVER['REQUEST_METHOD'];
$repository = new Repository();
$user = array();

switch ($request_method) {
    case 'GET':
        if (isset($_SESSION['login_ID'])) {
            $result =  $repository->find_user_with_id($_SESSION['login_ID']);
            $user = $result['body']['user'];
            if ($user['is_admin'] == 1) {
                header("Location: admin/admin.php");
                die();
            }
        }

        /**
          * Redirect to login page
          */
        if (isset($_SESSION['is_authenticated']) && $_SESSION['is_authenticated'] != 1){
            /* Redirect to a different page in the current directory that was requested */
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'login.php';
            header("Location: http://$host$uri/$extra");
            exit;
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
    <link href="res/vendor/materialize/css/materialize.css" rel="stylesheet" media="screen,projection">
    <link href="res/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen,projection">
    <!-- reference your copy Font Awesome here (from our CDN or by hosting yourself) -->
    <link href="res/vendor/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="res/vendor/fontawesome/css/brands.css" rel="stylesheet">
    <link href="res/vendor/fontawesome/css/solid.css" rel="stylesheet">
     <link href="res/css/main.css" rel="stylesheet" media="screen,projection">
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
         .fa-power-off {
             color: red;
         }

     </style>
    <script type="text/javascript">

    </script>
</head>
<body>
 <header>
     <nav class="" role="navigation" id="profileNav">
         <div class="nav-wrapper container">
             <a href="#" data-target="nav-mobile" class="sidenav-trigger black-text"><i class="fas fa-bars"></i></a>
             <ul class="right hide-on-med-and-down" id="navList">
                 <li><a href="../index.php">  view site |</a></li>
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
                     <img src="res/img/wood.jpg" alt="account-background">
                 </div>
                 <a href=""><img class="circle" src="<?php echo $user['url']; ?>" alt="user-avatar"></a>
                 <a href=""><span class="name"><?php echo $user['full_name']; ?></span></a>
                 <a href=""><span class="email"><?php echo $user['email']; ?></span></a>
             </div></li>
<!--         <li><a href="#!"><i class="material-icons">cloud</i>First Link With Icon</a></li>-->
         <li><a href="#!">Second Link</a></li>
         <li><a class="subheader">Account</a></li>
         <li><a class="waves-effect" href="auth/edit_user.php"><i class="fa fa-user-edit fa-1x"></i> Edit profile</a></li>
         <li><a class="subheader">Articles</a></li>
         <li><a class="waves-effect" href="articles/add_article.php"><i class="fa fa-newspaper fa-1x"></i> Add an article</a></li>
         <li><a class="waves-effect" href="articles/detail_article.php"><i class="fa fa-edit fa-1x"></i> Manage articles</a></li>
         <li><a class="subheader">Market</a></li>
         <li><div class="divider"></div></li>
         <li><a href="auth/logout.php" class=""><i class="fas fa-power-off fa-1x"></i> log out</a> </li
     </ul>
     <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
 </header>



<!--  Scripts-->
<script src="res/vendor/jquery-3.4.1.js"></script>
<script src="res/vendor/popper.min.js"></script>
 <script src="res/vendor/materialize/js/materialize.js"></script>
<script src="res/vendor/jquery.mobile-1.4.5.js"></script>
<script src="res/vendor/fontawesome/js/fontawesome.min.js"></script>
<script src="res/js/init.js"></script>
</body>
</html>

 