<?php

require_once 'ILoader.php';

/**
 * Description of Loader
 *
 * @author Vitaliy_Mukhin
 */
class Loader implements ILoader {

	/**
	 *
	 * @var string
	 */
	protected $baseFolder;

	/**
	 *
	 * @param string $folder 
	 */
	public function setBaseFolder($folder) {
		$this->baseFolder = $folder;

		return $this;
	}

	/**
	 *
	 * @param string $class
	 * @return array 
	 */
	protected function explodeClass($class) {
		return explode('_', $class);
	}
    
    protected $ignoreFirstPart = false;
    
    /**
     *
     * @param boolean $bool
     * @return \Loader 
     */
    public function setIgnoreFirstPart($bool) {
        $this->ignoreFirstPart = (bool)$bool;
        
        return $this;
    }

	/**
	 *
	 * @var string
	 */
	protected $prefix = '';

	/**
	 *
	 * @param string $prefix
	 * @return \Loader 
	 */
	public function setPrefix($prefix) {
		$this->prefix = $prefix;

		return $this;
	}

	/**
	 *
	 * @return string
	 */
	protected function getPrefix() {
		return ($this->prefix != '') ? $this->prefix : '';
	}

	protected $fileSuffix = '.php';
	protected $filePrefix = '';
	protected $useFilePrefix = true;

	/**
	 *
	 * @param string $folder
	 * @return string 
	 */
	protected function getFilePrefix($folder = '') {
		if ($this->useFilePrefix) {
			return strtolower($folder) . '.';
		}
		return $this->filePrefix;
	}

	/**
	 *
	 * @return string
	 */
	protected function getFileSuffix() {
		return $this->fileSuffix;
	}

	/**
	 *
	 * @param boolean $use_or_not
	 * @return \Loader 
	 */
	public function useFilePrefix($use_or_not) {
		$this->useFilePrefix = (boolean)$use_or_not;

		return $this;
	}

	/**
	 *
	 * @param string $class
	 * @return string
	 */
	public function getFile($class) {
		$class_array = $this->explodeClass($class);

        if ($this->ignoreFirstPart && count($class_array)>1) {
            array_shift($class_array);
        }
        
		if (count($class_array) > 1) {
			$path = implode(DIRECTORY_SEPARATOR, array_slice($class_array, 0, -1));
			$filename = $path .
					DIRECTORY_SEPARATOR .
					$this->getFilePrefix($class_array[count($class_array) - 2]) .
					$class_array[count($class_array) - 1] .
					$this->getFileSuffix();
		} else {
			$filename = $this->getPrefix() . implode($class_array) . $this->getFileSuffix();
		}
        
		return $this->baseFolder . DIRECTORY_SEPARATOR . $filename;
	}

}