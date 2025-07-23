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

$routes->get('kegiatan-option', 'KegiatanOptionController::index');
$routes->get('kegiatan-option/create', 'KegiatanOptionController::create');
$routes->post('kegiatan-option/store', 'KegiatanOptionController::store');
$routes->get('kegiatan-option/edit/(:segment)', 'KegiatanOptionController::edit/$1');
$routes->post('kegiatan-option/update/(:segment)', 'KegiatanOptionController::update/$1');
$routes->get('kegiatan-option/delete/(:segment)', 'KegiatanOptionController::delete/$1');

$routes->get('kegiatan', 'KegiatanController::index');
$routes->get('kegiatan/create', 'KegiatanController::create');
$routes->post('kegiatan/store', 'KegiatanController::store');
$routes->get('kegiatan/edit/(:segment)', 'KegiatanController::edit/$1');
$routes->post('kegiatan/update/(:segment)', 'KegiatanController::update/$1');
$routes->get('kegiatan/delete/(:segment)', 'KegiatanController::delete/$1');
