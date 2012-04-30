<?php

/**
 * Description of Input_Config
 *
 * @author Vitaliy_Mukhin
 */
class Input_Config extends Input {

    /**
     *
     * @param mixed $key
     * @param mixed $default
     * @return Input_Config|mixed
     */
    public function get($key, $default = null) {
        $result = parent::get($key, $default);

        if (is_array($result)) {
            return new static($result);
        }

        return $result;
    }

    public static function init($configFile) {
        $pathConfig = PATH_CONFIG . DIRECTORY_SEPARATOR . $configFile;
        if (!file_exists($pathConfig)) {
            throw new ErrorException(sprintf('Config file not found at <b>%s</b>', $configFile));
        }

        ob_start();

        $data = require $pathConfig;

        ob_end_clean();

        return new static($data);
    }

}