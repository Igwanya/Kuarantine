<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 14-Apr-20
 * Time: 11:55 AM
 */

namespace Src\api;

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

$url =  $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$content = file_get_contents($url);
echo $content;
echo "\n\n\n\n\n";
// Initialise the repository
$repo = new Repository();


/**
 * $id is the [4] param
 */
$userId = null;
if (isset($uri[4]))
{
    if ($uri[4] == 'user'){   // user/$id
        $userId = (int) $uri[5];
        $userId = filter_var($userId, FILTER_VALIDATE_INT);
    }

}

print_r($uri);

function not_found_response()
{
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
    $response = array(
        'status_code_header'  => $_SERVER["SERVER_PROTOCOL"]." 404 Not Found",
        'body'                =>  null
    );
    return json_encode($response);
}

function unprocessed_entity_response()
{
    $response['status_code_header'] = $_SERVER["SERVER_PROTOCOL"].' 422 Unprocessable Entity';
    $response['body'] = json_encode([
        'error' => 'Invalid input'
    ]);
    return $response;
}


$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($requestMethod) {
    case 'GET':
        if ($userId) {
            if ($repo->find_user_with_id($userId)) {
                header($_SERVER["SERVER_PROTOCOL"]."  200 OK");
                $response = array(
                    'status_code_header' =>  $_SERVER["SERVER_PROTOCOL"]." 200 OK ",
                    'body'               =>   $repo->find_user_with_id($userId)
                );
              echo json_encode($response);
            } else {
                echo not_found_response();
            }

        } else {
            if ($uri[4] == 'users'){
                header($_SERVER["SERVER_PROTOCOL"]."  200 OK");
                $response = array(
                    'status_code_header' =>  $_SERVER["SERVER_PROTOCOL"]." 200 OK ",
                    'body'               =>   $repo->find_all_users()
                );
                echo json_encode($response);
            } else {
                echo not_found_response();
            }
        }
        break;
    case 'POST':
        if ($uri[4] == 'create') {
            $json = $uri[5];
            echo $json;
        }
            echo file_get_contents('php://input');
//        $input = (array)json_decode(file_get_contents('php://input'), TRUE);
//        print_r($input);
        echo "\n\n\n\n\n";
//        $input['created'] = date('y/m/d');
//        $input['last_updated'] = date('y/m/d');
//        $user = new User(
//            $input['id'],
//            $input['username'],
//            $input['email'],
//            $input['first_name'],
//            $input['last_name'],
//            $input['is_admin'],
//            $input['created'],
//            $input['last_updated']
//        );
//        $user->set_password_hash($input['password']);
//        if ($repo->add_user_to_db($user)) {
//            $response['status_code_header'] = $_SERVER["SERVER_PROTOCOL"] . ' 201 CREATED';
//            $response['body'] = null;
//            return $response;
//        } else {
//            $response['status_code_header'] = $_SERVER["SERVER_PROTOCOL"] . ' 204 NO_CONTENT';
//            $response['body'] = null;
//            return $response;
//        }
        break;
    default:
        $response = notFoundResponse();
        break;
}




//echo "Creating a new user: \n\n\n\n ";
//echo "</br></br>";
//$cr = date('y/m/d');
//$ls =  date('y/m/d');
//$user = new User(
//    2,
//    'retro',
//    'retro@gmail.com',
//    'Marcus',
//    'Last',
//    false,
//    $cr,
//    $ls
//);
//$user->set_password_hash("admin1234");
//$results =   $repo->delete_user_from_db($user);
//
//echo "Result: \t\t\t\n\n\n\n    ";
//var_dump($results);