<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 21-May-20
 * Time: 10:50 AM
 */
namespace  Src;

require_once __DIR__ . '../../../vendor/autoload.php';

session_start();

use finfo;
use http\Exception\RuntimeException;

$repository = new Repository();
$result = array();
$article = array();
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

switch ($request_method) {
    case 'GET':
        $id = filter_input(INPUT_GET, "ID");
        $id = filter_var($id, FILTER_VALIDATE_INT);
        $result = $repository->read_post_by_id($id);
        $article = $result['body']['article'];
         break;
    case 'POST':
        $id = filter_input(INPUT_POST, "ID");
        $result = $repository->read_post_by_id($id);
        $article = $result['body']['article'];
        $headline   = filter_input(INPUT_POST, 'headline');
        $content    = filter_input(INPUT_POST, 'content');
        if (is_uploaded_file($_FILES['article_file']['tmp_name'])){
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
                if (is_uploaded_file($_FILES['article_file']['tmp_name'])) {
                    $path = preg_split("#/#", $article['url']);
                    unlink($upload_dir.$path[3]);
                }
                if (is_uploaded_file($_FILES['article_file']['tmp_name']) &&  move_uploaded_file($_FILES['article_file']['tmp_name'],
                        $upload_dir. sha1_file($_FILES['article_file']['tmp_name']).'.'.$ext)) {
                    $raw_url = preg_split("#/#", $upload_dir);
//            $result_url = $raw_url[3].'/'.$raw_url[4].'/'.$raw_url[5].'/'.$raw_url[6].'/'.$raw_url[7];
                    $result_url = $raw_url[4].'/'.$raw_url[5].'/'.$raw_url[6].'/'.$raw_url[7];
                    $article_url = $result_url.$uploadfile;  // Changes in the server directory structure will break things.
                    $repository = new Repository();
                    echo $article_url;
                    $finished_result =  $repository->update_post($article_url, $headline, $content, $_SESSION['login_ID']);
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
        
        $repository->update_post($article['id'], $headline, $content, $_SESSION['login_ID']);


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
    <title>Update Article</title>
    <style type="text/css">
        .fa-trash-alt {
            color: red;
        }
    </style>
</head>
<body>
<?php include "../partials/navbar.php"; ?>
<section>
    <div class="container">
        <div class="row">
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-image">
                        <img src="../<?php echo $article['url']; ?>">
                        <span class="card-title">Update the article</span>
                        <a href="delete_article.php" class="btn-floating halfway-fab waves-effect waves-light">
                            <i class="fas fa-trash-alt"></i></a>
                    </div>
                    <div class="card-content">
                        <form enctype="multipart/form-data" method="POST" action="edit_article.php">
                            <div class="file-field input-field" id="file">
                                <div class="btn-flat btn-small">
                                    <label for="productPhoto" class=""><i class="fas fa-file-upload fa-1x"></i> Upload a picture</label>
                                    <input id="productPhoto" type="file" name="article_file" class=""></div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" />
                                    <span class="helper-text grey-text text-lowercase" data-error="" data-success="">Update the image </span>
                                </div>
                            </div>
                            <div class="input-field ">
                                <input id="inputTitle" type="text" name="headline" value="<?php echo $article['headline']; ?>" class="validate">
                                <label for="inputTitle">Headline</label>
                                <span class="helper-text grey-text" data-error="" data-success="">A short descriptive title for the readers</span>
                            </div>
                            <div class="input-field ">
                                <textarea id="inputDescription" name="content"
                                          data-length="250" class="materialize-textarea"> <?php echo $article['content']; ?>
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
                            <input type="hidden" name="ID" value="<?php echo $article['id'];?>" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!--  Scripts-->
<script src="../res/vendor/jquery-3.4.1.js"></script>
<script src="../res/vendor/popper.min.js"></script>
<script src="../res/vendor/materialize/js/materialize.js"></script>
<script src="../res/vendor/jquery.mobile-1.4.5.js"></script>
<script src="../res/vendor/fontawesome/js/fontawesome.min.js"></script>
<script src="../res/js/init.js"></script>
</body>
</html>

