<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 21-May-20
 * Time: 10:06 AM
 */

namespace Src;

require_once __DIR__ . '../../../vendor/autoload.php';

session_start();
$repository = new Repository();

$result = array();
$query = array();
$request_method = $_SERVER['REQUEST_METHOD'];
switch ($request_method) {
    case 'GET':
        $result = $repository->read_all_posts();
        $query = $repository->find_user_with_id($_SESSION['login_ID']);


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
    <title>Articles</title>
</head>
<body>
<header>
    <nav>
        <div class="nav-wrapper p-2">
            <a href="<?php echo $_SERVER['SERVER_NAME']; ?>"> site</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="../admin/admin.php">
                        <i class="fa fa-user-astronaut"></i><?php echo $query['body']['user']['username']; ?></a></li>
                <li><a href="add_article.php"><i class="fas fa-edit"></i>Create</a> </li>
            
                <li class=""><i class="" ></i></li>
                <li><a href="../logout.php"><i class="fa fa-power-off fa-1x"></i>log out</a></li>
            </ul>
        </div>
    </nav>
</header>
<main>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <table class="table table-sm table-hover table-responsive">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">url</th>
                        <th scope="col">headline</th>
                        <th scope="col">content</th>
                        <th scope="col">user id</th>
                        <th scope="col">created</th>
                        <th scope="col">last updated</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($result['body']['articles'] as $arr) { ?>
                        <tr>
                            <th scope="row">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary btn-sm">Action</button>
                                    <button type="button" class="btn btn-secondary dropdown-toggle
                        dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#"></a>
                                        <a class="dropdown-item" href="#"></a>
                                        <a class="dropdown-item" style="color: #00fffd" href="../detail_article.php?ID=<?php echo $arr['id'] ?>">DETAIL</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item " style="color:red;" href="delete_article.php?ID=<?php echo $arr['id'] ?>">DELETE</a>
                                    </div>
                                </div>
                            </th>
                            <td><?php echo $arr['url'] ?></td>
                            <td><?php echo $arr['headline'] ?></td>
                            <td><?php echo $arr['content'] ?></td>
                            <td><?php echo $arr['user_id'] ?></td>
                            <td><?php echo $arr['created'] ?></td>
                            <td><?php echo $arr['last_updated'] ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="card-action">
                <?php if ($result['body']['count'] == 0) { ?>
                    <!-- Modal Trigger -->
                    <a class="waves-effect waves-light modal-trigger" href="#modal1">
                        <i class="fas fa-sync"></i> Load data <i class="fa fa-ellipsis-h"></i></a>
                    <!-- Modal Structure -->
                    <div id="modal1" class="modal modal-trigger">
                        <div class="modal-content">
                            <h4 class="h4"><?php echo $result['status']; ?></h4>
                            <p class="">No content has been posted yet <i class="fa fa-ellipsis-h"></i> </p>
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