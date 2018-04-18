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

class ExampleMiddleware{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $next($request, $response);

        return $response;
    }
}
