<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 06-Apr-20
 * Time: 5:22 AM
 */

namespace Src\database;


class DatabaseConnection
{
    private $APP_DB_NAME = 'kuarantine';   // remember to change the connection properties in mysqli.
    
    public $APP_DB_USERS_TABLE = 'users';

    /**
     * DatabaseConnection constructor.
     */
    public function __construct()
    {

    }


    /**
     * Get a handle on the db and returns the object.
     * @return \mysqli
     */
    public function get_db_connection()
    {
        $mysqli = new \mysqli('127.0.0.1', "root", "", $this->APP_DB_NAME, 3306);
        if ($mysqli->connect_errno) {
            trigger_error("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error,
                E_USER_ERROR);
        }
        return  $mysqli;
    }
}