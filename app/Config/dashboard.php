<?php

// Loaded by app/Config/Routes.php after the auth filter alias is registered.
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);
