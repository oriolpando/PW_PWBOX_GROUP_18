<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 5/4/2018
 * Time: 19:29
 * @param $container
 * @return \Slim\Views\Twig
 */


$container = $app->getContainer();

$container['view'] = function($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../src/View/templates', []);
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));
    return $view;
};


$container['doctrine'] = function ($container){
    $config = new \Doctrine\DBAL\Configuration();
    $connection = \Doctrine\DBAL\DriverManager::getConnection(
        $container->get('settings')['database'], $config
    );
    return $connection;
};

$container['file_repository'] = function($container){
    $repo = new PwBox\Model\Implementation\DoctrineFileRepository(
        $container->get('doctrine')
    );
    return $repo;
};

$container['mail_repository'] = function($container){
    return new \PwBox\Model\Implementation\SwiftMailerRepository();
};


$container['user_repository'] = function($container){
    $repository = new PwBox\Model\Implementation\DoctrineUserRepository(
        $container->get('doctrine')
    );
    return $repository;
};


$container['post_user_use_case'] = function($container){
    $useCase = new PwBox\Model\UseCase\PortUserUseCase(
        $container->get('user_repository')
    );
    return $useCase;
};


$container['flash'] = function($container){
    return new \Slim\Flash\Messages();
};