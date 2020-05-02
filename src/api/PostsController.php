<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 29-Apr-20
 * Time: 11:58 PM
 *
 * Description: A script to accept posts made.
 *
 * METHOD: POST
 * url:  PostController/insert
 *
 * METHOD : GET
 * The id is all caps.
 * url: PostController?ID={ID}
 *
 * METHOD: PUT
 * The update to the post.
 * url: PostController/update?ID={id}
 */

namespace Src\api;

use Src\database\DatabaseConnection;
use Src\Repository;

ini_set('display_errors', false);
ini_set('html_errors', false);
require_once __DIR__ . '../../../vendor/autoload.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);
$repository = new Repository();
$requestMethod = $_SERVER["REQUEST_METHOD"];
switch ($requestMethod)
{
    case 'GET':
        $post_id = filter_input(INPUT_GET, 'ID' );
        if (isset($post_id))
        {
            $post_id = filter_var($post_id, FILTER_VALIDATE_INT);
//            TODO:: read posts by author
            echo json_encode($repository->read_post($post_id));
        } else {
            echo json_encode($repository->read_all_posts());
        }
        break;
    case 'POST':
        /**
         * Perform insert ops
         */
        $result = array(
            "status"  => "",
            "body"    => array(),
            "error"   => ""
        );
        // GET DATA FORM REQUEST
        $data = json_decode(file_get_contents("php://input"));
        // CHECK IF RECEIVED DATA FROM THE REQUEST
        if(isset($data->headline) && isset($data->content) && isset($data->userID)){
            // CHECK DATA VALUE IS EMPTY OR NOT
            if(!empty($data->headline) && !empty($data->content)
                && !empty($data->userID)){
                echo json_encode(
                    $repository->add_post(
                        htmlspecialchars(strip_tags($data->headline)),
                        htmlspecialchars(strip_tags($data->content)),
                        htmlspecialchars(strip_tags( $data->userID)))
                );
                break;
            } else {
                header($_SERVER["SERVER_PROTOCOL"]."  404 not found.");
                $result['status'] = "Empty fields detected. Please fill all the fields | headline, content, userID";
                echo json_encode($result);
                break;
            }
        } else {
            header($_SERVER["SERVER_PROTOCOL"]."  404 not found.");
            $result['status'] = "Please fill all the fields | headline, content, userID";
            echo json_encode($result);
            break;
        }
        break;
    case 'PUT':
        /**
         * Update an Article
         */
        $data = json_decode(file_get_contents("php://input"));
        echo json_encode($repository->update_post($data->articleID, $data->headline, $data->content, $data->userID));
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        echo json_encode($repository->delete_post($data->id));
        break;
    default:
        echo json_encode($repository->read_all_posts());
}