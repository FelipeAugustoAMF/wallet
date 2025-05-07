<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->group('api', ['namespace' => 'App\Controllers'], static function ($routes) {
    // auth
    $routes->post('register', 'AuthController::register');
    $routes->post('login',    'AuthController::login');
    $routes->get('logout',    'AuthController::logout');

    // wallet
    $routes->get('balance',   'WalletController::balance');
    $routes->post('deposit',  'WalletController::deposit');
    $routes->post('transfer', 'WalletController::transfer');

    // transaction
    $routes->get('transactions',                 'TransactionController::index');
    $routes->post('transactions/(:num)/reverse', 'TransactionController::reverse/$1');

    // user
    $routes->get('user',            'UserController::showProfile');
    $routes->put('user/password',   'UserController::changePassword');
});

// pages
$routes->get('/',         'PageController::login');
$routes->get('dashboard', 'PageController::dashboard');
$routes->get('register',  'PageController::register');
