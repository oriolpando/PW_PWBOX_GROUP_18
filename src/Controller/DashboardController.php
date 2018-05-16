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
        $html = '<p>You don\'t have any owned file</p>';


        if (!empty($showItems)){
            $html = '<div id = "items">';
            foreach ($showItems as $item){


                if ($item['type'] == 0){
                    $html = $html.'<div>';
                    $html = $html.'<label>'.$item['nom'].'</label><a class="CMove" ondblclick = "enterFolder('.$item['id'].')">
                        <img src="/assets/resources/folder.png" name="'.$item['id'].'" width = 60px height = 60px></a>'
                        .'<button type="button" class="btn btn-danger" onclick="shareItem('.$item['id'].')">Share</button>'
                        .'<button type="button" class="btn btn-danger" onclick="renameItem('.$item['id'].')">Rename</button>'
                        .'<button type="button" class="btn btn-danger" onclick="deleteItem('.$item['id'].')">Delete</button>';
                    $html = $html.'</div>';
                }else{
                    $html = $html.'<div>';
                    $html = $html.'<label>'.$item['nom'].'</label><img src="/assets/resources/file.png" name="'.$item['id'].'" width = 60px height = 60px>'
                        .'<button type="button" class="btn btn-danger" onclick="downloadItem('.$item['id'].')">Download</button>'
                        .'<button type="button" class="btn btn-danger" onclick="renameItem('.$item['id'].')">Rename</button>'
                        .'<button type="button" class="btn btn-danger" onclick="deleteItem('.$item['id'].')">Delete</button>';
                    $html = $html.'</div>';
                }

            }
            $html = $html.'</div>';
        }

        $html2 = '<p>You don\'t have any shared file</p>';
        $folderName = $this->container->get('file_repository')->getFileNameFromId($_SESSION['currentSharedFolder']);
        var_dump($folderName);
        if (!empty($folderName)){
            if (strcmp('root', $folderName['nom'])== 0){
                $showItems2 = $this->container->get('file_repository')->getRootSharedItems();
                var_dump($showItems2);

                if (!empty($showItems2)){
                    $html2 = '<div id = "sharedItems">';
                    foreach ($showItems2 as $item2){
                        var_dump($item2);
                        $role = $this->container->get('file_repository')->getRoleFromId($item2['id_folder']);
                        $idShared = $this->container->get('file_repository')->getFileNameFromId($item2['id_folder']);
                        if (strcmp('Admin', $role['role']) == 0){
                            $html2 = $html2.'<div>';
                            $html2 = $html2.'<label>'.$idShared['nom'].'</label><a class="CMove" ondblclick = "enterSharedFolder('.$item2['id_folder'].')">
                            <img src="/assets/resources/folder.png" name="'.$item2['id_folder'].'" width = 60px height = 60px></a>'
                                .'<button type="button" class="btn btn-danger" onclick="shareItem('.$item2['id_folder'].')">Share</button>'
                                .'<button type="button" class="btn btn-danger" onclick="renameItem('.$item2['id_folder'].')">Rename</button>'
                                .'<button type="button" class="btn btn-danger" onclick="deleteItem('.$item2['id_folder'].')">Delete</button>';
                            $html2 = $html2.'</div>';
                        }else{
                            $html2 = $html2.'<div>';
                            $html2 = $html2.'<label>'.$idShared['nom'].'</label><a class="CMove" ondblclick = "enterSharedFolder('.$item2['id_folder'].')">
                            <img src="/assets/resources/folder.png" name="'.$item2['id_folder'].'" width = 60px height = 60px></a>';
                            $html2 = $html2.'</div>';
                        }
                    }
                    $html2 = $html2.'</div>';

                }
            }else{
                $html2 = '<div id = "sharedItems">';
                $showItems2 = $this->container->get('file_repository')->getCurrentSharedItems();
                var_dump($showItems2);

                if (!empty($showItems2)){

                    foreach ($showItems2 as $itemFull){
                        if($itemFull['type'] == 0){
                            $role = $this->container->get('file_repository')->getRoleFromId($itemFull['id']);
                            if (strcmp('Admin', $role['role']) == 0){
                                $html2 = $html2.'<div>';
                                $html2 = $html2.'<label>'.$itemFull['nom'].'</label><a class="CMove" ondblclick = "enterSharedFolder('.$itemFull['id'].')">
                                <img src="/assets/resources/folder.png" name="'.$itemFull['id'].'" width = 60px height = 60px></a>'
                                    .'<button type="button" class="btn btn-danger" onclick="shareItem('.$itemFull['id'].')">Share</button>'
                                    .'<button type="button" class="btn btn-danger" onclick="renameItem('.$itemFull['id'].')">Rename</button>'
                                    .'<button type="button" class="btn btn-danger" onclick="deleteItem('.$itemFull['id'].')">Delete</button>';
                                $html2 = $html2.'</div>';
                            }else{
                                $html2 = $html2.'<div>';
                                $html2 = $html2.'<label>'.$itemFull['nom'].'</label><a class="CMove" ondblclick = "enterSharedFolder('.$itemFull['id'].')">
                                <img src="/assets/resources/folder.png" name="'.$itemFull['id'].'" width = 60px height = 60px></a>';
                                $html2 = $html2.'</div>';
                            }
                        }else{
                            $role = $this->container->get('file_repository')->getRoleFromId($itemFull['id']);
                            if (strcmp('Admin', $role['role']) == 0){
                                $html2 = $html2.'<div>';
                                $html2 = $html2.'<label>'.$itemFull['nom'].'</label><img src="/assets/resources/file.png" name="'.$itemFull['id'].'" width = 60px height = 60px>'
                                    .'<button type="button" class="btn btn-danger" onclick="downloadItem('.$itemFull['id'].')">Download</button>'
                                    .'<button type="button" class="btn btn-danger" onclick="renameItem('.$itemFull['id'].')">Rename</button>'
                                    .'<button type="button" class="btn btn-danger" onclick="deleteItem('.$itemFull['id'].')">Delete</button>';
                                $html2 = $html2.'</div>';
                            }else{
                                $html2 = $html2.'<div>';
                                $html2 = $html2.'<label>'.$itemFull['nom'].'</label><img src="/assets/resources/file.png" name="'.$itemFull['id'].'" width = 60px height = 60px>'
                                    .'<button type="button" class="btn btn-danger" onclick="downloadItem('.$itemFull['id'].')">Download</button>';
                                $html2 = $html2.'</div>';
                            }

                        }

                    }
                }
                $html2 = $html2.'</div>';
            }
        }

        $idSend = $_SESSION['id'];

        $userSend = $this->container->get('user_repository')->getUser($idSend);

        $pathSend = 'assets/resources/perfils/'.$userSend->getUsername().'/profile.png';
        var_dump($html2);

        return $this->container->get('view')
            ->render($response,'dashboard.twig',
                ['srcProfileImg' =>$pathSend, 'folders'=>$html, 'sharedFolders' => $html2, 'user' => $userSend->getNom(),'name'=> $userSend->getNom(),'username'=> $userSend->getUsername(),'surname'=> $userSend->getSurname(), 'email'=> $userSend->getEmail(), 'birthDate'=> $userSend->getBirthDate()]);
    }

}