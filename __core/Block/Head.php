<?php

/**
 * Description of Head
 *
 * @author Vitaliy_Mukhin
 */
namespace Core\Block;
use Core\Block;

class Head extends Block {

	const TPL = 'block/head/default.twig';
	// consts for js scripts
	const JS_BLOCK_AUTH       = 'js/block/auth.js';
	const JS_BLOCK_AUTH_LOGIN = 'js/block/auth/login.js';
	// consts for css
	const CSS_AUTH_LOGIN = 'css/auth/login.css';

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