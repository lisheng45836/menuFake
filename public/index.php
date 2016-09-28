<?php

require_once '../vendor/autoload.php';

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
// home page
$router->add('',['controller' => 'Home', 'action' => 'index']);
$router->add('registration',['controller' => 'Register', 'action' => 'registration']);
$router->add('search',['controller' => 'Search', 'action' => 'index']);
// $router->add('restaurants',);
$router->add('auth/{controller}/{action}',['namespace' => 'Auth']);

$router->add('{controller}/{action}');
$router->add('{controller}/{name:\w+\_*}/{action}');




$router->dispatch($_SERVER['QUERY_STRING']);


