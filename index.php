<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 25-Mar-20
 * Time: 9:49 AM
 */

namespace Src;

require_once __DIR__ . '../vendor/autoload.php';
include_once "public/utils.php";
session_start();

$request_method = $_SERVER["REQUEST_METHOD"];
$repository = new Repository();
$user = array();
switch ($request_method) {
    case 'GET':
        if (isset($_SESSION['login_ID'])) {
            $user = $repository->find_user_with_id($_SESSION['login_ID'])['body']['user'];

}

setcookie("MyCookie[foo]", 'Testing 1', time()+3600);
setcookie("MyCookie[bar]", 'Testing 2', time()+3600);
if (isset($_COOKIE['count'])) {
    $count = $_COOKIE['count'] + 1;
} else {
    $count = 1;
}
setcookie('count', $count, time()+3600);
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
//$_SERVER["is_authenticated"] = true;

}

?>
<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="felixmuthui32@gmail.com">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <link href="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen,projection">
    <link href="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/materialize/css/materialize.css" rel="stylesheet" media="screen,projection">
    <link href="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/fontawesome/css/brands.css" rel="stylesheet">
    <link href="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/fontawesome/css/solid.css" rel="stylesheet">
    <link href="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/css/main.css" rel="stylesheet" media="screen,projection">
    <title>Kuarantine</title>
</head>
<body>
<nav class="white" role="navigation" id="indexNav">
    <div class="nav-wrapper container">
        <a id="logo-container" href="/" class="brand-logo">Logo</a>
        <ul class="right hide-on-med-and-down" id="navList">
            <li><a href="<?php echo \Src\get_server_url_domain_name(); ?>/public/featured.php" id="indexNavLinkFeatured">Featured</a></li>
            <li><a href="<?php echo \Src\get_server_url_domain_name(); ?>/public/products.php" id="indexNavLinkProducts">Products</a></li>
            <li><a href="<?php echo \Src\get_server_url_domain_name(); ?>/public/articles.php" id="indexNavLinkArticles">Articles</a></li>
            <li><a href="#about" id="indexNavLinkAbout">About</a></li>
            <li><a href="#contact" id="indexNavLinkContact">Contact</a></li>
            <?php if ( !isset($_SESSION["is_authenticated"]) && !isset($_SESSION["login_ID"]) ) {?>
                <li>
                    <ul>
                        <li><a href="<?php echo \Src\get_server_url_domain_name(); ?>/public/login.php" id="indexLinkLogin"><i class="fas fa-sign-in-alt fa-1x"></i> Login</a></li>
                        <li><a href="<?php echo \Src\get_server_url_domain_name(); ?>/public/auth/register.php" id="indexLinkRegister"><i class="fas fa-user fa-1x"></i> Register</a></li>
                    </ul>
                </li>
                <?php } ?>
                <?php if ( isset($_SESSION["is_authenticated"]) && $_SESSION["is_authenticated"] == 1 && isset($_SESSION['login_ID']) )  {
                    $user = $repository->find_user_with_id($_SESSION['login_ID'])['body']['user'] ?>
                <li>
                    <ul>
                        <li id="indexLinkProfile"></li>
                        <li><a href="<?php echo \Src\get_server_url_domain_name(); ?>/public/profile.php" id="indexLinkProfile"><i class="fas fa-user-circle fa-1x"></i> <?php echo $user['username']; ?></a></li>
                    </ul>
                </li>
            <?php } ?>
        </ul>
        <ul id="nav-mobile" class="sidenav">
            <li><a href="#featured" id="indexNavLinkFeatured">Featured</a></li>
            <li><a href="<?php echo \Src\get_server_url_domain_name(); ?>/public/products.php" id="indexNavLinkProducts">Products</a></li>
            <li><a href="<?php echo \Src\get_server_url_domain_name(); ?>/public/articles.php" id="indexNavLinkArticles">Articles</a></li>
            <li><a href="#" id="indexNavLinkAbout">About</a></li>
            <li><a href="#" id="indexNavLinkContact">Contact</a></li>
            <?php if ( !isset($_SESSION["is_authenticated"]) && !isset($_SESSION["login_ID"]) ) {?>
                <li>
                    <ul>
                        <li><a href="<?php echo \Src\get_server_url_domain_name(); ?>/public/login.php" id="indexLinkLogin"><i class="fas fa-sign-in-alt fa-1x"></i> Login</a></li>
                        <li><a href="<?php echo \Src\get_server_url_domain_name(); ?>/public/auth/register.php" id="indexLinkRegister"><i class="fas fa-user fa-1x"></i> Register</a></li>
                    </ul>
                </li>
            <?php } ?>
            <?php if ( isset($_SESSION["is_authenticated"]) && $_SESSION["is_authenticated"] == 1 && isset($_SESSION['login_ID']) )  {
                $user = $repository->find_user_with_id($_SESSION['login_ID'])['body']['user'] ?>
                <li>
                    <ul>

                        <li><a href="<?php echo \Src\get_server_url_domain_name(); ?>/public/profile.php" id="indexLinkProfile"><i class="fas fa-user-circle"></i></a></li>
                        <li><a href="<?php echo \Src\get_server_url_domain_name(); ?>/public/profile.php" id="indexLinkProfile"> | <?php echo $user['username']; ?></a></li>
                    </ul>
                </li>
            <?php } ?>
        </ul>
        <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="fas fa-bars">menu</i></a>
    </div>
</nav>
<header>
    <div id="index-banner" class="parallax-container">
        <div class="section no-pad-bot">
            <div class="container">
                <br><br>
                <h1 class="header center teal-text text-lighten-2">KUARANTINE</h1>
                <div class="row center">
                    <h5 class="header col s12 light">A modern responsive site for all your quarantine manennos.</h5>
                </div>
                <div class="row center">
                    <!--                TODO:: implement the download feature-->
                    <a href="" id="download-button" class="btn-large waves-effect waves-light teal lighten-1">Get Started</a>
                </div>
                <br><br>
            </div>
        </div>
        <div class="parallax"><img src="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/img/background1.jpg" alt="Unsplashed background img 1"></div>
    </div>
</header>

<div class="container">
    <div class="section">
        <!--   Icon Section   -->
        <div class="row">
            <div class="col s12 m4">
                <div class="icon-block">
                    <h2 class="center brown-text"><i class="fas fa-shopping-basket fa-2x"></i></h2>
                    <h5 class="center">Products</h5>
                    <p class="light">We did most of the heavy lifting for you to provide access to merchants that are ready to sell their products. Anything that you could possible need when in quarantine we have it for you on the platform</p>
                </div>
            </div>
            <div class="col s12 m4">
                <div class="icon-block">
                    <h2 class="center brown-text"><i class="fab fa-uikit fa-2x"></i></h2>
                    <h5 class="center">User Experience Focused</h5>
                    <p class="light">By utilizing elements and principles of Material Design, we were able to create an application that incorporates components and animations that provide more feedback to users.
                        Additionally, a single underlying responsive system across all platforms allow for a more unified user experience.</p>
                </div>
            </div>
            <div class="col s12 m4">
                <div class="icon-block">
                    <h2 class="center brown-text"><i class="fas fa-newspaper fa-2x"></i></h2>
                    <h5 class="center">Articles</h5>
                    <p class="light">We have provided detailed articles as well as user shared on the COVID-19 virus tips to help get started. We are also always open to feedback and can answer any questions a user may have about the quarantine situation.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="parallax-container valign-wrapper" id="featured">
    <div class="section no-pad-bot">
        <div class="container">
            <div class="row center">
                <div class="col s12">
                    <h2 class="header light text-center">Featured</h2>
                    <h5 class="header light">A curated list of new products that are being sold by merchants.</h5>
                </div>
            </div>
            <div class="row center">
                <!--                TODO:: implement the download feature-->
                <a href="" id="download-button" class="btn-large waves-effect waves-light teal lighten-1">Explore</a>
            </div>
        </div>
    </div>
    <div class="parallax"><img src="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/img/background2.jpg" alt="Unsplashed background img 2"></div>
</div>
<!--Contact section-->
<div class="container">
 <div class="section" id="contact">
  <div class="row">
   <div class="col s12 center">
       <h3><i class="mdi-content-send brown-text"></i></h3>
        <h4>About</h4>
         <p class="text-body">We are a team of college students working on this project like
             it's our full time job. This project is done inorder to complete Mobile application development CAT2 assignment. We have provided detailed articles as well as user shared
             on the COVID-19 virus tips to help get started. We are also always open to feedback and can answer any questions a user may have about the quarantine situation.</p>
   </div>
  </div>
 </div>
</div>

<div class="parallax-container valign-wrapper">
    <div class="section no-pad-bot">
        <div class="container">
            <div class="row center">
                <div class="col s12">
                    <h2 class="header light text-center">Articles</h2>
                    <h5 class="header light">Shared posts on COVID-19 status and tips on the quarantine.</h5>
                </div>

            </div>
            <div class="row center">
                <!--                TODO:: implement this feature-->
                <a href="" id="download-button" class="btn-large waves-effect waves-light teal lighten-1">Read & share</a>
            </div>
        </div>
    </div>
    <div class="parallax"><img src="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/img/background3.jpg" alt="Unsplashed background img 3"></div>
</div>

<footer class="page-footer teal">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="white-text">Company Bio</h5>
                <p class="grey-text text-lighten-4">We are a team of college students working on this project like it's our full time job. This project is done inorder to complete Mobile application development CAT2 assignment.</p>
            </div>
            <div class="col l3 s12">
                <h5 class="white-text">Settings</h5>
                <ul>
                    <li><a class="white-text" href="<?php echo \Src\get_server_url_domain_name(); ?>/public/admin/admin.php">Administrator</a></li>
                    <li><a class="white-text" href="#!">Link 2</a></li>
                    <li><a class="white-text" href="#!">Link 3</a></li>
                    <li><a class="white-text" href="#!">Link 4</a></li>
                </ul>
            </div>
            <div class="col l3 s12">
                <h5 class="white-text">Connect</h5>
                <ul>
                    <li><a class="white-text" href="#!">Facebook</a></li>
                    <li><a class="white-text" href="#!">Twitter</a></li>
                    <li><a class="white-text" href="http://www.github.com/Igwanya">GITHUB</a></li>
                    <li><a class="white-text" href="#!">felixmuthui32@gmail.com</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container">
            Made by <a class="brown-text text-lighten-3" href="#!">SCT221-0788/2016</a>
        </div>
    </div>
</footer>
<!--  Scripts-->
<script src="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/jquery-3.4.1.js"></script>
<script src="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/popper.min.js"></script>
<script src="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/materialize/js/materialize.js"></script>
<script src="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/jquery.mobile-1.4.5.js"></script>
<script src="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/vendor/fontawesome/js/fontawesome.min.js"></script>
<script src="<?php echo \Src\get_server_url_domain_name(); ?>/public/res/js/init.js"></script>
</body>
</html>