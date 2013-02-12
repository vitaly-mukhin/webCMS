<?php

/**
 * Description of Head
 *
 * @author Vitaliy_Mukhin
 */
namespace Core\Block;
use Core\Block;

class Head extends Block {

	const TPL = 'block/head';

	protected static $jsLinks = array();
	protected static $cssLinks = array();
	protected static $pageTitle = array();

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

	protected function invoke() {
		$Output = parent::invoke();

		$Output->bind(array('jsLinks' => static::getJsLinks(), 'cssLinks' => static::getCssLinks(), 'pageTitle' => static::getPageTitle()));

		return $Output;
	}

}
