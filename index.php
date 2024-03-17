<?php

require_once __DIR__.'/classes/autoload.php';

require_once __DIR__.'/routes.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->handleRequest($method, $path);

// use Controllers\UsersController;

// $controller = new UsersController();

// $action = isset($_GET['action']) ? $_GET['action'] : 'index';

// $controller->{$action}();