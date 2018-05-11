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
use PwBox\Model\FileRepository;
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

    public function register(Request $request, Response $response)
    {

        $name = $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $date = $_POST['birth'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        $errors = [];

        if($this->container->get('user_repository')->checkIfUserExists($username, $email)){
            $errors['login'] = 'the username or the mail already exist';
        }

        if (empty($name)){
            $errors['name'] = 'invalid user';
        }


        if (empty($username)||strlen($username)>20||!ctype_alnum($username)){
            $errors['username'] = 'invalid user';
        }

        if (empty($password)||strlen($password)>12||strlen($password)<6||!preg_match('/^[A-Za-z0-9]*([A-Z][A-Za-z0-9]*\d|\d[A-Za-z0-9]*[A-Z])[A-Za-z0-9]*$/',$password)){
            $errors['password'] = 'invalid password';
        }

        if(strcmp($confirmPassword, $password) != 0){
            echo($password);
            echo($confirmPassword);
            echo(" ".strcmp($confirmPassword, $password));
            $errors['confirmPassword'] = 'Confirm password field does not match up';

        }

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

        $target_dir = "assets/resources/perfils";

        if( !empty($_FILES["image"])){

            if($_FILES["image"]["size"]>500000){
               $errors['image'] = 'image too big';

           }else{

               mkdir($target_dir."/".$username, 0777, TRUE);
               mkdir($target_dir."/".$username."/root", 0777, TRUE);
               $target_file = $target_dir."/".$username."/"."profile.png";

               if (move_uploaded_file( $_FILES["image"]["tmp_name"], $target_file)) {
                   echo "The file ". basename( $_FILES["image"]["name"]). " has been uploaded. ";
               }

           }

       }else{
            echo("images empty");
            $target_file = $target_dir."/".$username."/"."profile.png";

            move_uploaded_file( "assets/resources/user.png", $target_file);
            echo "Default photo assigned";
        }




        $user = new User
        (null,
            $_POST['name'],
            $_POST['surname'],
            $username,$email,
            $_POST['password'],
            $_POST['birth'],
            null
        );
        try {
            /** @var UserRepository $userRepo */
            $this->container->get('user_repository')->save($user);
            $_SESSION['id'] = $this->container->get('user_repository')->getId($username);

            $id_motherfolder = $this->container->get('file_repository')->iniciaFolder();


            $this->container->get('user_repository')->setMotherFolder($id_motherfolder);


            $messages = $this->container->get('flash')->getMessages();
            $registerMessages = isset($messages['register'])?$messages['register']:[];

            $_SESSION['currentFolder'] = $id_motherfolder;


            return $this->container->get('view')
                ->render($response,'dashboard.twig',['messages'=> $registerMessages]);
        }catch (\Exception $e) {
            echo $e->getMessage();
        }

    }

    public function loginCheck(Request $request, Response $response)
    {

        $username = $_POST['title'];
        $password = $_POST['passwordLogin'];

        $erroUs = 0;
        $erroMail = 0;

        if (empty($username)||strlen($username)>20||!ctype_alnum($username)){
            $erroUs = 1;
        }

        if (empty($password)||strlen($password)>12||strlen($password)<6||!preg_match('/^[A-Za-z0-9]*([A-Z][A-Za-z0-9]*\d|\d[A-Za-z0-9]*[A-Z])[A-Za-z0-9]*$/',$password)){
            $errors['password'] = 'invalid password';
        }

        if(!filter_var($username, FILTER_VALIDATE_EMAIL)){
            $erroMail = 1;
        }

        if ($erroMail == 1 & $erroUs == 1){
            $errors['user&mail'] = 'invalid username or mail';
        }
        if (!empty($errors)) {
            return $this->container->get('view')
                ->render($response,'home.twig',['errors'=> $errors]);
        }

        $errors = [];
        var_dump($_POST);

        $id = [];
        $id = $this->container->get('user_repository')->tryLogin($_POST['title'], $_POST['passwordLogin']);

        if ($id[0] == -1){
            //Username o email no existeix a bbdd
            $errors['notexistent'] = 'The username or the email do not exist';
            return $this->container->get('view')->render($response,'home.twig',['errors'=> $errors]);
        }else{
            if ($id[0] == -2){
                //Contrasenya incorrecta

                $errors['password'] = 'Incorrect password';
                return $this->container->get('view')->render($response,'home.twig',['errors'=> $errors]);

            }else{

                /** @var FileRepository $fileRepo **/

                $_SESSION['currentFolder'] = $id[1];

                $_SESSION['id'] = $id[0];

                return $response->withStatus(302)->withHeader('Location','/dashboard');

                //return $this->container->get('view')->render($response,'dashboard.twig');

            }
        }
    }

    public function logOut(Request $request, Response $response)
    {

        session_destroy();
        return $response->withStatus(302)->withHeader('Location','/');
    }

}