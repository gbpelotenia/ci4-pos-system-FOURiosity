<?php

/** @var \CodeIgniter\Router\RouteCollection $routes */
$routes->get('/', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);
