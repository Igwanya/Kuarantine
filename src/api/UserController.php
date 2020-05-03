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

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$requestMethod = $_SERVER["REQUEST_METHOD"];
$repository = new Repository();
switch ($requestMethod) {
    case 'GET':
        $id = filter_input(INPUT_GET, 'ID');
        $id = filter_var($id, FILTER_VALIDATE_INT);
         if (!empty($id)){
             echo json_encode($repository->find_user_with_id($id));
         } else {
             echo json_encode($repository->find_all_users());
         }
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
            $user = new User(
                null,
                $data->username,
                $data->email,
                $data->first_name,
                $data->last_name,
                false,
                date("Y-m-d"),
                date("Y-m-d")
            );
            $user->set_password_hash($data->password);
            $register = new Register();
            $register->setUsername($user->get_username());
            $register->setEmail($user->get_email());
            $result["error"]["username_error"] = $register->perform_username_check();
            $result["error"]["email_error"]  = $register->perform_email_check();
            if ( empty($result["error"]["username_error"])
                && empty($result["error"]["email_error"])){
              $result["body"] = $repository->add_user_to_db($user)["body"];
            }
        }
        echo json_encode($result);
        break;
    default:

        break;
}
