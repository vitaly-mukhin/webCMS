<?php

/**
 * Description of Head
 *
 * @author Vitaliy_Mukhin
 */
class Block_Flow_Head extends Block_Flow {

	private static $jsLinks = array();
	private static $cssLinks = array();
	private static $pageTitle = array();

	public static function addJsLink($link) {
		self::$jsLinks[$link] = $link;
	}

	public static function getJsLinks() {
		return self::$jsLinks;
	}

	public static function addCssLink($link) {
		self::$cssLinks[$link] = $link;
	}

	public static function getCssLinks() {
		return self::$cssLinks;
	}

	public static function addPageTitle($string) {
		self::$pageTitle[] = $string;
	}

	public static function getPageTitle() {
		return self::$pageTitle;
	}

	public function getRoute(Input $InputRoute) {
		return array('action' => 'head');
	}

}