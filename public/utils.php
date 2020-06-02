<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 22-May-20
 * Time: 12:12 PM
 */
namespace Src;

require_once __DIR__ . '../../vendor/autoload.php';


function delete_directory($dir_name) {
    if (is_dir($dir_name)) {
        $dir_handle = opendir($dir_name);
        if (!$dir_handle)
            return false;
        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dir_name."/".$file)) {
                    unlink($dir_name."/".$file);
                } else {
                    delete_directory($dir_name."/".$file);
                }
            }
        }
        closedir($dir_handle);
        rmdir($dir_name);
    }

    return true;
}

function delete_all_session_variables(){
    unset($_SESSION['is_authenticated']);
    unset($_SESSION['is_admin']);
    unset($_SESSION['login_ID']);
}

function get_server_url_domain_name()
{
    $link = "http";
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $link = "https";
    } else {
        $link = "http";
    }
// append the common URL characters
    $link .= "://";
// append the host(domain name, ip)  to the URL
    $link .= $_SERVER['HTTP_HOST'];
    return $link;
}