<?php

/**
 * PHPUnit bootstrap file.
 *
 */

$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['HTTP_HOST'] = "localhost";

define("ABSPATH", "/var/www/html/");
define("WPINC", "wp-includes");

require_once "/var/www/html/wp-includes/functions.php";