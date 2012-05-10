<?php

// calling global bootstrap
define('PATH_BOOTSTRAP', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '__bootstrap');
require PATH_BOOTSTRAP . DIRECTORY_SEPARATOR . 'common.php';

// define a root folder for a current mode
define('PATH_MODE', __DIR__);

// calling mode config, and setup mode params
define('PATH_MODE_CONFIG', __DIR__ . DIRECTORY_SEPARATOR . 'config');
$ModeConfig = Input_Config::init(PATH_MODE_CONFIG . DIRECTORY_SEPARATOR . 'config.php');

$Dispatcher = new Dispatcher;
$Dispatcher->init($ModeConfig);

echo $Dispatcher->flow();