<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 12-May-20
 * Time: 5:43 PM
 */

namespace Src\api;

require_once __DIR__ . '../../../vendor/autoload.php';
ini_set('display_errors', true);
ini_set('html_errors', true);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];
switch ($requestMethod){
    case 'GET':
        $data = json_decode(file_get_contents("php://input"));
        print_r($data);
        break;
    case 'POST':;
        $data = json_decode(file_get_contents("php://input"));
        print_r($data);
}