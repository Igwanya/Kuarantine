<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 19-May-20
 * Time: 2:12 AM
 */

namespace Src;

require_once __DIR__ . '../../../vendor/autoload.php';

$repository = new Repository();
$users =  $repository->find_all_users()['body']['user'];
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
    <link href="../res/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen,projection">
    <link href="../res/vendor/fontawesome/css/fontawesome.min.css" rel="stylesheet" media="screen,projection">
    <link href="../res/css/main.css" rel="stylesheet" media="screen,projection">
    <title>Accounts</title>
    <style>
        header, main, footer {
            padding-left: 300px;
        }
        @media only screen and (max-width : 992px) {
            header, main, footer {
                padding-left: 0;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="container">


        <table class="table table-sm table-hover table-responsive">
            <thead class="thead-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">url</th>
                <th scope="col">username</th>
                <th scope="col">email</th>
                <th scope="col">first name</th>
                <th scope="col">last name</th>
                <th scope="col">full name</th>
                <th scope="col">is admin</th>
                <th scope="col">created</th>
                <th scope="col">last updated</th>
                <th scope="col">expiry date</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $arr) { ?>
            <tr>
                <th scope="row">
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary btn-sm">Action</button>
                        <button type="button" class="btn btn-secondary dropdown-toggle
                        dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#">Something else here</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Separated link</a>
                        </div>
                    </div>
                </th>
                
                <td><?php echo $arr['url'] ?></td>
                <td><?php echo $arr['username'] ?></td>
                <td><?php echo $arr['email'] ?></td>
                <td><?php echo $arr['first_name'] ?></td>
                <td><?php echo $arr['last_name'] ?></td>
                <td><?php echo $arr['full_name'] ?></td>
                <td><?php echo $arr['is_admin'] ?></td>
                <td><?php echo $arr['created'] ?></td>
                <td><?php echo $arr['last_updated'] ?></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>



    </div>
</main>





<script type="text/javascript" src="../res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="../res/vendor/popper.min.js"></script>
<script type="text/javascript" src="../res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="../res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="../res/js/init.js"></script>
</body>
</html>
