<?php

// calling mode config, and setup mode params
namespace App;
	//use Core\Input;
	//use Core\Router;
	//use Core\Dispatcher;
	//use Core\Output;
	//use Core\Renderer;
//use Core\Renderer\Http\Html;

$ModeConfig = \Core\Input\Config::init(PATH_MODE_CONFIG . 'config.php');

$InputGET = new \Core\Input($_GET);

// create instance of Router, by using a ModeConfig::router section
$Router = \Core\Router::init($ModeConfig->get(\Core\Dispatcher::MODE_ROUTER));
// get route string, parse it and ...
$routeString = $InputGET->get(\Core\Dispatcher::ROUTE_IN_GET, '');
// ... save parsed route to a Input
$InputRoute = new \Core\Input($Router->parse($routeString));

$InputHttp = new \Core\Input\Http(array(\Core\Input\Http::ROUTE  => $InputRoute,
                                        \Core\Input\Http::GET    => $InputGET,
                                        \Core\Input\Http::POST   => $_POST,
                                        \Core\Input\Http::SERVER => $_SERVER,
                                        \Core\Input\Http::COOKIE => $_COOKIE));

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

$BufferedOutput = new \Core\Output;
Block\Head::process(array(), $BufferedOutput);
try {
	$content = \Core\Renderer\Http::di()->render($OutputHttp, $OutputHttp->getTemplatePath());
} catch (\Exception $E) {
	$content = '';
}
$BufferedOutput->bind('html_body', $content);

echo \Core\Renderer\Http::di()->render($BufferedOutput, '__html');
