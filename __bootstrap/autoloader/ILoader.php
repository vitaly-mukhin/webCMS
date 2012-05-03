<?php

/**
 *
 * @author Vitaliy_Mukhin
 */
interface ILoader {

	/**
	 * @return Array of possible files 
	 */
	public function getFile($class);

}