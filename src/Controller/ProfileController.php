<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 7/5/2018
 * Time: 12:59
 */

namespace PwBox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PwBox\Model\User;
use PwBox\Model\UserRepository;


class ProfileController
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function profilePage(Request $request, Response $response)
    {

        $id = $_SESSION['id'];

        $user = $this->container->get('user_repository')->getUser($id);

        $path = 'assets/resources/perfils/'.$user->getUsername().'/profile.png';

        if (file_exists($path)) {
            echo "The file $path exists";
        } else {
            echo "The file $path does not exist";
        }

        return $this->container->get('view')
            ->render($response,'profile.twig',
                ['srcProfileImg' =>$path, 'user' => $user->getNom().' '.$user->getSurname(),'srcProfileImg'=> $path,'name'=> $user->getNom(),'username'=> $user->getUsername(),'surname'=> $user->getSurname(), 'email'=> $user->getEmail(), 'birthDate'=> $user->getBirthDate()]);
    }
}
