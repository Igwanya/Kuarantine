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
        "error"     => array(
            "username_error"       => "",
            "email_error"          => ""
        )
    );
    // GET DATA FORM REQUEST
    $data = json_decode(file_get_contents("php://input"));
    $repository = new Repository();
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
      $user = new User(null, $data->username, $data->email,
          "", "", false, date("Y-m-d"), date("Y-m-d"));
      $user->set_password_hash($data->password);
      $register =   $repository->add_user_to_db($user);
      $result['status'] = $register['status'];
      $result['error']  = $register['error'];
    }

    if (empty($result['error']['username_error'])
        && empty($result['error']['email_error'])) {
        $result['status'] = "Sign up successful";
        $result['body']   = null;
        $result['error']  = null;
    }

    echo json_encode($result);
}