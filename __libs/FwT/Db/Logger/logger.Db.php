<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Fw_Logger_Db
 *
 * @author Vitaliy_Mukhin
 */
class Fw_Logger_Db extends Fw_Logger_Abstract {

	const TIME_FORMAT = 'Y-m-d H:i:s';

	protected $_prepareCallback;

	public function __construct(Fw_Config $Config, $prepareCallback) {
		parent::__construct($Config);

		$this->_prepareCallback = $prepareCallback;
	}

	protected function _prepare($data) {
		if (empty($this->_prepareCallback) || !is_callable($this->_prepareCallback)) {
			throw new Fw_Exception_Logger(__CLASS__ . ' requires a callback for preparing data');
		}
		$function = $this->_prepareCallback;
		$this->_data = $function($data, $this->_Config);
	}

	protected function _write() {
		if ((!$this->_Config->db || !($this->_Config->db instanceof Fw_Db)) && error_reporting()) {
			throw new Fw_Exception_Logger('db parameter in config must be set, and has to be an instance of Fw_Db');
		}
		if (!$this->_Config->table && error_reporting()) {
			throw new Fw_Exception_Logger('Table name has to be set in Logger config');
		}
		/* @var $db Fw_Db */
		if ($this->_Config->db && $this->_Config->table) {
			try {
				$db = $this->_Config->db;
				$query = $db->query(null, null, true)->insert($this->_Config->table, $this->_data);
				if (!$query || !$query->fetchRow()) {
					throw new Fw_Db_Query('Insert doesnt work');
				}
			} catch (Fw_Exception_Db $e) {
				if (error_reporting()) {
					throw new Fw_Exception_Logger('Cannot save data to a database: ' . $e->getMessage());
				}
			}
		}

		return $this;
	}

}
