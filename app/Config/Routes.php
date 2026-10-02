<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('products', 'Products::index');
$routes->get('products/new', 'Products::new');
$routes->post('products/create', 'Products::create');
$routes->get('products/edit/(:num)', 'Products::edit/$1');
$routes->post('products/update/(:num)', 'Products::update/$1');
$routes->get('products/delete/(:num)', 'Products::delete/$1');

$routes->get('customers', 'Customers::index');
$routes->get('customers/new', 'Customers::new');
$routes->post('customers/create', 'Customers::create');
$routes->get('customers/edit/(:num)', 'Customers::edit/$1');
$routes->post('customers/update/(:num)', 'Customers::update/$1');
$routes->get('customers/delete/(:num)', 'Customers::delete/$1');


