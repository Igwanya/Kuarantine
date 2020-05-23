<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 21-May-20
 * Time: 11:15 AM
 */
namespace Src;

require_once __DIR__ . '../../vendor/autoload.php';

session_start();
$repository = new Repository();
$request_method = $_SERVER['REQUEST_METHOD'];
$result = array();
$article = array();
switch ($request_method) {
    case 'GET':
        $id = filter_input(INPUT_GET, "ID");
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
    <title>Detail Articles</title>
</head>
<body>
<div class="container">
    <header></header>
    <main>
        <div class="row">
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-image">
                        <img src="<?php echo $article['url']; ?>">
                        <span class="card-title"><?php echo $article['headline']; ?></span>
                        <a class="btn-floating halfway-fab waves-effect waves-light red"
                           href="edit_article.php?ID=<?php echo $article['id']; ?>"><i class="material-icons">edit</i></a>
                    </div>
                    <div class="card-content">
                        <p><?php echo $article['content']; ?></p>
                        <div>
                            <p class="teal-text grey-text">Posted by
                                <?php echo $repository->find_user_with_id($article['user_id'])['body']['user']['username']; ?>  at <?php echo $article['created'] ?></p>
                        </div>
                    </div>

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

