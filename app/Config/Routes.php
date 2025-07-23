<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::loginPost');
$routes->get('logout', 'AuthController::logout');
$routes->group('', ['filter' => 'auth'], function ($routes) {
  $routes->get('/', 'Dashboard::admin');
  $routes->get('admin', 'Dashboard::admin');
  $routes->get('ipds', 'Dashboard::ipds');
  $routes->get('user', 'UserController::index');
  $routes->get('user/create', 'UserController::create');
  $routes->post('user/store', 'UserController::store');
  $routes->get('user/edit/(:segment)', 'UserController::edit/$1');
  $routes->post('user/update/(:segment)', 'UserController::update/$1');
  $routes->get('user/delete/(:segment)', 'UserController::delete/$1');
});
