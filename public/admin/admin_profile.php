<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 20-May-20
 * Time: 12:29 AM
 */

namespace Src;

require_once __DIR__ . '../../../vendor/autoload.php';

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
    <!--        <link href="../res/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen,projection">-->
    <!-- reference your copy Font Awesome here (from our CDN or by hosting yourself) -->
    <link href="../res/vendor/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="../res/vendor/fontawesome/css/brands.css" rel="stylesheet">
    <link href="../res/vendor/fontawesome/css/solid.css" rel="stylesheet">
    <link href="../res/css/main.css" rel="stylesheet" media="screen,projection">
    <title>Account</title>
    <style type="text/css">
        header, main, footer {
            padding-left: 300px;
        }
        @media only screen and (max-width : 992px) {
            header, main, footer {
                padding-left: 0;
            }
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.tabs').tabs();
        });

        $('.dropdown-trigger').dropdown()
    </script>
</head>
<body>
<header>
    <div class="container">
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <li class="tab col s3"><a href="#test1">Test 1</a></li>
                    <li class="tab col s3"><a class="active" href="#test2">Test 2</a></li>
                    <li class="tab col s3 disabled"><a href="#test3">Disabled Tab</a></li>
                    <li class="tab col s3"><a href="#test4">Test 4</a></li>
                </ul>
            </div>
            <div id="test1" class="col s12">Test 1</div>
            <div id="test2" class="col s12">Test 2</div>
            <div id="test3" class="col s12">Test 3</div>
            <div id="test4" class="col s12">Test 4</div>
        </div>
    </div>
</header>
<!--  Scripts-->
<script src="../res/vendor/jquery-3.4.1.js"></script>
<script src="../res/vendor/popper.min.js"></script>
<script src="../res/vendor/materialize/js/materialize.js"></script>
<script src="../res/vendor/jquery.mobile-1.4.5.js"></script>
<script src="../res/vendor/fontawesome/js/fontawesome.min.js"></script>
<script src="../res/js/init.js"></script>
</body>
</html>
