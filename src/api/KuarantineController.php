<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 01-May-20
 * Time: 4:17 PM
 */

namespace Src\api;

require_once __DIR__ . '../../../vendor/autoload.php';

session_start();

ini_set('display_errors', true);
ini_set('html_errors', true);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];
switch ($requestMethod){
    case 'GET':
        $result = array(
            "status" => "Welcome to the Kuarantine app",
            "body"   => array(
                "app" => array(
                    "os_version"           => "4.1",
                    "terms_and_conditions" => false ,
                    "build_version"        => "0.0.1"
                )
            ),
            "error"  => array()
        );
        echo json_encode($result);
        break;
    default:
        echo json_encode(array("status" => "Welcome to the Kuarantine app"));
}