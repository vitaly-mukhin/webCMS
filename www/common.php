<?php

// calling global bootstrap
define('PATH_BOOTSTRAP', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '__bootstrap');
require PATH_BOOTSTRAP . DIRECTORY_SEPARATOR . 'common.php';

// define a root folder for a current mode
define('PATH_MODE', __DIR__);

define('PATH_MODE_TEMPLATES', PATH_MODE . DIRECTORY_SEPARATOR . 'templates');
define('PATH_MODE_TEMPLATES_C', PATH_MODE_TEMPLATES . DIRECTORY_SEPARATOR . '__c');

// calling mode config, and setup mode params
define('PATH_MODE_CONFIG', __DIR__ . DIRECTORY_SEPARATOR . 'config');
$ModeConfig = Input_Config::init(PATH_MODE_CONFIG . DIRECTORY_SEPARATOR . 'config.php');

$InputGET = new Input($_GET);

// create instance of Router, by using a ModeConfig::router section
$Router = Router::init($ModeConfig->get(Dispatcher::MODE_ROUTER));
// get route string, parse it and ...
$routeString = $InputGET->get(Dispatcher::ROUTE_IN_GET, '');
// ... save parsed route to a Input
$InputRoute = new Input($Router->parse($routeString));

$InputHttp = new Input_Http(array(
			Input_Http::INPUT_ROUTE => $InputRoute,
			Input_Http::INPUT_GET => $InputGET,
			Input_Http::INPUT_POST => $_POST,
			Input_Http::INPUT_COOKIE => $_COOKIE
		));
Input_Http::setDefault($InputHttp);

$Dispatcher = new Dispatcher;
$Dispatcher->init($ModeConfig);

echo $Dispatcher->flow($InputHttp);