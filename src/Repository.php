<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 07-Apr-20
 * Time: 1:03 PM
 */

namespace Src;

use Src\database\DatabaseConnection;
use Src\models\User;

require_once __DIR__ . '../../vendor/autoload.php';

class Repository
{
    private $db;

    /**
     * Repository constructor.
     */
    public function __construct()
    {
      $mysqli = new DatabaseConnection();
      $this->db = $mysqli->get_db_connection();
    }

    /**
     * Returns all the users in the users table
     * @return array $row[column_name]
     */
    public function find_all_users()
    {
        try{
            $stmt = $this->db->prepare("
            SELECT 
             id, username, email, first_name, last_name, is_admin, password_hash, created, last_updated
            FROM
              users 
            ");
            if (!($stmt))
            {
                trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error,
                    E_USER_ERROR);
            }
            if (!$stmt->execute()) {
                trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error,
                    E_CORE_ERROR);
            }
            $res = $stmt->get_result();
            $row = $res->fetch_all(\PDO::FETCH_ASSOC);
            return $row;
        }  catch (\PDOException $exception){
            exit($exception->getMessage());
        }
    }

    /**
     * Find a user with a specified id
     * @param $id
     * @return array $row[column_name]
     */
    public function find_user_with_id($id)
    {
        try{
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id LIKE ?");
            if (!($stmt))
            {
                trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error,
                    E_USER_ERROR);
            }
            if (!$stmt->bind_param('i', $id)){
                trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error,
                    E_ERROR);
            }
            if (!$stmt->execute()) {
                trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error,
                    E_CORE_ERROR);
            }
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            return $row;
        } catch (\PDOException $exception){
            exit($exception->getMessage());
        }
    }

    /**
     * @param $user User model object
     * @return int If the operation succeeds
     */
    public function add_user_to_db(&$user) : int
    {
//        TODO:: research more on how to auto generate the id with breaking shit
        try {
            /* Prepared statement, stage 1: prepare */
            if (!($stmt = $this->db->prepare("
            INSERT INTO users(
             id, username, email, first_name, last_name, is_admin, password_hash, created, last_updated
            ) VALUES (?,?,?,?,?,?,?,?,?)"))) {
                trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
            }
            /* Prepared statement, stage 2: bind and execute */
            if (!$stmt->bind_param("issssssss", $user->get_Id(),
                $user->get_username(), $user->get_email(), $user->get_first_name(), $user->get_last_name(),
                $user->is_Admin(), $user->get_password_hash(), $user->get_created(), $user->get_last_updated())) {
                trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            if (!$stmt->execute()) {
                trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            return $stmt->num_rows;
        } catch (\PDOException $exception) {
            trigger_error($exception->getMessage());
            exit($exception->getMessage());
        }
        return 0;
    }


    /**
     * @param $id user id
     * @param &$user User model object.
     * @return int If the operation succeeds
     * TODO:: needs more work and am bored right now ;)
     */
    public function update_user_to_db($id, &$user)
    {
        try {
            /* Prepared statement, stage 1: prepare */
            if (!($stmt = $this->db->prepare(
                "
            UPDATE users SET
            (
             username=:username, email=:email, first_name=:first_name, last_name=:last_name, 
             is_admin=:is_admin, password_hash=:password_hash, created=:created, last_updated=:last_updated
            )  WHERE id:=id"
            ))) {
                trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
            }
            /* Prepared statement, stage 2: bind and execute */
            if (!$stmt->execute()) {
                trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            return $stmt->num_rows;
        } catch (\PDOException $exception) {
            trigger_error($exception->getMessage());
            exit($exception->getMessage());
        }
        return 0;
    }
    
    /**
     * Delete from the database a user with a given email.
     * @param $user_id
     * @return int
     */
    public function delete_user_from_db($user_id)
    {
        try {
            /* Prepared statement, stage 1: prepare */
            if (!($stmt = $this->db->prepare("DELETE FROM users WHERE id LIKE ?"))) {
                trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
            }
            /* Prepared statement, stage 2: bind and execute */
            if (!$stmt->bind_param("s", $user_id)) {
                trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            if (!$stmt->execute()) {
                trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            return $stmt->num_rows;
        } catch (\PDOException $exception) {
            trigger_error($exception->getMessage());
            exit($exception->getMessage());
        }
        return 0;
    }

    /**
     * @param $headline
     * @param $content
     * @param $userID
     * @return array
     */
    public function add_post($headline, $content, $userID)
    {
        $result = array(
            "status"  => "",
            "body"    => array(),
            "error"   => ""
        );
        $insert_stmt = "INSERT INTO articles (headline, content, created, userID) VALUES (?,?,?,?)";
        /* Prepared statement, stage 1: prepare */
        if (!($stmt = $this->db->prepare($insert_stmt))) {
            trigger_error("Prepare failed: (" . $this->db->errno . ") "
                . $this->db->error);
        }
        $created = date("Y-m-d");
        /* Prepared statement, stage 2: bind and execute */
        if (!$stmt->bind_param("sssi", $headline, $content, $created, $userID)) {
            trigger_error("Binding parameters failed: (" . $stmt->errno . ") "
                . $stmt->error);
        }
        if (!$stmt->execute()) {
            trigger_error("Execute failed: (" . $stmt->errno . ") " .
                $stmt->error);
            $result["status"] = "Article not posted";
            $result["error"] =  $stmt->error;
        } else {
            $result["status"] = "Article inserted successfully";
//            TODO:: decide whether to return the posted article
            $result["body"] = null;
            $result["error"]   = null;
            return $result;
        }
        return $result;
    }

    /**
     * @param $articleID
     * @return array
     */
    public function read_post($articleID){
        $result = array(
            "status"   => "" ,
            "body"  => array(
                "articleID"         => 0,
                "headline"          => "",
                "content"           => "",
                "userID"            => "",
                "created"           => "",
                "lastUpdated"       => ""
            ),
            "error"    => ""
        );
        
        $sql =  "SELECT * FROM articles WHERE articleID LIKE ?";
        $stmt = $this->db->prepare($sql);
        $articleID = filter_var($articleID, FILTER_VALIDATE_INT);
        /* Prepared statement, stage 2: bind and execute */
        if (!$stmt->bind_param("i",$articleID)) {
            trigger_error("Binding parameters failed: ("
                . $stmt->errno . ") " . $stmt->error);
            $result['error'] =  $stmt->error;
        }
        if (!$stmt->execute()) {
            trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            $result['status']  = "Article read unsuccessful";
            $result["body"] = null;
            $result["error"] = $stmt->error;
        } else {
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            if ($row['articleID'] != null){
                $result["body"]['articleID']   =  $row['articleID'];
                $result["body"]['headline']    =  $row['headline'];
                $result["body"]['content']     =  $row['content'];
                $result["body"]['userID']      =  $row['userID'];
                $result["body"]['created']     =  $row['created'];
                $result["body"]['lastUpdated'] =  $row['lastUpdated'];
                $result['status']  = "Article read successfully";
                $result["error"]   = null;
                return $result;
            } else {
                // TODO:: Be careful,  call from api request ONLY!!!
                header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
                $result["body"] = null;
                $result["error"] = "No Article found";
            }
        }
        return $result;
    }

    /**
     * Read all the posts in the database
     * @return array
     */
     public function read_all_posts()
     {
         $result = array(
             "status"   => "" ,
             "body"  => array(
                 "articleID"         => 0,
                 "headline"          => "",
                 "content"           => "",
                 "userID"            => "",
                 "created"           => "",
                 "lastUpdated"       => ""
             ),
             "error"    => ""
         );
         $sql = "SELECT * FROM articles";
         $stmt = $this->db->prepare($sql);
         $stmt->execute();
         $res = $stmt->get_result();
         if ($res->num_rows > 0){
             $result['status'] = "Article read successful";
             while($row = $res->fetch_assoc()){
                 $post_data = [
                     'articleID'   => $row['articleID'],
                     'headline'    => $row['headline'],
                     'content'     => html_entity_decode($row['content']),
                     'userID'      => $row['userID'],
                     'created'     => $row['created'],
                     'lastUpdated' => $row['lastUpdated']
                 ];
                 array_push($result['body'], $post_data);
             }
         } else {
             $result['body'] = null;
             $result['error'] = "No Articles yet! ";
         }
         return $result;
     }


    /**
     * @param $articleID
     * @param $headline
     * @param $content
     * @param $userID
     * @return array
     */
   public function update_post($articleID, $headline, $content, $userID)
   {
       $sql = "UPDATE articles SET headline=?, content=?, userID=?, lastUpdated=? WHERE articleID=?";
       $result = array(
           "status" => "",
           "body" => array(),
           "error"   => ""
       );
       /* Prepared statement, stage 1: prepare */
       if (!($stmt = $this->db->prepare($sql))) {
           trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
       }
       /* Prepared statement, stage 2: bind and execute */
       // TODO:: Squash this bug !!!
       // TODO:: the date does not reflect in the table
       $lastUpdated = date("Y-m-d");
       if (!$stmt->bind_param("sssss", $headline,$content, $userID, $lastUpdated, $articleID)) {
           trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
       }
       if (!$stmt->execute()) {
           trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
           $result["status"] = "No article updated ...";
           $result['body']  = null;
           $result["error"] =  $stmt->error;
           return $result;
       } else{
           $result["status"] = "Article updated successfully.";
           $result["body"]   = $this->read_post($articleID)['body'];
           $result["error"]   = null;
       }
       return $result;
   }

    /**
     * TODO:: userID is foreign key to articles table   RESEARCH MORE!!
     * @param $articleID
     * @return array
     */
   public function delete_post($articleID)
   {
       $sql = "DELETE FROM articles WHERE articleID=?";
       $result = array(
           "status" => "",
           "body" => array(),
           "error"   => ""
       );
       /*epared statement, stage 1: prepare */
       if (!($stmt = $this->db->prepare($sql))) {
           trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
       }
       /* Prepared statement, stage 2: bind and execute */
       if (!$stmt->bind_param("i", $articleID)) {
           trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
       }
       if (!$stmt->execute()) {
           trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
           $result["body"]   = null;
           $result["status"] = "Article not deleted Invalid ID.";
           $result['error']  =  $stmt->error;
           return $result;
       }
       $result["status"] = "Article deleted successfully.";
       $result["body"]   = null;
       $result["error"]   = null;
       return $result;
   }

    /**
     * @param $app_version
     * @param $model
     * @param $user
     * @param $api_level
     * @param $screen_resolution
     * @param $screen_density
     * @return array
     */
   public function insert_app_data($app_version, $model, $user,
                                   $api_level, $screen_resolution, $screen_density) {
       $result = array(
           "status" => "" ,
           "body" => "",
           "error"   => ""
       );
       $insert_stmt = "INSERT INTO app (app_version, model, user, api_level,
                 screen_resolution, screen_density) VALUES (?,?,?,?,?,?)";
       /* Prepared statement, stage 1: prepare */
       if (!($stmt = $this->db->prepare($insert_stmt))) {
           trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
       }
       /* Prepared statement, stage 2: bind and execute */
       if (!$stmt->bind_param("ssssss", $app_version, $model, $user,
           $api_level, $screen_resolution,$screen_density)) {
           trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
       }
       if (!$stmt->execute()) {
           trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
           $result["body"] = null;
           $result["error"] = $stmt->error;
       } else {
           $result["status"] = "Data inserted successfully";
           $result["body"] = null;
           $result["error"]   = null;
           return $result;
       }
       return $result;
   }

    /**
     * @return array
     */
   public function read_app_data(){
       $result = array(
           "status" => "" ,
           "body" => array(),
           "error"   => ""
       );
       $sql = "SELECT * FROM app";
       $stmt = $this->db->prepare($sql);
       $stmt->execute();
       $res = $stmt->get_result();
       if ($res->num_rows > 0){
           $result['status'] = "Reading app data ...";
           $result['error'] = null;
           while($row = $res->fetch_assoc()){
               $data = [
                   "appID"             => $row['appID'],
	               "app_version"       => $row['app_version'],
                   "model"             => $row['model'],
                   "user"              => $row['user'],
                   "api_level"         => $row['api_level'],
                   "screen_resolution" => $row['screen_resolution'],
                   "screen_density"    => $row['screen_density']
               ];
               array_push($result['body'], $data);
           }
       } else {
           $result['body'] = null;
           $result['error'] = "Application data not present ... ";
       }
       return $result;
   }

   public function read_app_data_by_id($appID){
       $result = array(
           "status" => "" ,
           "body" => array(),
           "error"   => ""
       );
       $sql = "SELECT * FROM app WHERE appID LIKE ?";
       $stmt = $this->db->prepare($sql);
       /* Prepared statement, stage 2: bind and execute */
       if (!$stmt->bind_param("i",$appID)) {
           trigger_error("Binding parameters failed: ("
               . $stmt->errno . ") " . $stmt->error);
           $result['error'] =  $stmt->error;
       }
       $stmt->execute();
       $res = $stmt->get_result();
       if ($res->num_rows > 0){
           $result['status'] = "Reading app data ...";
           $result['error'] = null;
           $row = $res->fetch_assoc();
           $result['body'] = [
               "appID"             => $row['appID'],
               "app_version"       => $row['app_version'],
               "model"             => $row['model'],
               "user"              => $row['user'],
               "api_level"         => $row['api_level'],
               "screen_resolution" => $row['screen_resolution'],
               "screen_density"    => $row['screen_density']
           ];
       } else {
           $result['body'] = null;
           $result['status'] = "Application data not present ... ";
           $result['error']  = "Invalid Application id";
       }
       return $result;
   }

    /**
     * @param $appID
     * @param $app_version
     * @param $model
     * @param $user
     * @param $api_level
     * @param $screen_resolution
     * @param $screen_density
     * @return array
     */
   public function update_app_data($appID, $app_version, $model, $user,
                                   $api_level, $screen_resolution, $screen_density){
       $sql = "UPDATE app SET app_version=?, model=?, user=?,
                  api_level=?, screen_resolution=?, screen_density=? WHERE appID=?";
       $result = array(
           "status" => "",
           "body" => array(),
           "error"   => ""
       );
       /* Prepared statement, stage 1: prepare */
       if (!($stmt = $this->db->prepare($sql))) {
           trigger_error("Prepare failed: (" . $this->db->errno . ") " .
               $this->db->error);
       }
       /* Prepared statement, stage 2: bind and execute */
       if (!$stmt->bind_param("ssssssi",$app_version, $model, $user,
           $api_level, $screen_resolution, $screen_density,$appID)) {
           trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
       }
       if (!$stmt->execute()) {
           trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
           $result["body"] = null;
           $result["status"] = "App data not updated. Invalid ID.";
           $result['error']  = $stmt->error;
           return $result;
       } else {
           $result["status"] = "App data updated successfully.";
           $result["body"]   = $this->read_app_data_by_id($appID)['body'];
           $result['error']  = null;
       }

       return $result;
   }

    /**
     * @param $appID
     * @return array
     */
   public function delete_app_data($appID)
   {
       $sql = "DELETE FROM app WHERE appID=?";
       $result = array(
           "status"  => "",
           "body" => "",
           "error"   => ""
       );
       /* Prepared statement, stage 1: prepare */
       if (!($stmt = $this->db->prepare($sql))) {
           trigger_error("Prepare failed: (" .
               $this->db->errno . ") " . $this->db->error);
       }
       /* Prepared statement, stage 2: bind and execute */
       if (!$stmt->bind_param("i", $appID)) {
           trigger_error("Binding parameters failed: (" .
               $stmt->errno . ") " . $stmt->error);
       }
       if (!$stmt->execute()) {
           trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
           $result['status'] = "App data not deleted Invalid ID.";
           $result["body"] = null;
           $result["error"] = $stmt->error;
           return $result;
       }
       $result["status"] = "App data deleted successfully.";
       $result['body']   = null;
       $result["error"]   = null;
       return $result;

   }
   
}