<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 12-May-20
 * Time: 5:43 PM
 */

namespace Src\api;

use Src\Repository;

require_once __DIR__ . '../../../vendor/autoload.php';
session_start();

ini_set('display_errors', true);
ini_set('html_errors', true);
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Headers: access");
//header("Access-Control-Allow-Methods: GET,POST");
//header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//echo $_SERVER['DOCUMENT_ROOT'].'/public_html/redirects.php';
echo $_SERVER['SERVER_NAME'];
echo $_SERVER['HTTP_HOST'];


