<?php

if ($_SERVER['SERVER_NAME'] == 'www.webcms') {
	error_reporting(E_ALL);
}

// calling global bootstrap
define('PATH_BOOTSTRAP', __DIR__ . DIRECTORY_SEPARATOR . DIR_UP . DIRECTORY_SEPARATOR . '__bootstrap');
require PATH_BOOTSTRAP . DIRECTORY_SEPARATOR . 'boost.php';

// define a root folder for a current mode
define('PATH_MODE', __DIR__);

define('PATH_MODE_PHP', PATH_MODE . DIRECTORY_SEPARATOR . 'php');
define('PATH_MODE_CONFIG', PATH_MODE . DIRECTORY_SEPARATOR . 'config');
define('PATH_MODE_TEMPLATES', PATH_MODE . DIRECTORY_SEPARATOR . 'templates');
define('PATH_MODE_TEMPLATES_C', PATH_MODE_TEMPLATES . DIRECTORY_SEPARATOR . '__c');

// setup class autoloader for a model folder
$ModelLoader = new Loader();
$ModelLoader->setBaseFolder(PATH_MODE_PHP . DIRECTORY_SEPARATOR . 'model')->useFilePrefix(false);
Autoloader::add($ModelLoader);

// Setup the connection to DB
$DbLog = new Log_Fb(new Fw_Config(PATH_MODE_CONFIG . DIRECTORY_SEPARATOR . 'log.php'));
Fw_Db::i()->setLogger($DbLog);
Fw_Db::i()->connect(new Fw_Config(PATH_MODE_CONFIG . DIRECTORY_SEPARATOR . 'db.php'));