<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 29-Apr-20
 * Time: 5:08 PM
 */

namespace Src\auth;
require_once __DIR__ . '../../../vendor/autoload.php';

use Src\database\DatabaseConnection;
use Src\models\User;

class Login{
    private $username_or_email;

    /**
     * @return string
     */
    public function getUsernameOrEmail()
    {
        if (!empty($this->username_or_email))
            return $this->username_or_email;
        return $this->username_or_email;
    }

    /**
     * @param string $username_or_email
     */
    public function setUsernameOrEmail($username_or_email)
    {
        $this->username_or_email = $username_or_email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        if (!empty($this->password))
            return $this->password;
        return $this->username_or_email;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    private $password;

    /**
     * @var array Use it to see what values are expected
     */
    public $login_frm_inputs = array();

    private $login_frm_inputs_errors = array(
        "credentials_error"  => "",
        "password_error"     => ""
    );

    // Database connection handle
    private $conn;
    
    /**
     * Login constructor.
     */
    public function __construct()
    {
        // initialise the authentication variable
        $_SESSION['is_authenticated']  = '';
        $db = new DatabaseConnection();
        $this->conn = $db->get_db_connection();
    }

    /**
     * Checks the DB for the email.
     * @param $email
     * @return string
     *
     */
    public function perform_email_check($email){
        $stmt =  $this->conn->prepare("SELECT * FROM users WHERE email LIKE ?");
        if (!($stmt)){
            trigger_error("Prepare failed: (" . $this->conn->errno . ") " .
                $this->conn->error, E_USER_ERROR);
        }
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
        if ($row['email'] != null){
            $this->login_frm_inputs['id']           = $row['id'];
             $this->login_frm_inputs['url']         = $row['url'];
            $this->login_frm_inputs['email']        = $row['email'];
            $this->login_frm_inputs['username']     = $row['username'];
            $this->login_frm_inputs['first_name']   = $row['firstName'];
            $this->login_frm_inputs['last_name']    = $row['lastName'];
            $this->login_frm_inputs['full_name']    = $row['fullName'];
            $this->login_frm_inputs['is_admin']     = $row['isAdmin'];
            $this->login_frm_inputs['bio']          = $row['bio'];
            $this->login_frm_inputs['created']      = $row['created'];
            $this->login_frm_inputs['last_updated'] = $row['lastUpdated'];
            $this->login_frm_inputs['password']     = $row['password'];
            if (!isset($_SESSION['is_admin']) || isset($_SESSION['is_admin']) ) {
                $_SESSION['is_admin']                   = $row['isAdmin'];
            }
            if (!isset($_SESSION['login_ID']) || isset($_SESSION['login_ID']) ) {
                $_SESSION["login_ID"]                    =  $row['id'];
            }
            $this->login_frm_inputs_errors['credentials_error'] = "";
        } else {
            $this->login_frm_inputs_errors['credentials_error'] = 'The email address does not exist';
        }
        return $this->login_frm_inputs_errors['credentials_error'];
    }

    public function perform_username_check($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username LIKE ?");
        if (!($stmt)) {
            trigger_error("Prepare failed: (" . $this->conn->errno . ") " .
                $this->conn->error, E_USER_ERROR);
        }
        if (!$stmt->bind_param('s', $username)) {
            trigger_error("Binding parameters failed: (" . $stmt->errno . ") "
                . $stmt->error, E_ERROR);
        }
        if (!$stmt->execute()) {
            trigger_error("Execute failed: (" . $stmt->errno . ") "
                . $stmt->error, E_CORE_ERROR);
        }
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        if ($row['username'] != null) {
            $this->login_frm_inputs['id']           = $row['id'];
            $this->login_frm_inputs['url']          = $row['url'];
            $this->login_frm_inputs['username']     = $row['username'];
            $this->login_frm_inputs['email']        = $row['email'];
            $this->login_frm_inputs['first_name']   = $row['firstName'];
            $this->login_frm_inputs['last_name']    = $row['lastName'];
            $this->login_frm_inputs['full_name']    = $row['fullName'];
            $this->login_frm_inputs['is_admin']     = $row['isAdmin'];
            $this->login_frm_inputs['bio']          = $row['bio'];
            $this->login_frm_inputs['created']      = $row['created'];
            $this->login_frm_inputs['last_updated'] = $row['lastUpdated'];
            $this->login_frm_inputs['password']     = $row['password'];
            if (!isset($_SESSION['is_admin']) || isset($_SESSION['is_admin']) ) {
                $_SESSION['is_admin']                   = $row['isAdmin'];
            }
            if (!isset($_SESSION['login_ID']) || isset($_SESSION['login_ID']) ) {
                $_SESSION["login_ID"]                    =  $row['id'];
            }
            $this->login_frm_inputs_errors['credentials_error'] = "";
        } else {
            $this->login_frm_inputs_errors['credentials_error'] = 'The username does not exists. ';
        }
        return $this->login_frm_inputs_errors['credentials_error'];
    }

    /**
     *  Returns true if the $login_frm_credentials are valid
     * @return string
     */
    public function perform_username_or_email_check() : string {
        // Login with email
        if (filter_var($this->getUsernameOrEmail(), FILTER_VALIDATE_EMAIL)
            && filter_var($this->getUsernameOrEmail(), FILTER_SANITIZE_EMAIL) ){
            return $this->perform_email_check($this->getUsernameOrEmail());
        }
        // Login with username
        else if (!filter_var($this->getUsernameOrEmail(), FILTER_VALIDATE_EMAIL)
            &&  filter_var($this->getUsernameOrEmail(), FILTER_SANITIZE_STRING)){
            return $this->perform_username_check($this->getUsernameOrEmail());
        } else if (empty($this->getUsernameOrEmail())) {
            $this->login_frm_inputs_errors['credentials_error'] = 'The username or email address field is empty';
        }
        return $this->login_frm_inputs_errors['credentials_error'];
    }

    public function perform_password_check(){
        if (empty($this->getPassword())) {
            $this->login_frm_inputs_errors['password_error'] = "The password field is empty.";
        }
        if (!empty($this->login_frm_inputs['password']) &&
            !password_verify($this->getPassword(), $this->login_frm_inputs['password'])){
                $this->login_frm_inputs_errors['password_error'] = "Incorrect password entered.";
            }
        return $this->login_frm_inputs_errors['password_error'];
    }

    /**
     * Set the session var
     *
     */
    public function authenticate(){
        if (empty($this->perform_username_or_email_check()) &&
            empty($this->perform_password_check()) ) {
            if (!isset($_SESSION['is_authenticated']) || isset($_SESSION['is_authenticated']) ) {
                $_SESSION['is_authenticated'] = true;
            }
        }
    }

    /**
     * @return array
     */
    public function getLoginFrmInputs()
    {
        return $this->login_frm_inputs;
    }

}