<?php

define('PATH_ROOT', __DIR__);
define('PATH_BOOTSTRAP', PATH_ROOT . DIRECTORY_SEPARATOR . 'bootstrap');

require PATH_BOOTSTRAP . DIRECTORY_SEPARATOR . 'common.php';

$Input = new Input(array('page'=>'home', 'sub'=>'2'));
$Output = new Output();

$Flow = new Flow_Root();
$Flow->init($Input, $Output);

$Flow->process();

var_dump($Output);