<?php

define('PATH_ROOT', __DIR__);
define('PATH_BOOTSTRAP', PATH_ROOT . DIRECTORY_SEPARATOR . '__bootstrap');

require PATH_BOOTSTRAP . DIRECTORY_SEPARATOR . 'common.php';

$Config = Input_Config::init('config.php');

$Dispatcher = Dispatcher::i();
$Dispatcher->init($Config);
$Dispatcher->flow();
