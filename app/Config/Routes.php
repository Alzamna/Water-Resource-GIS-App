<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', function () {
    return redirect()->to('/login');
});

$routes->get('login', 'AdminController::login');
$routes->post('login', 'AdminController::loginPost');
$routes->get('logout', 'AdminController::logout');

$routes->group('admin', ['filter' => 'authadmin'], function ($routes) {
    // Dashboard
    $routes->get('/', 'AdminController::dashboard');
    $routes->get('dashboard', 'AdminController::dashboard');
    
    // Manajemen Konten
    $routes->get('konten', 'AdminController::konten');
    $routes->post('konten', 'AdminController::kontenPost');
    
    // Manajemen User
    $routes->get('users', 'AdminController::users');
    
    // Manajemen Peta - Halaman Utama
    $routes->get('maps', 'AdminController::maps');
    
    // Manajemen Peta - CRUD Pages
    $routes->get('maps/add', 'AdminController::mapsAdd');
    $routes->post('maps/add', 'AdminController::mapsAddPost');
    $routes->get('maps/edit/(:num)', 'AdminController::mapsEdit/$1');
    $routes->post('maps/edit/(:num)', 'AdminController::mapsEditPost/$1');
    $routes->get('maps/list', 'AdminController::mapsList');
    $routes->get('maps/export', 'AdminController::mapsExport');
    
    // API Routes untuk Water Resources
    $routes->group('maps', function ($routes) {
        $routes->get('get-locations', 'AdminController::getLocations');
        $routes->post('add-location', 'AdminController::addLocation');
        $routes->put('update-location/(:num)', 'AdminController::updateLocation/$1');
        $routes->delete('delete-location/(:num)', 'AdminController::deleteLocation/$1');
        $routes->get('photo/(:any)', 'AdminController::getPhoto/$1');
    });
    
    // Pengaturan
    $routes->get('settings', 'AdminController::settings');
    
    // Profil Admin
    $routes->get('profile', 'AdminController::profile');
});

$routes->match(['get', 'post'], 'peta', 'PetaController::index');

$routes->get('auth/logout', 'AdminController::logout');
