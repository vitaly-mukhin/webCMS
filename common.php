<?php

define('PATH_CONFIG', PATH_ROOT . DIRECTORY_SEPARATOR . 'config');
define('PATH_LIB', PATH_ROOT . DIRECTORY_SEPARATOR . 'Library');

require(PATH_LIB . DIRECTORY_SEPARATOR . 'Autoloader.php');

Autoloader::i()
		->pushAutoload('Fw', PATH_LIB . DIRECTORY_SEPARATOR, 'class');

$Config = new Fw_Config(PATH_CONFIG . DIRECTORY_SEPARATOR . 'config.php');

$Db = Fw_Db::i()->connect($Config->db);
