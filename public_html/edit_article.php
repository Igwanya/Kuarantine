<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 21-May-20
 * Time: 10:50 AM
 */
namespace  Src;

require_once __DIR__ . '../../vendor/autoload.php';

session_start();

use finfo;
use http\Exception\RuntimeException;

$repository = new Repository();
$result = array();
$article = array();
$query  = array();
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        $id = filter_input(INPUT_GET, "ID");
        $id = filter_var($id, FILTER_VALIDATE_INT);
        $result = $repository->read_post($id);
        $article = $result['body']['article'];

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
    <!-- reference your copy Font Awesome here (from our CDN or by hosting yourself) -->
    <link href="res/vendor/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="res/vendor/fontawesome/css/brands.css" rel="stylesheet">
    <link href="res/vendor/fontawesome/css/solid.css" rel="stylesheet">
    <link href="res/css/main.css" rel="stylesheet" media="screen,projection">
    <title>Update Article</title>
    <style type="text/css">
        .fa-camera {
            color: red;
        }
    </style>
</head>
<body>
<div class="container">
    <header></header>
    <main>
        <div class="row">
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-image">
                        <img src="<?php echo $article['url'] ?>">
                        <span class="card-title"><?php echo $article['headline']; ?></span>
                        <a href=admin/delete_article.php?ID=<?php echo $article['id']; ?>" class="btn-floating halfway-fab waves-effect waves-light red"><i class="fas fa-trash fa-2x"></i></a>
                    </div>
                    <div class="card-content">
                        <form enctype="multipart/form-data" method="POST" action="edit_article.php">
                                <div class="card-content">
                                    <div class="file-field input-field" id="inputPhoto">
                                        <div class="btn-small">
                                            <label for="productPhoto" class="">
                                                <i class="fas fa-file-upload"></i> Upload a picture</label>
                                            <input id="productPhoto" type="file" name="article_file" class="">
                                        </div>
                                        <div class="file-path-wrapper">
                                            <input class="file-path validate" type="text" />
                                        </div>
                                    </div>
                                    <div class="input-field">
                                        <input id="inputTitle" type="text" name="headline"
                                               value="<?php echo $article['headline']; ?>" class="validate">
                                        <label for="inputTitle">Headline</label>
                                        <span class="helper-text text-danger" data-error="wrong" data-success=""></span>
                                    </div>
                                    <div class="input-field ">
                                <textarea id="inputDescription" name="content" data-length="250"
                                          class="materialize-textarea"> <?php echo $article['content']; ?>
                                </textarea>
                                        <label for="inputDescription">Content</label>
                                        <span class="helper-text text-danger" data-error="wrong" data-success=""></span>
                                    </div>
                                    <div class="input-field">
                                        <input type="submit" id="inputSubmit" class="btn btn-sm right">
                                    </div>
                                </div>
                        </form>
                    </div>

                    <div class="card-action"></div>
                </div>
            </div>
        </div>

    </main>
    <?php include '../public_html/partials/footer.php'; ?>
</div>



<!--  Scripts-->
<script src="res/vendor/jquery-3.4.1.js"></script>
<script src="res/vendor/popper.min.js"></script>
<script src="res/vendor/materialize/js/materialize.js"></script>
<script src="res/vendor/jquery.mobile-1.4.5.js"></script>
<script src="res/vendor/fontawesome/js/fontawesome.min.js"></script>
<script src="res/js/init.js"></script>
</body>
</html>

