<?php
/**
 * Created by PhpStorm.
 * User: miquelator
 * Date: 17/5/18
 * Time: 20:18
 */

namespace PwBox\Model;


interface MailerRepository
{
    public function sendValidate($id, $name, $email);

}