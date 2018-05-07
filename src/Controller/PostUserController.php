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

        $username = $_POST['username'];
        $email = $_POST['email'];
        $date = $_POST['birth'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];



        $errors = [];


        if (strlen($username)>20||!ctype_alnum($username)){
            $errors['username'] = 'invalid user';
        }

        if (strlen($password)>12||strlen($password)<6||!preg_match('/^[A-Za-z0-9]*([A-Z][A-Za-z0-9]*\d|\d[A-Za-z0-9]*[A-Z])[A-Za-z0-9]*$/',$password)){
            $errors['password'] = 'invalid password';
        }

        //WHY DON'T YOU WORK???
       /* if(!strcmp($confirmPassword, $password)){
            echo($password);
            echo($confirmPassword);
            echo(" ".strcmp($confirmPassword, $password));
            $errors['confirmPassword'] = 'Confirm password field does not match up';

        }*/

        if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
            $errors['birth'] = 'wrong birth';
        }


        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors['email'] = 'invalid email';
        }
        if (!empty($errors)) {
            return $this->container->get('view')
                ->render($response,'home.twig',['errors'=> $errors]);
        }

        $target_dir = "assets/resources/imatges/perfils";

        if( !empty($_FILES["image"])){


            echo ("image not empty");
           if($_FILES["image"]["size"]>500000){
               $errors['image'] = 'image too big';

           }else{


               mkdir($target_dir."/".$username, 0777, TRUE);
               $target_file = $target_dir."/".$username."/"."profile.png";
               echo "\nfinal: ".$target_file;

               if (move_uploaded_file( $_FILES["image"]["tmp_name"], $target_file)) {
                   echo "The file ". basename( $_FILES["image"]["name"]). " has been uploaded. ";
               } else {
                   echo "Sorry, there was an error uploading your file.";
               }

           }

       }else{
            echo("images empty");
        }





        $user = new User($_POST['name'],$_POST['surname'],$username,$email,$_POST['password'],$_POST['birth']);
        try {
            /** @var UserRepository $userRepo */
            $this->container->get('user_repository')->save($user);

            $messages = $this->container->get('flash')->getMessages();
            $registerMessages = isset($messages['register'])?$messages['register']:[];

            return $this->container->get('view')
                ->render($response,'dashboard.twig',['messages'=> $registerMessages]);
        }catch (\Exception $e) {
            echo $e->getMessage();
        }

    }

    public function loginCheck(Request $request, Response $response)
    {

        var_dump($_POST);

        //TODO: COMPROVAR LOGIN

        $exists = $this->container->get('user_repository')->tryLogin($_POST['title'], $_POST['passwordLogin']);;

        if ($exists == -1){
            return $this->container->get('view')->render($response,'home.twig');
        }else{
            return $this->container->get('view')->render($response,'dashboard.twig');
        }

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