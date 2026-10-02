<?php

/** @var \CodeIgniter\Router\RouteCollection $routes */
$routes->group('products', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Products::index');
    $routes->post('create', 'Products::create');
    $routes->post('update/(:num)', 'Products::update/$1');
    $routes->post('delete/(:num)', 'Products::delete/$1');
});
