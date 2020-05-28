<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 16-May-20
 * Time: 5:24 AM
 */
namespace Src;

require_once __DIR__ . '../../vendor/autoload.php';
session_start();
$repository = new Repository();

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
    <link href="res/vendor/materialize/css/materialize.css" rel="stylesheet"  media="screen,projection">
    <link href="res/vendor/bootstrap/css/bootstrap.css" rel="stylesheet"  media="screen,projection">
    <link href="res/vendor/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="res/vendor/fontawesome/css/brands.css" rel="stylesheet">
    <link href="res/vendor/fontawesome/css/solid.css" rel="stylesheet">
    <link href="res/css/main.css" rel="stylesheet"  media="screen,projection">
    <title>Articles</title>
    <style type="text/css">
        .fa-check-circle {
            color: #0D47A1;
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-wrapper">
        <a href="../index.php" class="px-2"><i class="fab fa-cc-discover fa-2x"></i></a>
        <ul class="right hide-on-med-and-down">
            <li><a href="#"> <i class="fa fa-search fa-1x left"></i> Search an article</a></li>
            <li><a href="products.php"> <i class="fa fa-shopping-basket fa-1x right"></i> Explore the markets</a></li>
        </ul>
    </div>
</nav>
<header>
    <div class="container py-2">
        <div class="jumbotron text-white jumbotron-image shadow"
             style="background-image: url(../public_html/res/img/article_background.png);">
            <h2 class="mb-4">
                Share your stories .
            </h2>
            <p class="mb-4">
                All over the country people are following the quarantine measures set by the health officials, share your
                experience and also read other people stories.
            </p>
<!--            <a href="https://bootstrapious.com/snippets" class="btn btn-primary">More Bootstrap Snippets</a>-->
        </div>
    </div>
</header>

<section>
    <div class="container">
        <?php $articles = $repository->read_all_posts()['body']['articles'];
        foreach ($articles as $content) {?>
        <div class="card mb-3" style="max-width: 840px; max-height: 540px;">
            <div class="row no-gutters">
                <div class="col-md-4">
                    <img src="<?php echo $content['url']; ?>"
                         class="card-img" alt="Article image">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <div class="card-header">
                            <?php $user  = $repository->find_user_with_id($content['user_id'])['body']['user']; ?>
                            <img src="<?php echo $user['url']; ?>" class="circle float-left"
                                 style="width: 3rem; height: 3rem;" />
                            <p class="card-text text-sm-right grey-text"><?php echo $user['username']; ?>
                                <i class="fa fa-check-circle fa-1x" ></i> Verified<br/>
                                Updated  <?php echo $content['last_updated']; ?></p>
                        </div>
                        <h5 class="card-title py-2"><?php echo $content['headline']; ?></h5>
                        <p class="card-text"><?php echo $content['content']; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php    } ?>
    </div>
</section>

<script type="text/javascript" src="res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="res/vendor/popper.min.js"></script>
<script type="text/javascript" src="res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="res/js/init.js"></script>
</body>
</html>

