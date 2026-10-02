<?php

use CodeIgniter\Boot;
use Config\Paths;

/*
 * ---------------------------------------------------------------
 * CHECK PHP VERSION
 * ---------------------------------------------------------------
 */

$minPHPVersion = '8.2';

if (version_compare(PHP_VERSION, $minPHPVersion, '<')) {
    $message = sprintf(
        'PHP %s or newer is required. Current version: %s',
        $minPHPVersion,
        PHP_VERSION
    );

    http_response_code(503);
    echo $message;
    exit(1);
}

/*
 * ---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 * ---------------------------------------------------------------
 */

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

/*
 * ---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 * ---------------------------------------------------------------
 */

require FCPATH . '../app/Config/Paths.php';

$paths = new Paths();

require rtrim(
    $paths->systemDirectory,
    '\\/'
) . DIRECTORY_SEPARATOR . 'Boot.php';

exit(Boot::bootWeb($paths));