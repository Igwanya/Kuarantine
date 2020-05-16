<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 30-Apr-20
 * Time: 8:37 PM
 */

namespace Src\api;

use Src\auth\Login;
use Src\models\User;

require_once __DIR__ . '../../../vendor/autoload.php';
ini_set('display_errors', true);
ini_set('html_errors', true);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Content-Type: application/x-www-form-urlencoded");
header("Accept: multipart/form-data");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == 'POST')
{
    $result = array(
        "status"    => "",
        "body"      => array(
            "user" => array()
        ),
        "error"     => array(
            "username_or_email_error" => "",
            "password_error"          => ""
        )
    );
    // GET DATA FORM REQUEST
    $data = json_decode(file_get_contents("php://input"));
    $login = new Login();
    if (!empty($data)){
        $login->setUsernameOrEmail($data->username_or_email);
        $login->setPassword($data->password);
        $result['error']['username_or_email_error'] = $login->perform_username_or_email_check();
        $result['error']['password_error']          = $login->perform_password_check();
    }
    if (empty( $result['error']['username_or_email_error']) &&
        empty($result['error']['password_error'] )){
        http_response_code(200);
        $result['status'] = 'Login successful';
        $result['body']['user']  = $login->getLoginFrmInputs();
        $result['error']   = array();
    } else {
        http_response_code(404);
        $result['status'] = 'Login unsuccessful';
//        exit("Login details has errors");
    }
    echo json_encode($result);
} else{
    http_response_code(403);
    exit($_SERVER['REQUEST_METHOD']." Request failed: Api calls accepted only");
}
