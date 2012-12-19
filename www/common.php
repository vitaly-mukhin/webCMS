<?php

namespace App;

use VM\Loader;
use VM\Autoloader;
use Core\Log\Fb;

if (preg_match('/.*webcms$/i', $_SERVER['SERVER_NAME'])) {
	error_reporting(E_ALL);
}

// calling global bootstrap
define('PATH_BOOTSTRAP', __DIR__ . DIRECTORY_SEPARATOR . DIR_UP . DIRECTORY_SEPARATOR . '__bootstrap');
require PATH_BOOTSTRAP . DIRECTORY_SEPARATOR . 'boost.php';

// define a root folder for a current mode
define('PATH_MODE', __DIR__);

define('PATH_MODE_PHP', PATH_MODE . DIRECTORY_SEPARATOR . 'php');
define('PATH_MODE_CONFIG', PATH_MODE . DIRECTORY_SEPARATOR . 'config');
define('PATH_MODE_TEMPLATES', PATH_MODE . DIRECTORY_SEPARATOR . 'templates');
define('PATH_MODE_TEMPLATES_C', PATH_MODE_TEMPLATES . DIRECTORY_SEPARATOR . '__c');

require PATH_MODE_PHP . DIRECTORY_SEPARATOR . 'LoaderNames.php';

// Setup the connection to DB
$DbLog = new Fb(new \Fw_Config(PATH_MODE_CONFIG . DIRECTORY_SEPARATOR . 'log.php'));

\Fw_Db::i()->setLogger($DbLog);
\Fw_Db::i()->connect(new \Fw_Config(PATH_MODE_CONFIG . DIRECTORY_SEPARATOR . 'db.php'));