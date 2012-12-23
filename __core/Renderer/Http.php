<?php

/**
 * Description of Renderer\Http
 *
 * @author Vitaliy_Mukhin
 */
namespace Core\Renderer;
use Core\Renderer;
use Core\Output;
use Core\Input;

class Http extends Renderer {

	/**
	 * @param Output $Output
	 * @param        $templatePath
	 *
	 * @return string
	 */
	public function render(Output $Output, $templatePath) {
		if ($Output instanceof Output\Http) {
			$this->renderHeaders($Output);
			$this->renderCookie($Output);
		}

		return $this->renderInner($Output, $templatePath);
	}

	/**
	 * Set the headers to HTTP response
	 *
	 * @param Output\Http $Output
	 */
	protected function renderHeaders(Output\Http $Output) {
		$headers = $Output->headers();

		if (empty($headers)) {
			return;
		}

		foreach ($headers as $header) {
			header($header);
		}
	}

	/**
	 * Set the cookies to HTTP response
	 *
	 * @param Output\Http $Output
	 */
	protected function renderCookie(Output\Http $Output) {
		$cookies = $Output->cookies();

		if (empty($cookies)) {
			return;
		}

		foreach ($cookies as $Cookie) {
			/* @var $Cookie Input */
			$Cookie = new Input($Cookie->export());
			$name   = (string) $Cookie->get(Output\Http::OUTPUT_COOKIE_NAME);
			$value  = (string) $Cookie->get(Output\Http::OUTPUT_COOKIE_VALUE);
			$expire = (int) $Cookie->get(Output\Http::OUTPUT_COOKIE_EXPIRE);
			$path   = (string) $Cookie->get(Output\Http::OUTPUT_COOKIE_PATH);

			setcookie($name, $value, $expire, $path);
		}
	}

}