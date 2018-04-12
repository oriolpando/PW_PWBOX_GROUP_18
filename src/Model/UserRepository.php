<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 12/4/2018
 * Time: 20:23
 */

namespace PwBox\Model;


interface UserRepository
{
    public function save(User $user);

}