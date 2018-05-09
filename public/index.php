<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 5/4/2018
 * Time: 18:52
 */


require '../vendor/autoload.php';
$settings = require_once __DIR__ . '/../app/settings.php';
$app = new \Slim\App($settings);
require_once __DIR__ . '/../app/dependencies.php';
require_once __DIR__ . '/../app/routes.php';


// Start the session

session_start();

$app->run();

