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

     public function toSharedRoot (Request $request, Response $response){

         $_SESSION['currentSharedFolder'] = $_SESSION['motherFolder'];

         return $response->withStatus(302)->withHeader('Location','/dashboard');

     }

     public function addFile(Request $request, Response $response){


         $errors =[];

         $allowed_types =array('jpg','png','gif','pdf','md','txt' );



         if (empty($_FILES)){


             $errors["bullhit"] = "No files were introduced!";

             return $this->container->get('view')
                 ->render($response,'error.twig',['errors'=> $errors]);
         }
         for ($i = 0; $i < count($_FILES); $i++) {

             $auxerrors = false;
             $pos = "fitxerUpload".$i;
             $size = $_FILES[$pos]["size"];
             $name = $_FILES[$pos]['name'];

             if (!$name=="") {

                 if (strpos($name, '¿') !== FALSE) {
                     $errors["bullshit"] = "Has volgut penjar un fitxer amb un caràcter incompatible (¿) i no s'ha penjat. Els altres sí que s'han penjat correctament";
                     $auxerrors = true;
                 } else {


                     // Get the file extension
                     $extension = pathinfo($name, PATHINFO_EXTENSION);

                     // Search the array for the allowed file type

                     if (in_array($extension, $allowed_types, false) != true) {
                         $errors['extension'] = "Error when uploading " . $extension;
                         $auxerrors = true;
                     }

                     $filerepo = $this->container->get('file_repository');
                     $username = $filerepo->getUsernameFromId($_SESSION['id']);
                     $target_dir = "assets/resources/perfils";


                     if ($size > 2097152) {
                         $errors['file'] = 'One or more of your files were too big! Those will not be uploaded';
                         $auxerrors = true;

                     }

                     $bytes = $filerepo->getUsedBytes($_SESSION['id']);

                     if (($bytes+$size)>1073741824){
                         $errors['exceededSpace'] = 'You exceded your allowed space. One or more files will not be uploaded';
                         $auxerrors = true;

                     }

                     if(!$auxerrors) {

                        $target_file = $target_dir . "/" . $username . "/root/" . $_SESSION['currentFolder'] . "¿" . $name;
                         move_uploaded_file($_FILES[$pos]["tmp_name"], $target_file);
                         /** @var FileRepository $fileRepo * */
                         $item = new Item (null, $name, $_SESSION['currentFolder'], 1);
                         $ok = $filerepo->saveItem($item, $bytes+$size);

                         if (!$ok) {
                             $errors['itemExisteix'] = 'Already exists an item with the same name in the same folder. Please, change the name';
                         }
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

     public function addSharedFolder(Request $request, Response $response){


         $sharedFolder = $_SESSION['currentSharedFolder'];
         $filerepo = $this->container->get('file_repository');
         $curFolder = $filerepo->getFileNameFromId($sharedFolder);
         var_dump($curFolder);


         if (strcmp('root', $curFolder['nom']) != 0) {
                echo "OK";
             $item = new Item (null, $_POST['nom'],$sharedFolder,0);
             $ok = $this->container->get('file_repository')->saveSharedFolder($item);

             if ($ok == 0){
                 return $response->withStatus(302)->withHeader('Location','/dashboard');
             }else{
                 if ($ok == -1){
                     $errors['itemExisteix'] = 'Already exists an item with the same name in the same folder. Please, change the name';
                     return $this->container->get('view')->render($response,'error.twig', ['errors'=> $errors]);
                 }else{
                     $errors['role'] = 'You are not allowed to create folders here.';
                     return $this->container->get('view')->render($response,'error.twig', ['errors'=> $errors]);
                 }
             }

         } else {
             $errors['root'] = 'U cant add on shared root';
         }

         if (!empty($errors)){

             return $this->container->get('view')
                 ->render($response,'error.twig',['errors'=> $errors]);
         }

         return $response->withStatus(302)->withHeader('Location', '/dashboard');

     }

     public function addSharedFile(Request $request, Response $response)
     {

         $errors = [];

         $allowed_types = array('jpg', 'png', 'gif', 'pdf', 'md', 'txt');


         if (empty($_FILES)) {


             $errors["bullhit"] = "No has ficat fitxers, geni!";

             return $this->container->get('view')
                 ->render($response, 'error.twig', ['errors' => $errors]);
         }
         for ($i = 0; $i < count($_FILES); $i++) {

             $auxerrors = false;
             $pos = "fitxerUpload" . $i;
             $size = $_FILES[$pos]["size"];
             $name = $_FILES[$pos]['name'];

             if (!$name == "") {

                 if (strpos($name, '¿') !== FALSE) {
                     $errors["bullshit"] = "Has volgut penjar un fitxer amb un caràcter incompatible (¿) i no s'ha penjat. Els altres sí que s'han penjat correctament";
                     $auxerrors = true;

                 } else {


                     // Get the file extension
                     $extension = pathinfo($name, PATHINFO_EXTENSION);

                     // Search the array for the allowed file type

                     if (in_array($extension, $allowed_types, false) != true) {
                         $errors['extension'] = "Error when uploading " . $extension;
                         $auxerrors = true;

                     }

                     $sharedFolder = $_SESSION['currentSharedFolder'];
                     $filerepo = $this->container->get('file_repository');
                     $curFolder = $filerepo->getFileNameFromId($sharedFolder);

                     if (strcmp('root', $curFolder['nom']) != 0) {

                         $filerepo = $this->container->get('file_repository');
                         $username = $filerepo->getUsernameFromSharedFolder($sharedFolder);

                         $target_dir = "assets/resources/perfils/".$username."/root/";


                         if ($size > 2097152) {
                             $errors['file'] = 'One or more of your files were too big! Those will not be uploaded';
                             $auxerrors = true;

                         }
                         var_dump($curFolder);
                         $userBytes = $filerepo->getIdFromParent($sharedFolder);
                         var_dump($userBytes);
                         $bytes = $filerepo->getUsedBytes($userBytes);

                         if (($bytes+$size)>1073741824){
                             $errors['exceededSpace'] = 'You exceded your allowed space. One or more files will not be uploaded';
                             $auxerrors = true;

                         }
                          if ($auxerrors == false){
                              $target_file = $target_dir.$_SESSION['currentSharedFolder']."¿".$name;

                              move_uploaded_file($_FILES[$pos]["tmp_name"], $target_file);

                              /** @var FileRepository $fileRepo * */
                              $item = new Item (null, $name, $_SESSION['currentSharedFolder'], 1);
                              $ok = $this->container->get('file_repository')->saveSharedFile($item, $bytes+$size);

                              if ($ok == -1) {
                                  $errors['itemExisteix'] = 'Already exists an item with the same name in the same folder. Please, change the name';
                                  $auxerrors = true;

                              }else{
                                  if ($ok == -2) {
                                      $errors['role'] = 'You are not allowed to create files here.';
                                      $auxerrors = true;

                                  }
                              }
                          }
                     } else {
                         $errors['root'] = 'U cant add on shared root';
                         $auxerrors = true;

                     }

                 }

             }
         }
         if (!empty($errors)) {

             return $this->container->get('view')
                 ->render($response, 'error.twig', ['errors' => $errors]);
         }

         return $response->withStatus(302)->withHeader('Location', '/dashboard');
     }

     public function deleteItem (Request $request, Response $response, array $arg){
         $id = $arg['id'];

         $item = $this->container->get('file_repository')->getItem($id);

         $ok = false;
         if ($item['type'] == 1){
             $ok = $this->container->get('file_repository')->deleteFile($item);
         }else{
             $ok = $this->container->get('file_repository')->deleteFolder($item);
         }

         return $response->withStatus(302)->withHeader('Location','/dashboard');

     }

     public function renameItem (Request $request, Response $response, array $arg){
         $id = $arg['id'];
         $rename = $arg['name'];

         $item = $this->container->get('file_repository')->getItem($id);

         $ok = false;
         if ($item['type'] == 1){
             $ok = $this->container->get('file_repository')->renameFile($item, $rename);
         }else{
             $ok = $this->container->get('file_repository')->renameFolder($item, $rename);
         }

         return $response->withStatus(302)->withHeader('Location','/dashboard');

     }

     public function downloadItem (Request $request, Response $response, array $arg){
         $id = $arg['id'];

         $username = $this->container->get('file_repository')->getUsernameFromSharedFolder($id);
         $filename = $this->container->get('file_repository')->getFileNameFromId($id);
         $path = "assets/resources/perfils/".$username."/root/";

         $files = scandir($path);


         foreach ($files as $file) {

             $nom = explode("¿",$file);
             if ($nom[0] != '.' && $nom[0] != '..'){
                 if (strcmp($nom[1],$filename['nom']) == 0){

                     rename( $path.$file, $path.$nom[1]);

                     header('Content-Description: File Transfer');
                     header('Content-Type: application/octet-stream');
                     header('Content-Disposition: attachment; filename="'.basename($path.$nom[1]).'"');
                     header('Expires: 0');
                     header('Cache-Control: must-revalidate');
                     header('Pragma: public');
                     header('Content-Length: ' . filesize($path.$nom[1]));
                     readfile($path.$nom[1]);

                     rename( $path.$nom[1],$path.$file);
                 }
             }

         }
     }

     public function shareFolder (Request $request, Response $response){

         $idFolder = $_POST['idFolder'];
         $email = $_POST['email'];
         $role = $_POST['role'];

         $ok = $this->container->get('file_repository')->shareFolder($idFolder, $email, $role, 0);

         return $response->withStatus(302)->withHeader('Location','/dashboard');
     }

 }