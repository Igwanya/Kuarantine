<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 12-May-20
 * Time: 5:43 PM
 */

namespace Src\api;

use function Src\delete_all_session_variables;
use Src\models\User;
use Src\Repository;

require_once __DIR__ . '../../../vendor/autoload.php';
include_once "../../public/utils.php";
session_start();

ini_set('display_errors', true);
ini_set('html_errors', true);
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Headers: access");
//header("Access-Control-Allow-Methods: GET,POST");
//header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$repository = new Repository();
//print_r($_SESSION);
//delete_all_session_variables();

//echo \Src\get_server_url_domain_name();

$url_components = parse_url($_SERVER['REQUEST_URI']);
//print_r($url_components);
parse_str($url_components['query'], $params);
//print_r($params);
if (isset($params['ID']) && !empty($params['ID'])){
    echo $params['ID'];
}


//$result =  $repository->find_user_with_id(34);
//$user = $result['body']['user'];
////print_r($user);
//$username ="maxwell";
//$path = $user['url'];
//$arr  = preg_split("#/#", $path);
//print_r($arr);
//echo  $arr[0].'/'.$username.'/'.$arr[2];
//
// \Src\delete_all_session_variables();

//$upload_dir  = $_SERVER["DOCUMENT_ROOT"]."/public/uploads/";
//delete_directory($upload_dir."/todeleteretro");


//$article = $repository->read_post_by_id(5);
//print_r($article);
//$path = preg_split("#/#", $article['body']['article']['url']);
//print_r($path[3]);



