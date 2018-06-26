<?php

use Router\Router;
use Symfony\Component\Yaml\Yaml;

session_start();

require __DIR__ . '/vendor/autoload.php';

$config = Yaml::parse(file_get_contents('config/config.yml'));

$router = new Router($config['routes']);
if (!empty($_GET['action']))
    $router->callAction($_GET['action']);
else
    $router->callAction($config['defaut_route']);
