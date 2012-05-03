<?php

/**
 * Description of Dispatcher
 *
 * @author Vitaliy_Mukhin
 */
class Dispatcher {

    /**
     *
     * @var Dispatcher
     */
    private static $instance;

    /**
     *
     * @var Router
     */
    private $Router;

    /**
     *
     * @var Input_Config
     */
    private $Config;

    /**
     *
     * @var Flow
     */
    private $RootFlow;

    const INPUT_ROUTE = 'route_data';
    const INPUT_GET = 'get_data';
    const INPUT_POST = 'post_data';
    const INPUT_COOKIE = 'coockie_data';
    const ROUTE = 'route';
    const OUTPUT_HTML = 'output as HTML';
    const OUTPUT_DATA = 'output data';
    const NO_FLOW = 'noFlowFound';

    private function __construct() {
        
    }

    /**
     *
     * @return Dispatcher
     */
    public static function i() {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     *
     * @param Input_Config $Config 
     */
    public function init(Input_Config $Config) {
        $this->Config = $Config;
        $this->Router = new Router();


        foreach (array_keys($Config->export()) as $key) {
            switch (strtolower($key)) {
                case 'mode':
                    if (!$modeFolder = $this->Config->get($key)) {
                        throw new ErrorException('mode must be set!');
                    }

                    define('PATH_MODE', PATH_MODES . DIRECTORY_SEPARATOR . $modeFolder);

                    $this->addModeAutoloaders(PATH_MODE);
                    break;

                case 'router':
                    $defaultRouterConfig = new Input_Config(array(
                                'mask' => Router::DEFAULT_MASK
                        ));
                    $this->Router->setRouteMask($Config->get($key, $defaultRouterConfig)->get('mask'));
                    break;

                case 'flow':
                    $flow = $this->Config->get($key);
                    $flowObject = $this->getFlow($flow);
                    $this->RootFlow = $flowObject;
                    break;
            }
        }
    }

    /**
     *
     * @param type $modeFolder 
     */
    private function addModeAutoloaders($modeFolder) {
        $baseFolder = $modeFolder . DIRECTORY_SEPARATOR . 'php';
        $FlowLoader = new Loader();
        $FlowLoader
            ->setBaseFolder($baseFolder . DIRECTORY_SEPARATOR . 'flows')
            ->setIgnoreFirstPart(true)
            ->setPrefix('flow.');
        Autoloader::add($FlowLoader);
    }

    /**
     *
     * @param string $flowString
     * @return Flow_I
     * @throws ErrorException 
     */
    private function getFlow($flowString, $BaseFlow = null) {
        $class = !is_null($BaseFlow) && !get_class($BaseFlow) == 'Flow' ? get_class($object) : 'Flow';
        
        $flowClass = $class . '_' . ucfirst($flowString);
        if (!class_exists($flowClass)) {
            throw new ErrorException(sprintf('Flow not found %s', $flowClass));
        }
        /* @var $Flow Flow */
        $Flow = new $flowClass();

        return $Flow;
    }

    public function flow() {
        $InputGet = new Input($_GET);

        $Input = new Input(array(
                    static::INPUT_ROUTE => new Input($this->Router->parse($InputGet->get('route', ''))),
                    static::INPUT_GET => $InputGet,
                    static::INPUT_POST => new Input($_POST)
            ));

        // initiating a Output object, and setting its default params
        $Output = new Output();
        $Output->appender(Dispatcher::OUTPUT_HTML);


        $result = null;
        $Flow = $this->RootFlow;
        while (is_null($result) || is_string($result)) {
            $Flow->init($Input, $Output);

            $result = $Flow->process();

            if ($result === false) {
                $result = Dispatcher::NO_FLOW;
            }

            if (!empty($result) && is_string($result)) {
                try {
                    $Flow = $this->getFlow($result, $Flow);
                } catch (ErrorException $E) {
                    $Flow = false;
                }
                if (!$Flow) {
                    var_dump('cannot find proper flow');
                    $result = false;
                }
            }
        }
        
        $Output->flow($Flow);
        
        $this->render($Output);
    }
    
    public function render($Output) {
        switch ($Output->appender()) {
            case static::OUTPUT_HTML:
                $Renderer = new Renderer_Html();
                $Renderer->render($Output);
                break;
                
            default:
                echo 'Unknown output appender';
        }
    }

}