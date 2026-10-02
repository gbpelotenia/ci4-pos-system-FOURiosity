<?php

$routes->group('users', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Users::index', ['as' => 'users']);
    $routes->get('new', 'Users::new');
    $routes->post('create', 'Users::create');
    $routes->get('edit/(:num)', 'Users::edit/$1');
    $routes->post('update/(:num)', 'Users::update/$1');
    $routes->post('delete/(:num)', 'Users::delete/$1');
});