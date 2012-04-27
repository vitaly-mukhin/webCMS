<?php

/**
 * Description of Output
 *
 * @author Mukhenok
 */
class Output implements Output_I {
	
	protected $data = array();

	public function bind($name, $value) {
		$this->data[$name] = $value;
		
		return $this;
	}

}