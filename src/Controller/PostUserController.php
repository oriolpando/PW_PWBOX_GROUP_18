<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 12/4/2018
 * Time: 21:01
 */

namespace PwBox\Controller;


use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PwBox\Model\Implementation\DoctrineUserRepository;
use PwBox\Model\User;
use PwBox\Model\UserRepository;


class PostUserController
{
    /** @var ContainerInterface */
    private $container;

    /**
     * PostUserController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function indexAction(Request $request, Response $response)
    {
        $messages = $this->container->get('flash')->getMessages();
        $registerMessages = isset($messages['register'])?$messages['register']:[];

        return $this->container->get('view')
            ->render($response,'register.twig',['messages'=> $registerMessages]);
    }

    public function inserir(Request $request, Response $response)
    {

       var_dump($_POST);

       $user = new User($_POST['name'],$_POST['surname'],$_POST['username'],$_POST['email'],$_POST['$password'],$_POST['birth']);

        /** @var UserRepository $userRepo */
        $userRepo = $this->container->get('user_repository')->save($user);

        $messages = $this->container->get('flash')->getMessages();
        $registerMessages = isset($messages['register'])?$messages['register']:[];

        return $this->container->get('view')
            ->render($response,'prova.twig',['messages'=> $registerMessages]);
    }

    public function __invoke(Request $request, Response $response)
    {
        try{
            $data = $request->getParsedBody();
            $service = $this->container->get('post_user_use_case');
            $service($data);
            $this->container->get('flash')->addMessage('register','User registered.');

            return $response->withStatus(302)->withHeader('Location','/user');

        }catch (\Exception $e){

            return $this->container->get('view')->render($response,'register.twig', [
                'error'=>$e->getMessage(),
            ]);


        }
        return $response;
    }

}