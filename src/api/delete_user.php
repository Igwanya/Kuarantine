<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 02-Jun-20
 * Time: 2:56 PM
 */
require_once __DIR__ . '../../../vendor/autoload.php';
include_once "../../public/utils.php";
$request_method = $_SERVER['REQUEST_METHOD'];
$repository = new \Src\Repository();
if ($request_method == 'POST') {
    $result = array(
        "status" => "",
        "path"  => "",
        "body"   => array(),
        "error"  => array(
            "query_error" => null,
        )
    );
    $url_components = parse_url(\Src\get_server_url_domain_name().$_SERVER['REQUEST_URI']);
    parse_str($url_components['query'], $params);
    print_r($params);
    $db_query = array();
    if (isset($params['id']) && !empty($params['id'])){
        $id = filter_var($params['id'], FILTER_VALIDATE_INT);
        $db_query = $repository->find_user_with_id($id);
        $username = $db_query['body']['user']['username'];
        $upload_dir = $_SERVER["DOCUMENT_ROOT"]."/public/uploads/".$username;
        \Src\delete_directory($upload_dir);
        $db_query = $repository->delete_user_from_db($id);
        $result['status'] = $db_query['status'];
        $result['body'] = $db_query['body'];
        http_response_code(200);
        echo json_encode($result);
    }
     else {
        $result['status']  = "Delete unsuccessful";
        $result['error']['query_error']  = $db_query['error'];
     }


}