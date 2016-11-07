<?php
/****************************************************/
// Filename: index.php
// Created: Lisheng Liu
/****************************************************/

// require autoload file
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
// index page router
$router->add('',['controller' => 'Home', 'action' => 'index']);
// partner page router
$router->add('partner',['controller' => 'Partner', 'action' => 'index']);
// admin page router
$router->add('admin',['controller' => 'Admin', 'action' => 'index']);
// registration page router
$router->add('registration',['controller' => 'Register', 'action' => 'registration']);
// search page router
$router->add('search',['controller' => 'Search', 'action' => 'index']);
// authtication router with namespace
$router->add('auth/{controller}/{action}',['namespace' => 'Auth']);
$router->add('auth/{controller}/{name:\w+\_*}/{action}',['namespace' => 'Auth']);
//$router->add('auth/{controller}/{name:\w+\_*}/{action}',['namespace'=>'Auth']);
// universal router
$router->add('{controller}/{action}');
// universal router with name variable
$router->add('{controller}/{name:\w+\_*}/{action}');
// call router dispatch
$router->dispatch($_SERVER['QUERY_STRING']);


