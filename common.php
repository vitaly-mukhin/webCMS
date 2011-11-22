<?php

define('PATH_CONFIG', PATH_ROOT . DIRECTORY_SEPARATOR . 'config');
define('PATH_LIB', PATH_ROOT . DIRECTORY_SEPARATOR . 'Library');

require(realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Autoloader.php');

Autoloader::i()
		->pushAutoload('class', PATH_CLASS)
		->pushAutoload('Controller', PATH_CONTROLLER, false)
		->pushAutoload('Block', PATH_BLOCK, 'block')
		->pushAutoload('Model', PATH_MODEL, 'model');

$Config = new Config(PATH_CONFIG.'config.php');
$Dbi = DbI::i($Config->db);

$Db = Db::i();
$Db->query('SET NAMES "utf8" COLLATE "utf8_general_ci"');
$Db->setErrorHandler(function($message, $info) {
			// Если использовалась @, ничего не делать.
			if(!error_reporting()) return;
			throw new Error("SQL Error: $message<br>", $info, $GLOBALS['sql_array']);
		});
if(SQL_LOG) {
	$Db->setLogger(function(DbSimple_Mysql $db, $sql, $caller = null) {
				if(!isset($GLOBALS['sql_array'])) {
					$GLOBALS['sql_array'] = array();
				}
				$caller = (object)set($caller, $db->findLibraryCaller());
				$logger = $db->_logger;
				$db->setLogger(false);
				if(substr($sql, 0, 4) == '  --') {
					$result = preg_match('/^  --( (\d)* ms(;)? )?/i', $sql, $m);
					if(!$m || count($m) < 2) $m[1] = 0;
					$db->query('UPDATE ?# SET ?a WHERE sqllog_id=LAST_INSERT_ID()', TBL_SQL_LOG, array('result'=>substr($sql, strlen($m[0])), 'duration'=>(int)$m[1]));
				} else
						$id = $db->query('INSERT INTO ?# SET ?a', TBL_SQL_LOG, array(
						'sql'=>$sql,
						'duration'=>(int)$db->_statistics['time'],
						'file'=>$caller->file,
						'line'=>$caller->line,
							));
				$db->setLogger($logger);
			});
}

register_shutdown_function(
		function() {
			$error = error_get_last();
			if($error && !error_reporting()) {
				$mesage = Common::formatErrorMessage($error);
				$e = new Error($mesage);
				echo $e;
			}
		}
);


set_error_handler(function($no, $str, $file, $line) {
			if(error_reporting() == 0) return;
			$message = Common::formatErrorMessage($no, $str, $file, $line);
			throw new Error($message);
		});

set_exception_handler(function(Exception $e) {
			echo '<pre>' . htmlspecialchars($e) . '</pre>';
		});

session_set_save_handler(
		function($save_path, $session_name) {
			
		}, function() {
			return(true);
		}, function($session_id) {
			$session = Db::i()->session->findById($session_id, array('data', 'created', 'updated', 'ip', 'user_id'));
			if($session) {
				if($session['ip'] != gV('REMOTE_ADDR', '', $_SERVER)) {
					throw new Error('Попытка кражи сессии');
					return '';
				}
			}
			return ($session) ? $session['data'] : '';
		}, function($session_id, $data) {
			try {
				$data = array(
					'session_id'=>$session_id,
					'data'=>$data,
					'ip'=>gV('REMOTE_ADDR', '', $_SERVER),
//							'user_id' => gV('user_id', GData::$user->user_id, $_SESSION),
					'updated'=>date('Y.m.d H:i:s')
				);
				Db::i()->session->insertOnDuplicateUpdate($data);
			} catch (Error $e) {
				print_r($e->getMessage());
			}
		}, function() {
			
		}, function($maxlifetime) {
			Db::i()->session->delete(array('updated < '=>date('Y.m.d H:i:s', time() - SESSION_LIVETIME)));
		}
);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 20);

session_start();