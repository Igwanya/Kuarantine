<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 12-May-20
 * Time: 2:06 AM
 */

namespace Src;

use finfo;
use http\Exception\RuntimeException;

require_once __DIR__ . '../../vendor/autoload.php';

//header('Content-Type: text/plain; charset=utf-8');
$result = array();
$request_method = $_SERVER['REQUEST_METHOD'];
if ($request_method == 'GET') {
    $repo = new Repository();
    $result =  $repo->read_all_categories();
}

if ($request_method == 'POST') {
    $repo = new Repository();
    $result =  $repo->read_all_categories();
    try {
        $upload_dir = $_SERVER['DOCUMENT_ROOT'].'/public_html/res/assets/products/';
        $title = filter_input(INPUT_POST, 'title');
        $description = filter_input(INPUT_POST, 'inputDescription');
        $category_name =  filter_input(INPUT_POST, 'category_name');
        $price = filter_input(INPUT_POST, 'price');
        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (
            !isset($_FILES['productfile']['error']) ||
            is_array($_FILES['productfile']['error'])
        ) {
            throw new RuntimeException('Invalid parameters.');
        }
        // Check $_FILES['productfile']['error'] value.
        switch ($_FILES['productfile']['error']) {
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
        if ($_FILES['productfile']['size'] > 1000000) {
            throw new RuntimeException('Exceeded filesize limit.');
        }

        // DO NOT TRUST $_FILES['productfile']['mime'] VALUE !!
        // Check MIME Type by yourself.
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
                $finfo->file($_FILES['productfile']['tmp_name']),
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
//            sha1_file($_FILES['productfile']['tmp_name']
        // You should name it uniquely.
        // DO NOT USE $_FILES['productfile']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        $uploadfile =  sha1_file($_FILES['productfile']['tmp_name']).'.'.$ext;
        if (move_uploaded_file($_FILES['productfile']['tmp_name'],
            $upload_dir. sha1_file($_FILES['productfile']['tmp_name']).'.'.$ext)) {
            $raw_url = preg_split("#/#", $upload_dir);
//            $result_url = $raw_url[3].'/'.$raw_url[4].'/'.$raw_url[5].'/'.$raw_url[6].'/'.$raw_url[7];
            $result_url = $raw_url[4].'/'.$raw_url[5].'/'.$raw_url[6].'/'.$raw_url[7];
            $product_url = $result_url.$uploadfile;  // Changes in the server directory structure will break things.
            $repository = new Repository();
            $category_id = $repository->find_category_id($category_name)['body']['category']['id'];
            $repository->add_product_to_db($product_url, $title, $description, $category_id, $price);

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
    <link href="../res/css/main.css" rel="stylesheet" media="screen,projection">
    <title>Products</title>
</head>
<body>
<nav class="white" id="passwordResetNav">
    <div class="nav-wrapper">
        <ul id="nav-mobile" class="left">
            <li>
                <a href="../admin/admin.php" >
                    <i id="passwordResetLoginPageIcon" class="material-icons">chevron_left</i>
                </a></li>
            <li id="passwordResetNavBack" >
                Go Back
            </li>
        </ul>
    </div>
</nav>
<section>
    <div class="container">
        <div class="row" >
            <div class="col-md-3"></div>
            <div class="col-md-7 mt-5">
                <form enctype="multipart/form-data" method="POST" action="add_product.php">
                    <fieldset>
                        <legend>Add a product</legend>
                        <div class="file-field input-field" id="inputPhoto">
                            <div class="btn">
                                <label for="productPhoto" class="text-light">Upload a picture</label>
                                <input id="productPhoto" type="file" name="productfile" class="">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" />
                            </div>
                            <div class="input-field ">
                                <select id="inputCategory" name="category_name">
                                    <option value=""  disabled selected>Choose a product category </option>
                                    <?php foreach ($result['body']['categories'] as $value) {
                                        foreach ($value as $x => $y) {
                                            if (!is_numeric($y)) {?>
                                                <option value="<?php echo $y; ?>" ><?php echo $y; ?></option>
                                            <?php  }
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="inputCategory">Category</label>
                            </div>
                            <div class="input-field ">
                                <input id="inputTitle" type="text" name="title" class="validate">
                                <label for="inputTitle">Title</label>
                                <span class="helper-text text-danger" data-error="wrong" data-success=""></span>
                            </div>
                            <div class="input-field ">
                                <textarea id="inputDescription" name="inputDescription"
                                          data-length="250" class="materialize-textarea">
                                </textarea>
                                <label for="inputDescription">Description</label>
                                <span class="helper-text text-danger" data-error="wrong" data-success=""></span>
                            </div>
                            <div class="input-field ">
                                <input id="inputPrice" type="number" name="price" class="validate">
                                <label for="inputPrice">Price</label>
                                <span class="helper-text text-danger" data-error="wrong" data-success=""></span>
                            </div>
                            <div class="input-field right">
                                <input type="submit" id="inputSubmit" class="btn" />
                            </div>

                    </fieldset>
                </form>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
</section>

<script type="text/javascript" src="../res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="../res/vendor/popper.min.js"></script>
<script type="text/javascript" src="../res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="../res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="../res/js/init.js"></script>
</body>
</html>
