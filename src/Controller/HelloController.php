<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 11/4/2018
 * Time: 19:04
 */

namespace PwBox\Controller;

use Dflydev\FigCookies\SetCookie;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookies;

class  HelloController{
    protected $container;

    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;

    }

    public function indexAction(Request $request, Response $response, array $arg){
        $name = $arg['name'];
        return $this->container->get('view')->render($response, 'home.twig',['name' => $name]);
    }

    public function __invoke(Request $request, Response $response, array $arg)
    {


        /*
        $cookie = FigRequestCookies::get($request, 'advice',0);
        if(empty($cookie->getValue())){
            $response = FigResponseCookies::set($response, SetCookie::create('advice')
                ->withValue(1)
                ->withPath('/')
                ->withDomain('pwbox.test')
            );
        }
        return $this->container->get('view')->render(
            $response,
            'home.twig',
            ['counter'=>$_SESSION['counter'],
            'advice'=>$cookie->getValue()]);
        */
    }


}
