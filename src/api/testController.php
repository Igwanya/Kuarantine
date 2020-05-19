<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 12-May-20
 * Time: 5:43 PM
 */

namespace Src\api;

use Src\Repository;

require_once __DIR__ . '../../../vendor/autoload.php';
ini_set('display_errors', true);
ini_set('html_errors', true);
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Headers: access");
//header("Access-Control-Allow-Methods: GET,POST");
//header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$repo = new Repository();
//print_r($repo->read_all_categories()) ;
$result =  $repo->load_all_products()['body']['products'];
foreach ($result as $arr) {
    print_r($arr['url']);
}

