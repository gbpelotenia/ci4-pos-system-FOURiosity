<?php

/** @var \CodeIgniter\Router\RouteCollection $routes */
$routes->group('sales', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Sales::index');
    $routes->post('create', 'Sales::create');
});
