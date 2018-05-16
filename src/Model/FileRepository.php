<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 12/4/2018
 * Time: 20:23
 */

namespace PwBox\Model;


interface FileRepository
{
public function saveItem($item, $size);
public function iniciaFolder();

}