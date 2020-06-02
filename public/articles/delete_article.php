<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 21-May-20
 * Time: 10:29 AM
 */
namespace Src;

session_start();
require_once __DIR__ . '../../../vendor/autoload.php';

$repository = new Repository();
$request_method = $_SERVER['REQUEST_METHOD'];
switch ($request_method) {
    case 'GET':
        $id = filter_input(INPUT_GET, "ID");
        $upload_dir = $_SERVER['DOCUMENT_ROOT'].'/public/res/assets/blog/';
        $result = $repository->read_post_by_id($id);
        $article = $result['body']['article'];
        $path = preg_split("#/#", $article['url']);
        unlink($upload_dir.$path[3]);
        $result = $repository->delete_post($id);
        header("Location: ../profile.php");
        break;


}

