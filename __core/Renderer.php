<?php

/**
 * Description of Renderer
 *
 * @author Vitaliy_Mukhin
 */
abstract class Renderer {

    /**
     *
     * @var Renderer_Engine
     */
    protected $Engine;

    /**
     *
     * @var array
     */
    protected static $instances;

    protected function __construct() {
        
    }

    /**
     * 
     * @return Renderer
     */
    public static function di() {
        $calledClass = get_called_class();
        if (!empty(static::$instances[$calledClass])) {
            return static::$instances[$calledClass];
        }

        $instance = new static();

        $instance->Engine = $instance->getRendererEngine();

        return static::$instances[$calledClass] = $instance;
    }

    /**
     *
     * @return Renderer_Engine 
     */
    private function getRendererEngine() {

        $Engine = new Renderer_Engine();
        $Engine->init();

        return $Engine;
    }

    /**
     *
     * @param Output $Output
     * @return string
     */
    public function render(Output_Http $Output) {
        $templatePath = $Output->getTemplatePath();
        $content = $this->Engine->render($templatePath, $Output->export());

        return $content;
    }

}