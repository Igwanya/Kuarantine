<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 21-May-20
 * Time: 7:22 AM
 */

namespace Src;

require_once __DIR__ . '../../../vendor/autoload.php';
session_start();
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('html_errors', true);
date_default_timezone_set('Africa/Nairobi');

$repository = new Repository();
$request_method = $_SERVER['REQUEST_METHOD'];
$result = array();


/**
 * Handle the form and log in the user
 */
function redirect_to_login_page()
{
    /* Redirect to a different page in the current directory that was requested */
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'login.php';
    header("Location: http://$host$uri/$extra");
    exit;
}

/**
 * Redirect to the profile page
 */
function redirect_to_profile_page()
{
    /* Redirect to a different page in the current directory that was requested */
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'profile.php';
    header("Location: http://$host$uri/$extra");
    exit;
}

switch ($request_method) {
    case 'GET':
        if (isset($_SESSION['login_ID']) && isset($_SESSION['is_authenticated']))  {
           $result = $repository->load_all_apps_data();
//           print_r($result);
        } else {
            redirect_to_login_page();
        }
        

        break;
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
    <!-- reference your copy Font Awesome here (from our CDN or by hosting yourself) -->
    <link href="../res/vendor/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="../res/vendor/fontawesome/css/brands.css" rel="stylesheet">
    <link href="../res/vendor/fontawesome/css/solid.css" rel="stylesheet">
    <link href="../res/css/main.css" rel="stylesheet" media="screen,projection">
    <title>Application</title>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.modal').modal();
        });
    </script>
</head>
<body>
<header>
    <?php include '../partials/navbar.php'; ?>
</header>
<main>
    <div class="container">

        <div class="card">
            <div class="card-content">
                <table class="table table-sm table-hover table-responsive">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">applicationID</th>
                        <th scope="col">versionName</th>
                        <th scope="col">versionCode</th>
                        <th scope="col">userID</th>
                        <th scope="col">display</th>
                        <th scope="col">created</th>
                        <th scope="col">last updated</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($result['body']['app'] as $arr) { ?>
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
                                        <a class="dropdown-item btn-small red" href="delete_app_data.php?ID=<?php echo $arr['id'] ?>">DELETE</a>
                                </div>
                            </th>
                            <!--                    <td>--><?php //echo $arr['id'] ?><!--</td>-->
                            <td><?php echo $arr['app_id'] ?></td>
                            <td><?php echo $arr['version_name'] ?></td>
                            <td><?php echo $arr['version_code'] ?></td>
                            <td><?php echo $arr['user_id'] ?></td>
                            <td><?php echo $arr['display'] ?></td>
                            <td><?php echo $arr['created'] ?></td>
                            <td><?php echo $arr['last_updated'] ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="card-action ">
                <?php if ($result['body']['count'] == 0) { ?>
                    <!-- Modal Trigger -->
                    <a class="waves-effect waves-light modal-trigger" href="#modal1">
                        <i class="fas fa-sync"></i> Load data <i class="fa fa-ellipsis-h"></i></a>
                    <!-- Modal Structure -->
                    <div id="modal1" class="modal modal-trigger">
                        <div class="modal-content">
                            <h4 class="h4">Application data not present</h4>
                            <p class="">No apps have sent data to the server yet <i class="fa fa-ellipsis-h"></i> </p>
                        </div>
                        <div class="modal-footer">
                            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Ok</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

    </div>
</main>

<!--  Scripts-->
<script src="../res/vendor/jquery-3.4.1.js"></script>
<script src="../res/vendor/popper.min.js"></script>
<script src="../res/vendor/materialize/js/materialize.js"></script>
<script src="../res/vendor/jquery.mobile-1.4.5.js"></script>
<script src="../res/vendor/fontawesome/js/fontawesome.min.js"></script>
<script src="../res/js/init.js"></script>
</body>
</html>
