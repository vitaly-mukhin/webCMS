<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mukhenok
 * Date: 07.12.12
 * Time: 23:50
 */
namespace App\Block;

class Head extends \Core\Block\Head {

	const TPL = 'block/head';

	// consts for js scripts

	const JS_BOOTSTRAP = 'js/bootstrap-2.2.0.js';

	const JS_COMMON = 'js/common.js';

	const JS_JQUERY = 'js/jquery-1.7.2.min.js';

	const JS_BLOCK_AUTH = 'js/block/auth.js';

	const JS_BLOCK_AUTH_LOGIN = 'js/block/auth/login.js';

	const JS_GALLERY = 'js/gallery/default.js';

	const JS_GALLERY_UPLOAD = 'js/gallery/upload.js';

	// consts for css

	const CSS_MAIN = 'css/main.css';

	const CSS_BOOTSTRAP = 'css/bootstrap-2.1.1a.css';

	const CSS_AUTH_LOGIN = 'css/auth/login.css';

}
