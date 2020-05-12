<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 12-May-20
 * Time: 2:06 AM
 */

namespace Src;

require_once __DIR__ . '../../vendor/autoload.php';


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
    <title>Notifications</title>
</head>
<body>
<nav class="white" id="passwordResetNav">
    <div class="nav-wrapper">
        <ul id="nav-mobile" class="left">
            <li>
                <a href="admin.php" >
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
                <form enctype="multipart/form-data" method="POST" action="products.php">
                    <fieldset>
                        <legend>Add a notification</legend>
                        <div class="file-field input-field" id="inputPhoto">
                            <div class="btn">
                                <label for="notificationPhoto" class="text-light">Upload a picture</label>
                                <input id="notificationPhoto" type="file" name="notificationfile" class="">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" />
                            </div>
                            <div class="input-field ">
                                <select id="inputLevel">
                                    <option value=""  disabled selected>Choose the level</option>
                                    <option value="" ></option>
                                </select>
                                <label for="inputCategory">LEVEL</label>
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

<script type="text/javascript" src="res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="res/vendor/popper.min.js"></script>
<script type="text/javascript" src="res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="res/js/init.js"></script>
</body>
</html>
