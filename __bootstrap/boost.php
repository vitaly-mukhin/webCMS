<?php

use VM\Loader;
use VM\Autoloader;
use VM\LoaderNames;

define('PATH_ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('PATH_APP', PATH_ROOT . '__app' . DIRECTORY_SEPARATOR);
define('PATH_CORE', PATH_ROOT . '__core' . DIRECTORY_SEPARATOR);
define('PATH_LIBS', PATH_ROOT . '__libs' . DIRECTORY_SEPARATOR);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'consts.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'funcs.php';

/** @noinspection PhpIncludeInspection */
require __DIR__ . DIRECTORY_SEPARATOR . 'autoload' . DIRECTORY_SEPARATOR . '__include.php';

Autoloader::register();

// setup basic class autoloader for a system folder
$SystemLoader = new LoaderNames();
$SystemLoader->setBaseFolder(PATH_CORE)->useFilePrefix(false)->setIgnoreFirstPart(true);
Autoloader::add($SystemLoader);

// setup basic class autoloader for a app folder
$AppLoader = new LoaderNames();
$AppLoader->setBaseFolder(PATH_APP)->useFilePrefix(false)->setIgnoreFirstPart(true);
Autoloader::add($AppLoader);

$FwLoader = new Loader();
$FwLoader->setBaseFolder(PATH_LIBS . 'Fw')->useFilePrefix(true)->setPrefix('fw.')->setIgnoreFirstPart(true);
Autoloader::add($FwLoader);

require_once PATH_LIBS . 'FirePHPCore' . DIRECTORY_SEPARATOR . 'fb.php';

set_error_handler(function ($code, $message, $file, $line, $context) {
	if (error_reporting()) {
		new \Core\Exception($message, $code);
		//		throw new \ErrorException($message, $code, 1, $file, $line, $context);
	}
});
set_exception_handler(function (\Exception $Exception) {
	$Exc = new \Core\Exception($Exception->getMessage(), $Exception->getCode(), $Exception);
	echo $Exc->getMessage();
});
