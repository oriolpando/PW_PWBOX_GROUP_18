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
            $email=$_REQUEST['email'];
            $password=$_REQUEST['psw'];
            $confirmPassword=$_REQUEST['pswConf'];



            if (empty($password)||strlen($password)>12||strlen($password)<6||!preg_match('/^[A-Za-z0-9]*([A-Z][A-Za-z0-9]*\d|\d[A-Za-z0-9]*[A-Z])[A-Za-z0-9]*$/',$password)){
                $errors['password'] = 'invalid password';
            }

            if(strcmp($confirmPassword, $password) != 0){
                $errors['confirmPassword'] = 'Confirm password field does not match up';

            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $errors['email'] = 'invalid email';
            }

            if ($this->container->get('user_repository')->checkIfEmailExists($email)){
                $errors['exists'] = 'This email already exists';
            }

            if (empty($errors)) {

                $username = $this->container->get('user_repository')->getUser($_SESSION['id']);
                $target_file = '';
                if( !empty($_FILES["img"])) {
                    $target_file = "assets/resources/perfils/" .$username->getUsername()."/profile.png";


                    if ($_FILES["img"]["size"] > 62500) {
                        $errors['img'] = 'image too big';
                    } else {

                        if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
                            $errors['uploaded'] = "The file " . basename($_FILES["img"]["name"]) . " has been uploaded. ";
                        }
                    }
                }

                $passwordHashed = password_hash($password,PASSWORD_DEFAULT);

                $exists = $this->container->get('user_repository')->updateUser($email, $passwordHashed);




                echo $target_file;
            }else{
                return $response->withStatus(500);
            }

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
        $error['deletedUs'] = 'The user has been deletd';
        session_destroy();
        return $this->container->get('view')->render($response, 'error.twig',['errors' => $error]);

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