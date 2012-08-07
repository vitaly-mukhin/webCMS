<?php

/**
 * Description of Head
 *
 * @author Vitaliy_Mukhin
 */
class Block_Head extends Block {
    
    const TPL = 'block/head/default.twig';

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

}