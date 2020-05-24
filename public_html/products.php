<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 16-May-20
 * Time: 5:23 AM
 */
namespace Src;

require_once __DIR__ . '../../vendor/autoload.php';
session_start();

ini_set('display_errors', false);
ini_set('html_errors', false);

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

    <link href="res/css/main.css" rel="stylesheet"  media="screen,projection">
    <title>Products</title>
</head>
<body>
<header>
    <div class="container py-5">
        <div class="jumbotron text-white jumbotron-image shadow"
             style="background-image: url(../public_html/res/img/products_background.jpg);">
            <h2 class="mb-4">
                Flea market.
            </h2>
            <p class="mb-4">
                Buy and sell products
            </p>
            <!--            <a href="https://bootstrapious.com/snippets" class="btn btn-primary">More Bootstrap Snippets</a>-->
        </div>
    </div>
</header>
<main>
    <div class="container">
        <div class="row">
            <div class="card-deck">
                <?php $products = $repository->load_all_products();
                foreach ($products as $arr) {
                    foreach ($arr as $body => $product) {
                        foreach ($product as $content) { ?>
                            <div class="">
                                <div class="card" style="width: 18rem;">
                                    <img src="<?php print_r($content['url']) ?>"
                                         class="card-img-top" alt="Product image">
                                    <div class="card-body">
                                        <h5 class="card-title text-justify"><?php echo $content['title'] ?></h5>
                                        <div class="card-text">
                                            <p class="text-body"><?php echo $content['detail'] ?></p>
                                            <div class="text-right">
                                                <p class="text-monospace text-primary text-sm-right">
                                                    Kshs <?php echo $content['price'] ?></p>
                                            </div>
                                            <p class="text-light text-right"><?php echo $content['last_updated'] ?>rtr</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php     }
                    }
                } ?>

            </div>
        </div>
    </div>
</main>

<script type="text/javascript" src="res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="res/vendor/popper.min.js"></script>
<script type="text/javascript" src="res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="res/js/init.js"></script>
</body>
</html>
