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
        $ok = $this->container->get('file_repository')->saveItem($item, null);

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

     public function enterSharedFolder (Request $request, Response $response, array $arg){
         $id = $arg['id'];

         $_SESSION['currentSharedFolder'] = $id;

         return $response->withStatus(302)->withHeader('Location','/dashboard');

     }

     public function toRoot (Request $request, Response $response){

         $_SESSION['currentFolder'] = $_SESSION['motherFolder'];


         return $response->withStatus(302)->withHeader('Location','/dashboard');

     }

     public function addFile(Request $request, Response $response){

         $errors =[];

         $allowed_types =array('jpg','png','gif','pdf','md','txt' );



         if (empty($_FILES)){


             $errors["bullhit"] = "No has ficat fitxers, geni!";

             return $this->container->get('view')
                 ->render($response,'error.twig',['errors'=> $errors]);
         }
         for ($i = 0; $i < count($_FILES); $i++) {

             $pos = "fitxerUpload".$i;

             $name = $_FILES[$pos]['name'];

             if (!$name=="") {

                 if (strpos($name, '&') !== FALSE) {
                     $errors["bullshit"] = "Has volgut penjar un fitxer amb un caràcter incompatible (&) i no s'ha penjat. Els altres sí que s'han penjat correctament";

                 } else {


                     // Get the file extension
                     $extension = pathinfo($name, PATHINFO_EXTENSION);

                     // Search the array for the allowed file type

                     if (in_array($extension, $allowed_types, false) != true) {
                         $errors['extension'] = "Error when uploading " . $extension;

                     }

                     $filerepo = $this->container->get('file_repository');
                     $username = $filerepo->getUsernameFromId($_SESSION['id']);
                     $target_dir = "assets/resources/perfils";


                     if ($_FILES[$pos]["size"] > 2097152) {
                         $errors['file'] = 'file too big';

                         return $this->container->get('view')
                             ->render($response, 'error.twig', ['errors' => $errors]);

                     }
                     /* if (!empty($errors)) {
                          return $this->container->get('view')
                              ->render($response, 'dashboard.twig', ['errors' => $errors]);

                      }*/

                     $target_file = $target_dir . "/" . $username . "/root/" . $_SESSION['currentFolder'] . "&" . $name;

                     move_uploaded_file($_FILES[$pos]["tmp_name"], $target_file);

                     /** @var FileRepository $fileRepo * */
                     $item = new Item (null, $name, $_SESSION['currentFolder'], 1);
                     $ok = $this->container->get('file_repository')->saveItem($item, $_FILES[$pos]["size"]);

                     if (!$ok) {

                         $errors['itemExisteix'] = 'Already exists an item with the same name in the same folder. Please, change the name';

                     }

                 }
             }

         }

         if (!empty($errors)){

             return $this->container->get('view')
                 ->render($response,'error.twig',['errors'=> $errors]);
         }

         return $response->withStatus(302)->withHeader('Location', '/dashboard');

     }

     public function deleteItem (Request $request, Response $response, array $arg){
         $id = $arg['id'];

         $_SESSION['currentFolder'] = $id;

         return $response->withStatus(302)->withHeader('Location','/dashboard');

     }

     public function downloadItem (Request $request, Response $response, array $arg){
         $id = $arg['id'];

         $username = $this->container->get('file_repository')->getUsernameFromId($_SESSION['id']);
         $filename = $this->container->get('file_repository')->getFileNameFromId($id);
         $path = "assets/resources/perfils/".$username."/root/";

         $files = scandir($path);


         foreach ($files as $file) {

             $nom = explode("&",$file);
             if ($nom[0] != '.' && $nom[0] != '..'){
                 if (strcmp($nom[1],$filename['nom']) == 0){
                     var_dump($filename);
                     var_dump($file);
                     var_dump($nom);
                     var_dump(basename($path.$nom[1]));
                     var_dump(basename($path.$nom[1]));

                     rename( $path.$file, $path.$nom[1]);

                     header('Content-Description: File Transfer');
                     header('Content-Type: application/octet-stream');
                     header('Content-Disposition: attachment; filename="'.basename($path.$nom[1]).'"');
                     header('Expires: 0');
                     header('Cache-Control: must-revalidate');
                     header('Pragma: public');
                     header('Content-Length: ' . filesize($path.$nom[1]));
                     var_dump($path.$nom[1]);
                     readfile($path.$nom[1]);

                     //rename( $path.$nom[1], $path.$file);

                 }
             }

         }

         echo "AAA";
     }

     public function shareFolder (Request $request, Response $response){

         $idFolder = $_POST['idFolder'];
         $email = $_POST['email'];
         $role = $_POST['role'];

         $ok = $this->container->get('file_repository')->shareFolder($idFolder, $email, $role, 0);

         return $response->withStatus(302)->withHeader('Location','/dashboard');
     }

 }