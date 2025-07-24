<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AdminController::dashboard');
$routes->get('login', 'AdminController::login');
$routes->post('login', 'AdminController::loginPost');
$routes->get('logout', 'AdminController::logout');
$routes->get('admin/konten', 'AdminController::konten');
$routes->post('admin/konten', 'AdminController::kontenPost');
$routes->match(['get', 'post'], 'peta', 'PetaController::index');

