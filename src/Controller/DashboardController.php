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
        $html = '<div id = "items">';


        if (!empty($showItems)){
            foreach ($showItems as $item){




                if ($item['type'] == 0){
                    $html = $html.'<div>';
                    $html = $html.'<label>'.$item['nom'].'</label><a class="CMove" ondblclick = "enterFolder()"><img src="/assets/resources/folder.png" name="'.$item['id'].'" width = 60px height = 60px></a>';
                    $html = $html.'</div>';
                }else{
                    $html = $html.'<div>';
                    $html = $html.'<label>'.$item['nom'].'</label><img src="/assets/resources/file.png" name="'.$item['id'].'" width = 60px height = 60px>'
                        .'<button type="button" class="btn btn-danger" onclick="enterFolder('.$item['id'].')">Enter</button>';
                    $html = $html.'</div>';
                }

            }
        }
        $html = $html.'</div>';


        return $this->container->get('view')->render($response,'dashboard.twig', ['folders'=>$html]);
    }

}