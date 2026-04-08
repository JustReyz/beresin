<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get("/", "Auth::login"); // Default route
$routes->get("/login", "Auth::login");
$routes->post("/login", "Auth::loginSubmit");
$routes->get("/register", "Auth::register");
$routes->post("/register", "Auth::registerSubmit");
$routes->get("/dashboard", "Auth::dashboard");
$routes->get("/logout", "Auth::logout");
$routes->post('/profile/password', 'Auth::updatePassword');

$routes->get('/admin/dashboard', 'Admin::dashboard');
$routes->post('/admin/users/create-admin', 'Admin::createAdmin');
$routes->post('/admin/orders/(:num)/status', 'Admin::updateOrderStatus/$1');
$routes->post('/admin/orders/(:num)/delete', 'Admin::deleteOrder/$1');

$routes->get('/mitra/dashboard', 'Mitra::dashboard');
$routes->post('/mitra/orders/(:num)/accept', 'Mitra::acceptOrder/$1');
$routes->post('/mitra/orders/(:num)/complete', 'Mitra::completeOrder/$1');

$routes->post('/orders/create', 'UserOrder::create');
$routes->post('/orders/(:num)/rate', 'UserOrder::rate/$1');

$routes->post('/api/admins', 'Api\\AdminUserApi::create');
