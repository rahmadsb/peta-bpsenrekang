<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('admin', 'Dashboard::admin');
$routes->get('ipds', 'Dashboard::ipds');
