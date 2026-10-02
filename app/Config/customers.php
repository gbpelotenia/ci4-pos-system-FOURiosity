<?php

/** @var \CodeIgniter\Router\RouteCollection $routes */
$routes->group('customers', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Customers::index');
    $routes->post('create', 'Customers::create');
    $routes->post('update/(:num)', 'Customers::update/$1');
    $routes->post('delete/(:num)', 'Customers::delete/$1');
});
