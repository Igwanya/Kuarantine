<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 22-May-20
 * Time: 11:37 AM
 */
namespace Src;

require_once __DIR__ . '../../vendor/autoload.php';


/**
 * Handle the form and log in the user
 */
function to_profile_page()
{
    /* Redirect to a different page in the current directory that was requested */
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'auth/profile.php';
    header("Location: http://$host$uri/$extra");
    exit;
}

/**
 * Redirect the user to the home page.
 */
function to_index_page()
{
    /* Redirect to a different page in the current directory that was requested */
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'index.php';
    header("Location: http://$host$uri/$extra");
    exit;
}
