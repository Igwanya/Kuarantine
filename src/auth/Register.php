<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 29-Apr-20
 * Time: 5:12 PM
 */

namespace Src\auth;
use Src\database\DatabaseConnection;
use Src\models\User;
use Src\Repository;

require_once __DIR__ . '../../../vendor/autoload.php';

class Register{
    private $m_username;

    private $m_email;

    private $m_password;

    private $conn;

    private $register_form_errors = array(
        "username_error"   => "",
        "email_error"      => "",
        "password_error"   => ""
    );

    /**
     * Register constructor.
     */
    public function __construct()
    {
        $mysql = new DatabaseConnection();
        $this->conn = $mysql->get_db_connection();
    }

    private $url;


    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $username
     * @return Register
     */
    public function setUsername($username)
    {
        $this->m_username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername() : string
    {
        return $this->m_username;
    }

    /**
     * @param string $email
     * @return Register
     */
    public function setEmail($email)
    {
        $this->m_email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()  : string
    {
        return $this->m_email;
    }

    /**
     * @param string $password
     * @return Register
     */
    public function setPassword($password)
    {
        $this->m_password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->m_password;
    }

    private $full_name;

    /**
     * @param mixed $full_name
     */
    public function set_full_name($full_name)
    {
        $this->full_name = $full_name;
    }

    /**
     * @return mixed
     */
    public function get_full_name()
    {
        return $this->full_name;
    }

    private $first_name;


    /**
     * @param mixed $first_name
     */
    public function set_first_name($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return mixed
     */
    public function get_first_name()
    {
        return $this->first_name;
    }

    /**
     * @param mixed $last_name
     */
    public function set_last_name($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return mixed
     */
    public function get_last_name()
    {
        return $this->last_name;
    }

    private $last_name;


    private $api_registration_request;

    /**
     * @param bool $api_registration_request
     */
    public function setApiRegistrationRequest($api_registration_request)
    {
        $this->api_registration_request = $api_registration_request;
    }

    /**
     * @return bool
     */
    public function getApiRegistrationRequest()
    {
        return $this->api_registration_request;
    }

    /**
     * Checks the username if it already exists.
     * @return string
     */
    public function perform_username_check()
    {
        $stmt =  $this->conn->prepare("SELECT username FROM users WHERE username LIKE ?");
        if (!($stmt)){
            trigger_error("Prepare failed: (" . $this->conn->errno . ") " .
                $this->conn->error, E_USER_ERROR);
        }
        $username = $this->getUsername(); // local scope
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
            $this->register_form_errors['username_error'] = "Username already exists.";
        }
        return $this->register_form_errors['username_error'];
    }

    public function perform_email_check()
    {
        $stmt =  $this->conn->prepare("SELECT email FROM users WHERE email LIKE ?");
        if (!($stmt)){
            trigger_error("Prepare failed: (" . $this->conn->errno . ") " .
                $this->conn->error, E_USER_ERROR);
        }
        $email = $this->getEmail(); // local scope
        if (!$stmt->bind_param('s', $email)){
            trigger_error("Binding parameters failed: (" . $stmt->errno . ") " .
                $stmt->error, E_ERROR);
        }
        if (!$stmt->execute()) {
            trigger_error("Execute failed: (" . $stmt->errno . ") " .
                $stmt->error, E_CORE_ERROR);
        }
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        if ($row['email'] != null ) {
            $this->register_form_errors['email_error'] = "Email already exists.";
        }
        return $this->register_form_errors['email_error'];
    }

    /**
     * Register the user and log them in
     */
    public function register_user(){
        $result = array(
            "status"  => "",
            "body"    => array(),
            "error"   => array()
        );
        $repo = new Repository();
        $user = new User();
        $user->set_email($this->getEmail());
        $user->set_password_hash($this->getPassword());
        if ($this->getApiRegistrationRequest() == 1) {
            $result =  $repo->add_user_to_db($this->url, $this->m_username, $this->m_email, $this->first_name, $this->last_name, $this->full_name, null,false, $user->get_password_hash());
        }

        $result =  $repo->add_user_to_db($this->url, $this->m_username, $this->m_email, $this->first_name, $this->last_name, $this->full_name, null,false, $user->get_password_hash());
        if (empty($result['error'])){
            $user = $repo->find_user_with_email($this->m_email)['body']['user'];
            if (!isset($_SESSION['is_authenticated'])
                || isset($_SESSION['is_authenticated']) ){
                $_SESSION['is_authenticated']  = 1;
            }
            if ( !isset($_SESSION['login_ID']) || isset($_SESSION['login_ID']))  {
                $_SESSION['login_ID']  = $user['id'];
            }
            header("Location: ../login.php");
        }

    }
}