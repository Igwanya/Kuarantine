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
    <link href="res/vendor/bootstrap/css/bootstrap.css" rel="stylesheet"  media="screen,projection">
    <link href="res/vendor/materialize/css/materialize.css" rel="stylesheet"  media="screen,projection">
    <link href="res/css/main.css" rel="stylesheet"  media="screen,projection">
    <title>Articles</title>
</head>
<body>
<div class="container">
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">Fluid jumbotron</h1>
            <p class="lead">This is a modified jumbotron that occupies the entire horizontal space of its parent.</p>
        </div>
    </div>
    <div class="row">
        <div class="card-deck">
            <?php $articles = $repository->read_all_posts()['body']['articles'];
            foreach ($articles as $content) {?>
                        <div class="">
                            <div class="card mb-3" style="max-width: 740px;">
                                <div class="row no-gutters">
                                    <div class="col-md-4">
                                        <img src="<?php print_r($content['url']) ?>"
                                             class="card-img figure-img img-fluid rounded" alt="Article image">
                                    </div>
                                    <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title text-justify"><?php echo $content['headline'] ?></h5>
                                    <div class="card-text">
                                        <p class="text-body"><?php echo $content['content'] ?></p>
                                        <p class="text-light text-right"><?php echo $content['last_updated'] ?>rtr</p>
                                    </div>
                                </div>
                                    </div>
                            </div>
                        </div>
                    <?php    } ?>

        </div>
    </div>
</div>
<script type="text/javascript" src="res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="res/vendor/popper.min.js"></script>
<script type="text/javascript" src="res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="res/js/init.js"></script>
</body>
</html>

