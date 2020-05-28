<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 24-May-20
 * Time: 10:24 AM
 */

$user =array();
if (isset($_SESSION['login_ID'])) {
    $user = $repository->find_user_with_id($_SESSION['login_ID'])['body']['user'];
}
?>
<nav class="white" role="navigation" id="indexNav">
    <div class="nav-wrapper container">
        <a id="logo-container" href="/" class="brand-logo">Logo</a>
        <ul class="right hide-on-med-and-down" id="navList">
            <li><a href="../featured.php" id="indexNavLinkFeatured">Featured</a></li>
            <li><a href="../products.php" id="indexNavLinkProducts">Products</a></li>
            <li><a href="../articles.php" id="indexNavLinkArticles">Articles</a></li>
            <?php if ( !isset($_SESSION["is_authenticated"]) && !isset($_SESSION["login_ID"]) ) {?>
                <li>
                    <ul>
                        <li><a href="../login.php" id="indexLinkLogin"><i class="fas fa-sign-in-alt fa-1x"></i> Login</a></li>
                        <li><a href="../auth/register.php" id="indexLinkRegister"><i class="fas fa-user fa-1x"></i> Register</a></li>
                    </ul>
                </li>
                <?php } ?>
                <?php if ( isset($_SESSION["is_authenticated"]) && $_SESSION["is_authenticated"] == 1 && isset($_SESSION['login_ID']) )  {
                    $user = $repository->find_user_with_id($_SESSION['login_ID'])['body']['user'] ?>
                <li>
                    <ul>
                        <li id="indexLinkProfile"></li>
                        <li><a href="../profile.php" id="indexLinkProfile"><i class="fas fa-user-circle fa-1x"></i> <?php echo $user['username']; ?></a></li>
                    </ul>
                </li>
            <?php } ?>
        </ul>
        <ul id="nav-mobile" class="sidenav">
            <li><a href="../featured.php" id="indexNavLinkFeatured">Featured</a></li>
            <li><a href="../products.php" id="indexNavLinkProducts">Products</a></li>
            <li><a href="../articles.php" id="indexNavLinkArticles">Articles</a></li>
            <?php if ( !isset($_SESSION["is_authenticated"]) && !isset($_SESSION["login_ID"]) ) {?>
                <li>
                    <ul>
                        <li><a href="../login.php" id="indexLinkLogin"><i class="fas fa-sign-in-alt fa-1x"></i> Login</a></li>
                        <li><a href="../auth/register.php" id="indexLinkRegister"><i class="fas fa-user fa-1x"></i> Register</a></li>
                    </ul>
                </li>
            <?php } ?>
            <?php if ( isset($_SESSION["is_authenticated"]) && $_SESSION["is_authenticated"] == 1 && isset($_SESSION['login_ID']) )  {
                $user = $repository->find_user_with_id($_SESSION['login_ID'])['body']['user'] ?>
                <li>
                    <ul>

                        <li><a href="../public_html/profile.php" id="indexLinkProfile"><i class="fas fa-user-circle"></i></a></li>
                        <li><a href="../public_html/profile.php" id="indexLinkProfile"> | <?php echo $user['username']; ?></a></li>
                    </ul>
                </li>
            <?php } ?>
        </ul>
        <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="fas fa-bars">menu</i></a>
    </div>
</nav>

