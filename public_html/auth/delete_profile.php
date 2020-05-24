<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 23-May-20
 * Time: 5:30 AM
 */

namespace Src;

session_start();
require_once __DIR__ . '../../../vendor/autoload.php';
include_once "../utils.php";

$id = filter_var($_SESSION['login_ID'], FILTER_VALIDATE_INT);
$repository = new Repository();
$id = filter_var($_SESSION["login_ID"], FILTER_VALIDATE_INT);
$result = $repository->find_user_with_id($id);
$user = $result['body']['user'];
$upload_dir  = $_SERVER["DOCUMENT_ROOT"]."/public_html/uploads/";

if (empty($repository->delete_user_from_db($id)['error'])) {
    delete_all_session_variables();
    delete_directory($upload_dir."/".$user['username']);
    header("Location: http://" .$_SERVER['SERVER_NAME']);
}
