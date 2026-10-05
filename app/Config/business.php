<?php
/** @var \CodeIgniter\Router\RouteCollection $routes */
$routes->group('business', ['filter' => 'auth'], static function ($routes) {
    $routes->post('category', 'Business::category');
    $routes->post('supplier', 'Business::supplier');
    $routes->post('expense', 'Business::expense');
    $routes->post('expense/delete/(:num)', 'Business::deleteExpense/$1');
});
