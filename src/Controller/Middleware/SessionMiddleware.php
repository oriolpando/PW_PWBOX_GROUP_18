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

class SessionMiddleware{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        //start session
      //  session_start();

        //Session id
      //  $id = $this->id;
       // $_SESSION["id"] = $id;

       /* if (empty($id)) {
            $cookies = $request->getCookieParams();
                $id = $cookies[$id];
        }
        if (!empty($id)) {
            session_id($id);
        }*/

        return $next($request, $response);

    }
}
