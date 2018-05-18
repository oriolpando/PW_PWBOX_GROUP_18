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

    public function resendValidate(Request $request, Response $response){
        $torna = $this->container->get('user_repository')->getInfoValidate($_SESSION['id']);


        $name = $torna[0];
        $mail = $torna[1];

        $this->container->get('mail_repository')->sendValidate($_SESSION['id'],$name,$mail);

        return $response->withStatus(302)->withHeader('Location','/profile');

    }

    public function profilePage(Request $request, Response $response)
    {

        $id = $_SESSION['id'];

        $user = $this->container->get('user_repository')->getUser($id);

        $path = 'assets/resources/perfils/'.$user->getUsername().'/profile.png';
        $errors[]='';
        if (file_exists($path)) {
            $errors['existeix'] = "The file $path exists";
        } else {
            $errors['noexisteix'] = "The file $path does not exist";
        }

        $validate = null;
        if($this->container->get('user_repository')->checkValidate($id)){
            $validate ="hidden";
        }else{
            $validate = "";
        }


        $espai = $this->container->get("file_repository")->getUsedBytes($id);
        $percentatge = (($espai)/125000000)*100;
        $espai =$espai/1000000;

        return $this->container->get('view')
            ->render($response,'profile.twig',
                ['percentatge'=>$percentatge, 'espai'=>$espai,'validate'=>$validate, 'srcProfileImg' =>$path, 'user' => $user->getNom().' '.$user->getSurname(),'srcProfileImg'=> $path,'name'=> $user->getNom(),'username'=> $user->getUsername(),'surname'=> $user->getSurname(), 'email'=> $user->getEmail(), 'birthDate'=> $user->getBirthDate()]);
    }
}
