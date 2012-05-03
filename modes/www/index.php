<?php

// calling global bootstrap
define('PATH_BOOTSTRAP', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '__bootstrap');
require PATH_BOOTSTRAP . DIRECTORY_SEPARATOR . 'common.php';

// calling mode config, and setup mode params
define('PATH_MODE_CONFIG', __DIR__ . DIRECTORY_SEPARATOR . 'config');
$ModeConfig = Input_Config::init(PATH_MODE_CONFIG . DIRECTORY_SEPARATOR . 'config.php');

$Dispatcher = Dispatcher::i();
$Dispatcher->init($ModeConfig);

$Dispatcher->flow();
