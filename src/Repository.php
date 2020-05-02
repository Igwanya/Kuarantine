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
     * Create a new posts.
     * @param $title
     * @param $body
     * @param $author
     * @return array
     */
    public function add_post($title, $body, $author)
    {
        $result = array(
            "message" => "",
            "error"   => ""
        );
        $insert_stmt = "INSERT INTO posts (title, body, author) VALUES (?,?,?)";
        /* Prepared statement, stage 1: prepare */
        if (!($stmt = $this->db->prepare($insert_stmt))) {
            trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
        }
        /* Prepared statement, stage 2: bind and execute */
        if (!$stmt->bind_param("sss", $title, $body, $author)) {
            trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        if (!$stmt->execute()) {
            trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            $result["message"] = null;
            $result["error"] = $_SERVER["SERVER_PROTOCOL"]." 404 Not Found";
        } else {
            $result["message"] = "Post inserted successfully";
            $result["error"]   = null;
            return $result;
        }
        return $result;
    }

    /**
     * @param $id
     * @return array
     */
    public function read_post($id){
        $result = array(
            "message"  => array(
                "id"         => 0,
                "title"      => "",
                "body"       => "",
                "author"     => "",
                "created_at" => ""
            ),
            "error"    => ""
        );
        $sql =  "SELECT * FROM posts WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        if (!$stmt->execute()) {
            trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            $result["message"] = null;
            $result["error"] = $_SERVER["SERVER_PROTOCOL"]." 404 Not Found";
        } else {
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            if ($row['id'] != null){
                $result["message"]['id']         =  $row['id'];
                $result["message"]['title']      =  $row['title'];
                $result["message"]['body']       =  $row['body'];
                $result["message"]['author']     =  $row['author'];
                $result["message"]['created_at'] =  $row['created_at'];
                $result["error"]   = null;
                return $result;
            } else {
                // TODO:: Be careful,  call from api request ONLY!!!
                header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
                $result["message"] = null;
                $result["error"] = "No post found";
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
             "message"  => array(
                 "id"         => 0,
                 "title"      => "",
                 "body"       => "",
                 "author"     => "",
                 "created_at" => ""
             ),
             "error"    => ""
         );
         $sql = "SELECT * FROM posts";
         $stmt = $this->db->prepare($sql);
         $stmt->execute();
         $res = $stmt->get_result();

         if ($res->num_rows > 0){
             while($row = $res->fetch_assoc()){
                 $post_data = [
                     'id'         => $row['id'],
                     'title'      => $row['title'],
                     'body'       => html_entity_decode($row['body']),
                     'author'     => $row['author'],
                     'created_at' => $row['created_at']
                 ];
                 array_push($result['message'], $post_data);
             }
         } else {
             $result['message'] = null;
             $result['error'] = "No posts yet! ";
         }
         return $result;
     }

    /**
     * @param $id
     * @param $title
     * @param $body
     * @return array
     */
   public function update_post($id, $title, $body)
   {
       $sql = "UPDATE posts SET title=?, body=? WHERE id=?";
       $result = array(
           "message" => "",
           "error"   => ""
       );
       /* Prepared statement, stage 1: prepare */
       if (!($stmt = $this->db->prepare($sql))) {
           trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
       }
       /* Prepared statement, stage 2: bind and execute */
       if (!$stmt->bind_param("ssi", $title, $body, $id)) {
           trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
       }
       if (!$stmt->execute()) {
           trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
           $result["message"] = null;
           $result["error"] = "Post not updated. Invalid ID.";
           return $result;
       }
       $result["message"] = "Post updated successfully.";
       $result["error"]   = null;
       return $result;
   }

   public function delete_post($post_id)
   {
       $sql = "DELETE FROM posts WHERE id=?";
       $result = array(
           "message" => "",
           "error"   => ""
       );
       /* Prepared statement, stage 1: prepare */
       if (!($stmt = $this->db->prepare($sql))) {
           trigger_error("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
       }
       /* Prepared statement, stage 2: bind and execute */
       if (!$stmt->bind_param("i", $post_id)) {
           trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
       }
       if (!$stmt->execute()) {
           trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
           $result["message"] = null;
           $result["error"] = "Post not deleted Invalid ID.";
           return $result;
       }
       $result["message"] = "Post deleted successfully.";
       $result["error"]   = null;
       return $result;
   }

}