<?php

define('PATH_SYSTEM', PATH_ROOT . DIRECTORY_SEPARATOR . 'system');

require PATH_ROOT . DIRECTORY_SEPARATOR . 'autoloader' . DIRECTORY_SEPARATOR . 'ILoader.php';
require PATH_ROOT . DIRECTORY_SEPARATOR . 'autoloader' . DIRECTORY_SEPARATOR . 'Loader.php';
require PATH_ROOT . DIRECTORY_SEPARATOR . 'autoloader' . DIRECTORY_SEPARATOR . 'Autoloader.php';

Autoloader::register();

// setup basic class autoloader for a system folder
$SystemLoader = new Loader();
$SystemLoader->setBaseFolder(PATH_SYSTEM)->useFilePrefix(false);

Autoloader::add($SystemLoader);