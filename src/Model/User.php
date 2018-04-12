<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 12/4/2018
 * Time: 20:19
 */

namespace PwBox\Model;


class User
{
    private  $id;
    private $username;
    private $email;
    private $password;
    private $created_at;
    private $updated_at;

    /**
     * User constructor.
     * @param $id
     * @param $username
     * @param $email
     * @param $password
     * @param $created_at
     * @param $updated_at
     */
    public function __construct($id, $username, $email, $password, $created_at, $updated_at)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

}