<?php

/**
 * Description of Fb
 *
 * @author Mukhenok
 */
class Log_Fb extends Fw_Logger_Abstract {

	protected function prepareForWrite($data) {
		$this->_data = $data;
	}

	protected function write() {
		Fb::log($this->_data);

		return $this;
	}

}