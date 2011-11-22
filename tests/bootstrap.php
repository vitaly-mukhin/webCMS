<?php

/**
 * @author Slevin
 */
define('PATH_TESTS', realpath(dirname(__FILE__)));
define('PATH_CONFIG', PATH_TESTS . DIRECTORY_SEPARATOR . 'config');

define('PATH_ROOT', PATH_TESTS . DIRECTORY_SEPARATOR . '..');
define('PATH_LIB', PATH_ROOT . DIRECTORY_SEPARATOR . 'Library');

require_once PATH_LIB . DIRECTORY_SEPARATOR . 'Autoloader.php';

Autoloader::i()->pushAutoload('Fw', PATH_LIB . DIRECTORY_SEPARATOR, 'class');
