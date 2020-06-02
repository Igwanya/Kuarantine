<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 14-Apr-20
 * Time: 11:55 AM
 */

namespace Src\api;

use http\Exception\RuntimeException;
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
            http_response_code(200);
            echo json_encode($result);
        } else {
            $db_query = $repository->find_all_users();
            $result['body']   = $db_query['body'];
            $result['error']  = $db_query['error'];
            http_response_code(200);
            echo json_encode($result);
        }
        if (!empty($result['error'])) {
            http_response_code(404);
        }
        break;
    case 'POST':
        $result = array(
            "status" => "",
            "path"  => "",
            "body"   => array(),
            "error"  => array(
                "query_error" => null,
            )
        );
        $result['path']  = \Src\get_server_url_domain_name().$_SERVER['REQUEST_URI'];
        // GET DATA FORM REQUEST
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->id) && isset($data->username) && isset($data->url) && isset($data->email) && isset($data->first_name)
        && isset($data->last_name) && isset($data->full_name) && isset($data->bio)) {
            $db_query = $repository->find_user_with_id($data->id);
             if ($db_query['body']['user'] != null)  {
                 $result['status'] = $repository->update_user_to_db($data->id, $data->url, $data->username, $data->email, $data->first_name, $data->last_name, $data->full_name, $data->bio)['status'];
                 $result['body'] = $repository->find_user_with_id($data->id)['body'];
                 http_response_code(200);
                 echo json_encode($result);
             } else {
                 $result['status']  = "Update failed";
                 $result['error']  = $db_query['error'];
                 http_response_code(404);
                 echo json_encode($result);
                 exit(0);
             }
        } else {
            $result['status'] = "Expected fields | id | username | url | email | first_name | last_name | full_name | bio ";
            http_response_code(404);
            echo json_encode($result);
        }
        break;
}
