<?php

/**
 * Description of Block
 *
 * @author Vitaliy_Mukhin
 */
namespace Core;
class Block {

	use Tpl;

	/**
	 *
	 * @var array
	 */
	protected $params = array();

	/**
	 *
	 * @param array $params
	 *
	 * @return Block
	 */
	protected function init($params) {
		$this->params = (array) $params;

		return $this;
	}

	/**
	 *
	 * @param array  $params
	 *
	 * @param Output $Output
	 *
	 * @throws \Exception
	 * @throws \Exception
	 */
	public static function process($params = array(), Output $Output = null) {
		$Block = new static();

		$Block->init($params);

		$OutputResult = $Block->invoke();

		// если к нам пришел объект вывода, то пишем туда результат
		if ($Output) {
			$name  = $Block->getBindName();
			$value = Renderer\Http::di()->render($OutputResult, $OutputResult->getTemplatePath());
			$Output->bind($name, $value);
		}

		return $OutputResult;
	}

	/**
	 * @return Output
	 */
	protected function invoke() {
		$Output = new Output;
		$Output->setTemplatePath($this->getTemplatePath());
		return $Output;
	}

	/**
	 * Returns the name of variable, where processed result is binded
	 *
	 * @return string
	 */
	public function getBindName() {
		$class = get_class($this);
		$class = str_replace('\\', '_', $class);
		$class = stripos($class, '_') === 0 ? substr($class, 1) : $class;

		return $class;
	}

}
