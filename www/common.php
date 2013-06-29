<?php

namespace App;

use VM\Autoloader;
use Core\Log\Fb;

define('SESS', md5(microtime(true) . rand(100000, 999999)));

// define a root folder for a current mode
define('PATH_MODE', __DIR__ . DIRECTORY_SEPARATOR);

// calling global bootstrap
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '__bootstrap' . DIRECTORY_SEPARATOR . 'boost.php';

register_shutdown_function(function () {
	if ($e = error_get_last()) {
		print_r($e);
		echo 'Hello, World!';
	}
});

define('PATH_MODE_PHP', PATH_MODE . 'php' . DIRECTORY_SEPARATOR);
define('PATH_MODE_CONFIG', PATH_MODE . 'config' . DIRECTORY_SEPARATOR);
define('PATH_MODE_TEMPLATES', PATH_MODE . 'templates' . DIRECTORY_SEPARATOR);
define('PATH_MODE_TEMPLATES_C', PATH_MODE_TEMPLATES . '__c' . DIRECTORY_SEPARATOR);

require PATH_APP . 'LoaderNames.php';

// Setup the connection to DB
$Db = \Fw_Db::i();

//$DbLog = new Fb(new \Fw_Config(PATH_MODE_CONFIG . 'log.php'));
$DbLog = \App\DbLog::i(PATH_MODE_CONFIG . 'log.php', $Db);


$Db->setLogger($DbLog);
$Db->connect(new \Fw_Config(PATH_MODE_CONFIG . 'db.php'));

\Core\Exception::$Db = $Db;
