<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mukhenok
 * Date: 22.05.13
 * Time: 23:23
 */

namespace Core;


class Exception extends \Exception {

	/**
	 * @var \Fw_Db
	 */
	public static $Db;

	public function __construct($message = "", $code = 0, Exception $previous = null) {
		parent::__construct($message, $code, $previous); // TODO: Change the autogenerated stub
		if (error_reporting() != 0 && static::$Db) {
			static::$Db->query(null, null, true)->insert(DB_TBL_LOG, array(
			                                                              'title' => 'Exception: ' . $message,
			                                                              'description' => $this->getTraceAsString(),
			                                                              'date_added' => date(DB_DATETIME_FMT)
			                                                         ))->fetchRow();
		}
	}
}
