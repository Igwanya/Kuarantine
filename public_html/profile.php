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
 * Redirect to the admin page if the user is admin
 */
 $_SESSION['is_admin'] = false;
 if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']){
     /* Redirect to a different page in the current directory that was requested */
     $host = $_SERVER['HTTP_HOST'];
     $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
     $extra = 'admin.php';
     header("Location: http://$host$uri/$extra");
     exit;
 }
 ?>
<?php
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
 <link href="res/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"  media="screen,projection">
 <link href="res/vendor/materialize/css/materialize.css" rel="stylesheet"  media="screen,projection">
 <link href="res/vendor/fontawesome/css/fontawesome.min.css" rel="stylesheet"  media="screen,projection">
 <link href="res/css/main.css" rel="stylesheet"  media="screen,projection">
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
                 <a href=""><span class="text-black-50 name">John Doe</span></a>
                 <a href=""><span class="email">jdandturk@gmail.com</span></a>
             </div></li>
         <li><a href="#!"><i class="material-icons">cloud</i>First Link With Icon</a></li>
         <li><a href="#!">Second Link</a></li>
         <li><div class="divider"></div></li>
         <li><a class="subheader">Subheader</a></li>
         <li><a class="waves-effect" href="partials/edit-user.php">Edit</a></li>
     </ul>
     <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
 </header>



<!--  Scripts-->
<script src="res/vendor/jquery-3.4.1.js"></script>
<script src="res/vendor/popper.min.js"></script>
<script src="res/vendor/jquery.mobile-1.4.5.js"></script>
<script src="res/vendor/materialize/js/materialize.js"></script>
<script src="res/vendor/fontawesome/js/fontawesome.min.js"></script>
<script src="res/js/init.js"></script>
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

 