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
 

$router->add('',['controller' => 'Home', 'action' => 'index']);
$router->add('partner',['controller' => 'Partner', 'action' => 'index']);
$router->add('admin',['controller' => 'Admin', 'action' => 'index']);
$router->add('registration',['controller' => 'Register', 'action' => 'registration']);
$router->add('search',['controller' => 'Search', 'action' => 'index']);

$router->add('auth/{controller}/{action}',['namespace' => 'Auth']);
//$router->add('auth/{controller}/{name:\w+\_*}/{action}',['namespace'=>'Auth']);

$router->add('{controller}/{action}');
$router->add('{controller}/{name:\w+\_*}/{action}');

$router->dispatch($_SERVER['QUERY_STRING']);


