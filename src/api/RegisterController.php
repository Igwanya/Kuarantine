<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 01-May-20
 * Time: 1:05 PM
 */

namespace Src\api;

use Src\auth\Register;
use Src\models\User;
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
if ($requestMethod == 'POST')
{
    $result = array(
        "status"    => "",
        "body"      => array(),
        "error"     => array()
    );
    // GET DATA FORM REQUEST
    $data = json_decode(file_get_contents("php://input"));

    if (empty($data)){
        $result['status'] = "Empty fields detected,  expected | username | email | full_name | password ";
        http_response_code(404);
        echo json_encode($result);
        exit(0);
    }

    if (!empty($data) && isset($data->username) && isset($data->email) &&
        isset($data->full_name) && isset($data->password)) {
        $repository = new Repository();
        $register = new Register();
        $register->setApiRegistrationRequest(true);
        $register->setUsername($data->username);
        $register->setEmail($data->email);
        $register->set_full_name($data->full_name);
        $register->setPassword($data->password);
        $result['error']['username_error'] = $register->perform_username_check();
        $result['error']['email_error']    = $register->perform_email_check();
        if (!empty($result['error']['username_error']) ||
            !empty($result['error']['email_error']) ){
            http_response_code(404);
            $result['status'] = "Sign up unsuccessful";
            echo json_encode($result);
            exit(0); 
        } else {
            http_response_code(201);
            $register->register_user();
            $user = $repository->find_user_with_email($data->email)['body']['user'];
            $result['body']   = array("user_id" => $user['id']);
            $result['status'] = "Sign up successful";
            echo json_encode($result);
//            exit(0);
        }

    }

}