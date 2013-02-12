<?php

define('DIR_UP', '..');

ob_start();
require_once __DIR__ . DIRECTORY_SEPARATOR . DIR_UP . DIRECTORY_SEPARATOR . 'common.php';

require_once PATH_MODE_PHP . DIRECTORY_SEPARATOR . 'html.php';
ob_flush();
