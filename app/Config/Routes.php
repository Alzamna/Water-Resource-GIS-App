<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ✅ Redirect root ke login atau halaman umum
$routes->get('/', function () {
    return redirect()->to('/login');
});

// ✅ Route login/logout (tanpa filter)
$routes->get('login', 'AdminController::login');
$routes->post('login', 'AdminController::loginPost');
$routes->get('auth/logout', 'AdminController::logout');

// ✅ Route admin dikelompokkan, bisa pakai filter nanti (contoh: 'authadmin')
$routes->group('admin', ['filter' => 'authadmin'], function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('konten', 'AdminController::konten');
    $routes->post('konten', 'AdminController::kontenPost');
});

// ✅ Route untuk peta (publik, tidak perlu login)
$routes->match(['get', 'post'], 'peta', 'PetaController::index');
