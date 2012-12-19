<?php

/**
 * Description of Fb
 *
 * @author Mukhenok
 */
namespace Core\Log;
class Fb extends \Fw_Logger_Abstract {

	protected function prepareForWrite($data) {
		$this->_data = $data;
	}

	protected function write() {
		\Fb::log($this->_data);

		return $this;
	}

}