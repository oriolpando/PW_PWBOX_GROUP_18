<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 3/5/2018
 * Time: 20:28
 */

namespace PwBox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PwBox\Model\User;
use PwBox\Model\UserRepository;

class DashboardController
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function dashboardPage(Request $request, Response $response)
    {


        $username = $this->container->get('file_repository')->getUsernameFromId($_SESSION['id']);


        $path    = "assets/resources/perfils/".$username."/root/";
        $files = scandir($path);

        $showItems = [];

        $showItems = $this->container->get('file_repository')->getCurrentItems();


        $html = '';
        if (!empty($showItems)){
            foreach ($showItems as $item){

                if ($item['type'] == 0){
                    $html = $html.'<div class="row" id="files"><div class="col-sm carpeta"><div class="col center">';
                    $html = $html.'<a class="CMove" ondblclick = "enterFolder('.$item['id'].')"><img src="/assets/resources/folder.png" name="'.$item['id'].'" width = 60px height = 60px></a>'
                        .'</div><div class="col center"><label class="nameFolder">'.$item['nom'].'</label></div></div><div class="col-sm iep sizeF">'
                        .'<button class="bttn-minimal" type="button" onclick="shareItem('.$item['id'].')">Share</button></div><div class="col-sm iep sizeF">'
                        .'<button class="bttn-minimal" type="button"  onclick="renameItem('.$item['id'].')">Rename</button></div><div class="col-sm iep sizeF">'
                        .'<button class="bttn-minimal" type="button"  onclick="deleteItem('.$item['id'].')">Delete</button></div><div class="col-sm iep sizeF">'
                        .'<div class="col-sm iep">'
                        .'<button class="bttn-minimal nonButton" disabled></button></div></div></div>';
                }else{
                    $html = $html.'<div class="row" id="files"><div class="col-sm carpeta"><div class="col center">';
                    $html = $html.'<img src="/assets/resources/file.png" name="'.$item['id'].'" width = 60px height = 60px></div><div class="col center">'
                        .'<label class="nameFolder">'.$item['nom'].'</label></div></div><div class="col-sm iep">'
                        .'<button type="button" class="bttn-minimal" onclick="downloadItem('.$item['id'].')">Download</button></div><div class="col-sm iep">'
                        .'<button type="button" class="bttn-minimal" onclick="renameItem('.$item['id'].')">Rename</button></div><div class="col-sm iep">'
                        .'<button type="button" class="bttn-minimal" onclick="deleteItem('.$item['id'].')">Delete</button></div><div class="col-sm iep">'
                        .'</div></div>';
                }
            }
        }else{
            $html = '<h5>You don\'t have any owned file here.</h5>';
        }

        $html2 = '';
        $folderName = $this->container->get('file_repository')->getFileNameFromId($_SESSION['currentSharedFolder']);
        if (!empty($folderName)){
            if (strcmp('root', $folderName['nom'])== 0){
                $showItems2 = $this->container->get('file_repository')->getRootSharedItems();

                if (!empty($showItems2)){
                    foreach ($showItems2 as $item2){
                        $role = $this->container->get('file_repository')->getRoleFromId($item2['id_folder']);
                        $idShared = $this->container->get('file_repository')->getFileNameFromId($item2['id_folder']);
                        if (strcmp('Admin', $role['role']) == 0){
                            $html2 = $html2.'<div class="row" id="files"><div class="col-sm carpeta"><div class="col center">';
                            $html2 = $html2.'<a class="CMove" ondblclick = "enterSharedFolder('.$item2['id_folder'].')"><img src="/assets/resources/folder.png" name="'.$item2['id_folder'].'" width = 60px height = 60px></a>'
                                .'</div><div class="col center"><label class="nameFolder">'.$idShared['nom'].'</label></div></div><div class="col-sm iep sizeF">'
                                .'<button class="bttn-minimal" type="button" onclick="shareItem('.$item2['id_folder'].')">Share</button></div><div class="col-sm iep sizeF">'
                                .'<button class="bttn-minimal" type="button"  onclick="renameItem('.$item2['id_folder'].')">Rename</button></div><div class="col-sm iep sizeF">'
                                .'<button class="bttn-minimal" type="button"  onclick="deleteItem('.$item2['id_folder'].')">Delete</button></div><div class="col-sm iep sizeF">'
                                .'<div class="col-sm iep">'
                                .'<button class="bttn-minimal nonButton" disabled></button></div></div></div>';
                        }else{
                            $html2 = $html2.'<div class="row" id="files"><div class="col-sm carpeta"><div class="col center">';
                            $html2 = $html2.'<a class="CMove" ondblclick = "enterSharedFolder('.$item2['id_folder'].')"><img src="/assets/resources/folder.png" name="'.$item2['id_folder'].'" width = 60px height = 60px></a>'
                                .'</div><div class="col center"><label class="nameFolder">'.$idShared['nom'].'</label></div></div><div class="col-sm iep sizeF">'
                                .'<div class="col-sm iep"><button class="bttn-minimal nonButton" disabled></button></div></div><div class="col-sm iep sizeF">'
                                .'<div class="col-sm iep"><button class="bttn-minimal nonButton" disabled></button></div></div><div class="col-sm iep sizeF">'
                                .'<div class="col-sm iep"><button class="bttn-minimal nonButton" disabled></button></div></div><div class="col-sm iep sizeF">'
                                .'<div class="col-sm iep">'
                                .'<button class="bttn-minimal nonButton" disabled></button></div></div></div>';

                        }
                    }

                }else{
                    $html2 = '<h5>You don\'t have any shared file here.</h5>';
                }
            }else{
                $html2 = '<div id = "sharedItems">';
                $showItems2 = $this->container->get('file_repository')->getCurrentSharedItems();

                if (!empty($showItems2)){

                    foreach ($showItems2 as $itemFull){
                        if($itemFull['type'] == 0){
                            $role = $this->container->get('file_repository')->getRoleFromId($itemFull['id']);
                            if (strcmp('Admin', $role['role']) == 0){
                                $html2 = $html2.'<div class="row" id="files"><div class="col-sm carpeta"><div class="col center">';
                                $html2 = $html2.'<a class="CMove" ondblclick = "enterSharedFolder('.$itemFull['id'].')"><img src="/assets/resources/folder.png" name="'.$itemFull['id'].'" width = 60px height = 60px></a>'
                                    .'</div><div class="col center"><label class="nameFolder">'.$itemFull['nom'].'</label></div></div><div class="col-sm iep sizeF">'
                                    .'<button class="bttn-minimal" type="button" onclick="shareItem('.$itemFull['id'].')">Share</button></div><div class="col-sm iep sizeF">'
                                    .'<button class="bttn-minimal" type="button"  onclick="renameItem('.$itemFull['id'].')">Rename</button></div><div class="col-sm iep sizeF">'
                                    .'<button class="bttn-minimal" type="button"  onclick="deleteItem('.$itemFull['id'].')">Delete</button></div><div class="col-sm iep sizeF">'
                                    .'<div class="col-sm iep">.<button class="bttn-minimal nonButton" disabled></button></div></div></div>';

                            }else{
                                $html2 = $html2.'<div class="row" id="files"><div class="col-sm carpeta"><div class="col center">';
                                $html2 = $html2.'<a class="CMove" ondblclick = "enterSharedFolder('.$itemFull['id'].')"><img src="/assets/resources/folder.png" name="'.$itemFull['id'].'" width = 60px height = 60px></a>'
                                    .'</div><div class="col center"><label class="nameFolder">'.$itemFull['nom'].'</label></div></div><div class="col-sm iep sizeF">'
                                .'<div class="col-sm iep"><button class="bttn-minimal nonButton" disabled></button></div></div><div class="col-sm iep sizeF">'
                                .'<div class="col-sm iep"><button class="bttn-minimal nonButton" disabled></button></div></div><div class="col-sm iep sizeF">'
                                .'<div class="col-sm iep"><button class="bttn-minimal nonButton" disabled></button></div></div><div class="col-sm iep sizeF">'
                                .'<div class="col-sm iep">'
                                .'<button class="bttn-minimal nonButton" disabled></button></div></div></div>';
                            }
                        }else{
                            $role = $this->container->get('file_repository')->getRoleFromId($itemFull['id']);
                            if (strcmp('Admin', $role['role']) == 0){

                                $html2 = $html2.'<div class="row" id="files"><div class="col-sm carpeta"><div class="col center">';
                                $html2 = $html2.'<img src="/assets/resources/file.png" name="'.$itemFull['id'].'" width = 60px height = 60px></div><div class="col center">'
                                    .'</div><div class="col center"><label class="nameFolder">'.$itemFull['nom'].'</label></div></div><div class="col-sm iep sizeF">'
                                    .'<button class="bttn-minimal" type="button" onclick="downloadItem('.$itemFull['id'].')">Download</button></div><div class="col-sm iep sizeF">'
                                    .'<button class="bttn-minimal" type="button"  onclick="renameItem('.$itemFull['id'].')">Rename</button></div><div class="col-sm iep sizeF">'
                                    .'<button class="bttn-minimal" type="button"  onclick="deleteItem('.$itemFull['id'].')">Delete</button></div><div class="col-sm iep sizeF">'
                                    .'<div class="col-sm iep"><button class="bttn-minimal nonButton" disabled></button></div></div></div>';
                            }else{
                                $html2 = $html2.'<div class="row" id="files"><div class="col-sm carpeta"><div class="col center">';
                                $html2 = $html2.'<img src="/assets/resources/file.png" name="'.$itemFull['id'].'" width = 60px height = 60px></div><div class="col center">'
                                    .'</div><div class="col center"><label class="nameFolder">'.$itemFull['nom'].'</label></div></div><div class="col-sm iep sizeF">'
                                    .'<button class="bttn-minimal" type="button" onclick="downloadItem('.$itemFull['id'].')">Download</button></div><div class="col-sm iep sizeF">'
                                    .'<button class="bttn-minimal nonButton" disabled></button></div><div class="col-sm iep sizeF">'
                                    .'<button class="bttn-minimal nonButton" disabled></button></div><div class="col-sm iep sizeF">'
                                    .'<div class="col-sm iep"><button class="bttn-minimal nonButton" disabled></button></div></div></div>';
                            }

                        }

                    }
                }else{
                    $html2 = '<h5>You don\'t have any shared file here.</h5>';
                }
                $html2 = $html2.'</div>';
            }
        }

        $idSend = $_SESSION['id'];

        $userSend = $this->container->get('user_repository')->getUser($idSend);

        $pathSend = 'assets/resources/perfils/'.$userSend->getUsername().'/profile.png';


        $espai = $this->container->get("file_repository")->getUsedBytes($idSend);
        $percentatge = (($espai)/125000000)*100;
        $espai =$espai/1000000;

        return $this->container->get('view')
            ->render($response,'dashboard.twig',
                ['espai'=>$espai,'percentatge' => $percentatge, 'srcProfileImg' =>$pathSend, 'folders'=>$html, 'sharedFolders' => $html2, 'user' => $userSend->getNom(),'name'=> $userSend->getNom(),'username'=> $userSend->getUsername(),'surname'=> $userSend->getSurname(), 'email'=> $userSend->getEmail(), 'birthDate'=> $userSend->getBirthDate()]);
    }

}