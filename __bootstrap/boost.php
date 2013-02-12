<?php

use VM\Loader;
use VM\Autoloader;
use VM\LoaderNames;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'consts.php';

define('PATH_ROOT', __DIR__ . DIRECTORY_SEPARATOR . DIR_UP);

define('PATH_CORE', PATH_ROOT . DIRECTORY_SEPARATOR . '__core');
define('PATH_LIBS', PATH_ROOT . DIRECTORY_SEPARATOR . '__libs');

/** @noinspection PhpIncludeInspection */
require __DIR__ . DIRECTORY_SEPARATOR . 'autoload' . DIRECTORY_SEPARATOR . '__include.php';
//require __DIR__ . DIRECTORY_SEPARATOR . 'autoloader' . DIRECTORY_SEPARATOR . 'Autoloader.php';

Autoloader::register();

// setup basic class autoloader for a system folder
$SystemLoader = new LoaderNames();
$SystemLoader->setBaseFolder(PATH_CORE)->useFilePrefix(false)->setIgnoreFirstPart(true);
Autoloader::add($SystemLoader);

$FwLoader = new Loader();
$FwLoader->setBaseFolder(PATH_LIBS . DIRECTORY_SEPARATOR . 'Fw')->useFilePrefix(true)->setPrefix('fw.')
		->setIgnoreFirstPart(true);
Autoloader::add($FwLoader);

require_once PATH_LIBS . DIRECTORY_SEPARATOR . 'FirePHPCore' . DIRECTORY_SEPARATOR . 'fb.php';
