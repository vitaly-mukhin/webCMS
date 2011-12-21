<?php

/**
 * @author Slevin
 */
define('PATH_TESTS', realpath(dirname(__FILE__)));
define('PATH_CONFIG', PATH_TESTS . DIRECTORY_SEPARATOR . 'config');

define('PATH_ROOT', PATH_TESTS . DIRECTORY_SEPARATOR . '..');
define('PATH_FW', PATH_ROOT . DIRECTORY_SEPARATOR . 'Fw');

require_once PATH_FW . DIRECTORY_SEPARATOR . 'Autoloader.php';

Autoloader::i()->pushAutoload('Fw', PATH_FW . DIRECTORY_SEPARATOR, 'fw');

define('TBL_CATEGORY', 'category');
define('TBL_FILM', 'film');
define('TBL_LOG', 'log');
