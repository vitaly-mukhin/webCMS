<?php

/**
 * Description of Router
 *
 * @author Mukhenok
 */
class Router {
	/**
	 * Default route mask 
	 */

	const DEFAULT_MASK = '/(?<p>[^\/]*)(\/(?<a>[^\/]+))?(\/(?<s>[^\/]+))?/i';

	/**
	 * 
	 * 
	 * @var string
	 */
	protected $routeMask;

	/**
	 *
	 * @param Input_Config $Config
	 * @return \Router 
	 */
	public static function init(Input_Config $Config) {
		$Router = new Router();
		$Router->setRouteMask($Config->get('mask'));

		return $Router;
	}

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
			foreach (array_keys($m) as $k) {
				if (is_int($k)) {
					unset($m[$k]);
				}
			}
			return $m;
		}

		return false;
	}

}