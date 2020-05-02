<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 01-May-20
 * Time: 4:17 PM
 */

namespace Src\api;

use Src\Repository;

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
        $repository = new Repository();
        $id = filter_input(INPUT_GET, 'ID');
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!empty($id)){
            echo json_encode($repository->read_app_data_by_id($id));
        }else {
            echo json_encode($repository->read_app_data());
        }

        break;
    case 'POST':
        $repository = new Repository();
        $data = json_decode(file_get_contents("php://input"));
        if (
            isset($data->app_version) && isset($data->model) && isset($data->user)
            && isset($data->api_level) && isset($data->screen_resolution) && isset($data->screen_density)
        ){
            echo json_encode($repository->insert_app_data($data->app_version, $data->model,
                $data->user, $data->api_level, $data->screen_resolution, $data->screen_density));

        } else{
            http_response_code(404);
        }
        break;
    case 'PUT':
        $repository = new Repository();
        $data = json_decode(file_get_contents("php://input"));
        if (
            isset($data->appID) && isset($data->app_version) && isset($data->model) && isset($data->user)
            && isset($data->api_level) && isset($data->screen_resolution) && isset($data->screen_density)
        ){
            echo json_encode($repository->update_app_data($data->appID,$data->app_version, $data->model,
                $data->user, $data->api_level, $data->screen_resolution, $data->screen_density));
        } else{
            http_response_code(404);
        }
        break;
    case 'DELETE':
        $repository = new Repository();
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->appID)){
            echo json_encode($repository->delete_app_data($data->appID));
        }
        break;
    default:
        echo json_encode(array("status" => "Welcome to the Kuarantine app"));
}