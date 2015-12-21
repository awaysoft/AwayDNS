<?php

define('ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR);
define('CONF_DIR', ROOT_DIR . 'config' . DIRECTORY_SEPARATOR);

require 'vendor/autoload.php';
$config = require CONF_DIR . 'config.php';

$dns = new away\DNS\Server($config);

$dns->start();
