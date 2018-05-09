<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 5/4/2018
 * Time: 19:04
 */

//$app->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->get('/', 'PwBox\Controller\HelloController')->add('PwBox\Controller\Middleware\SessionMiddleware');

//TODO: Evitar $app->get(login), $app->get(register)

$app->post('/login', 'PwBox\Controller\PostUserController:loginCheck');

$app->get('/login', 'PwBox\Controller\PostUserController:loginCheck')->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->post('/register', 'PwBox\Controller\PostUserController:register');

$app->get('/dashboard', 'PwBox\Controller\DashboardController:dashboardPage')->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->post('/profile', 'PwBox\Controller\ProfileController:profilePage')->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->get('/updateUser', 'PwBox\Controller\UpdateUserController:updateUser')->add('PwBox\Controller\Middleware\SessionMiddleware');
