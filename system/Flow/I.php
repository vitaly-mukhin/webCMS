<?php

/**
 *
 * @author Mukhenok
 */
interface Flow_I {

	/**
     * Returns a string if a next Flow is required, or TRUE if request is processed
     * 
	 * @return boolean|string 
	 */
	public function process();
	
}