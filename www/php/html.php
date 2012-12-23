<?php

// calling mode config, and setup mode params
namespace App;
//use Core\Input;
//use Core\Router;
//use Core\Dispatcher;
//use Core\Output;
//use Core\Renderer;
//use Core\Renderer\Http\Html;

$ModeConfig = \Core\Input\Config::init(PATH_MODE_CONFIG . DIRECTORY_SEPARATOR . 'config.php');

$InputGET = new \Core\Input($_GET);

// create instance of Router, by using a ModeConfig::router section
$Router = \Core\Router::init($ModeConfig->get(\Core\Dispatcher::MODE_ROUTER));
// get route string, parse it and ...
$routeString = $InputGET->get(\Core\Dispatcher::ROUTE_IN_GET, '');
// ... save parsed route to a Input
$InputRoute = new \Core\Input($Router->parse($routeString));

$InputHttp = new \Core\Input\Http(array(\Core\Input\Http::INPUT_ROUTE  => $InputRoute,
                                        \Core\Input\Http::INPUT_GET    => $InputGET,
                                        \Core\Input\Http::INPUT_POST   => $_POST,
                                        \Core\Input\Http::INPUT_SERVER => $_SERVER,
                                        \Core\Input\Http::INPUT_COOKIE => $_COOKIE));

\Core\Input\Http::setDefault($InputHttp);

// TODO 2012-05-22: hard-coded 'page' key
switch ($InputRoute->get('page')) {
	case 'block':
		$initialFlow = 'block';
		break;
	default:
		$initialFlow = 'main';
}

$Dispatcher = \Core\Dispatcher::di(array(\Core\Dispatcher::PARAM_MODE_CONFIG  => $ModeConfig,
                                   \Core\Dispatcher::PARAM_INITIAL_FLOW => $initialFlow));

$OutputHttp = new \Core\Output\Http;
$Dispatcher->flow($InputHttp, $OutputHttp);

echo \Core\Renderer\Http\Html::di()->render($OutputHttp, $OutputHttp->getTemplatePath());
