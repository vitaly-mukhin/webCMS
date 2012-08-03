<?php

/**
 * Description of Html
 *
 * @author Vitaliy_Mukhin
 */
class Renderer_Http extends Renderer {

	/**
	 *
	 * @param Output_Http $Output
	 * @param string $templatePath 
	 */
	public function render(Output_Http $Output) {
		if ($Output instanceof Output_Http) {
			$this->renderHeaders($Output);
			$this->renderCookie($Output);
		}

		return parent::render($Output);
	}

    /**
     * Set the headers to HTTP response
     * 
     * @param Output_Http $Output
     */
	protected function renderHeaders(Output_Http $Output) {
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
     * @param Output_Http $Output
     */
	protected function renderCookie(Output_Http $Output) {
		$cookies = $Output->cookies();

		if (empty($cookies)) {
			return;
		}

		foreach ($cookies as $Cookie) {
			/* @var $Cookie Input */
			$Cookie = new Input($Cookie->export());
			$name = (string)$Cookie->get(Output_Http::OUTPUT_COOKIE_NAME);
			$value = (string)$Cookie->get(Output_Http::OUTPUT_COOKIE_VALUE);
			$expire = (int)$Cookie->get(Output_Http::OUTPUT_COOKIE_EXPIRE);
			$path = (string)$Cookie->get(Output_Http::OUTPUT_COOKIE_PATH);

			setcookie($name, $value, $expire, $path);
		}
	}

}