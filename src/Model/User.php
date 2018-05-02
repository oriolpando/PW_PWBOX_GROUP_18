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
    private $nom;
    private $surname;
    private $username;
    private $email;
    private $psw;
    private $birth_date;

    /**
     * User constructor.
     * @param $id
     * @param $nom
     * @param $surname
     * @param $username
     * @param $email
     * @param $psw
     * @param $birth_date
     */
    public function __construct($nom, $surname, $username, $email, $psw, $birth_date)
    {
        $this->id = null;
        $this->nom = $nom;
        $this->surname = $surname;
        $this->username = $username;
        $this->email = $email;
        $this->psw = $psw;
        $this->birth_date = $birth_date;
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
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
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
    public function getPsw()
    {
        return $this->psw;
    }

    /**
     * @return mixed
     */
    public function getBirthDate()
    {
        return $this->birth_date;
    }

    /**
     * User constructor.
     * @param $id
     * @param $username
     * @param $email
     * @param $password
     * @param $created_at
     * @param $updated_at
     */


}