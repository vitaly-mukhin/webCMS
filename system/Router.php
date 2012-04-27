<?php

/**
 * Description of Router
 *
 * @author Mukhenok
 */
class Router {

	/**
	 * 
	 * 
	 * @var string
	 */
	protected $routeMask;

	/**
	 *
	 * @param string $routeMask
	 * @return \Router 
	 */
	public function setRouteMask($routeMask) {
		$this->routeMask = $routeMask;

		return $this;
	}

	/**
	 *
	 * @param string $route
	 * @return array
	 * @throws Exception 
	 */
	public function parse($route) {
		if (is_null($this->routeMask)) {
			throw new Exception('Route mask has not been set');
		}
		
		if (preg_match($this->routeMask, $route, $m)) {
			foreach (array_keys($m) as $k){
				if (is_int($k)) {
					unset($m[$k]);
				}
			}
			return $m;
		}
		
		return false;
	}

}