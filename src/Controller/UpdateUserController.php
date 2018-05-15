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

        try{
            $email=$_GET['email'];
            $password=$_GET['psw'];
            $confirmPassword=$_GET['pswConf'];
            $img = $_GET['image'];

            echo "hello".$_FILES["image"];



            if (empty($password)||strlen($password)>12||strlen($password)<6||!preg_match('/^[A-Za-z0-9]*([A-Z][A-Za-z0-9]*\d|\d[A-Za-z0-9]*[A-Z])[A-Za-z0-9]*$/',$password)){
                $errors['password'] = 'invalid password';
            }

            if(strcmp($confirmPassword, $password) != 0){
                echo($password);
                echo($confirmPassword);
                echo(" ".strcmp($confirmPassword, $password));
                $errors['confirmPassword'] = 'Confirm password field does not match up';

            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $errors['email'] = 'invalid email';
            }

            if (!empty($errors)) {
                return $this->container->get('view')
                    ->render($response,'profile.twig',['errors'=> $errors]);
            }


            if( !empty($_FILES["image"])){


                if($_FILES["image"]["size"]>500000){
                    $errors['image'] = 'image too big';
                }else{

                /*
                    if (move_uploaded_file( $_FILES["image"]["tmp_name"], $target_file)) {
                        echo "The file ". basename( $_FILES["image"]["name"]). " has been uploaded. ";
                    }
                */

                }
            }else{
                echo("images empty");

                echo "Default photo assigned";
            }

            $passwordHashed = password_hash($password,PASSWORD_DEFAULT);

            $exists = $this->container->get('user_repository')->updateUser($email, $passwordHashed);

            return $response->withStatus(200);
        }catch (\Exception $e){
            return $response->withStatus(500);
        }

    }

    public function deleteUser(Request $request, Response $response)
    {

        $id = $_SESSION['id'];
        $user = $this->container->get('user_repository')->getUser($id);

        $dirPath = 'assets/resources/perfils/'.$user->getUsername();

        self::deleteDir($dirPath);

        $this->container->get('user_repository')->deleteUser();

        echo 'User deleted';
        session_destroy();
        return $this->container->get('view')
            ->render($response,'home.twig');

    }

    public static function deleteDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

}