<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 22-May-20
 * Time: 12:12 PM
 */
namespace Src;

use Src\database\DatabaseConnection;

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


/**
 * Retrieve the domain name of the server
 * @return string The server url
 */
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

/**
 * Checks the username if it already exists.
 * @param $username
 * @return string
 */
function validate_username ($username)
{
    $mysql  = new DatabaseConnection();
    $conn = $mysql->get_db_connection();
    $stmt =  $conn->prepare("SELECT username FROM users WHERE username LIKE ?");
    if (!($stmt)){
        trigger_error("Prepare failed: (" . $conn->errno . ") " .
            $conn->error, E_USER_ERROR);
    }
    if (!$stmt->bind_param('s', $username)){
        trigger_error("Binding parameters failed: (" . $stmt->errno . ") " .
            $stmt->error, E_ERROR);
    }
    if (!$stmt->execute()) {
        trigger_error("Execute failed: (" . $stmt->errno . ") " .
            $stmt->error, E_CORE_ERROR);
    }
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    if ($row['username'] != null )
    {
        return "Username already exists.";
    }
    return null;
}

/**
 * Validates the email if found
 * IF the email does not exist returns empty
 * @param $email
 * @return string The error if it exists
 */
function validate_email($email)
{
    $mysql  = new DatabaseConnection();
    $conn = $mysql->get_db_connection();
    $result = null;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address !";
    }
    $stmt =  $conn->prepare("SELECT email FROM users WHERE email LIKE ?");
    if (!($stmt)){
        trigger_error("Prepare failed: (" . $conn->errno . ") " .
            $this->conn->error, E_USER_ERROR);
    }
    if (!$stmt->bind_param('s', $email)){
        trigger_error("Binding parameters failed: (" . $stmt->errno . ") " .
            $stmt->error, E_ERROR);
    }
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        if ($row['email'] != null ) {
            $result = "Email already exists.";
        }
    }else {
        trigger_error("Execute failed: (" . $stmt->errno . ") " .$stmt->error, E_CORE_ERROR);
    }

    return $result;
}

