<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 5/4/2018
 * Time: 19:04
 */

//$app->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->get('/', 'PwBox\Controller\DashboardController:dashboardPage')->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->post('/login', 'PwBox\Controller\PostUserController:loginCheck');

$app->get('/login', 'PwBox\Controller\DashboardController:dashboardPage')->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->get('/logOut', 'PwBox\Controller\PostUserController:logOut');

$app->post('/register', 'PwBox\Controller\PostUserController:register');

$app->get('/notifications', 'PwBox\Controller\NotificationsController:getNotifications');

$app->get('/register', 'PwBox\Controller\DashboardController:dashboardPage')->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->get('/dashboard', 'PwBox\Controller\DashboardController:dashboardPage')->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->post('/profile', 'PwBox\Controller\ProfileController:profilePage');

$app->get('/profile', 'PwBox\Controller\ProfileController:profilePage')->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->post('/updateUser', 'PwBox\Controller\UpdateUserController:updateUser')->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->post('/addFolder', 'PwBox\Controller\FolderController:addFolder');

$app->get('/deleteUser', 'PwBox\Controller\UpdateUserController:deleteUser');

$app->get('/enterFolder/{id}', 'PwBox\Controller\FolderController:enterFolder')->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->get('/toRoot', 'PwBox\Controller\FolderController:toRoot')->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->get('/toSharedRoot', 'PwBox\Controller\FolderController:toSharedRoot')->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->get('/activate/id={id}', 'PwBox\Controller\PostUserController:activate');

$app->post('/addFile', 'PwBox\Controller\FolderController:addFile');

$app->post('/shareFolder', 'PwBox\Controller\FolderController:shareFolder');

$app->post('/addSharedFile', 'PwBox\Controller\FolderController:addSharedFile');

$app->post('/addSharedFolder', 'PwBox\Controller\FolderController:addSharedFolder');

$app->get('/enterSharedFolder/{id}', 'PwBox\Controller\FolderController:enterSharedFolder')->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->get('/download/{id}', 'PwBox\Controller\FolderController:downloadItem')->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->get('/rename/{id}/{name}', 'PwBox\Controller\FolderController:renameItem')->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->get('/delete/{id}', 'PwBox\Controller\FolderController:deleteItem')->add('PwBox\Controller\Middleware\SessionMiddleware');

$app->get('/resendValidate', 'PwBox\Controller\ProfileController:resendValidate');









