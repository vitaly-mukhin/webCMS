<?php

define('PATH_HTDOCS', __DIR__ . DIRECTORY_SEPARATOR);

ob_start();
require_once PATH_HTDOCS . '..' . DIRECTORY_SEPARATOR . 'common.php';

require_once PATH_MODE_PHP . 'json.php';
ob_flush();
