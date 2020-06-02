<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 21-May-20
 * Time: 11:15 AM
 */
namespace Src;

require_once __DIR__ . '../../../vendor/autoload.php';

session_start();
$repository = new Repository();
$request_method = $_SERVER['REQUEST_METHOD'];
$articles = array();
$article = array();
$user     = array();
switch ($request_method) {
    case 'GET':
        if (isset($_SESSION['login_ID'])){
            $user = $repository->find_user_with_id($_SESSION['login_ID'])['body']['user'];
            $articles = $repository->read_post_for_user($user['id'])['body']['articles'];
            $article = $repository->read_post_by_user_id($user['id'])['body']['article'];
        }
        if (empty($articles)) {
            $articles = $repository->read_post_for_user($user['id'])['body']['articles'];
            $article = $repository->read_post_by_user_id($user['id'])['body']['article'];
        }
        if ($user['is_admin'] == 1) {
            $articles = $repository->read_all_posts()['body']['articles'];
            $article = $repository->read_post_by_user_id($user['id'])['body']['article'];
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
    <link href="../res/vendor/materialize/css/materialize.css" rel="stylesheet" media="screen,projection">
    <link href="../res/vendor/bootstrap/css/bootstrap.css" rel="stylesheet"  media="screen,projection">
    <!-- reference your copy Font Awesome here (from our CDN or by hosting yourself) -->
    <link href="../res/vendor/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="../res/vendor/fontawesome/css/brands.css" rel="stylesheet">
    <link href="../res/vendor/fontawesome/css/solid.css" rel="stylesheet">
    <link href="../res/css/main.css" rel="stylesheet" media="screen,projection">
    <title>Detail Article</title>
    <style type="text/css">
        .fa-trash-alt {
            color: red;
        }
        .fa-edit {
            color: dodgerblue;
        }
        .fa-check-circle {
            color: #0D47A1;
        }
    </style>
    <script type="text/javascript">
        $('.dropdown-trigger').dropdown();
    </script>
</head>
<body>
<?php include "../partials/navbar.php"; ?>
<?php if (!empty($articles)) { ?>
<section>
    <div class="container">
        <div class="card mb-3" style="max-width: 840px; max-height: 540px;">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <img src="../<?php echo $article['url']; ?>"
                             class="card-img" alt="Article image">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <div class="card-header">
                                <img src="../<?php echo $user['url']; ?>" class="circle float-left"
                                     style="width: 3rem; height: 3rem;" />
                                <p class="card-text text-sm-right grey-text"><?php echo $user['username']; ?>
                                    <i class="fa fa-check-circle fa-1x" ></i> Verified<br/>
                                    Updated  <?php echo $article['last_updated']; ?>
                                </p>
                            </div>
                            <div class="card-title py-2">
                                <h5 class="h5 left"><?php echo $article['headline']; ?></h5>
                                <!-- Dropdown Trigger -->
                                <a class='dropdown-trigger right'
                                   href='#' data-target='dropdown1'><i class="fas fa-ellipsis-v fa-1x"></i></a>
                                <!-- Dropdown Structure -->
                                <ul id='dropdown1' class='dropdown-content'>
                                    <li><a href="edit_article.php?ID=<?php echo $article['id']; ?>"><i class="fas fa-edit fa-1x"></i>Edit</a></li>
                                    <li><a href="delete_article.php?ID=<?php echo $article['id']; ?>"><i class="fa fa-trash-alt fa-1x"></i>Delete</a></li>
                                </ul>
                            </div>

                            <p class="card-content"><?php echo $article['content']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php foreach ($articles as $content) { ?>
            <div class="card mb-3" style="max-width: 840px; max-height: 540px;">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <img src="../<?php echo $content['url']; ?>"
                             class="card-img" alt="Article image">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <div class="card-header">
                                <img src="../<?php echo $user['url']; ?>" class="circle float-left"
                                     style="width: 3rem; height: 3rem;" />
                                <p class="card-text text-sm-right grey-text"><?php echo $user['username']; ?>
                                    <i class="fa fa-check-circle fa-1x" ></i> Verified<br/>
                                    Updated  <?php echo $content['last_updated']; ?>
                                </p>
                            </div>
                            <div class="card-title py-2">
                                <h5 class="h5 left"><?php echo $content['headline']; ?></h5>
                                <!-- Dropdown Trigger -->
                                <a class='dropdown-trigger right'
                                   href='#' data-target='dropdown1'><i class="fas fa-ellipsis-v fa-1x"></i></a>
                            </div>
                            <!-- Dropdown Structure -->
                            <ul id='dropdown1' class='dropdown-content'>
                                <li><a href="edit_article.php?ID=<?php echo $content['id']; ?>"><i class="fas fa-edit fa-1x"></i>Edit</a></li>
                                <li><a href="delete_article.php?ID=<?php echo $content['id']; ?>"><i class="fa fa-trash-alt fa-1x"></i>Delete</a></li>
                            </ul>
                            <p class="card-content"><?php echo $content['content']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php    } ?>
    </div>
</section>
<?php } ?>
<?php if (empty($articles) && !empty($article) ) { ?>
    <section>
        <div class="container">
                <div class="card mb-3" style="max-width: 840px; max-height: 540px;">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img src="../<?php echo $article['url']; ?>"
                                 class="card-img" alt="Article image">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <div class="card-header">
                                    <img src="../<?php echo $user['url']; ?>" class="circle float-left"
                                         style="width: 3rem; height: 3rem;" />
                                    <p class="card-text text-sm-right grey-text"><?php echo $user['username']; ?>
                                        <i class="fa fa-check-circle fa-1x" ></i> Verified<br/>
                                        Updated  <?php echo $article['last_updated']; ?>
                                    </p>
                                </div>
                                <div class="card-title py-2">
                                    <h5 class="h5 left"><?php echo $article['headline']; ?></h5>
                                    <!-- Dropdown Trigger -->
                                    <a class='dropdown-trigger right'
                                       href='#' data-target='dropdown1'><i class="fas fa-ellipsis-v fa-1x"></i></a>
                                </div>
                                <!-- Dropdown Structure -->
                                <ul id='dropdown1' class='dropdown-content'>
                                    <li><a href="edit_article.php?ID=<?php echo $article['id']; ?>"><i class="fas fa-edit fa-1x"></i>Edit</a></li>
                                    <li><a href="delete_article.php?ID=<?php echo $article['id']; ?>"><i class="fa fa-trash-alt fa-1x"></i>Delete</a></li>
                                </ul>
                                <p class="card-content"><?php echo $article['content']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </section>
<?php } ?>

<!--  Scripts-->
<script src="../res/vendor/jquery-3.4.1.js"></script>
<script src="../res/vendor/popper.min.js"></script>
<script src="../res/vendor/materialize/js/materialize.js"></script>
<script src="../res/vendor/jquery.mobile-1.4.5.js"></script>
<script src="../res/vendor/fontawesome/js/fontawesome.min.js"></script>
<script src="../res/js/init.js"></script>
</body>
</html>

