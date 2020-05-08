<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 03-May-20
 * Time: 6:29 PM
 */
namespace Src;

require_once __DIR__ . '../../vendor/autoload.php';
 session_start();
$url = "D:/PHP/www.reelgood.com/public_html/uploads/weed/LFIzPJ4.jpg";
$var = preg_split("#/#", $url);
print_r($var);
$result = $var[3].'/'.$var[4].'/'.$var[5].'/'.$var[6];
echo "<br/><br/>";
echo $result;