<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 14-Apr-20
 * Time: 11:55 AM
 */

namespace Src\api;

use Src\auth\Login;
use Src\auth\Register;
use Src\models\User;
use Src\Repository;

require_once __DIR__ . '../../../vendor/autoload.php';
include_once "../../public/utils.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$repository = new Repository();
$result = array(
    "status" => "",
    "path"  => "",
    "body"  => array(),
    "error" => array()
);
switch ($requestMethod) {
    case 'GET':
        $url_components = parse_url($_SERVER['REQUEST_URI']);
        $result['path']  = \Src\get_server_url_domain_name().$url_components['path'];
        parse_str($url_components['query'], $params);
        $db_query = array();
        if (isset($params['id']) && !empty($params['id'])){
            $id = filter_var($params['id'], FILTER_VALIDATE_INT);
            $db_query = $repository->find_user_with_id($id);
            $result['status'] = $db_query['status'];
            $result['body'] = $db_query['body'];
            $result['error'] = $db_query['error'];
            echo json_encode($result);
        } else {
            $db_query = $repository->find_all_users();
            $result['body']   = $db_query['body'];
            $result['error']  = $db_query['error'];
            echo json_encode($result);
        }



//
//        if (isset($DI) && !empty(filter_input(INPUT_GET, 'ID'))) {
//            $id = filter_input(INPUT_GET, 'ID');
//            $id = filter_var($id, FILTER_VALIDATE_INT);
//            echo json_encode($repository->find_user_with_id($id));
//            if (!empty($id)){
//
//            } else {
//                echo json_encode();
//            }
//        }

        break;
    case 'POST':
        $result = array(
            "status" => "",
            "body"   => array(),
            "error"  => array(
                "username_error" => "",
                "email_error"    => ""
            )
        );
        // GET DATA FORM REQUEST
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data)) {
            $register = new Register();
            $register->setUsername($data->username);
            $register->setEmail($data->email);
            $result["error"]["username_error"] = $register->perform_username_check();
            $result["error"]["email_error"]  = $register->perform_email_check();
            $register->setPassword($data->password);
            $result['error']  = array();
            if ( empty($result["error"]["username_error"])
                && empty($result["error"]["email_error"])){
               $result = $register->register_user();
            }
        }
        echo json_encode($result);
        break;
    default:

        break;
}
