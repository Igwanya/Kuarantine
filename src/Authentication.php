<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 06-Apr-20
 * Time: 9:46 PM
 */

namespace Src;


class Authentication
{
    private $m_password;

    /**
     * @return mixed
     */
    public function get_password()
    {
        return $this->m_password;
    }

    /**
     * @param mixed $password
     * @return Authentication
     */
    public function set_password($password)
    {
        $this->m_password = $password;
        return $this;
    }
    private $m_email;

    /**
     * @return mixed
     */
    public function get_email()
    {
        return $this->m_email;
    }

    /**
     * @param mixed $email
     */
    public function set_email($email)
    {
        $this->m_email = $email;
    }

    /**
     * Authentication constructor.
     */
    public function __construct()
    {
    }

    public function send_reset_email_link()
    {

    }
}
$auth =  new Authentication();
$auth->set_email(
    filter_input(INPUT_POST, 'passwordResetEmail')
);
$auth->set_password(
    filter_input(INPUT_POST, 'password')
);



