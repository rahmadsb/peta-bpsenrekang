<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::loginPost');
$routes->get('logout', 'AuthController::logout');
$routes->get('/', 'Dashboard::index');
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
  $routes->get('blok-sensus', 'BlokSensusController::index');
  $routes->get('blok-sensus/create', 'BlokSensusController::create');
  $routes->post('blok-sensus/store', 'BlokSensusController::store');
  $routes->get('blok-sensus/edit/(:segment)', 'BlokSensusController::edit/$1');
  $routes->post('blok-sensus/update/(:segment)', 'BlokSensusController::update/$1');
  $routes->post('blok-sensus/delete/(:segment)', 'BlokSensusController::delete/$1');
  $routes->get('blok-sensus/export-excel', 'BlokSensusController::exportExcel');
  $routes->post('blok-sensus/import-excel', 'BlokSensusController::importExcel');
  $routes->get('blok-sensus/detail/(:segment)', 'BlokSensusController::detail/$1');

  $routes->get('sls', 'SlsController::index');
  $routes->get('sls/create', 'SlsController::create');
  $routes->post('sls/store', 'SlsController::store');
  $routes->get('sls/edit/(:segment)', 'SlsController::edit/$1');
  $routes->post('sls/update/(:segment)', 'SlsController::update/$1');
  $routes->post('sls/delete/(:segment)', 'SlsController::delete/$1');
  $routes->get('sls/export-excel', 'SlsController::exportExcel');
  $routes->post('sls/import-excel', 'SlsController::importExcel');
  $routes->get('sls/detail/(:segment)', 'SlsController::detail/$1');

  $routes->get('desa', 'DesaController::index');
  $routes->get('desa/create', 'DesaController::create');
  $routes->post('desa/store', 'DesaController::store');
  $routes->get('desa/edit/(:segment)', 'DesaController::edit/$1');
  $routes->post('desa/update/(:segment)', 'DesaController::update/$1');
  $routes->post('desa/delete/(:segment)', 'DesaController::delete/$1');
  $routes->get('desa/export-excel', 'DesaController::exportExcel');
  $routes->post('desa/import-excel', 'DesaController::importExcel');
  $routes->get('desa/detail/(:segment)', 'DesaController::detail/$1');

  $routes->get('kelola-peta-wilkerstat/(:segment)', 'KelolaPetaWilkerstatController::index/$1');
  $routes->post('kelola-peta-wilkerstat/upload', 'KelolaPetaWilkerstatController::upload');
  $routes->get('kelola-peta-wilkerstat/download/(:num)', 'KelolaPetaWilkerstatController::download/$1');
  $routes->post('kelola-peta-wilkerstat/delete/(:num)', 'KelolaPetaWilkerstatController::delete/$1');
  $routes->post('kelola-peta-wilkerstat/replace/(:num)', 'KelolaPetaWilkerstatController::replace/$1');
  $routes->post('kelola-peta-wilkerstat/rename/(:num)', 'KelolaPetaWilkerstatController::rename/$1');
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

$routes->get('preview-peta/(:any)', function ($filename) {
  $path = WRITEPATH . 'uploads/' . $filename;
  if (!is_file($path)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
  $mime = mime_content_type($path);
  header('Content-Type: ' . $mime);
  readfile($path);
  exit;
});

$routes->get('subject-matter', 'Dashboard::subjectMatter');
$routes->get('guest', 'Dashboard::guest');
$routes->post('import-wilkerstat', 'ImportWilkerstatController::importWilkerstat');
