<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Redirect root ke login
$routes->get('/', function () {
    return redirect()->to('/login');
});

// Route login/logout (tanpa filter)
$routes->get('login', 'AdminController::login');
$routes->post('login', 'AdminController::loginPost');
$routes->get('logout', 'AdminController::logout');

// Route admin dengan filter autentikasi
$routes->group('admin', ['filter' => 'auth_admin'], function ($routes) {
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
    $routes->get('api/locations', 'AdminController::getLocations');
    $routes->post('api/locations', 'AdminController::addLocation');
    $routes->put('api/locations/(:num)', 'AdminController::updateLocation/$1');
    $routes->delete('api/locations/(:num)', 'AdminController::deleteLocation/$1');
    $routes->get('photo/(:any)', 'AdminController::getPhoto/$1');
    
    // Categories Management
    $routes->get('categories', 'AdminController::categories');
    $routes->get('categories/add', 'AdminController::categoriesAdd');
    $routes->post('categories/add', 'AdminController::categoriesAddPost');
    $routes->get('categories/edit/(:num)', 'AdminController::categoriesEdit/$1');
    $routes->post('categories/edit/(:num)', 'AdminController::categoriesEditPost/$1');
    
    // Categories API Routes
    $routes->get('categories/get-categories', 'AdminController::getCategories');
    $routes->get('api/categories', 'AdminController::getCategories');
    $routes->get('api/categories/stats', 'AdminController::getCategoryStats');
    $routes->get('api/categories/(:num)', 'AdminController::getCategory/$1');
    $routes->post('api/categories', 'AdminController::addCategory');
    $routes->put('api/categories/(:num)', 'AdminController::updateCategory/$1');
    $routes->put('api/categories/(:num)/toggle', 'AdminController::toggleCategoryStatus/$1');
    $routes->delete('api/categories/(:num)', 'AdminController::deleteCategory/$1');
    $routes->get('api/categories/export', 'AdminController::exportCategories');
    
    // Pengaturan
    $routes->get('settings', 'AdminController::settings');
    
    // Profil Admin
    $routes->get('profile', 'AdminController::profile');
});

// Route untuk peta publik (jika diperlukan)
$routes->match(['get', 'post'], 'peta', 'PetaController::index');

// Route alternatif untuk logout
$routes->get('auth/logout', 'AdminController::logout');
