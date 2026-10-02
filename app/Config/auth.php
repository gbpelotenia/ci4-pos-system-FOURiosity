<?php

/** @var \CodeIgniter\Router\RouteCollection $routes */
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->post('logout', 'Auth::logout', ['filter' => 'auth']);
