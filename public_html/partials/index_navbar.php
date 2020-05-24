<?php
///**
// * Created by PhpStorm.
// * User: MUTHUI
// * Date: 24-May-20
// * Time: 10:24 AM
// */
//
//<nav class="white" role="navigation" id="indexNav">
//    <div class="nav-wrapper container">
//        <a id="logo-container" href="/" class="brand-logo">Logo</a>
//        <ul class="right hide-on-med-and-down" id="navList">
//            <li><a href="public_html/featured.php" id="indexNavLinkFeatured">Featured</a></li>
//            <li><a href="public_html/products.php" id="indexNavLinkProducts">Products</a></li>
//            <li><a href="public_html/articles.php" id="indexNavLinkArticles">Articles</a></li>
//            <li><a href="#" id="indexNavLinkAbout">About</a></li>
//            <li><a href="#" id="indexNavLinkContact">Contact</a></li>
//            <?php if ( !isset($_SESSION["is_authenticated"]) && !isset($_SESSION["login_ID"]) ) {?>
<!--                <li>-->
<!--                    <ul>-->
<!--                        <li><a href="public_html/login.php" id="indexLinkLogin"><i class="fas fa-sign-in-alt fa-1x"></i> Login</a></li>-->
<!--                        <li><a href="public_html/auth/register.php" id="indexLinkRegister"><i class="fas fa-user fa-1x"></i> Register</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                --><?php //} ?>
<!--                --><?php //if ( isset($_SESSION["is_authenticated"]) && $_SESSION["is_authenticated"] == 1 && isset($_SESSION['login_ID']) )  {
//                    $user = $repository->find_user_with_id($_SESSION['login_ID'])['body']['user'] ?>
<!--                <li>-->
<!--                    <ul>-->
<!---->
<!--                        <li><a href="public_html/profile.php" id="indexLinkProfile"><i class="fas fa-user-circle"></i></a></li>-->
<!--                        <li><a href="public_html/profile.php" id="indexLinkProfile"> | --><?php //if (!empty($user)) echo $user['username']; ?><!--</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--            --><?php //} ?>
<!---->
<!---->
<!--        </ul>-->
<!--        <ul id="nav-mobile" class="sidenav">-->
<!--            <li><a href="#featured" id="indexNavLinkFeatured">Featured</a></li>-->
<!--            <li><a href="public_html/products.php" id="indexNavLinkProducts">Products</a></li>-->
<!--            <li><a href="public_html/articles.php" id="indexNavLinkArticles">Articles</a></li>-->
<!--            <li><a href="#" id="indexNavLinkAbout">About</a></li>-->
<!--            <li><a href="#" id="indexNavLinkContact">Contact</a></li>-->
<!--            --><?php //if ( isset($_SERVER["is_authenticated"])) {?>
<!--                --><?php //if ( !$_SERVER["is_authenticated"]){ ?>
<!--                    <li><a href="public_html/login.php" id="indexLinkLogin">Login</a></li>-->
<!--                    <li><a href="public_html/auth/register.php" id="indexLinkRegister">Register</a></li>-->
<!--                --><?php //} else { ?>
<!--                    <li>-->
<!--                        <ul>-->
<!--                            <li><a href="public_html/profile.php" id="indexLinkProfile"><i class="material-icons">account_circle</i></a></li>-->
<!--                            <li><a href="public_html/profile.php" id="indexLinkProfile">My Account</a></li>-->
<!--                        </ul>-->
<!--                    </li>-->
<!--                --><?php //} ?>
<!--            --><?php //} else { ?>
<!--            <li><a href="public_html/login.php" id="indexLinkLogin">Login</a></li>-->
<!--            <li><a href="public_html/auth/register.php" id="indexLinkRegister">Register</a></li>-->
<!--            --><?php //} ?>
<!--        </ul>-->
<!--        <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="fas fa-bars">menu</i></a>-->
<!--    </div>-->
<!--</nav>-->
<!---->
