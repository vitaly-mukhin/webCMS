<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Mukhenok
 */
// TODO: check include path
//ini_set('include_path', ini_get('include_path'));

define('PATH_TESTS', realpath(dirname(__FILE__)));
define('PATH_CONFIG', PATH_TESTS . DIRECTORY_SEPARATOR . 'config');

define('PATH_ROOT', PATH_TESTS . DIRECTORY_SEPARATOR . '..');
define('PATH_LIB', PATH_ROOT . DIRECTORY_SEPARATOR . 'Library');
