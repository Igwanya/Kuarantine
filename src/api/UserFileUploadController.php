<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 02-Jun-20
 * Time: 1:11 PM
 */
require_once __DIR__ . '../../../vendor/autoload.php';
include_once "../../public/utils.php";

header("Content-Type: application/json; charset=UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . '/public/redirects.php';
$request_method = $_SERVER['REQUEST_METHOD'];
$repository = new \Src\Repository();
switch ($request_method) {
    case 'POST':
        $result = array(
            "status" => "",
            "path"  => "",
            "body"   => array(),
            "error"  => array(
                "invalid_file_format" => null,
                "query_error" => null,
            )
        );
        $url_components = parse_url($_SERVER['REQUEST_URI']);
        $result['path']  = \Src\get_server_url_domain_name().$url_components['path'];
        $id = filter_input(INPUT_POST, 'id');
        $db_query = array();
        if (isset($id) && !empty($id)){
            $id = filter_var($id, FILTER_VALIDATE_INT);
            $db_query = $repository->find_user_with_id($id);
            $result['error']['query_error'] = $db_query['error']['query_error'];
            if (!empty($result['error']['query_error'])) {
                $result['status'] = "Upload unsuccessful";
                http_response_code(404);
                echo json_encode($result);
                exit(0);
            } else {
                $result['error']['query_error'] = null;
            }
        }

        $upload_dir = null;
        $username   = null;
        if (isset($db_query['body']['user']['username'])) {
            $username = $db_query['body']['user']['username'];
            $upload_dir = $_SERVER["DOCUMENT_ROOT"]."/public/uploads/".$username."/";
        }

        if (is_uploaded_file($_FILES['user_file']['tmp_name'])) {
            // DO NOT TRUST $_FILES['article_file']['mime'] VALUE !!
            // Check MIME Type by yourself.
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search(
                    $finfo->file($_FILES['user_file']['tmp_name']),
                    array(
                        'jpg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                    ),
                    true
                )) {
                $result['status'] = "Upload unsuccessful";
                $result['error']['invalid_file_format'] = "upload jpg, png, gif formats only";
                http_response_code(404);
                echo json_encode($result);
                exit(0);
            } // file ext validation end

            $file_name =  sha1_file($_FILES['user_file']['tmp_name']).'.'.$ext;
            $upload_path = $upload_dir.basename($file_name);
            $upload_path_username =  $upload_dir.basename($file_name);
            unlink($_SERVER['DOCUMENT_ROOT'].$db_query['body']['user']['url']);
            if (move_uploaded_file($_FILES['user_file']['tmp_name'], $upload_path_username)){
                $path = scandir($upload_dir);
                $updated_user_url_path = "/public/uploads/".$username."/".$path[2];
                $user = $db_query['body']['user'];
                $result['status'] =  $repository->update_user_to_db($user['id'], $updated_user_url_path, $user['username'], $user['email'], $user['first_name'], $user['last_name'], $user['full_name'], $user['bio'])['status'];
                $db_query = $repository->find_user_with_id($user['id']);
                $result['body'] = $db_query['body'];
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(404);
            }
            
            
        }

}


