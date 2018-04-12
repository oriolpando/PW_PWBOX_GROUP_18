<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 12/4/2018
 * Time: 21:01
 */

namespace PwBox\Controller;


use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class PostUserController
{
    /** @var ContainerInterface */
    private $container;

    /**
     * PostUserController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response)
    {
        try{
            $data = $request->getParsedBody();
            $service = $this->container->get('post_user_use_case');
            $service($data);
            echo "lol";
        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-Type', 'text/html')
                ->write($e->getMessage());
        }
        return $response;
    }

}