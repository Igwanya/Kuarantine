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
    public $login_frm_inputs = array(
        'id'             =>  '',
        'url'            =>  '',
        'username'       =>  '' ,
        'email'          =>  '',
        'password'       =>  '',
        'first_name'     =>  '' ,
        'last_name'      =>  '',
        'full_name'      =>  '',
        'is_admin'       => false,
        'created'        =>  '',
        'last_updated'   =>  ''
    );

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
            $this->login_frm_inputs['created']      = $row['created'];
            $this->login_frm_inputs['last_updated'] = $row['lastUpdated'];
            $this->login_frm_inputs['password']     = $row['passwordHash'];
            $_SESSION['is_admin']                   = $row['isAdmin'];
            $_SESSION["login_ID"]                    =  $row['id'];
            $this->login_frm_inputs_errors['credentials_error'] = "";
        } else {
            $this->login_frm_inputs_errors['credentials_error'] = 'Email address does not exist';
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
            $this->login_frm_inputs['url']         = $row['url'];
            $this->login_frm_inputs['username']     = $row['username'];
            $this->login_frm_inputs['email']        = $row['email'];
            $this->login_frm_inputs['first_name']   = $row['firstName'];
            $this->login_frm_inputs['last_name']    = $row['lastName'];
            $this->login_frm_inputs['full_name']    = $row['fullName'];
            $this->login_frm_inputs['is_admin']     = $row['isAdmin'];
            $this->login_frm_inputs['created']      = $row['created'];
            $this->login_frm_inputs['last_updated'] = $row['lastUpdated'];
            $this->login_frm_inputs['password']     = $row['passwordHash'];
            $_SESSION['is_admin']                   = $row['isAdmin'];
            $_SESSION["login_ID"]                    =  $row['id'];
            $this->login_frm_inputs_errors['credentials_error'] = "";
        } else {
            $this->login_frm_inputs_errors['credentials_error'] = 'No user with that username exists. ';
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
            $this->login_frm_inputs_errors['credentials_error'] = 'Enter a valid username or email address';
        }
        return $this->login_frm_inputs_errors['credentials_error'];
    }

    /**
     * Handle the form and log in the user
     */
    public function redirect_to_profile_page()
    {
        /* Redirect to a different page in the current directory that was requested */
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'profile.php';
        header("Location: http://$host$uri/$extra");
        exit;
    }

    public function perform_password_check(){
        if (password_verify($this->getPassword(), $this->login_frm_inputs['password'])){
            $this->login_frm_inputs_errors['password_error'] = "";
        } else {
            $this->login_frm_inputs_errors['password_error'] = "Incorrect password";
        }
        return $this->login_frm_inputs_errors['password_error'];
    }

    public function perform_login(){
        $result = array(
            "status" => false,
            "error"  => ""
        );
        if (empty($this->perform_username_or_email_check()) &&
            empty($this->perform_password_check()) )  {
            $_SESSION['is_authenticated'] = true;
            $result["status"]  = true;
            $this->redirect_to_profile_page();
        } else {
            $result["status"]  = false;
            $result["error"] = "Login process halted ";
        }
//        header("Location: http://www.reelgood.com/public_html/login.php",FALSE,200);
        return $result;
    }

    /**
     * @return array
     */
    public function getLoginFrmInputs()
    {
        return $this->login_frm_inputs;
    }

}