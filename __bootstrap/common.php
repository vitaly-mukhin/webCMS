<?php

define('PATH_ROOT', __DIR__ . DIRECTORY_SEPARATOR . '..');

define('PATH_CORE', PATH_ROOT . DIRECTORY_SEPARATOR . '__core');
define('PATH_LIBS', PATH_ROOT . DIRECTORY_SEPARATOR . '__libs');
define('PATH_MODES', PATH_ROOT . DIRECTORY_SEPARATOR . 'modes');

require __DIR__ . DIRECTORY_SEPARATOR . 'autoloader' . DIRECTORY_SEPARATOR . 'Autoloader.php';

Autoloader::register();

// setup basic class autoloader for a system folder
$SystemLoader = new Loader();
$SystemLoader->setBaseFolder(PATH_CORE)->useFilePrefix(false);

Autoloader::add($SystemLoader);