<?php

define ('DB_TBL_BLOG', 'blog');
define ('DB_TBL_DB_LOG', 'db_log');
define ('DB_TBL_USER', 'users');
define ('DB_TBL_USER_AUTH', 'user_auths');
define ('DB_TBL_LOG', 'logs');

return array('driver'   => 'mysql',
             'server'   => 'localhost',
             'port'     => '3306',
             'user'     => 'root',
             'password' => 'fe3109',
             'encoding' => 'UTF8',
             'name'     => 'webcms');
