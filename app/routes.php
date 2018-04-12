<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 5/4/2018
 * Time: 19:04
 */


$app->get('/hello/{name}', 'PwBox\Controller\HelloController:indexAction')
->add('PwBox\Controller\Middleware\ExampleMiddleware');

$app->post(
    '/user', 'PwBox\Controller\PostUserController'
)
;