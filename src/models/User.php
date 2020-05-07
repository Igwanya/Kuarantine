<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 05-Apr-20
 * Time: 7:41 PM
 */

namespace Src\models;


class User
{
    private $m_username;
    private $m_email;
    private $m_first_name;
    private $m_last_name;
    private $is_admin;
    private $m_password_hash;
    private $m_id;
    private $m_created;
    private $m_last_updated;

    /**
     * @return int
     */
    public function get_Id() : int
    {
        return $this->m_id;
    }

    /**
     * @param int $m_id
     */
    public function set_Id($m_id)
    {
        $this->m_id = $m_id;
    }

    /**
     * @return mixed
     */
    public function get_created()
    {
        return $this->m_created;
    }

    /**
     * @param mixed $m_created
     */
    public function set_created($m_created)
    {
        $this->m_created = $m_created;
    }

    /**
     * @return mixed
     */
    public function get_last_updated()
    {
        return $this->m_last_updated;
    }

    /**
     * @param mixed $m_last_updated
     */
    public function set_last_updated($m_last_updated)
    {
        $this->m_last_updated = $m_last_updated;
    }

    /**
     * @return mixed The hashed password, or FALSE on failure.
     */
    public function get_password_hash()
    {
        return $this->m_password_hash;
    }

    /**
     * creates a new password hash using a strong one-way hashing algorithm.
     * Supported algorithm
     *      PASSWORD_DEFAULT - Use the bcrypt algorithm.
     *      PASSWORD_BCRYPT - Use the CRYPT_BLOWFISH algorithm to create the hash.
     *                        This will produce a standard crypt() compatible hash
     *                      using the "$2y$" identifier. The result will always be
     *                      a 60 character string, or FALSE on failure.
     *      PASSWORD_ARGON2I - Use the Argon2i hashing algorithm to create the hash.
     *                      This algorithm is only available if PHP has been compiled
     *                      with Argon2 support.
     *      PASSWORD_ARGON2ID - Use the Argon2id hashing algorithm to create the hash.
     *                         This algorithm is only available if PHP has been compiled
     *                          with Argon2 support.
     * @param mixed $password
     * @return mixed The hashed password, or FALSE on failure.
     */
    public function set_password_hash($password)
    {
//        determine the appropriate algo cost depending on hardware
        $timeTarget = 0.05; // 50 milliseconds
        $cost = 8;
        do {
            $cost++;
            $start = microtime(true);
            password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);
        $options = [
            'cost' =>  $cost,
        ];
        $this->m_password_hash = password_hash($password, PASSWORD_BCRYPT, $options);
        return $this->m_password_hash;
    }

    /**
     * User constructor.
     * @param $username
     * @param $email
     * @param $first_name
     * @param $last_name
     * @param $is_admin
     */
    public function __construct()
    {
    }

    /**
     * @var string
     */
    private $url;

    private $full_name;

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * @param mixed $full_name
     */
    public function setFullName($full_name)
    {
        $this->full_name = $full_name;
    }

    /**
     * @return string
     */
    public function get_username() :string
    {
        return $this->m_username;
    }

    /**
     * @param mixed $username
     */
    public function set_username($username)
    {
        $this->m_username = $username;
    }

    /**
     * @return string
     */
    public function get_email() : string
    {
        return $this->m_email;
    }

    /**
     * @param string $email
     */
    public function set_email($email)
    {
        $this->m_email = $email;
    }

    /**
     * @return string
     */
    public function get_first_name() : string
    {
        return $this->m_first_name;
    }

    /**
     * @param mixed $first_name
     */
    public function set_first_name($first_name)
    {
        $this->m_first_name = $first_name;
    }

    /**
     * @return string
     */
    public function get_last_name() : string
    {
        return $this->m_last_name;
    }

    /**
     * @param string $last_name
     */
    public function set_last_name($last_name)
    {
        $this->m_last_name = $last_name;
    }

    /**
     * @return bool
     */
    public function is_Admin() :bool
    {
        return $this->is_admin;
    }

    /**
     * @param bool $is_admin
     */
    public function set_Admin($is_admin)
    {
        $this->is_admin = $is_admin;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->m_email;
    }


}