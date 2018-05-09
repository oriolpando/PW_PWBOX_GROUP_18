<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 8/5/2018
 * Time: 17:17
 */

namespace PwBox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PwBox\Model\User;
use PwBox\Model\UserRepository;

class UpdateUserController
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function updateUser(Request $request, Response $response)
    {

        var_dump($_GET);
        try{
            $email=$_GET['email'];

            $exists = $this->container->get('user_repository')->updateEmail($email);

            var_dump($exists);

            return $response->withStatus(200)->withHeader('ok');
        }catch (\Exception $e){
            return $response->withStatus(500)->withHeader('noOk');
        }


    }

}