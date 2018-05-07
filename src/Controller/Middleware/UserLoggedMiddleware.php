<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 11/4/2018
 * Time: 19:31
 */

namespace PwBox\Controller\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class UserLoggedMiddleware {

    private $id;

    public function __invoke(Request $request, Response $response, callable $next){

        // Start the session
        session_start();

        // Set id variable
        $_SESSION["id"] = $_POST['name'];

        /*
        if (!isset($_SESSION['user_id'])){
            return $response->withStatus(302)->withHeader('Location','/login');
        }*/
        return $response->getBody()->write("El id guardat és " . $_SESSION["id"] . ".<br>") ;

        return $next($request, $response);

    }
}
