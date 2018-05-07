<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 5/4/2018
 * Time: 19:04
 */

$app->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->get('/hello/{name}', 'PwBox\Controller\HelloController')
->add('PwBox\Controller\Middleware\UserLoggedMiddleware');

$app->get('/', 'PwBox\Controller\HelloController');

$app->post('/login', 'PwBox\Controller\PostUserController:loginCheck');

$app->post('/register', 'PwBox\Controller\PostUserController:register');

//$app->post('/inserir', 'PwBox\Controller\PostUserController:controlSession');

$app->post('/profile', 'PwBox\Controller\ProfileController:profilePage');