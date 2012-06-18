<?php

/**
 * Description of Fw_Db
 *
 * @author Vitaly Mukhin
 * 
 * @property-read PDO $pointer
 * @property-read Fw_Logger_Abstract $Logger
 * 
 */
class Fw_Db {

	protected static $_instance;

	/**
	 *
	 * @var PDO
	 */
	protected $_connection;

	/**
	 *
	 * @var Fw_Logger_Abstract
	 */
	protected $_Logger;

	/**
	 *
	 * @return Fw_Db
	 */
	public static function i($reset = false) {
		if (empty(static::$_instance) || $reset) {
			static::$_instance = new static();
		}

		return static::$_instance;
	}

	/**
	 * 
	 */
	protected function __construct() {
		
	}

	public function __get($name) {
		switch ($name) {
			case 'pointer':
				return $this->_connection;
			case 'Logger':
				return $this->_Logger;
		}
	}

	/**
	 *
	 * @param Fw_Config $Config
	 * @return Fw_Db 
	 */
	public function connect(Fw_Config $Config) {
		$dsn = $Config->driver . ':host=' . $Config->server . ';port=' . $Config->port . ';dbname=' . $Config->name;

		$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $Config->encoding);

		try {
			$this->_connection = new PDO($dsn, $Config->user, $Config->password, $options);
		} catch (PDOException $e) {
			$this->_connection = null;
			throw new Fw_Exception_Db_Connection($e->getMessage(), $e->getCode(), $e->getPrevious());
		}

		return $this;
	}

	/**
	 *
	 * @return Fw_Db_Query 
	 */
	public function query($sql = null, $binds = null, $skip_logger = false) {
		return new Fw_Db_Query($this, $sql, $binds, ($skip_logger ? null : $this->Logger));
	}

	/**
	 *
	 * @param Fw_Logger_Abstract $Logger
	 * @return Fw_Db 
	 */
	public function setLogger(Fw_Logger_Abstract $Logger) {
		$this->_Logger = $Logger;

		return $this;
	}

	/**
	 *
	 * @return \Fw_Db 
	 */
	public function unsetLogger() {
		$this->_Logger = null;

		return $this;
	}

}