<?php

define('PATH_ROOT', __DIR__);
define('PATH_BOOTSTRAP', PATH_ROOT . DIRECTORY_SEPARATOR . 'bootstrap');

require PATH_BOOTSTRAP . DIRECTORY_SEPARATOR . 'common.php';

$Router = new Router();
$Router->setRouteMask('/(?<page>[^\/]*)(\/(?<action>[^\/]+))?(\/(?<step>[^\/]+))?/i');
var_dump($Router->parse(''));
var_dump($Router->parse('/'));
var_dump($Router->parse('ssss'));
var_dump($Router->parse('ssss/'));
var_dump($Router->parse('ssss/bbbb'));
var_dump($Router->parse('ssss/bbbb/'));
var_dump($Router->parse('ssss/bbbb/cccc'));

die();

$Input = new Input(array('page'=>'home', 'sub'=>'2'));
$Output = new Output();

$Flow = new Flow_Root();
$Flow->init($Input, $Output);

$Flow->process();

var_dump($Output);