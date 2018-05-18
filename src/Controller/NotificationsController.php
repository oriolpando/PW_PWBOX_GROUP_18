<?php
/**
 * Created by PhpStorm.
 * User: carlaradresa
 * Date: 17/5/18
 * Time: 19:49
 */

namespace PwBox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class NotificationsController
{

    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function getNotifications(Request $request, Response $response)
    {

        $idSend = $_SESSION['id'];

        $userSend = $this->container->get('user_repository')->getUser($idSend);

        $pathSend = 'assets/resources/perfils/'.$userSend->getUsername().'/profile.png';

        $html = $this->container->get('user_repository')->getNotifications($idSend);
        return $this->container->get('view')
            ->render($response,'notifications.twig',
                ['notification' => $html,'srcProfileImg' =>$pathSend,  'user' => $userSend->getNom(),'name'=> $userSend->getNom(),'username'=> $userSend->getUsername(),'surname'=> $userSend->getSurname(), 'email'=> $userSend->getEmail(), 'birthDate'=> $userSend->getBirthDate()]);

    }

}