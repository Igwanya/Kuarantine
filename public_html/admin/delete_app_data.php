<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 21-May-20
 * Time: 9:35 AM
 */

namespace Src;

session_start();
require_once __DIR__ . '../../../vendor/autoload.php';

$repository = new Repository();
$request_method = $_SERVER['REQUEST_METHOD'];
switch ($request_method) {
    case 'GET':
        include '../partials/navbar.php';
        $id = filter_input(INPUT_GET, "ID");
        $result = $repository->delete_app_data($id);
        if (empty($result['error'])) {
            echo $result['status'];
        }
        break;


}
