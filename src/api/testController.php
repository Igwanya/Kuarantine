<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 12-May-20
 * Time: 5:43 PM
 */

namespace Src\api;

use Src\models\User;
use Src\Repository;

require_once __DIR__ . '../../../vendor/autoload.php';
include_once "../../public_html/utils.php";
session_start();

ini_set('display_errors', true);
ini_set('html_errors', true);
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Headers: access");
//header("Access-Control-Allow-Methods: GET,POST");
//header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$repository = new Repository();
print_r($_SESSION);
//$result =  $repository->find_user_with_id(34);
//$user = $result['body']['user'];
////print_r($user);
//$username ="maxwell";
//$path = $user['url'];
//$arr  = preg_split("#/#", $path);
//print_r($arr);
//echo  $arr[0].'/'.$username.'/'.$arr[2];

// \Src\delete_all_session_variables();

//$upload_dir  = $_SERVER["DOCUMENT_ROOT"]."/public_html/uploads/";
//delete_directory($upload_dir."/todeleteretro");




