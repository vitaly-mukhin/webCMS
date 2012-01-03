<?php

define('TBL_CATEGORY', 'category');
define('TBL_FILM', 'film');
define('TBL_LOG', 'log');

define('PATH_CONFIG', PATH_ROOT . DIRECTORY_SEPARATOR . 'config');
define('PATH_FW', PATH_ROOT . DIRECTORY_SEPARATOR . 'Fw');

require('./Autoloader.php');

Autoloader::i()
	->pushAutoload('Fw', PATH_FW . DIRECTORY_SEPARATOR, 'fw');

$Config = new Fw_Config(PATH_CONFIG . DIRECTORY_SEPARATOR . 'config.php');


$Db = Fw_Db::i()->connect($Config->db);

$id = $Db->query()->insert(TBL_LOG, array('sql' => 'aaaaaaaaa', 'result' => 'empty', 'duration' => 5))->fetchRow();
