<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 19-May-20
 * Time: 4:55 AM
 */
namespace  Src;

require_once __DIR__ . '../../../vendor/autoload.php';

session_start();

use finfo;
use http\Exception\RuntimeException;

$repository = new Repository();
$result = array();
$query  = array();

/**
 * Redirect to the profile page
 */
function redirect_to_profile_page()
{
    /* Redirect to a different page in the current directory that was requested */
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = '../profile.php';
    header("Location: http://$host$uri/$extra");
    exit;
}

$request_method = $_SERVER['REQUEST_METHOD'];
$user = array();
if ($request_method == 'GET') {
    $query = $repository->find_user_with_id($_SESSION['login_ID']);
    $user = $query['body']['user'];
}

if ($request_method == 'POST') {
    $repo = new Repository();
    $result =  $repo->read_all_categories();
    try {
        $upload_dir = $_SERVER['DOCUMENT_ROOT'].'/public_html/res/assets/blog/';
        $headline   = filter_input(INPUT_POST, 'headline');
        $content    = filter_input(INPUT_POST, 'content');

        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (
            !isset($_FILES['article_file']['error']) ||
            is_array($_FILES['article_file']['error'])
        ) {
            throw new RuntimeException('Invalid parameters.');
        }
        // Check $_FILES['article_file']['error'] value.
        switch ($_FILES['article_file']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }

        // You should also check filesize here.
        if ($_FILES['article_file']['size'] > 1000000) {
            throw new RuntimeException('Exceeded filesize limit.');
        }

        // DO NOT TRUST $_FILES['article_file']['mime'] VALUE !!
        // Check MIME Type by yourself.
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
                $finfo->file($_FILES['article_file']['tmp_name']),
                array(
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                ),
                true
            )) {
            throw new RuntimeException('Invalid file format.');
        }

//        sprintf('./uploads/%s.%s',
//            sha1_file($_FILES['article_file']['tmp_name']
        // You should name it uniquely.
        // DO NOT USE $_FILES['article_file']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        $uploadfile =  sha1_file($_FILES['article_file']['tmp_name']).'.'.$ext;
        if (move_uploaded_file($_FILES['article_file']['tmp_name'],
            $upload_dir. sha1_file($_FILES['article_file']['tmp_name']).'.'.$ext)) {
            $raw_url = preg_split("#/#", $upload_dir);
//            $result_url = $raw_url[3].'/'.$raw_url[4].'/'.$raw_url[5].'/'.$raw_url[6].'/'.$raw_url[7];
            $result_url = $raw_url[4].'/'.$raw_url[5].'/'.$raw_url[6].'/'.$raw_url[7];
            $article_url = $result_url.$uploadfile;  // Changes in the server directory structure will break things.
            $repository = new Repository();
            $finished_result =  $repository->add_post($article_url, $headline, $content, $_SESSION['login_ID']);
            if (empty($finished_result['error'])){
                redirect_to_profile_page();
            }

        } else {
            echo "Possible file upload attack!\n";
        }
    } catch (RuntimeException $e) {
        echo $e->getMessage();
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
    <link href="../res/vendor/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen,projection">
    <link href="../res/vendor/materialize/css/materialize.css" rel="stylesheet" media="screen,projection">
    <!-- reference your copy Font Awesome here (from our CDN or by hosting yourself) -->
    <link href="../res/vendor/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="../res/vendor/fontawesome/css/brands.css" rel="stylesheet">
    <link href="../res/vendor/fontawesome/css/solid.css" rel="stylesheet">
    <link href="../res/css/main.css" rel="stylesheet" media="screen,projection">
    <title>Article</title>
    <style type="text/css">
        .fa-comments {
            color: grey;
        }
    </style>
</head>
<body>
<header>
  <?php include_once '../partials/navbar.php' ?>
</header>
<section>
    <div class="container">
        <div class="row" >
            <div class="col-md-2"></div>
            <div class="col-md-8">
                 <div class="card">
                     <div class="">
                         <img class="card-img" src="../res/img/welcome_image.jpg">
                         <div class="card-title">
                             <h6 class="text-center">Share your story <i class="fas fa-comments"></i></h6>
                         </div>
                     </div>
                     <div class="card-content">
                             <form enctype="multipart/form-data" method="POST" action="add_article.php">
                             <div class="file-field input-field" id="file">
                                 <div class="btn btn-small">
                                     <label for="productPhoto" class="text-light"><i class="fas fa-file-upload"></i> Upload a picture</label>
                                     <input id="productPhoto" type="file" name="article_file" class=""></div>
                                 <div class="file-path-wrapper">
                                     <input class="file-path validate" type="text" />
                                     <span class="helper-text grey-text text-lowercase" data-error="" data-success="">upload an image to go with your story</span>
                                 </div>
                             </div>
                             <div class="input-field ">
                                 <input id="inputTitle" type="text" name="headline" class="validate">
                                 <label for="inputTitle">Headline</label>
                                 <span class="helper-text grey-text" data-error="" data-success="">A short descriptive title for the readers</span>
                             </div>
                             <div class="input-field ">
                                <textarea id="inputDescription" name="content"
                                          data-length="250" class="materialize-textarea">
                                </textarea>
                                 <label for="inputDescription">Content</label>
                                 <span class="helper-text grey-text" data-error="" data-success="">Words not more than 250 should be nice</span>
                             </div>
                             <div class="input-field">
                                 <div class="right">
                                     <i class="fa fa-share"></i>
                                     <input type="submit" id="inputSubmit" class="btn btn-sm btn-flat">
                                 </div>
                             </div>
                             </form>
                         </div>
                     <div class="card-action"></div>
                 </div>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
</section>
<script type="text/javascript" src="../res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="../res/vendor/popper.min.js"></script>
<script type="text/javascript" src="../res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="../res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="../res/js/init.js"></script>
</body>
</html>


