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
    private $m_first_name;
    private $m_last_name;
    private $m_password;
    private $conn;
    private $register_form_errors = array(
        "username_error"   => "",
        "email_error"      => "",
        "first_name_error" => "",
        "last_name_error"  => "",
        "password_error"   => ""
    );
    public $register_correct_inputs = array(
        "username"     => "",
        "email"        => "",
        "first_name"   => "",
        "last_name"    => "",
        "is_admin"     => 0,
        "password"     => "",
        "created"      => "",
        "last_updated" => "",
        "expiry_date"  => ""
    );

    /**
     * Register constructor.
     */
    public function __construct()
    {
        $mysql = new DatabaseConnection();
        $this->conn = $mysql->get_db_connection();
    }

    /**
     * @return string
     */
    public function getUsername() : string
    {
        if (!empty($this->m_username) && $this->m_username !=null){
            return $this->m_username;
        }
        return $this->m_username = "";
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
    public function getEmail()  : string
    {
        if (!empty($this->m_email && $this->m_email !=null )) {
            return $this->m_email;
        }
        return $this->m_email = "";
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
    public function getFirstName() : string
    {
        if (!empty($this->m_first_name) &&
            $this->m_first_name !=null) {
            return $this->m_first_name;
        }
        return $this->m_email = "";
    }

    /**
     * @param string $first_name
     * @return Register
     */
    public function setFirstName($first_name)
    {
        $this->m_first_name = $first_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        if (!empty($this->m_last_name) && $this->m_last_name !=null){
            return $this->m_last_name;
        }
        return $this->m_last_name = "";
    }

    /**
     * @param string $last_name
     * @return Register
     */
    public function setLastName($last_name)
    {
        $this->m_last_name = $last_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->m_password;
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
            $this->register_form_errors['username_error'] = "username already exists.";
        } else {
            $this->register_correct_inputs['username'] = $username;
            $this->register_form_errors['username_error'] = "";
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
            $this->register_form_errors['email_error'] = "email already exists.";
        } else {
            $this->register_correct_inputs['email'] = $email;
            $this->register_form_errors['email_error'] = "";
        }
        return $this->register_form_errors['email_error'];
    }

    /**
     * @return array
     */
    public function getRegisterCorrectInputs(): array
    {
        $this->register_correct_inputs['password']  = $this->getPassword();
        $this->register_correct_inputs['created']   = date("Y-m-d");
        $this->register_correct_inputs['last_updated'] = date("Y-m-d");

        return $this->register_correct_inputs;
    }

    public function register_user(){
        $result = array(
            "status"  => "",
            "body"    => array(),
            "error"   => ""
        );
        $repo = new Repository();
        $user = new User(
            null,
            $this->getRegisterCorrectInputs()['username'],
            $this->getRegisterCorrectInputs()['email'],
            $this->getRegisterCorrectInputs()["first_name"],
            $this->getRegisterCorrectInputs()["last_name"],
            $this->getRegisterCorrectInputs()["is_admin"],
            $this->getRegisterCorrectInputs()["created"],
            $this->getRegisterCorrectInputs()["last_updated"]
        );
        $user->set_password_hash($this->getPassword());
        $result =  $repo->add_user_to_db($user);
        return $result;
    }



    public function performLogin(){
        $login = new Login();
        $login->setUsernameOrEmail($this->getUsername());
        $login->setPassword($this->m_password);
        $login->perform_username_check($this->getUsername());
        $login->perform_login();
    }



}