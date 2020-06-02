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
date_default_timezone_set('Africa/Nairobi');

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
        $result = array(
            "status"  => "",
            "body"    => array(
                "user" => array(),
                "count"  => 0
            ),
            "error"   => array()
        );
        $stmt = $this->db->prepare("SELECT * FROM users");
        if (!($stmt))
        {
            trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error,
                E_USER_ERROR);
        }
        if (!$stmt->execute()) {
            trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error,
                E_CORE_ERROR);
            $result["error"] = $stmt->error;
        }
        $res = $stmt->get_result();
        if ($res->num_rows > 0){
            $result["status"] = $res->num_rows." users found";
            $result['body']['count'] =  $res->num_rows;
            while($row = $res->fetch_assoc()){
              $user = array(
                  "id"            => $row["id"],
                  "url"           => $row["url"],
                  "username"      => $row["username"],
                  "email"         => $row["email"],
                  "first_name"    => $row["firstName"],
                  "last_name"     => $row["lastName"],
                  "full_name"     => $row["fullName"],
                  "bio"           => $row["bio"],
                  "is_admin"      => $row["isAdmin"],
                  "password"      => $row["password"],
                  "created"       => $row["created"],
                  "last_updated"  => $row["lastUpdated"],
              );
              array_push($result["body"]["user"], $user);
            }
        }  else {
            $result['body'] = null;
            $result['status'] = "No users signed up. ";
        }
        return $result;
    }

    /**
     * Find a user with a specified id
     * @param $id
     * @return array 
     */
    public function find_user_with_id($id)
    {
        $result = array(
            "status"  => "",
            "body"    => array(
                "user"    => array()
            ),
            "error"   => array(
                "query_error"  => ""
            )
        );
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
            $result["error"]  =  $stmt->error;
        }
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        if ($row["id"] != null){
            $result["status"]  = "Query successful";
            $result["body"]    = array(
                "user"   => array(
                    "id"            => $row["id"],
                    "url"           => $row["url"],
                    "username"      => $row["username"],
                    "email"         => $row["email"],
                    "first_name"    => $row["firstName"],
                    "last_name"     => $row["lastName"],
                    "full_name"     => $row["fullName"],
                    "bio"           => $row["bio"],
                    "is_admin"      => $row["isAdmin"],
                    "password"      => $row["password"],
                    "created"       => $row["created"],
                    "last_updated"  => $row["lastUpdated"]
                )
            );
            return $result;
        } else {
            $result['status'] = "No user with that id exists";
            $result["error"]['query_error']  =  "invalid id in query passed.";
        }
        return $result;
    }


    /**
     * Find a user with a specified email
     * @param $email
     * @return array
     */
    public function find_user_with_email($email)
    {
        $result = array(
            "status"  => "",
            "body"    => array(),
            "error"   => array()
        );
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email LIKE ?");
        if (!($stmt))
        {
            trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error,
                E_USER_ERROR);
        }
        if (!$stmt->bind_param('s', $email)){
            trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error,
                E_ERROR);
        }
        if (!$stmt->execute()) {
            trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error,
                E_CORE_ERROR);
            $result['status'] = "No user with that email exists";
            $result["error"]  =  $stmt->error;
        }
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        if ($row["id"] != null){
            $result["status"]  = "Query successful";
            $result["body"]    = array(
                "user"   => array(
                    "id"            => $row["id"],
                    "url"           => $row["url"],
                    "username"      => $row["username"],
                    "email"         => $row["email"],
                    "first_name"    => $row["firstName"],
                    "last_name"     => $row["lastName"],
                    "full_name"     => $row["fullName"],
                    "bio"           => $row["bio"],
                    "is_admin"      => $row["isAdmin"],
                    "password"      => $row["password"],
                    "created"       => $row["created"],
                    "last_updated"  => $row["lastUpdated"]
                )
            );
            return $result;
        } else {
            $result['status'] = "No user with that email exists";
        }
        return $result;
    }


    /**
     * Find a user with a specified username
     * @param $username
     * @return array
     */
    public function find_user_with_username($username)
    {
        $result = array(
            "status"  => "",
            "body"    => array(),
            "error"   => array()
        );
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username LIKE ?");
        if (!($stmt))
        {
            trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error,
                E_USER_ERROR);
        }
        if (!$stmt->bind_param('s', $username)){
            trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error,
                E_ERROR);
        }
        if (!$stmt->execute()) {
            trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error,
                E_CORE_ERROR);
            $result["error"]  =  $stmt->error;
        }
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        if ($row["id"] != null){
            $result["status"]  = "Query successful";
            $result["body"]    = array(
                "user"   => array(
                    "id"           => $row["id"],
                    "url"          => $row["url"],
                    "username"     => $row["username"],
                    "email"        => $row["email"],
                    "firstName"    => $row["firstName"],
                    "lastName"     => $row["lastName"],
                    "fullName"     => $row["fullName"],
                    "bio"          => $row["bio"],
                    "isAdmin"      => $row["isAdmin"],
                    "password" => $row["password"],
                    "created"      => $row["created"],
                    "lastUpdated"  => $row["lastUpdated"]
                )
            );
            return $result;
        } else {
            $result['status'] = "No user with that username exists";
        }
        return $result;
    }

    /**
     * @param $url
     * @param $username
     * @param $email
     * @param $firstName
     * @param $lastName
     * @param $fullName
     * @param  $bio
     * @param $isAdmin
     * @param $passwordHash
     * @return array
     */
    public function add_user_to_db($url, $username, $email, $firstName,
                                   $lastName, $fullName, $bio, $isAdmin, $passwordHash)
    {
        $result = array(
            "status"  => "",
            "body"    => array(),
            "error"   => array()
        );
        /* Prepared statement, stage 1: prepare */
        if (!($stmt = $this->db->prepare("
        INSERT INTO users(url, username, email, firstName, lastName, fullName, bio, isAdmin,
         password ) VALUES (?,?,?,?,?,?,?,?,?)"))) {
            trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
        }
        /* Prepared statement, stage 2: bind and execute */
        if (!$stmt->bind_param("sssssssss",$url, $username, $email,
            $firstName, $lastName, $fullName, $bio,  $isAdmin, $passwordHash)) {
            trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        if (!$stmt->execute()) {
//            trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            $result["status"]  = "User insertion failed";
            $result["error"]   =  $stmt->error;
        } else {
            $result["status"] = "Insertion success";
        }
        return $result;
    }


    public function update_user_to_db($id, $url, $username, $email, $first_name, $last_name, $full_name, $bio)
    {
        $result = array(
            "status"  => "",
            "body"    => array(),
            "error"   => array()
        );
        $sql = "UPDATE users SET url=?, username=?, email=?, firstName=?, lastName=?, fullName=?, bio=?, lastUpdated=? WHERE id=?";
        date_default_timezone_set("Africa/Nairobi");
        $dt = date("Y-m-d h:i:s");
        /* Prepared statement, stage 1: prepare */
        if (!$stmt = $this->db->prepare($sql)){
            trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
        }
        if (!$stmt->bind_param("ssssssssi",$url,$username,  $email, $first_name, $last_name, $full_name, $bio, $dt, $id)){
            trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
            }
        /* Prepared statement, stage 2: bind and execute */
        if (!$stmt->execute()) {
            $result["error"] =  $stmt->error;
            trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        } else{
            $result["status"] = "Update successful";
        }
        return $result;
    }

    /**
     * @param $id
     * @param $password
     * @return array
     */
    public function update_user_password($id, $password)
    {
        $result = array(
            "status"  => "",
            "body"    => array(),
            "error"   => array()
        );
        $current_user = $this->find_user_with_id($id)['body']['user'];
        $sql = "UPDATE users SET passwordHash=?, lastUpdated=NOW()  WHERE id=?";
//        $lastUpdated = gmdate("n/j/Y g:i:s A"); // today's date
        /* Prepared statement, stage 1: prepare */
        if (!$stmt = $this->db->prepare($sql)){
            trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
        }
        if (!$stmt->bind_param("si",$password, $id)){
            trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        /* Prepared statement, stage 2: bind and execute */
        if (!$stmt->execute()) {
            $result["error"] =  $stmt->error;
            trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        } else{
            $result["status"] = "Update password successful";
        }
        return $result;
    }

    /**
     * @param $id
     * @param $admin
     * @return array
     */
    public function update_user_admin_status($id, $admin)
    {
        $result = array(
            "status"  => "",
            "body"    => array(),
            "error"   => array()
        );
        $current_user = $this->find_user_with_id($id)['body']['user'];

        $sql = "UPDATE users SET isAdmin=?, lastUpdated=NOW()  WHERE id=?";
//        $lastUpdated = gmdate("n/j/Y g:i:s A"); // today's date
        /* Prepared statement, stage 1: prepare */
        if (!$stmt = $this->db->prepare($sql)){
            trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
        }
        if (!$stmt->bind_param("si",$admin, $id)){
            trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        /* Prepared statement, stage 2: bind and execute */
        if (!$stmt->execute()) {
            $result["error"] =  $stmt->error;
            trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        } else{
            $result["status"] = "Update admin status successful";
        }
        return $result;
    }

    /**
     * @param $user_id
     * @return array
     */
    public function delete_user_from_db($user_id)
    {
        $result = array(
            "status"  => "",
            "body"    => array(),
            "error"   => array()
        );
        /* Prepared statement, stage 1: prepare */
        if (!($stmt = $this->db->prepare("DELETE FROM users WHERE id LIKE ?"))) {
            trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
        }
        /* Prepared statement, stage 2: bind and execute */
        if (!$stmt->bind_param("s", $user_id)) {
            trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        if (!$stmt->execute()) {
            $result["error"]  = "Invalid id parameter passed";
            trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        } else {
            $result["status"] = "Deleted successful";
        }
        return $result;
    }

    /**
     * @param $headline
     * @param $content
     * @param $userID
     * @return array
     */
    public function add_post($url, $headline, $content, $userID)
    {
        $result = array(
            "status"  => "",
            "body"    => array(),
            "error"   => ""
        );
        $insert_stmt = "INSERT INTO articles (url, headline, content, created, userID) VALUES (?,?,?,?,?)";
        /* Prepared statement, stage 1: prepare */
        if (!($stmt = $this->db->prepare($insert_stmt))) {
            trigger_error("Prepare failed: (" . $this->db->errno . ") "
                . $this->db->error);
        }
        $created = date("Y-m-d");
        /* Prepared statement, stage 2: bind and execute */
        if (!$stmt->bind_param("ssssi", $url,$headline, $content, $created, $userID)) {
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
            return $result;
        }
        return $result;
    }

    /**
     * @param $articleID
     * @return array
     */
    public function read_post_by_id($articleID){
        $result = array(
            "status"   => "" ,
            "body"  => array(
                "article"  => array()
            ),
            "error"    => array()
        );
        
        $sql =  "SELECT * FROM articles WHERE id LIKE ?";
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
            $result["error"] = $stmt->error;
        } else {
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            if ($row['id'] != null){
                $result['body']['article'] = array(
                    'id'           => $row['id'],
                    'url'          => $row['url'],
                    'headline'     => $row['headline'],
                    'content'      => html_entity_decode($row['content']),
                    'user_id'       => $row['userID'],
                    'created'      => $row['created'],
                    'last_updated' => $row['lastUpdated']
                );
                $result['status']  = "Article read successfully";
                return $result;
            } else {
                // TODO:: Be careful,  call from api request ONLY!!!
                header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
                $result["body"] = array();
                $result["error"] = "No Article found";
            }
        }
        return $result;
    }

    public function read_post_by_user_id($user_id){
        $result = array(
            "status"   => "" ,
            "body"  => array(
                "article"  => array()
            ),
            "error"    => array()
        );

        $sql =  "SELECT * FROM articles WHERE userID LIKE ?";
        $stmt = $this->db->prepare($sql);
        $articleID = filter_var($user_id, FILTER_VALIDATE_INT);
        /* Prepared statement, stage 2: bind and execute */
        if (!$stmt->bind_param("i",$user_id)) {
            trigger_error("Binding parameters failed: ("
                . $stmt->errno . ") " . $stmt->error);
            $result['error'] =  $stmt->error;
        }
        if (!$stmt->execute()) {
            trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            $result['status']  = "Article read unsuccessful";
            $result["error"] = $stmt->error;
        } else {
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            if ($row['id'] != null){
                $result['body']['article'] = array(
                    'id'           => $row['id'],
                    'url'          => $row['url'],
                    'headline'     => $row['headline'],
                    'content'      => html_entity_decode($row['content']),
                    'user_id'       => $row['userID'],
                    'created'      => $row['created'],
                    'last_updated' => $row['lastUpdated']
                );
                $result['status']  = "Article read successfully";
                return $result;
            } else {
                $result["error"] = "No Article found";
            }
        }
        return $result;
    }


    /**
     * @param $userID
     * @return array
     */
    public function read_post_for_user($userID){
        $result = array(
            "status"   => "" ,
            "body"  => array(
                "articles"  => array(),
                "count"   => 0,
            ),
            "error"    => array()
        );

        $sql =  "SELECT * FROM articles WHERE userID LIKE ?";
        $stmt = $this->db->prepare($sql);
        $articleID = filter_var($userID, FILTER_VALIDATE_INT);
        /* Prepared statement, stage 2: bind and execute */
        if (!$stmt->bind_param("i",$userID)) {
            trigger_error("Binding parameters failed: ("
                . $stmt->errno . ") " . $stmt->error);
            $result['error'] =  $stmt->error;
        }
        if (!$stmt->execute()) {
            trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            $result['status']  = "Article read unsuccessful";
            $result["error"] = $stmt->error;
        } else {
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            if ($res->num_rows > 0){
            $result["status"] = $res->num_rows." articles found";
            $result['body']['count'] =  $res->num_rows;
            while($row = $res->fetch_assoc()){
                $article = array(
                    'id'            => $row['id'],
                    'url'           => $row['url'],
                    'headline'      => $row['headline'],
                    'content'       => html_entity_decode($row['content']),
                    'user_id'       => $row['userID'],
                    'created'       => $row['created'],
                    'last_updated'  => $row['lastUpdated']
                );
                array_push($result["body"]["articles"], $article);
            }
        }  else {
                $result['body'] = null;
                $result['status'] = "No users signed up. ";
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
                 "articles"  => array(),
                 "count"    => 0
             ),
             "error"    => array()
         );
         $sql = "SELECT * FROM articles";
         $stmt = $this->db->prepare($sql);
         $stmt->execute();
         $res = $stmt->get_result();
         if ($res->num_rows > 0){
             $result['status'] = "Article read successful";
             $result['body']['count']  = $res->num_rows;
             while($row = $res->fetch_assoc()){
                 $data = [
                     'id'          => $row['id'],
                     'url'         => $row['url'],
                     'headline'    => $row['headline'],
                     'content'     => html_entity_decode($row['content']),
                     'user_id'      => $row['userID'],
                     'created'     => $row['created'],
                     'last_updated' => $row['lastUpdated']
                 ];
                 array_push($result['body']['articles'], $data);
             }
         } else {
             $result['status'] = "No Articles yet! ";
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
       $sql = "UPDATE articles SET headline=?, content=?, userID=?, lastUpdated=? WHERE id=?";
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
           $result["body"]   = $this->read_post_by_id($articleID)['body'];
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
       $sql = "DELETE FROM articles WHERE id=?";
       $result = array(
           "status" => "",
           "body" => array(),
           "error"   => array()
       );
       /* prepared statement, stage 1: prepare */
       if (!($stmt = $this->db->prepare($sql))) {
           trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
       }
       /* Prepared statement, stage 2: bind and execute */
       if (!$stmt->bind_param("i", $articleID)) {
           trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
       }
       if (!$stmt->execute()) {
           trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
           $result["status"] = "Article not deleted Invalid ID.";
           $result['error']  =  $stmt->error;
           return $result;
       }
       $result["status"] = "Article deleted successfully.";
       return $result;
   }

    /**
     * Expected inputs:
     *      (
     *          [application_id] =>
     *          [created]        =>
     *          [display]        =>
     *          [user_id]        =>
     *          [version_name]   =>
     *          [version_code]   =>
     *   )
     * @return array
     */
   public function insert_app_data($application_id, $created, $display,
                                   $user_id, $version_name, $version_code) {
       $result = array(
           "status" => "" ,
           "body" => array(),
           "error"   => array()
       );
       $insert_stmt = "INSERT INTO app (applicationID, versionName, versionCode,  
              userID, display, created, lastUpdated ) VALUES (?, ?, ?, ?, ?, ?, ?)";
       /* Prepared statement, stage 1: prepare */
       if (!($stmt = $this->db->prepare($insert_stmt))) {
           trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
       }
       $last_updated = date("Y-m-d");
       /* Prepared statement, stage 2: bind and execute */
       if (!$stmt->bind_param("sssssss", $application_id, $version_name, $version_code,
           $user_id, $display , $created, $last_updated)) {
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
     * Retrieve all data that apps have submitted
     * @return array
     */
   public function load_all_apps_data(){
       $result = array(
           "status" => "" ,
           "body" => array(
               "app"   => array(),
               "count" => 0
           ),
           "error"   => array()
       );
       $sql = "SELECT * FROM app";
       $stmt = $this->db->prepare($sql);
       $stmt->execute();
       $res = $stmt->get_result();
       if ($res->num_rows > 0){
           $result['status'] = "Reading app data ...";
           $result['body']['count']  = $res->num_rows;
           while($row = $res->fetch_assoc()){
               $data = [
                   "id"              => $row['id'],
	               "app_id"          => $row['applicationID'],
                   "version_name"    => $row['versionName'],
                   "version_code"    => $row['versionCode'],
                   "user_id"         => $row['userID'],
                   "display"         => $row['display'],
                   "created"         => $row['created'],
                   "last_updated"    => $row['lastUpdated']
               ];
               array_push($result['body']['app'], $data);
           }
       } else {
           $result['error'] = "Application data not present ... ";
       }
       return $result;
   }

    /**
     * @param $appID
     * @return array
     */
   public function read_app_data_by_id($appID){
       $result = array(
           "status" => "" ,
           "body" => array(
               "app" => array()
           ),
           "error"   => array()
       );
       $sql = "SELECT * FROM app WHERE id LIKE ?";
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
           $result['body']['app'] = [
               "id"              => $row['id'],
               "app_id"          => $row['applicationID'],
               "version_name"    => $row['versionName'],
               "version_code"    => $row['versionCode'],
               "user_id"         => $row['userID'],
               "display"         => $row['display'],
               "created"         => $row['created'],
               "last_updated"    => $row['lastUpdated']
           ];
       } else {
           $result['status'] = "Application data not present ... ";
           $result['error']  = "Invalid Application id";
       }
       return $result;
   }

    /**
     * @param $id
     * @param $application_id
     * @param $version_name
     * @param $version_code
     * @param $user_id
     * @param $user_id
     * @param $display
     * @return array
     */
   public function update_app_data($id, $application_id, $version_name, $version_code, $user_id, $display){
       $sql = "UPDATE app SET applicationID=?, versionName=?, versionCode=?,
                  userID=?, display=?,  WHERE id=?";
       $result = array(
           "status" => "",
           "body" => array(
               "app" => array()
           ),
           "error"   => array()
       );
       /* Prepared statement, stage 1: prepare */
       if (!($stmt = $this->db->prepare($sql))) {
           trigger_error("Prepare failed: (" . $this->db->errno . ") " .
               $this->db->error);
       }
       /* Prepared statement, stage 2: bind and execute */
       if (!$stmt->bind_param("sssssi",$application_id, $version_name, $version_code,
           $user_id, $display, $id)) {
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
           $result["body"]   = $this->read_app_data_by_id($id)['body']['app'];
       }

       return $result;
   }

    /**
     * @param $appID
     * @return array
     */
   public function delete_app_data($appID)
   {
       $sql = "DELETE FROM `app` WHERE `app`.`id` = ?";
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
           $result["error"] = $stmt->error;
           return $result;
       }
       $result["status"] = "App data deleted successfully.";
       return $result;
   }

    /**
     * @param $url
     * @param $title
     * @param $description
     * @param $category_id
     * @param $price
     * @return array
     */
   public function add_product_to_db($url, $title, $description, $category_id,
                                   $price)
    {
        $result = array(
            "status"  => "",
            "body"    => array(),
            "error"   => array()
        );
        $created = date("Y-m-d");
        $last_updated  = date("Y-m-d");
        /* Prepared statement, stage 1: prepare */
        if (!($stmt = $this->db->prepare("
        INSERT INTO products(url, title, detail, categoryID, price, created, lastUpdated )
         VALUES (?,?,?,?,?,?,?)"))) {
            trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
        }
        /* Prepared statement, stage 2: bind and execute */
        if (!$stmt->bind_param("sssssss",$url, $title, $description,
            $category_id, $price, $created, $last_updated)) {
            trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        if (!$stmt->execute()) {
            trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            $result["status"]  = "Product not created ";
            $result["error"]   =  $stmt->error;
        } else {
            $result["status"] = "Product created ";

        }
        return $result;
    }

    /**
     * @return array
     */
    public function load_all_products(){
        $result = array(
            "status"   => "" ,
            "body"  => array(
                "products"  => array(),
                "count"   => 0
            ),
            "error"    => array()
        );
        $sql = "SELECT * FROM products";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        $result['body']['count']  = $res->num_rows;
        if ($res->num_rows > 0){
            $result['status'] = "Products read ";
            $result['body']['count'] =  $res->num_rows;
            while($row = $res->fetch_assoc()){
                $data = array(
                    "id"           => $row['id'],
                    "url"          => $row['url'],
                    "title"        => $row['title'],
                    "detail"       => $row['detail'],
                    "category_id"  => $row['categoryID'],
                    "price"        => $row['price'],
                    "created"      => $row['created'],
                    "last_updated" => $row['lastUpdated']
                );
                array_push($result['body']['products'], $data);
            }
        } else {
            $result['status'] = "No categories found ";
        }
       return $result;
    }

    /**
     * Read all the categories
     * @return array
     */
    public function read_all_categories()
    {
        $result = array(
            "status"   => "" ,
            "body"     => array(
                "categories"  => array(
                    "id"            => 0,
                    "category_name" => ""
                ),
                "count"   => 0
            ),
            "error"    => array()
        );
        $sql = "SELECT * FROM categories";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        $result['body']['count']  = $res->num_rows;
        if ($res->num_rows > 0){
            $result['status'] = "Categories read ";
            while($row = $res->fetch_assoc()){
                $data = array(
                    "id"            => $row["id"],
                    "category_name" => $row['categoryName']
                );
                array_push($result['body']['categories'], $data);
            }
        } else {
            $result['status'] = "No categories found ";
        }
        return $result;
    }

    /**
     * Find a user with a specified username
     * @param $category_name
     * @return array
     */
    public function find_category_id($category_name)
    {
        $result = array(
            "status"   => "" ,
            "body"  => array(
                "category"  => array(
                    "id"            => 0,
                    "category_name" => ""
                ),
                "count"   => 0
            ),
            "error"    => array()
        );
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE categoryName LIKE ?");
        if (!($stmt))
        {
            trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error,
                E_USER_ERROR);
        }
        if (!$stmt->bind_param('s', $category_name)){
            trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error,
                E_ERROR);
        }
        if (!$stmt->execute()) {
            trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error,
                E_CORE_ERROR);
            $result["error"]  =  $stmt->error;
        }
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        if ($row["id"] != null){
            $result["status"]       = "Query successful";
            $result["body"]         = array(
                "category"          => array(
                    "id"            => $row["id"],
                    "category_name" => $row["categoryName"]
                )
            );
            return $result;
        } else {
            $result['status'] = "No category with that name exists";
        }
        return $result;
    }


}