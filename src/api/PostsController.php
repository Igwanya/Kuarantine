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
            $post_id = filter_var($post_id, FILTER_VALIDATE_INT, [
                'options' => [
                    'default'   => 'all_posts',
                    'min_range' => 1
                ]
            ]);
            echo json_encode($repository->read_post($post_id));
        } else {
            echo json_encode($repository->read_all_posts());
        }
        break;
    case 'POST':
        /**
         * Perform insert ops
         */
        if ($uri[4] == 'insert')
        {
            // GET DATA FORM REQUEST
            $data = json_decode(file_get_contents("php://input"));
            $result = array(
                "message"  => "",
                "error"    => null
            );
            // CHECK IF RECEIVED DATA FROM THE REQUEST
            if(isset($data->title) && isset($data->body) && isset($data->author)){
                // CHECK DATA VALUE IS EMPTY OR NOT
                if(!empty($data->title) && !empty($data->body) && !empty($data->author)){
                    echo json_encode(
                        $repository->add_post(
                            htmlspecialchars(strip_tags($data->title)),
                            htmlspecialchars(strip_tags($data->body)),
                            htmlspecialchars(strip_tags( $data->author)))
                    );
                    break;
                } else {
                    header($_SERVER["SERVER_PROTOCOL"]."  404 not found.");
                    $result['message'] = "Empty fields detected. Please fill all the fields | title, body, author";
                    echo json_encode($result);
                    break;
                }
            } else {
                header($_SERVER["SERVER_PROTOCOL"]."  404 not found.");
                $result['message'] = "Please fill all the fields | title, body, author";
                echo json_encode($result);
                break;
            }
        }
        break;
    case 'PUT':
        /**
         * Update a post
         * todo: needs to rechecked again
         */
        $data = json_decode(file_get_contents("php://input"));
        echo json_encode($repository->update_post($data->id, $data->title, $data->body));
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        echo json_encode($repository->delete_post($data->id));
        break;
    default:
        echo json_encode($repository->read_all_posts());
}