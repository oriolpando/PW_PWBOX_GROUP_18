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

        //canviar path depenent de qui siguis
        $path = "assets/resources/perfils/carla/root";
        $path    = "assets/resources/perfils/miquelator/root/";
        $files = scandir($path);

        $folders = [];

        echo ("yooo ".sizeof($files));
        for($i=0;$i<sizeof($files);$i++){
            if (is_dir($path.$files[$i])){
                $folders[$files[$i]] = $files[$i];
            }
        }


        return $this->container->get('view')
            ->render($response,'dashboard.twig', ['folders'=>$folders]);
    }

}