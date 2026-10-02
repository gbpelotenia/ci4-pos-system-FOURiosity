<?php

$routes->get('login', 'Auth::login', ['as' => 'login']);
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout', ['as' => 'logout']);