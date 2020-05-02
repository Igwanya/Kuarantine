<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 01-May-20
 * Time: 1:05 PM
 */

namespace Src\api;

use Src\auth\Register;

require_once __DIR__ . '../../../vendor/autoload.php';

session_start();

ini_set('display_errors', true);
ini_set('html_errors', true);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type,
         Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == 'POST')
{
    $result = array(
        "status"    => "",
        "body"      => array(),
        "error"     => array(
            "username_error"       => "",
            "email_error"          => ""
        )
    );
    // GET DATA FORM REQUEST
    $data = json_decode(file_get_contents("php://input"));
    $register = new Register();
    if (!empty($data)){
        $register->setUsername($data->username);
        $register->setEmail($data->email);
        $register->setPassword($data->password);
        $result['error']['username_error'] = $register->perform_username_check();
        $result['error']['email_error']   = $register->perform_email_check();

    }else{
       $result['status'] = "Empty fields detected ";
    }

    if (empty($result['error']['username_error'])
        && empty($result['error']['email_error']))  {
         $register->register();
         $result['status']  = "Sign up successful";
         $result['body']    = $register->getRegisterCorrectInputs();
         $result['error'] = null;
    }

    echo json_encode($result);
}