<?php

 namespace PwBox\Controller;

 use Psr\Container\ContainerInterface;
 use Psr\Http\Message\ServerRequestInterface as Request;
 use Psr\Http\Message\ResponseInterface as Response;
 use PwBox\Model\User;
 use PwBox\Model\Item;

 use PwBox\Model\UserRepository;

 class FolderController
 {
     /** @var ContainerInterface */
     private $container;

     public function __construct(ContainerInterface $container)
     {
         $this->container = $container;
     }

     public function addFolder (Request $request, Response $response){

         /** @var FileRepository $fileRepo **/
        $item = new Item (null, $_POST['nom'],$_SESSION['currentFolder'],0);
        $ok = $this->container->get('file_repository')->saveItem($item);

        if ($ok){
            return $response->withStatus(302)->withHeader('Location','/dashboard');
        }else{
            $errors['itemExisteix'] = 'Already exists an item with the same name in the same folder. Please, change the name';
            return $this->container->get('view')
                ->render($response,'dashboard.twig', ['errors'=> $errors]);
        }
     }

     public function enterFolder (Request $request, Response $response, array $arg){
         $id = $arg['id'];

        $_SESSION['currentFolder'] = $id;

        return $response->withStatus(302)->withHeader('Location','/dashboard');

     }

     public function addFile(Request $request, Response $response){

         $errors =[];

         $allowed_types =array('jpg','png','gif','pdf','md','txt' );
         $name = $_FILES['uploadFile']['name'];
         $error = null;

    // Get the file extension
    $extension = pathinfo($name, PATHINFO_EXTENSION);

    // Search the array for the allowed file type

    if (in_array($extension, $allowed_types, false) != true) {
        $errors['extension'] = "Error when uploading ". $extension;

    }




         $filerepo = $this->container->get('file_repository');
         $username = $filerepo->getUsernameFromId($_SESSION['id']);
         $target_dir = "assets/resources/perfils";



             if($_FILES["uploadFile"]["size"]>2000000){
                 $errors['file'] = 'file too big';

             }
             if(!empty($errors)){
                 return $this->container->get('view')
                     ->render($response,'dashboard.twig', ['errors'=> $errors]);

             }




         /** @var FileRepository $fileRepo **/
         $item = new Item (null, $name , $_SESSION['currentFolder'],1);
         $ok = $this->container->get('file_repository')->saveItem($item);

         if ($ok){
             return $response->withStatus(302)->withHeader('Location','/dashboard');
         }else{
             $errors['itemExisteix'] = 'Already exists an item with the same name in the same folder. Please, change the name';
             return $this->container->get('view')
                 ->render($response,'dashboard.twig', ['errors'=> $errors]);
         }
     }









     public function toRoot (Request $request, Response $response){

         var_dump($_SESSION);
         $_SESSION['currentFolder'] = $_SESSION['motherFolder'];


     }

 }