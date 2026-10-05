<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->setDefaultNamespace('App\\Controllers');
$routes->setDefaultController('Dashboard');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

require APPPATH . 'Config/auth.php';
require APPPATH . 'Config/users.php';
require APPPATH . 'Config/products.php';
require APPPATH . 'Config/customers.php';
require APPPATH . 'Config/sales.php';
require APPPATH . 'Config/dashboard.php';
require APPPATH . 'Config/business.php';
