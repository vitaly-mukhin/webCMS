<?php

define('PATH_CORE', PATH_ROOT . DIRECTORY_SEPARATOR . '__core');
define('PATH_CONFIG', PATH_ROOT . DIRECTORY_SEPARATOR . '__config');
define('PATH_MODES', PATH_ROOT . DIRECTORY_SEPARATOR . 'modes');

require PATH_ROOT . DIRECTORY_SEPARATOR . 'autoloader' . DIRECTORY_SEPARATOR . 'ILoader.php';
require PATH_ROOT . DIRECTORY_SEPARATOR . 'autoloader' . DIRECTORY_SEPARATOR . 'Loader.php';
require PATH_ROOT . DIRECTORY_SEPARATOR . 'autoloader' . DIRECTORY_SEPARATOR . 'Autoloader.php';

Autoloader::register();

// setup basic class autoloader for a system folder
$SystemLoader = new Loader();
$SystemLoader->setBaseFolder(PATH_CORE)->useFilePrefix(false);

Autoloader::add($SystemLoader);