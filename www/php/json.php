<?php

// calling mode config, and setup mode params
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
			Input_Http::INPUT_SERVER => $_SERVER,
			Input_Http::INPUT_COOKIE => $_COOKIE
		));

Input_Http::setDefault($InputHttp);

// TODO 2012-05-22: hard-coded 'page' key
switch ($InputRoute->get('page')) {
	case 'block':
		$initialFlow = 'block';
		break;
	default:
		$initialFlow = 'www';
}

$Dispatcher = Dispatcher::di(array(
			Dispatcher::PARAM_MODE_CONFIG => $ModeConfig,
			Dispatcher::PARAM_INITIAL_FLOW => $initialFlow
		));

$OutputHttp = new Output_Http_Json();
$Dispatcher->flow($InputHttp, $OutputHttp);

// render in JSON format
echo Renderer_Json::di()->render($OutputHttp);